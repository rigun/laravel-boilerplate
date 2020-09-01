<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $hidden = [
        'createdBy','updatedBy'
    ];

    protected $appends  = [
        'creator_name','updater_name'
    ];
    public function getCreatorNameAttribute(){
        return $this->creator()->first();
    }
    public function getUpdaterNameAttribute(){
        return $this->updater()->first();
    }
    public function creator(){
        return $this->belongsTo('App\User','createdBy','username')->pluck('name');
    }
    public function updater(){
        return $this->belongsTo('App\User','updatedBy','username')->pluck('name');
    }
    public function user(){
        return $this->hasMany('App\User','role_id','id');
    }
}
