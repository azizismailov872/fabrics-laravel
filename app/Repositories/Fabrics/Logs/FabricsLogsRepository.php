<?php 

namespace App\Repositories\Fabrics\Logs;

use App\Models\Fabrics\Fabric;
use App\Models\Fabrics\FabricsExport;
use App\Models\Fabrics\FabricsImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FabricsLogsRepository {

    const ALL = 'all';
    
    const IMPORT = 'import';

    const EXPORT = 'export';

    protected $exportQuery;

    protected $importQuery;

    public function get($type = 'all',$sortBy = 'date_time',$sort = 'desc', $pageSize = 30,$filters = null)
    {
        switch($type) {
            case self::ALL:
                return $this->getAll($sortBy,$sort,$pageSize,$filters);

            case self::IMPORT:
                return $this->getImports($sortBy,$sort,$pageSize,$filters);

            case self::EXPORT: 
                return $this->getExports($sortBy,$sort,$pageSize,$filters);
            default:
                return null;
        }
    }

    public function getAll($sortBy,$sort,$pageSize,$filters)
    {   
        $this->exportQuery = FabricsExport::query();

        $this->importQuery = FabricsImport::query();

        if(isset($filters) && !empty($filters)) {
            foreach($filters as $filter => $filterValue) {
                if(method_exists($this,$filter)) {
                    $this->$filter($filterValue,self::ALL);
                }
            }
        }

        $imports = $this->importQuery->with('fabric');

        $importsAndExports = $this->exportQuery->union($imports)->orderBy($sortBy,$sort)->paginate($pageSize);

        return $importsAndExports;
    }

    public function getImports($sortBy,$sort,$pageSize,$filters)
    {
        $this->importQuery = FabricsImport::query();

        if(isset($filters) && !empty($filters)) {
            foreach($filters as $filter => $filterValue) {
                if(method_exists($this,$filter)) {
                    $this->$filter($filterValue,self::IMPORT);
                }
            }
        }

        $imports = $this->importQuery->with('fabric')->orderBy($sortBy,$sort)->paginate($pageSize);

        return $imports;
    }

    public function getExports($sortBy,$sort,$pageSize,$filters)
    {
        $this->exportQuery = FabricsExport::query();

        if(isset($filters) && !empty($filters)) {
            foreach($filters as $filter => $filterValue) {
                if(method_exists($this,$filter)) {
                    $this->$filter($filterValue,self::EXPORT);
                }
            }
        }

        $exports = $this->exportQuery->with('fabric')->orderBy($sortBy,$sort)->paginate($pageSize);

        return $exports;
    }

    public function create($type,$data) 
    {           
        if(!isset($data) && empty($data)) return null;

        $data['user_id'] = Auth::check() ? Auth::user()->id : null;

        $log = $type === self::EXPORT ? new FabricsExport : ($type === self::IMPORT ? new FabricsImport : null);

        if($this->checkForUniqueRow($type,$data)) {
            return [
                'isError' => true,
                'type' => 'validation',
                'messages' => [
                    'quantity' => 'Данная запись уже существует в базе данных',
                    'weight' => 'Данная запись уже существует в базе данных',
                    'date_time' => 'Данная запись уже существует в базе данных',
                    'fabric_id' => 'Данная запись уже существует в базе данных',
                ]
            ];
        }

        if($log === null) return ['isError' => true,'type' =>  'typeError','messages' => ['type' => 'Выберите корректный тип']];

        $log->fill($data);
        
        $operation = $type === self::EXPORT ? $this->substractFabrics($log->fabric_id,$log->quantity,$log->weight) : 
        ($type === self::IMPORT ? $this->supplementFabrics($log->fabric_id,$log->quantity,$log->weight) : null);

        if(isset($operation['isError']) && $operation['isError']) return $operation;

        return $operation ? $log->save() : null;
    }

    // This method increases fabrics quantity and weight.
    public function supplementFabrics($fabricId,$quantity,$weight)
    {
        $fabric = Fabric::find($fabricId);

        if(!isset($fabric) && empty($fabric)) return ['isError' => true,'type' => 'validation','messages' => ['fabric_id' => 'Такой модели нет на складе']];

        $fabric->quantity = ($fabric->quantity + $quantity);

        $fabric->weight = ($fabric->weight + $weight);

        return $fabric->save();
    }

    // This method decreases fabrics quantity and weight.
    public function substractFabrics($fabricId,$quantity,$weight)
    {
        $fabric = Fabric::find($fabricId);

        if(!isset($fabric) && empty($fabric)) return ['isError' => true,'type' => 'validation','messages' => ['fabric_id' => 'Такой модели нет на складе']];

        if($fabric->quantity < $quantity) return ['isError' => true,'type' => 'validation','messages' => ['quantity' => 'Вы указали большее колличество чем есть на складе']];

        if($fabric->weight < $weight) return ['isError' => true,'type' => 'validation','messages' => ['weight' => 'Вы указали больший вес тканей чем есть на складе']];

        $fabric->quantity = ($fabric->quantity - $quantity);

        $fabric->weight = ($fabric->weight - $weight);

        return $fabric->save();
    }
    
    public function delete($id,$type)
    {
        $log = $type === self::EXPORT ? FabricsExport::find($id) : ($type === self::IMPORT ? FabricsImport::find($id) : null);

        if(!isset($log) && empty($log)) return ['status' => 0,'message' => 'такая модель не найдена'];

        $operation = $log->type === self::EXPORT ? $this->supplementFabrics($log->fabric_id,$log->quantity,$log->weight) : 
        ($log->type === self::IMPORT ? $this->substractFabrics($log->fabric_id,$log->quantity,$log->weight) : null);

        return $log->delete();
    }

    public function destroy($type,$ids)
    {
        if(!isset($ids) && empty($ids)){
            return null;
        }

        if($type === self::EXPORT) {
            return FabricsExport::destroy($ids);
        }

        if($type === self::IMPORT) {
            return FabricsImport::destroy($ids);
        }
        
        return null;
    }

    public function checkForUniqueRow($type,$data,$updatingId = null)
    {   
        if(!isset($data) && empty($data)) return null;

        $whereQuery = [
            'date_time' => $data['date_time'],
            'quantity' => $data['quantity'],
            'fabric_id' => $data['fabric_id'],
            'user_id' => $data['user_id']
        ];
     
        if($type === self::IMPORT) {
            return $updatingId ? FabricsImport::where('id','!=',$updatingId)->where($whereQuery)->first() 
            : FabricsImport::where($whereQuery)->first(); 
        }
        if($type === self::EXPORT) {
            return $updatingId ? FabricsExport::where('id','!=',$updatingId)->where($whereQuery)->first() 
            : FabricsExport::where($whereQuery)->first(); 
        }
    }
    
    public function quantity($value,$type)
    {   
        switch($type){
            case self::ALL:
                $this->importQuery->where('quantity','=',$value);
                $this->exportQuery->where('quantity','=',$value);
                return;
            case self::IMPORT:
                $this->importQuery->where('quantity','=',$value);
                return;
            case self::EXPORT:
                $this->exportQuery->where('quantity','=',$value);
                return;

        }
    }

    public function quantity_from($value,$type)
    {   
        switch($type){
            case self::ALL:
                $this->importQuery->where('quantity','>=',$value);
                $this->exportQuery->where('quantity','>=',$value);
                return;
            case self::IMPORT:
                $this->importQuery->where('quantity','>=',$value);
                return;
            case self::EXPORT:
                $this->exportQuery->where('quantity','>=',$value);
                return;

        }
    }

    public function quantity_to($value,$type)
    {   
        switch($type){
            case self::ALL:
                $this->importQuery->where('quantity','<=',$value);
                $this->exportQuery->where('quantity','<=',$value);
                return;
            case self::IMPORT:
                $this->importQuery->where('quantity','<=',$value);
                return;
            case self::EXPORT:
                $this->exportQuery->where('quantity','<=',$value);
                return;

        }
    }

    public function date_time($value,$type)
    {
        switch($type){
            case self::ALL:
                $this->importQuery->whereDate('date_time',$value);
                $this->exportQuery->whereDate('date_time',$value);
                return;
            case self::IMPORT:
                $this->importQuery->whereDate('date_time',$value);
                return;
            case self::EXPORT:
                $this->exportQuery->whereDate('date_time',$value);
                return;
        }
    }
}