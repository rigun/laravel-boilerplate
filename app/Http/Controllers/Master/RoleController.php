<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Master\RoleRequest;
use App\Http\Requests\GetDataRequest;
use App\Role;

class RoleController extends Controller
{
    private $mainController;

    public function __construct()
    {
        $this->mainController = new MainController();
    }

    public function getData(GetDataRequest $request)
    {
        $show = $request->show != null ? $request->show : 10;
        $sortBy = $request->sortBy != null ? $request->sortBy : "name";
        $sorting = $request->sorting != null ? $request->sorting : "DESC";
        $search = $request->search;
        try {

            $data = Role::where('name','like','%'.$search.'%');
            $data = $data->select('id','name','createdBy','updatedBy');
            $data = $data->orderBy($sortBy, $sorting);
            $data = $data->paginate($show);
            return $this->responseJson("Data Role",1, $data, 200);
        } catch (\Exception $e) {
            return $this->responseJson($e->getMessage(),0);
        }
    }

    public function update(RoleRequest $request, $id){
        $item = Role::findOrFail($id);
        $item->updatedBy = auth()->user()->username;
        return $this->mainController->submit($request,$item);
    }

    public function store(RoleRequest $request){
        $item = new Role();
        $item->createdBy = auth()->user()->username;
        return $this->mainController->submit($request,$item);
    }
   
    public function destroy($id){
      
        $item = Role::findOrfail($id);
        if($item->user()->exists()){
            return $this->responseJson("Tidak dapat menghapus data ini",0);
        }
        return $this->mainController->destroy($item);
    }

    public function listQuery(){
        return $this->mainController->listQuery('roles');
    }
}
