<?php

namespace App\Http\Controllers\Api;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Validator;

class UsersController extends Controller
{
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
            ]);
        }
        Excel::import(new UsersImport, $request->file('file'));
        return response()->json([
        'result' => 1,
        'message' => 'Import successfully'
       ]);
    }
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}



