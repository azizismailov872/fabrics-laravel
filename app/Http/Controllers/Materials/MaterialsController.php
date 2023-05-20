<?php

namespace App\Http\Controllers\Materials;

use App\Http\Controllers\Controller;
use App\Http\Requests\Materials\CreateRequest;
use App\Http\Requests\Materials\FilterRequest;
use App\Http\Requests\Materials\UpdateRequest;
use App\Repositories\Materials\MaterialsRepository;
use Illuminate\Http\Request;

class MaterialsController extends Controller
{
    public function get(FilterRequest $request, MaterialsRepository $repository)
    {
        $filters = $request->validated();

        $sort = $request->sort ? $request->sort : 'asc';

        $pageSize = $request->pageSize ? $request->pageSize : 30;

        $colors = $repository->get($sort,$pageSize,$filters);

        return response()->json([
            'status' => 1,
            'data' => $colors->items(),
            'total' => $colors->total(),
            'per_page' => $colors->perPage(),
            'current_page' => $colors->currentPage(),
            'last_page' => $colors->lastPage(),
            'from' => $colors->firstItem(),
            'to' => $colors->lastItem(),
        ],200);
        
    }

    public function one(MaterialsRepository $repository, $id)
    {
        $material = $repository->one($id);
        
        return isset($material) ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно получены',
            'data' => $material
        ],200) : response()->json([
            'status' => 0,
            'message' => 'Данные не были найдены',
            'data' => $material
        ],404);
    }

    public function list(MaterialsRepository $repository) 
    {
        $list = $repository->list();

        return response()->json([
            "status" => 1,
            'message' => 'Данные успешно получены',
            'data' => $list
        ],200);
    }

    public function create(CreateRequest $request, MaterialsRepository $repository)
    {
        $data = $request->validated();

        $material = $repository->create($data);

        return isset($material) ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно сохранены'
        ],201) : response()->json([
            "status" => 0,
            'message' => 'Ошибка, данные не были сохранены'
        ],500);
    }

    public function update(UpdateRequest $request, MaterialsRepository $repository,$id)
    {
        $data = $request->validated();

        $response = $repository->update($id,$data);

        if(is_null($response)) {
            return response()->json([
                'status' => 0,
                'message' => 'Такой материал не был найден'
            ],404);
        }

        return $response ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно обновлены'
        ],200) : response()->json([
            'status' => 0,
            'message' => 'Ошибка, данные не были обновлены'
        ],500);
    }  

    public function delete(MaterialsRepository $repository, $id)
    {
        $response = $repository->delete($id);

        if(is_array($response) && isset($response['notFound']) && $response['notFound'] === true)
        {
            return response()->json([
                'status' => 0,
                'message' => 'Такой материал не был найден'
            ],404);
        }

        return $response ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно удалены'
        ],200) : response()->json([
            "status" => 0,
            'message' => 'Ошибка, данные не были удалены'
        ],500);
    }

    public function destroy(Request $request, MaterialsRepository $repository)
    {
        $ids = $request->ids;

        if(is_string($ids)) {
            $ids = explode(',',$ids);
        }

        if(!isset($ids) && empty($ids)) {
            return response()->json([
                'status' => 0,
                'message' => 'Данные не найдены'
            ])->setStatusCode(404);
        }

        $response = $repository->destroy($ids);
        
        return ($response !== null && $response !== false) ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно удалены'
        ],200) : response()->json([
            'status' => 0,
            'message' => 'Ошибка при удалении данных'
        ],500);
       
    }
}
