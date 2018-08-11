<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Runway extends Model
{
    protected $fillable = ['id'];

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if ($value == '') {
                $value = null;
            }

            if ($key != '') {
                $this->$key = $value;
            }
        }
    }
}
