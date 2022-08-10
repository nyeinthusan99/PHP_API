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
        \Log::info($request);
         $post = Post::where('title', 'LIKE', '%'. $request['title'] .'%')
                ->when($request['description'], function($query) {
                 $query->where('description', 'LIKE', '%' . $request['description'] . '%');
                })->paginate(5);

        //  if(isset($request['title'])){
        //     $post->where('title', 'LIKE', '%'. $request['title'] .'%');
        //  }
        // // if(isset($request['description'])){
        // //     $post->where(DB::raw('description'), 'LIKE', '%'. $request['description'] .'%');
        // // }
        //  $post->paginate(5);
        //  \Log::info($post);
         return $post;
        // return $post;
        // if($request->user()->type == 0){
        //     return $post->orderBy('id', 'DESC')->paginate(5);
        //     // return Post::when($request->search,function($query){
        //     //     $query->where('title', 'LIKE', '%'.request('search').'%');
        //     // })->orderBy('id', 'DESC')->paginate(5);
        // }else{
        //     // return Post::where('user_id',"=",$request->user()->id)->when($request->search,function($query){
        //     //     $query->where('title', 'LIKE', '%'.request('search').'%');
        //     // })->orderBy('id', 'DESC')->paginate(5);
        //     return $post->where('user_id',"=",$request->user()->id)->orderBy('id', 'DESC')->paginate(5);
        // }

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
    public function update(Request $request,Post $post)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
        //'title' => 'required|unique:posts,title,'.$post->id,
         'title' => [Rule::unique("posts","title")->ignore($post->id)],
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
            ],400);
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



