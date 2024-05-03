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
            'client_name',
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

certificate_number
client_name
inspection_type
inspection_location
equipment_name
equipment_brand
equipment_serial_chassis
equipment_rated_capacity
equipment_swl
inspection_date
validity_date
inspection_remarks
inspection_internal_notes
created_by
updated_by
deleted_by