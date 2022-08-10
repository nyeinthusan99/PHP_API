<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class UsersImport implements ToCollection, WithHeadingRow
{
    

    public function collection(Collection $rows)
    {
         Validator::make($rows->toArray(), [
             '*.name' => 'required',
             '*.email' => 'required|email|unique:users',
             '*.password' => 'required|min:8',
             '*.phone' => 'required|min:11|numeric'
         ])->validate();

         foreach ($rows as $row) {
               User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => bcrypt($row['password']),
                'image' => '',
                'type' => $row['type'],
                'phone' =>$row['phone'],
                'address' => $row['address'],
                'dob' =>$row['dob'],
            ]);
        }
    }
}
