<?php

namespace App\Exports;

use App\Models\Domains;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DomainsExport implements FromView
{
    public function view(): View
    {
        return view('Export.domains', [
            'domains' => Domains::with(['registers', 'names_servers'])->get()
        ]);
    }
}
