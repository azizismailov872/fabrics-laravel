<?php 

namespace App\Repositories\Colors;

use App\Models\Colors\Color;

class ColorsRepository {

    protected $query;

    public function one($id)
    {   
        return Color::find($id);
    }

    public function get($sort = 'asc',$pageSize = 30,$filters = null)
    {   
        $this->query = Color::query();

        if(isset($filters) && !empty($filters)) {
            foreach($filters as $filter => $filterValue) {
                if(method_exists($this,$filter)) {
                    $this->$filter($filterValue);
                }
            }
        }

        return $this->query->orderBy('name',$sort)->paginate($pageSize);  
    }

    public function list()
    {
        return Color::select(['id','name'])->get()->toArray();
    }

    public function create($data)
    {   
        if(!isset($data) && empty($data)) return null;

        $color =  Color::create($data);

        return (isset($color) && !empty($color)) ? $color : null;
    }

    public function update($id,$updatedData)
    {
        $color = Color::find($id);

        if(!$color) return null;

        return $color->update($updatedData);
    }

    public function delete($id)
    {
        if(!isset($id) && empty($id)) {
            return null;
        }

        $color = Color::find($id);

        if(!isset($color) && empty($color)) {
            return ['notFound' => true];
        }

        return $color->delete($id);
    }

    public function destroy($ids)
    {
        if(!isset($ids) && empty($ids)){
            return null;
        }

        return Color::destroy($ids);
    }


    public function name($value)
    {   
        $modelValue = trim($value);
        $this->query->where('name','like',"%$modelValue%");
    }

   
}