<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Passport\TokenRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PassportAuthController extends Controller
{
    //register

     public function register(Request $request)
     {
       $input = $request->all();

        $validate_data = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => 'required|numeric|min:11',
        ];

        $validator = Validator::make($input, $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error!',
                'errors' => $validator->errors()
            ],400);
        }
        if($request->hasFile('image')){
            $file = $request->file('image');
            $file_name = uniqid(time()).$file->getClientOriginalName();
            Storage::disk('public')->put($file_name, File::get($file));
            $filePath   = 'storage/' . $file_name;
        }else {
            $filePath = "";
          }

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => bcrypt($request->password),
            'image' => $filePath,
            'type' => $input['type'],
            'phone' =>$input['phone'],
            'address' => $input['address'],
            'dob' =>$input['dob'],
        ]);
             return response()->json([
                'success' => true,
                'message' => 'User register succesfully, Use token to authenticate.',
            ], 200);

    }

    //login

    public function login(Request $request)
    {
        $input = $request->only(['email', 'password']);

        $validate_data = [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];

        $validator = Validator::make($input, $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Wrong',
                'errors' => $validator->errors()
            ],400);
        }

        if (auth()->attempt($input)) {
            $token = auth()->user()->createToken('passport_token')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'User login succesfully, Use token to authenticate.',
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'User authentication failed.'
            ], 401);
        }
    }

    //get user information
    public function userInfo(Request $request)
    {
        $user = $request->user();
        return response()->json(['user'=>$user],200);

    }

    //logout

    public function logout(Request $request)
    {
        $access_token = $request->user()->token()->revoke();

        return response()->json([
            'success' => true,
            'message' => 'User logout successfully.'
        ], 200);
    }

    //update

    public function updateUser(Request $request,$id,User $user)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
           'name' => 'required',
           'email' => [Rule::unique("users","email")->ignore($id),'required'],
           'type' => 'required',
           'phone' => 'required|numeric|min:11',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Wrong',
                'errors' => $validator->errors()
            ],401);
        }
        $user = User::find($id);
        if($request->hasFile('image')){
            $file = $request->file('image');
            $file_name = uniqid(time()).$file->getClientOriginalName();
            Storage::disk('public')->put($file_name, File::get($file));
            $filePath   = 'storage/' . $file_name;
        }else{
            $filePath= $user->image;
        }

        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->image = $filePath;
        $user->type = $input['type'];
        $user->phone = $input['phone'];
        $user->address = $input['address'];
        $user->dob = $input['dob'];
        $user->save();
        return response()->json([
        "success" => true,
        "message" => "User updated successfully.",
        "data" => $user
        ],200);
    }

    //delete

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
        "success" => true,
        "message" => "Post deleted successfully.",
        "data" => $user
        ]);
    }

    //list & search

    public function userLists(Request $request)
    {
            $user = User::all();
            // if($request->name){
            //     $user->where('name','LIKE','%'.$request->name.'%');
            // }
            // if($request->email){
            //     $user->where('email','LIKE','%'.$request->email.'%');
            // }
            // if($request->$user->type){
            //     $user->where('type',$request->$user->type);
            // }
            // return $user->orderBy('id','DESC')->paginate(3);
            return $user;
            // return User::when(request('search'),function($query){
            //     $query->where('name', 'LIKE', '%'.request('search').'%')->orwhere('email', 'LIKE', '%'.request('search').'%')->orwhere('type',$request->search);
            // })->orderBy('id', 'DESC')->paginate(3);
    }
    // public function emailSearch(Request $request)
    // {
    //         return User::when(request('search'),function($query){
    //             $query->where('email', 'LIKE', '%'.request('search').'%');
    //         })->orderBy('id', 'DESC')->paginate(3);
    // }
    // public function typeSearch(Request $request)
    // {
    //     return User::where('type',$request->search)->orderBy('id','DESC')->paginate(3);
    // }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => 'required|numeric|min:11',
            'type'=>'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Validation Error!',
                'errors' => $validator->errors()
            ],400);
        }

        if($request->hasFile('image')){
            $file = $request->file('image');
            $file_name = uniqid(time()).$file->getClientOriginalName();
            Storage::disk('public')->put($file_name, File::get($file));
            $filePath   = 'storage/' . $file_name;
        }else {
            $filePath = "";
          }
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => bcrypt($request->password),
            'image' => $filePath,
            'type' => $input['type'],
            'phone' =>$input['phone'],
            'address' => $input['address'],
            'dob' =>$input['dob'],

        ]);
             return response()->json([
                'success' => true,
                "message" => "User created successfully.",
                "data" => $user
            ], 200);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
        return response()->json([
            "success" => false,
            "message" => "User not found."
            ]);
        }
        return response()->json([
        "success" => true,
        "message" => "User retrieved successfully.",
        "data" => $user
        ]);
    }
}



