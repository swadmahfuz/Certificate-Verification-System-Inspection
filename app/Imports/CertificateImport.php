<?php

namespace App\Imports;

use App\Models\Certificate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CertificateImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Certificate([
            'certificate_number' => $row['certificate_number'],
            'inspector' => $row['inspector'],
            'client_name' => $row['client_name'],
            'inspection_type' => $row['inspection_type'],
            'inspection_location' => $row['inspection_location'],
            'equipment_name' => $row['equipment_name'],
            'equipment_brand' => $row['equipment_brand'],
            'equipment_serial_chassis' => $row['equipment_serial_chassis'],
            'equipment_rated_capacity' => $row['equipment_rated_capacity'],
            'equipment_swl' => $row['equipment_swl'],
            'inspection_date' => $row['inspection_date'],
            'validity_date' => $row['validity_date'],
            'inspection_remarks' => $row['inspection_remarks'],
            'inspection_internal_notes' => $row['inspection_internal_notes'],
            'created_by' => $row['created_by'],
        ]);
    }
}