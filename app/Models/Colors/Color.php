<?php

namespace App\Models\Colors;

use App\Models\Fabrics\Fabric;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Color extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];


    public function fabrics(): BelongsToMany
    {
        return $this->belongsToMany(Fabric::class,'fabrics_colors','color_id','fabric_id');
    }

    protected static function booted () {
        static::deleting(function(Color $color) { 
             $color->fabrics()->detach();
        });
    }
}
