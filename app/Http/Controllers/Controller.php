<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function responseJson($msg,$status,$data = [
        "last_page" => 0,
        "data" =>  [], 
        "total" => 0
      ],$code = 200){
          return response()->json([
              "msg" => $msg,
              "status" => $status,
              "data" => $data
          ],$code);
      }
      public function respondWithToken($token)
      {
        $userData = $this->userData();
        if($userData['status'] == 1){
          return response()->json([
            'msg' => 'Berhasil',
            'access_token' => $token,
            'user' => $userData,
            'status' => 1
          ]);
        }else{
          return response()->json([
            'msg' => 'Mohon maaf, anda tidak dapat login karena status anda tidak aktif',
            'access_token' => null,
            'user' => null,
            'status' => 0
          ]);
        }
      }
      public function userData(){
        $user = auth()->user();
        return [
          'id' => $user->id,
          'name' => $user->name,
          'status' => $user->status,
          'role' => $user->role_name,
          'username' => $user->username
        ];
      }
}
