<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class MainController extends Controller
{
    public function submit($request, $item){
        DB::beginTransaction();
        try {
            $item->name = $request->name;
            $item->save();
        } catch(\Exception $e)
        {
            DB::rollback();
            return $this->responseJson($e->getMessage(),0);
        }
        DB::commit();
        return $this->responseJson("Berhasil",1);
    }
    public function destroy($item){
        DB::beginTransaction();
        try {
            $item->delete();
        } catch(\Exception $e)
        {
            DB::rollback();
            return $this->responseJson("Gagal menghapus data",0);
        }
        DB::commit();
        return $this->responseJson("Berhasil menghapus data ".$item->name, 1);
    }
    public function listQuery($tableName){
        $data = DB::select('select id, name from '.$tableName);
        return $this->responseJson("List data ", 1, $data, 200);
    }
}
