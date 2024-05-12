<?php

namespace App\Exports;

use App\Models\Certificate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CertificateExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Certificate::all();
    }
    public function headings(): array
    {
        return [
            'DB ID',
            'Certificate Number',
            'Client',
            'Inspection Type',
            'Location',
            'Equipment Name',
            'Brand/Manufacturer',
            'Serial/Chassis no.',
            'Rated Capacity',
            'SWL',
            'Inspection Date',
            'Validity Date',
            'Inspection Remarks',
            'Internal Notes',
            'Created by',
            'Updated by',
            'Deleted by',
            'Created at',
            'Updated at',
            'Deleted at',
        ];
    }
}