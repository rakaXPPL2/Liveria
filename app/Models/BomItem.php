<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BomItem extends Model
{
    protected $fillable = ['design_id', 'part_name', 'quantity', 'spec_note'];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
}