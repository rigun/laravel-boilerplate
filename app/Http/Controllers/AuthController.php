<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\User;
use App\Role;
use App\UserDetail;
use DB;
use Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
      $this->validateWith([
          'username' => 'required',
          'password' => 'required'
      ]);
      $credentials = $request->only(['username', 'password']);
      if (!$token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized', 'status' => -1], 401);
      }
      return $this->respondWithToken($token);
    }
    public function registration(AuthRequest $request)
    {
      DB::beginTransaction();
      try {

        $role = Role::where('name', 'guest')->first();

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->role_id = $role->id;
        $user->createdBy = $request->username;
        $user->save();

      } catch(\Exception $e)
      {
          DB::rollback();
          return $this->responseJson($e->getMessage(),0);
      }
      DB::commit();

      return $this->login($request);
    }
    
    public function logout(Request $request){
      try{
        auth()->logout();
        return $this->responseJson("Logout berhasil",1);
      }catch(\Exception $e){
        return $this->responseJson("Terjadi kesalahan coba lagi",0);
      }
    }
}
