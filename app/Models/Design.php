<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $fillable = [
        'code_id', 'title', 'workflow_type', 'material',
        'mass_grams', 'poly_count', 'render_image', 'glb_file',
        'stl_file', 'blend_file',
    ];

    public function bomItems()
    {
        return $this->hasMany(BomItem::class);
    }
}