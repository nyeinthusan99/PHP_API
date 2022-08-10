<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = User::select('name','email','type','phone','address','dob')->get();
        for($i=0;$i<count($user);$i++){
            \Log::info($user[$i]);
        if($user[$i]->type == 0){
            $user[$i]->type = 'Admin';
        }else{
            $user[$i]->type = 'User';
        }
        }
        return $user;
    }
    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Type',
            'Phone',
            'address',
            'dob',
        ];
    }
}
