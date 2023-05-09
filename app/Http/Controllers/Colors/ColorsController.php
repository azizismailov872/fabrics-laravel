<?php

namespace App\Http\Controllers\Colors;

use App\Http\Controllers\Controller;
use App\Http\Requests\Colors\CreateRequest;
use App\Http\Requests\Colors\FilterRequest;
use App\Http\Requests\Colors\UpdateRequest;
use App\Repositories\Colors\ColorsRepository;
use Illuminate\Http\Request;

class ColorsController extends Controller
{   

    public function get(FilterRequest $request, ColorsRepository $repository)
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
        ])->setStatusCode(200);
        
    }

    public function one(ColorsRepository $repository, $id)
    {
        $fabric = $repository->one($id);
        
        return isset($fabric) ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно получены',
            'data' => $fabric
        ])->setStatusCode(201) : response()->json([
            'status' => 0,
            'message' => 'Данные не были найдены',
            'data' => $fabric
        ])->setStatusCode(404);
    }

    public function list(ColorsRepository $repository) 
    {
        $list = $repository->list();

        return response()->json([
            "status" => 1,
            'message' => 'Данные успешно получены',
            'data' => $list
        ]);
    }

    public function create(CreateRequest $request, ColorsRepository $repository)
    {
        $data = $request->validated();

        $color = $repository->create($data);

        return isset($color) ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно сохранены'
        ]) : response()->json([
            "status" => 0,
            'message' => 'Ошибка, данные не были сохранены'
        ]);
    }

    public function update(UpdateRequest $request, ColorsRepository $repository,$id)
    {
        $data = $request->validated();

        $response = $repository->update($id,$data);

        return $response ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно обновлены'
        ])->setStatusCode(200) : response()->json([
            'status' => 0,
            'message' => 'Ошибка, данные не были обновлены'
        ])->setStatusCode(500);
    }  

    public function delete(ColorsRepository $repository, $id)
    {
        $response = $repository->delete($id);

        if(is_array($response)) return response()->json($response);

        return $response ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно удалены'
        ])->setStatusCode(200) : response()->json([
            "status" => 0,
            'message' => 'Ошибка, данные не были удалены'
        ])->setStatusCode(500);
    }

    public function destroy(Request $request, ColorsRepository $repository)
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
        ])->setStatusCode(201) : response()->json([
            'status' => 0,
            'message' => 'Ошибка при удалении данных'
        ])->setStatusCode(500);
       
    }
    
}
