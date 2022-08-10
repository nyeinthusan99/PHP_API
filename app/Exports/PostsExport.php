<?php

namespace App\Exports;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostsExport implements FromCollection,WithHeadings
{
    //private $user;
    public function  __construct($id)
    {
       $this->user_id = $id;
    }
    public function collection()
    {

        $user = User::where('id', $this->user_id)->get();

        if ($user[0]->type == 0) {
           $post = Post::select('title', 'description')->get();
        } else {
           $post = Post::select('title', 'description')->where('user_id', $this->user_id)->get();
        }
        return $post;
    }

    public function headings(): array
    {
        return [
            'Title',
            'Description',
        ];
    }
}



