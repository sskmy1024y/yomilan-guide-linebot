<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WantFacility extends Model
{
  protected $table = "want_facility";

  protected $fillable = [
    'facility_id',
  ];

  public function facility()
  {
    return Facility::find($this->facility_id);
  }
}
