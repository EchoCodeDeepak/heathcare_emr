<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PatientsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $patients;

    public function __construct($patients)
    {
        $this->patients = $patients;
    }

    public function collection()
    {
        return $this->patients;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Date of Birth',
            'Gender',
            'Blood Group',
            'Address',
            'Created At',
        ];
    }

    public function map($patient): array
    {
        return [
            $patient->id,
            $patient->name,
            $patient->email,
            $patient->phone,
            $patient->date_of_birth ? date('Y-m-d', strtotime($patient->date_of_birth)) : 'N/A',
            $patient->gender,
            $patient->blood_group,
            $patient->address,
            $patient->created_at->format('Y-m-d H:i:s'),
        ];
    }
}