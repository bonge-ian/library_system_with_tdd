<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $guarded = [];

    // cast the dob into carbon instance like timestamps
    protected $dates = ['dob'];

}
