<?php 

namespace App\Repositories\Fabrics;

use App\Models\Fabrics\Fabric;
use Illuminate\Support\Facades\DB;

class FabricsRepository {

    protected $query;

    public function one($id, $withColors = false)
    {   
        return $withColors ? Fabric::with(['colors' => function($query){
            return $query->select('name');
        }])->find($id) : Fabric::find($id);
    }

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
            ->leftJoin('fabrics_colors', 'fabrics.id', '=', 'fabrics_colors.fabric_id')
            ->leftJoin('colors', 'fabrics_colors.color_id', '=', 'colors.id')
            ->orderBy('colors',$sort)
            ->select('fabrics.*', DB::raw('ARRAY_AGG(colors.name) as colors'))
            ->groupBy('fabrics.id');

            return $this->query->paginate($pageSize);
        }
        

        return $this->query->with(['colors' => function($query){
            return $query->select('name');
        }])->orderBy($sortBy,$sort)->paginate($pageSize);
           
    }

    public function create($data)
    {   
        if(!isset($data) && empty($data)) return null;
        $fabric =  Fabric::create($data);
        
        if(isset($data['colors']) && !empty($data['colors'])) {
            $fabric->colors()->attach($data['colors']);
        }

        return (isset($fabric) && !empty($fabric)) ? $fabric : null;
    }

    public function update($id,$updatedData)
    {
        $fabric = Fabric::find($id);

        if(!$fabric) return null;

        if(isset($updatedData['colors']) && !empty($updatedData['colors'])) {
            $fabric->colors()->attach($updatedData['colors']);
        }

        if(empty($data['colors'])) {
            $fabric->colors()->detach();
        }

        return $fabric->update($updatedData);
    }

    public function delete($id)
    {
        if(!isset($id) && empty($id)) {
            return null;
        }

        $fabric =  Fabric::find($id);

        if(!isset($fabric) && empty($fabric)) {
            return [
                'status' => 0,
                'message' => 'Такая модель ткани не найдена'
            ];
        }

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

    public function materials($value)
    {
        $this->query->where('materials','like',"%$value%");
    }

    public function colors($value)
    {
        $colorNames = array_map('trim',explode(',',$value));
        $this->query->whereHas('colors',function($q) use ($colorNames){
            $q->whereIn('name',$colorNames);
        });
    }

    public function quantity_from($value) 
    {
        $this->query->where('quantity','>=',$value);
    }

    public function quantity_to($value) 
    {
        $this->query->where('quantity','<=',$value);
    }
}