<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Hash;
use App\User;

class MainController extends Controller
{
    public function getData($request, $roleName)
    {
        $show = $request->show != null ? $request->show : 10;
        $sortBy = $request->sortBy != null ? $request->sortBy : "created_at";
        $sorting = $request->sorting != null ? $request->sorting : "DESC";
        $search = $request->search;
        try {

            $data = User::whereHas('role', function($query) use($roleName){
                            $query->where('name', $roleName);
                        })->where(function($query) use($search){
                            $query->where('name', 'like',  '%'.$search.'%')
                                ->orwhere('username', 'like',  '%'.$search.'%');
                        })
                    ->select('id','name','username','role_id','createdBy','updatedBy')
                    ->orderBy($sortBy, $sorting)
                    ->paginate($show);
            return $this->responseJson("Data User ".$roleName,1, $data, 200);
        } catch (\Exception $e) {
            return $this->responseJson($e->getMessage(),0);
        }
    }

    public function store($request, $item){
        DB::beginTransaction();
        try {
            $item->name = $request->name;
            $item->username = $request->username;
            $item->password = bcrypt($request->username);
            $item->createdBy = auth()->user()->username;
            $item->save();
        } catch(\Exception $e)
        {
            DB::rollback();
            return $this->responseJson($e->getMessage(),0);
        }
        DB::commit();
        return $this->responseJson("Berhasil menambahkan data",1);
    }
    public function update($request, $item){
        DB::beginTransaction();
        try {
            $item->name = $request->name;
            $item->updatedBy = auth()->user()->username;
            $item->save();
        } catch(\Exception $e)
        {
            DB::rollback();
            return $this->responseJson($e->getMessage(),0);
        }
        DB::commit();
        return $this->responseJson("Berhasil mengubah data",1);
    }
    public function destroy($item){
        DB::beginTransaction();
        try {
            $item->deletedBy = auth()->user()->username;
            $item->save();
            $item->delete();
        } catch(\Exception $e)
        {
            DB::rollback();
            return $this->responseJson("Gagal menghapus data",0);
        }
        DB::commit();
        return $this->responseJson("Berhasil menghapus data ".$item->name, 1);
    }
    public function getAuthenticatedUser()
    {
        try{
            $user = $this->userData();
            return $this->responseJson("Get my data","1", $user, 200);
        }catch(Exception $e){
            return $this->responseJson("Gagal mengambil data",0);
        }
    }
    
    public function changepassword(Request $request)
    {
        $this->validateWith([
            'old_password' => 'required',
            'new_password' => 'required'
        ]);
        if($request->old_password == $request->new_password){
          return $this->responseJson("Password tidak boleh sama",0);
        }
        DB::beginTransaction();
        try {
            $user = auth()->user();
            if(Hash::check($request->old_password, $user->password)){
                $user->password = bcrypt($request->new_password);    
            }else{
                return $this->responseJson("Password salah, silahkan coba lagi",0);
            }
            $user->updatedBy = $user->username;
            $user->save();
        } catch(\Exception $e)
        {
            DB::rollback();
            return $this->responseJson($e->getMessage(),0);
        }
        DB::commit();
        return $this->responseJson('Berhasil mengubah password',1, [], 200);
    }
}
