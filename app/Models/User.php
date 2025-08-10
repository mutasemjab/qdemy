<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;



class User extends Authenticatable
{
   use HasApiTokens, HasFactory, Notifiable;

   /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   protected $guarded = [];

   /**
    * The attributes that should be hidden for serialization.
    *
    * @var array<int, string>
    */
   protected $hidden = [
      'password',
      'remember_token',
   ];

   public function clas()
   {
      return $this->hasOne(Clas::class);
   }

   public function teacher()
   {
      return $this->hasOne(Teacher::class);
   }


   public function parentt()
   {
      return $this->hasOne(Parentt::class);
   }

   public function parentRelationships()
   {
      return $this->hasMany(ParentStudent::class);
   }

}
