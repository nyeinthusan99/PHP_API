<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PostsExport;
use App\Imports\PostsImport;
use Illuminate\Support\Facades\Validator;
class PostController extends Controller
{

    //to get post list & search
    public function index(Request $request)
    {
        if($request->user()->type == 0){
            return Post::when(request('search'),function($query){
                $query->where('title', 'LIKE', '%'.request('search').'%');
            })->paginate(5);
        }else{
            return Post::where('user_id',"=",$request->user()->id)->when(request('search'),function($query){
                $query->where('title', 'LIKE', '%'.request('search').'%');
            })->paginate(5);
        }
    }


    //create
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
        'title' => 'required',
        'description' => 'required',
        'user_id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Wrong',
                'errors' => $validator->errors()
            ],400);
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
            ]);
        }
        return response()->json([
        "success" => true,
        "message" => "Post retrieved successfully.",
        "data" => $post
        ]);
    }


    //update
    public function update(Request $request, Post $post)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
        'title' => 'required',
        'description' => 'required',
        'user_id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Wrong',
                'errors' => $validator->errors()
            ],401);
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

    public function search($title)
    {
        $result = Post::where('title', 'LIKE', '%'. $title. '%')->get();
        if(count($result)){
         return Response()->json($result);
        }
        else
        {
        return response()->json(['Result' => 'No Data not found'], 404);
      }
    }

    //import
    public function import(Request $request)
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
            ],400);
        }
        Excel::import(new PostsImport, $request->file('file'));
        return response()->json([
        'result' => 1,
        'message' => 'Import successfully'
       ],200);

    }

    //export
    public function export()
    {
        return Excel::download(new PostsExport, 'posts.xlsx');
    }

}



