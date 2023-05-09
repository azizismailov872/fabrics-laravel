<?php

namespace App\Models\Fabrics;

use App\Models\Colors\Color;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fabric extends Model
{
    use HasFactory;

    protected $fillable = ['model','quantity','materials'];


    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class,'fabrics_colors','fabric_id','color_id');
    }

    protected static function booted () {
        static::deleting(function(Fabric $fabric) { 
             $fabric->colors()->detach();
        });
    }
}
