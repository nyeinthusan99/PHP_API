<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;

class ForgetPasswordController extends Controller
{
    protected function sendResetLinkResponse(Request $request)
        {

            //send link to mail
            $input = $request->only('email');

            $validator = Validator::make($input, [
              'email' => "required|email"
            ]);

            if ($validator->fails()) {
              return response()->json([
                'success' => false,
                'message' => 'Wrong',
                'errors' => $validator->errors()
            ]);
            }

            $response =  Password::sendResetLink($input);

            if($response == Password::RESET_LINK_SENT){
              $message = "Mail send successfully";
            }
            else{
              $message = "Email could not be sent to this email address";
            }

            $response = ['message' => $message];

            return response($response, 200);
        }
}
