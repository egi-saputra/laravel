<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataUsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DB::table('users')
            ->select('name', 'email', 'password', 'role')
            ->get();
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Password', 'Role'];
    }
}
