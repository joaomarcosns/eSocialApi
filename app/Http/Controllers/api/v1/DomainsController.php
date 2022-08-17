<?php

namespace App\Http\Controllers\api\v1;

use App\Exports\DomainsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\DomainsStoreRequest;
use App\Http\Requests\DomainsUploadingRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportExcel;
use App\Models\Domains;
use App\Models\NameServer;
use App\Models\Registers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DomainsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => "listagem dos domínios",
            'data' => Domains::with(['registers', 'names_servers'])->get(),
            'status_code' => 200
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DomainsStoreRequest $request)
    {
        $register = Registers::query()->where('name', trim($request->register))->get();
        if (empty($register->toArray())) {
            return $this->storeEmptyRegisters($request);
        }
        $domain = Domains::query()->create([
            "name" => $request->name,
            "tld" => $request->tld,
            "created_at" => $request->created_at,
            "updated_at" => $request->updated_at,
            'expiration_date' => new \DateTime("$request->updated_at + 365 days"),
            'fk_registers_id' => $register[0]->id
        ]);

        NameServer::query()->create([
            'names_server'  => $request->nameserver_1,
            'fk_domains_id' => $domain->id
        ]);

        NameServer::query()->create([
            'names_server'  => $request->nameserver_2,
            'fk_domains_id' => $domain->id
        ]);
        return response()->json([
            'message' => 'Sucesso ao criar um novo domínio',
            'data' => Domains::with(['registers', 'names_servers'])->where('id', $domain->id)->get(),
            'status_code' => 201,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Domains::with(['registers', 'names_servers'])->where('id', $id)->get();
        return response()->json([
            'message' => empty($data) ? 'Não encontrado nos registros' : 'Domínio encontrado nos registros',
            'data' => empty($data) ? [] : $data,
            'status_code' => 201,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $register = Registers::query()->where('name', trim($request->register))->get();
        $serveNames = NameServer::query()->where('fk_domains_id', $id)->get();
        // dd($serveNames->toArray()[0]);
        if (empty($register->toArray())) {
            return $this->updateEmptyRegisters($request, $id, $serveNames);
        }
        Domains::query()->where('id', $id)->update([
            "name" => $request->name,
            "tld" => $request->tld,
            "created_at" => $request->created_at,
            "updated_at" => $request->updated_at,
            'expiration_date' => new \DateTime("$request->updated_at + 365 days"),
            'fk_registers_id' => $register[0]->id
        ]);
        $serveNames[0]->update([
            'names_server'  => $request->nameserver_1,
        ]);
        $serveNames[1]->update([
            'names_server'  => $request->nameserver_2,
        ]);
        return response()->json([
            'message' => 'Sucesso ao atualizar um novo domínio',
            'data' => Domains::with(['registers', 'names_servers'])->where('id', $id)->get(),
            'status_code' => 201,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(DomainsUploadingRequest  $request)
    {
        $arrays = Excel::toArray(new ImportExcel, request()->file('file'));
        foreach ($arrays as $date) {
            foreach ($date as $key => $value) {
                $domain = $value['domain'];
                $domainExplode =  explode('.', $domain, 2);
                if (!empty($domainExplode[1])) {
                    $domain  = $domainExplode[0];
                    $tld = $domainExplode[1];
                    $created = $value['registration_date'];
                    $updated = $value['last_update'];

                    $responsible = $value['responsible'];
                    $serveName1 = $value['serve_names_1'];
                    $serveName2 = $value['serve_names_2'];

                    if (is_int($created) == 1) {
                        $created = new \DateTime("1899-12-30 + $created days");
                        $created = $created->format("Y-m-d");
                    }
                    if (is_int($updated) == 1) {
                        $updated = new \DateTime("1899-12-30 + $updated days");
                        $updated = $updated->format("Y-m-d");
                    }

                    $this->storeUpload($domain, $tld, $created, $updated, $responsible, $serveName1, $serveName2);
                } else {
                    break;
                }
            }
        }
        return response()->json([
            'message' => "Dados do arquivos importados com sucesso",
            'data' => '',
            'status_code' => 201
        ], 201);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  String $domain
     * @param  String $tld
     * @param  Date $created
     * @param  Date $updated
     * @param  String $responsible
     * @param  String $serveName1
     * @param  String $serveName2
     */
    protected function storeUpload($domain, $tld, $created, $updated, $responsible, $serveName1, $serveName2)
    {
        $registers = Registers::firstOrCreate([
            'name' => $responsible,
        ]);

        $domains = Domains::firstOrCreate([
            'name' => $domain,
            'tld' => $tld,
            'created_at' => $created,
            'updated_at' => $updated,
            'expiration_date' => new \DateTime("$updated + 365 days"),
            'fk_registers_id' => $registers->id
        ]);

        if (empty($serveName1) && empty($serveName2)) {
            $serveName1 = $domain . '.' . $tld;
            NameServer::firstOrCreate([
                'names_server'  => $serveName1,
                'fk_domains_id' => $domains->id
            ]);
        } else {
            NameServer::firstOrCreate([
                'names_server'  => $serveName1,
                'fk_domains_id' => $domains->id
            ]);

            NameServer::firstOrCreate([
                'names_server'  => $serveName2,
                'fk_domains_id' => $domains->id
            ]);
        }
    }

    public function export()
    {
        return Excel::download(new DomainsExport, 'domains.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function modelImport()
    {
        $file = public_path('storage/logo/modelo.xlsx');
        return response()->json($file);
    }

    protected function storeEmptyRegisters(Request $request)
    {
        $registers = Registers::query()->create([
            'name' => $request->register,
        ]);

        $domain = Domains::query()->create([
            "name" => $request->name,
            "tld" => $request->tld,
            "created_at" => $request->created_at,
            "updated_at" => $request->updated_at,
            'expiration_date' => new \DateTime("$request->updated_at + 365 days"),
            'fk_registers_id' => $registers->id
        ]);

        NameServer::query()->create([
            'names_server'  => $request->nameserver_1,
            'fk_domains_id' => $domain->id
        ]);

        NameServer::query()->create([
            'names_server'  => $request->nameserver_2,
            'fk_domains_id' => $domain->id
        ]);
        return response()->json([
            'message' => 'Sucesso ao criar um novo domínio',
            'data' => Domains::with(['registers', 'names_servers'])->where('id', $domain->id)->get(),
            'status_code' => 201,
        ], 201);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function updateEmptyRegisters(Request $request, $id, $serveNames)
    {
        $registers = Registers::query()->create([
            'name' => $request->register,
        ]);

        Domains::query()->where('id', $id)->update([
            "name" => $request->name,
            "tld" => $request->tld,
            "created_at" => $request->created_at,
            "updated_at" => $request->updated_at,
            'expiration_date' => new \DateTime("$request->updated_at + 365 days"),
            'fk_registers_id' => $registers->id
        ]);

        $serveNames[0]->update([
            'names_server'  => $request->nameserver_1,
        ]);
        $serveNames[1]->update([
            'names_server'  => $request->nameserver_2,
        ]);
    }
}
