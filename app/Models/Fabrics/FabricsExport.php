<?php

namespace App\Models\Fabrics;

use App\Models\Fabrics\Fabric;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FabricsExport extends Model
{
    use HasFactory;

    protected $fillable = ['quantity','weight','date_time','fabric_id','messaage','user_id','type'];

    protected $table = 'fabrics_exports';

    public function fabric()
    {   
        return $this->belongsTo(Fabric::class,'fabric_id');
    }
}
