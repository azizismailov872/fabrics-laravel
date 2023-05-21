<?php

namespace App\Http\Controllers\Fabrics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Fabrics\CreateRequest;
use App\Http\Requests\Fabrics\FilterRequest;
use App\Http\Requests\Fabrics\UpdateRequest;
use App\Repositories\Fabrics\FabricsRepository;

class FabricsController extends Controller
{
    public function get(FilterRequest $request,FabricsRepository $repository)
    {   
        $sortBy = $request->sortBy ? $request->sortBy : 'created_at';

        $sort = $request->sort ? $request->sort : 'desc';

        $pageSize = $request->pageSize ? $request->pageSize : 30;

        $filters = $request->validated();

        $fabrics = $repository->get($sortBy,$sort,$pageSize,$filters);

        return response()->json([
            'status' => 1,
            'data' => $fabrics->items(),
            'total' => $fabrics->total(),
            'per_page' => $fabrics->perPage(),
            'current_page' => $fabrics->currentPage(),
            'last_page' => $fabrics->lastPage(),
            'from' => $fabrics->firstItem(),
            'to' => $fabrics->lastItem(),
        ],200);
    }

    public function one(FabricsRepository $repository, $id)
    {
        $fabric = $repository->one($id,true,true);
        
        return isset($fabric) ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно получены',
            'data' => $fabric
        ],200): response()->json([
            'status' => 0,
            'message' => 'Такая модель не была найдена',
            'data' => $fabric
        ],404);
    }

    public function create(CreateRequest $request,FabricsRepository $repository)
    {
        $data = $request->validated();

        $fabric = $repository->create($data);

        return isset($fabric) ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно сохранены',
        ],201) : response()->json([
            "status" => 0,
            'message' => 'Ошибка при сохранении данных'
        ],500);
    }

    public function update(UpdateRequest $request, FabricsRepository $repository, $id)
    {   
        $updatedData = $request->validated();
        
        $response = $repository->update($id,$updatedData);

        if(is_null($response)) {
            return response()->json([
                'status' => 0,
                'message' => 'Такая модель не найдена'
            ],404);
        }

        return $response ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно обновлены'
        ],200) : response()->json([
            'status' => 0,
            'message' => 'Ошибка при сохранении данных'
        ],500);

    }

    public function delete(FabricsRepository $repository, $id)
    {
        $response = $repository->delete($id);

        if(is_null($response)) return response()->json([
            'status' => 0,
            'message' => 'Такая модель не найдена'
        ],404);

        return $response ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно удалены'
        ],200) : response()->json([
            "status" => 0,
            'message' => 'Ошибка, данные не были удалены'
        ],500);
    }

    public function destroy(Request $request, FabricsRepository $repository)
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
