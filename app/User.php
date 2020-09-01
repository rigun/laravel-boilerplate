<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use \App\Http\Traits\UsesUuid;
    use SoftDeletes; 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','role_id','createdBy','updatedBy'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $appends  = [
        'role_name',  'creator_name','updater_name'
    ];
    public function getJWTIdentifier()
    {
      return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
      return [];
    }
    public function role(){
        return $this->belongsTo("App\Role",'role_id','id');
    }
    public function getRoleNameAttribute(){
        if($temp = $this->role()->first()){
            return $temp->name;
        }
        return null;
    }
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
}
