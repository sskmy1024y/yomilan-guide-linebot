<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WantFacility extends Model
{
  public function facility()
  {
    return Facility::find($this->facility_id);
  }
}
