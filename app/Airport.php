<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = ['id'];

    public function __construct(array $data = [])
    {
        foreach($data as $key => $value) {
            if ($value == '') {
                $value = null;
            }

            if ($key != '') {
                $this->$key = $value;
            }
        }
    }

    public function frequencies()
    {
        return $this->hasMany('App\AirportFrequency', 'airport_ref');
    }

    public function runways()
    {
        return $this->hasMany('App\Runway', 'airport_ref');
    }
}
