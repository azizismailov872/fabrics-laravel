<?php

namespace App\Models\Mateirals;

use App\Models\Fabrics\Fabric;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function fabrics(): BelongsTo
    {
        return $this->belongsTo(Fabric::class);
    }

    protected static function booted () {
        static::deleting(function(Material $material) { 
            Fabric::where('material_id',$material->id)->update(['material_id' => null]);
        });
    }
}
