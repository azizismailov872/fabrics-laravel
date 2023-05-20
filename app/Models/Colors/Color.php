<?php

namespace App\Models\Colors;

use App\Models\Fabrics\Fabric;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Color extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];


    public function fabrics(): BelongsTo
    {
        return $this->belongsTo(Fabric::class);
    }

    protected static function booted () {
        static::deleting(function(Color $color) { 
            Fabric::where('color_id',$color->id)->update(['color_id' => null]);
        });
    }
}
