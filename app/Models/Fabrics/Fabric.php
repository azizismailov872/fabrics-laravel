<?php

namespace App\Models\Fabrics;

use App\Models\Colors\Color;
use App\Models\Mateirals\Material;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fabric extends Model
{
    use HasFactory;

    protected $fillable = ['model','quantity','material_id','color_id','weight'];

    public function color(): HasOne
    {
        return $this->hasOne(Color::class,'id','color_id');
    }

    public function material(): HasOne
    {
        return $this->hasOne(Material::class,'id','material_id');
    }

    protected static function booted () {
        static::deleting(function(Fabric $fabric) { 
            //actions
        });
    }
}
