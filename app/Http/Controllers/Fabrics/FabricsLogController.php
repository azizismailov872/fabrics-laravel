<?php

namespace App\Http\Controllers\Fabrics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Fabrics\Logs\FilterRequest;
use App\Http\Requests\Fabrics\Logs\UpdateRequest;
use App\Http\Requests\Fabrics\Logs\CreateRequest;
use App\Repositories\Fabrics\Logs\FabricsLogsRepository;
use Exception;

class FabricsLogController extends Controller
{   
   
    public function get(FilterRequest $request,FabricsLogsRepository $repository)
    {   
        $filters = $request->validated();

        $type = $request->type ? $request->type : 'all';

        $sortBy = $request->sortBy ? $request->sortBy : 'date_time';

        $sort = $request->sort ? $request->sort : 'desc';

        $pageSize = $request->pageSize ? $request->pageSize : 30;

        $data = $repository->get($type,$sortBy,$sort,$pageSize,$filters);

        return response()->json([
            'status' => 1,
            'message' => 'Данные успешно получены',
            'data' => $data->items(),
            'total' => $data->total(),
            'per_page' => $data->perPage(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'from' => $data->firstItem(),
            'to' => $data->lastItem(),
        ]);
    }

    public function create(CreateRequest $request, FabricsLogsRepository $repository)
    {
        $data = $request->validated();

        $log = $repository->create($data['type'],$data);

        if(isset($log['isError']) && $log['isError']) {
            if(isset($log['messages'])) throw ValidationException::withMessages($log['messages']);
        }

        return $log ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно сохранены',
            'data' => $log
        ],201) : response()->json([
            "status" => 0,
            'message' => 'Ошибка, данные не были сохранены',
            'data' => null
        ],500);
    }


    public function delete(Request $request,FabricsLogsRepository $repository,$id)
    {   
        $type = $request->type ? $request->type : null;

        if(!$type) return response()->json([
            "status" => 0,
            'message' => 'Ошибка, выберите тип удаляемого файла'
        ],422);

        $response = $repository->delete($id,$type);

        if(is_array($response)) return response()->json($response);

        return $response ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно удалены'
        ],200) : response()->json([
            "status" => 0,
            'message' => 'Ошибка, данные не были удалены'
        ],500);
    }

    public function destroy(Request $request,FabricsLogsRepository $repository)
    {
        $ids = $request->ids;

        $type = $request->type;

        if(is_string($ids)) {
            $ids = explode(',',$ids);
        }

        if(!isset($ids) && empty($ids)) {
            return response()->json([
                'status' => 0,
                'message' => 'Выберите записи которые хотите удалить !'
            ],422);
        }

        if(!isset($type) && empty($type)){
            return response()->json([
                'status' => 0,
                'message' => 'Выберите тип ввоз или вывоз'
            ],422);
        }

        $response = $repository->destroy($type,$ids);
        
        return ($response !== null && $response !== false) ? response()->json([
            'status' => 1,
            'message' => 'Данные успешно удалены'
        ],200) : response()->json([
            'status' => 0,
            'message' => 'Ошибка при удалении данных'
        ],500);
    }
}
