<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PostsExport;
use App\Imports\PostsImport;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class PostController extends Controller
{

    //to get post list & search
    public function index(Request $request)
    {
         return Post::where('title', 'LIKE', '%'. request('title') .'%')
                ->when($request['description'], function($query) {
                 $query->where('description', 'LIKE', '%' . request('description') . '%');
                })->when(request()->user()->type!=0,function($query){
                    $query->where('user_id',request()->user()->id);
                })->orderBy('id','DESC')->paginate(5)->withQueryString();
    }

    //create
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
        'title' => 'required|max:50|unique:posts',
        'description' => 'required',
        'user_id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Wrong',
                'errors' => $validator->errors()
            ],422);
        }
        $post = Post::create([
            'title' => $input['title'],
            'description' => $input['description'],
            'user_id' => $input['user_id'],
        ]);
        return response()->json([
        "success" => true,
        "message" => "Post created successfully.",
        "data" => $post
        ],200);
    }

    //get post info
    public function show($id)
    {
        $post = Post::find($id);
        if (is_null($post)) {
        return response()->json([
            "success" => false,
            "message" => "Product not found."
            ],422);
        }
        return response()->json([
        "success" => true,
        "message" => "Post retrieved successfully.",
        "data" => $post
        ],200);
    }


    //update
    public function update(Request $request,Post $post)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
        'title' => [Rule::unique("posts","title")->ignore($post->id)],
        'description' => 'required',
        'user_id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Wrong',
                'errors' => $validator->errors()
            ],422);
        }
        $post->title = $input['title'];
        $post->description = $input['description'];
        $post->user_id =$input['user_id'];
        $post->save();
        return response()->json([
        "success" => true,
        "message" => "Post updated successfully.",
        "data" => $post
        ],200);
    }


    //delete
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json([
        "success" => true,
        "message" => "Post deleted successfully.",
        "data" => $post
        ]);
    }


    //import
    public function import(Request $request,User $user)
    {

        $input = $request->only(['file']);

        $validate_data = [
            'file' => 'required|mimes:xlsx,csv,txt',
        ];

        $validator = Validator::make($input, $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error!',
                'errors' => $validator->errors()
            ],422);
        }
        Excel::import(new PostsImport($request->user()->id), $request->file('file'));
        return response()->json([
        'result' => 1,
        'message' => 'Import successfully'
       ],200);



    }

    //export
    public function export($id)
    {
        return Excel::download(new PostsExport($id),'posts.xlsx');
    }

}



