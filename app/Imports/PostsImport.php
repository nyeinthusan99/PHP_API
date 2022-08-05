<?php

namespace App\Imports;

use App\Models\Post;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class PostsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
         Validator::make($rows->toArray(), [
             '*.title' => 'required',
             '*.description' => 'required'
         ])->validate();

         foreach ($rows as $row) {
               Post::create([
                'title' => $row['title'],
                'description' => $row['description'],
                'user_id' => $row['user_id'],
            ]);
        }
    }
}
