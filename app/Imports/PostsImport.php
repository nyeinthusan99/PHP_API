<?php

namespace App\Imports;

use App\Models\Post;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Validation\Rule;

class PostsImport implements  ToCollection,WithHeadingRow
{
    public function  __construct($userid)
    {
        $this->user_id = $userid;
    }
    public function collection(Collection $rows)
    {
         Validator::make($rows->toArray(), [
             '*.title' => 'required|max:50|unique:posts',
             '*.description' => 'required'
         ])->validate();

         foreach ($rows as $row) {
               Post::create([
                'title' => $row['title'],
                'description' => $row['description'],
                'user_id' => $this->user_id,
            ]);
        }
    }

    // public function model(array $row)
    // {
    //     return new Post([
    //         'title' => $row['title'],
    //         'description' => $row['description'],
    //         'user_id' => $this->user_id,
    //     ]);
    // }

    // public function rules():array
    // {
    //     return [
    //         '*.title' => 'required|max:5|unique:posts,title',
    //         '*.description' => 'required'
    //     ];

    //  }

    // public function validationMessages(){
    //     return [
    //         '*.title.required'=>trans('title is required'),
    //         'description.required'=>trans('description is required'),
    //     ];
    // }
}
