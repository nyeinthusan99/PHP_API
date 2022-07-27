<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;

class UsersImport implements ToCollection, WithHeadingRow ,WithStartRow
{
    public function headingRow(): int
    {
        return 0;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 1;
    }
    
    public function collection(Collection $rows)
    {
         Validator::make($rows->toArray(), [
             '*.name' => 'required',
             '*.email' => 'required|email|unique:users',
             '*.password' => 'required|min:8',
         ])->validate();

         foreach ($rows as $row) {
               User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => bcrypt($row['password']),
            ]);
        }
    }
}
