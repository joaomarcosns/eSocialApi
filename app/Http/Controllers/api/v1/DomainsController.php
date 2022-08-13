<?php

namespace App\Http\Controllers\api\v1;

use App\Exports\DomainsExport;
use App\Http\Controllers\Controller;
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
                $domainExplode =  explode('.', $value['domain'], 2);
                $domain  = $domainExplode[0];
                $tld = $domainExplode[1];
                $created = $value['registration_date'];
                $updated = $value['last_update'];
                $responsible = $value['responsible'];

                if (empty($value['serve_names'])){
                    $serveNames = null;
                }else{
                    $serveNames = $value['serve_names'];
                }
                
                if (is_int($created) == 1) {
                    $created = new \DateTime("1899-12-30 + $created days");
                    $created = $created->format("Y-m-d");
                }
                $this->storeUpload($domain, $tld, $created, $updated, $responsible, $serveNames);
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
     * @param  String $serveNames
     */
    protected function storeUpload($domain, $tld, $created, $updated, $responsible, $serveNames)
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
        if (empty($serveNames)) $serveNames = $domain.'.'.$tld;

        NameServer::firstOrCreate([
            'names_server'  => $serveNames,
            'fk_domains_id' => $domains->id
        ]);


    }

    public function export() 
    {
        return Excel::download(new DomainsExport, 'domains.csv', \Maatwebsite\Excel\Excel::CSV);

    }

    public function modelImport() {
        $file = public_path('storage/logo/modelo.xlsx');
        return response()->json($file);
    }
}
