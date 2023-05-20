<?php 

namespace App\Repositories\Materials;

use App\Models\Mateirals\Material;

class MaterialsRepository {
    protected $query;

    public function get($sort = 'asc',$pageSize = 30,$filters = null)
    {   
        $this->query = Material::query();

        if(isset($filters) && !empty($filters)) {
            foreach($filters as $filter => $filterValue) {
                if(method_exists($this,$filter)) {
                    $this->$filter($filterValue);
                }
            }
        }

        return $this->query->orderBy('name',$sort)->paginate($pageSize);
    }

    public function one($id)
    {   
        return Material::find($id);
    }

    public function list()
    {
        return Material::select(['id','name'])->get()->toArray();
    }

    public function create($data)
    {   
        if(!isset($data) && empty($data)) return null;

        $material =  Material::create($data);

        return (isset($material) && !empty($material)) ? $material : null;
    }

    public function update($id,$updatedData)
    {
        $material = Material::find($id);

        if(!$material) return null;

        return $material->update($updatedData);
    }

    public function delete($id)
    {
        if(!isset($id) && empty($id)) {
            return null;
        }

        $material = Material::find($id);

        if(!isset($material) && empty($material)) {
           return ['notFound' => true];
        }

        return $material->delete($id);
    }

    public function destroy($ids)
    {
        if(!isset($ids) && empty($ids)){
            return null;
        }

        return Material::destroy($ids);
    }


    public function name($value)
    {   
        $modelValue = trim($value);
        $this->query->where('name','like',"%$modelValue%");
    }
}