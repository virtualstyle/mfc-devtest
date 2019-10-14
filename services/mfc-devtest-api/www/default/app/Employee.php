<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
  protected $fillable = array('first_name', 'last_name', 'email', 'phone', 'company');

  public function companies()
  {
      return $this->belongsTo(Company::class);
  }
}
