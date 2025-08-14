<?php

namespace App\Imports;

use App\Models\Certificate;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Inspection Certificate Verification System (CVS) 
| TUV Austria Bureau of Inspection & Certification 
| Developed by: Swad Ahmed Mahfuz (Assistant Manager - Sales & Operations, Bangladesh)
| Contact: swad.mahfuz@gmail.com, +1-725-867-7718, +88 01733 023 008
| Project Start: 12 October 2022
|--------------------------------------------------------------------------
*/

class CertificateImport implements ToModel, WithHeadingRow
{
    /**
     * Map Excel rows to Certificate model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $createdUser  = User::where('email', $row['created_by_email'])->first();   
        $reviewUser   = User::where('email', $row['review_by_email'])->first();
        $approvalUser = User::where('email', $row['approval_by_email'])->first();
        $loggedInUser = Auth::user();

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
            'status' => 'Pending Review',
            'created_by' => $createdUser ? $createdUser->name : null,
            'created_by_id' => $createdUser ? $createdUser->id : null,
            'created_at' => Carbon::now(),
            'review_by' => $reviewUser ? $reviewUser->name : null,
            'review_by_id' => $reviewUser ? $reviewUser->id : null,
            'approval_by' => $approvalUser ? $approvalUser->name : null,
            'approval_by_id' => $approvalUser ? $approvalUser->id : null,
            'updated_by' => $loggedInUser ? $loggedInUser->name : null,
            'updated_by_id' => $loggedInUser ? $loggedInUser->id : null,
            'updated_at' => Carbon::now(),

            
        ]);
    }
}
