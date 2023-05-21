<?php 

namespace App\Repositories\Fabrics;

use App\Models\Fabrics\Fabric;

class FabricsRepository {

    protected $query;

    public function get($sortBy = 'created_at',$sort = 'desc',$pageSize = 30,$filters = null)
    {   
        $this->query = Fabric::query();

        if(isset($filters) && !empty($filters)) {
            foreach($filters as $filter => $filterValue) {
                if(method_exists($this,$filter)) {
                    $this->$filter($filterValue);
                }
            }
        }

        if($sortBy == 'colors') {
            $this->query
            ->leftJoin('colors','fabrics.color_id','=','colors.id')
            ->orderBy('colors.name',$sort)
            ->select('fabrics.*','colors.name as color');

            return $this->query->with('material')->paginate($pageSize);
        }
        
        if($sortBy == 'materials') {
            $this->query
            ->leftJoin('materials','fabrics.material_id','=','materials.id')
            ->orderBy('materials.name',$sort)
            ->select('fabrics.*','materials.name as material');

            return $this->query->with('color')->paginate($pageSize);
        }

        return $this->query->with(['color','material'])->orderBy($sortBy,$sort)->paginate($pageSize);
           
    }

    public function one($id, $withColor = false, $withMaterial = false)
    {   
        return $withColor ? 
        ($withMaterial ? Fabric::with(['color','material'])->find($id) : Fabric::with('color')->find($id)) 
        : Fabric::find($id);
    }

    public function create($data)
    {   
        if(!isset($data) && empty($data)) return null;

        $fabric = Fabric::create($data);

        return (isset($fabric) && !empty($fabric)) ? $fabric : null;
    }

    public function update($id,$data)
    {   
        $fabric = Fabric::find($id);

        if(!$fabric) return null;

        return $fabric->update($data);
    }

    public function delete($id)
    {
        if(!isset($id) && empty($id)) {
            return null;
        }

        $fabric =  Fabric::find($id);

        if(!isset($fabric) && empty($fabric)) return null;

        return $fabric->delete($id);
    }

    public function destroy($ids)
    {
        if(!isset($ids) && empty($ids)){
            return null;
        }

        return Fabric::destroy($ids);
    }

    public function model($value)
    {   
        $modelValue = trim($value);
        $this->query->where('model','like',"%$modelValue%");
    }

    public function quantity($value)
    {
        $this->query->where('quantity',$value);
    }

    public function quantity_from($value) 
    {
        $this->query->where('quantity','>=',$value);
    }

    public function quantity_to($value) 
    {
        $this->query->where('quantity','<=',$value);
    }

    public function weight($value)
    {
        $this->query->where('weight',$value);
    }

    public function weight_from($value) 
    {
        $this->query->where('weight','>=',$value);
    }

    public function weight_to($value) 
    {
        $this->query->where('weight','<=',$value);
    }


    public function materials($values)
    {
        if(is_array($values) && !empty($values)) {
            $this->query->whereIn('material_id',$values);
        }
    }

    public function colors($values)
    {
        if(is_array($values) && !empty($values)) {
            $this->query->whereIn('color_id',$values);
        }
    }

    public function color_id($value)
    {
        is_array($value) ? $this->query->whereIn('color_id',$value) : $this->query->where('color_id',$value);
    }

    public function material_id($value)
    {
        is_array($value) ? $this->query->whereIn('material_id',$value) : $this->query->where('material_id',$value);
    }

   

   /*  public function colors($value)
    {
        $colorNames = array_map('trim',explode(',',$value));
        $this->query->whereHas('colors',function($q) use ($colorNames){
            $q->whereIn('name',$colorNames);
        });
    } */

    
}