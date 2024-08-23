<?php

namespace App\Models;

use CodeIgniter\Model;

class ReporteModel extends Model
{
    // protected $DBGroup          = 'default';
    protected $table            = 'reporte';
    protected $table_opera      = 'operador';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = [
        'id' , 'id_operador', 'fecha', 'respuesta_api','csv', 'estado'
    ];

    // // Dates
    // protected $useTimestamps = false;
    // protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // // Validation
    // protected $validationRules      = [];
    // protected $validationMessages   = [];
    // protected $skipValidation       = false;
    // protected $cleanValidationRules = true;

    // // Callbacks
    // protected $allowCallbacks = true;
    protected $beforeInsert   = ['beforeInsert'];
    // protected $afterInsert    = [];
    protected $beforeUpdate   = ['beforeUpdate'];
    // protected $afterUpdate    = [];
    // protected $beforeFind     = [];
    // protected $afterFind      = [];
    // protected $beforeDelete   = [];
    // protected $afterDelete    = [];
    public function findReporteById($id){
        $reporte = $this->asArray()->where(['id' => $id])->first();
        if( !$reporte){
            throw new \Exception('El reporte no existe.');
        }

        return $reporte;
    }
    
	public function insertReport($data){

        $query = "INSERT INTO ".$this->table." (id_operador, fecha, respuesta_api,csv, estado, sala) 
                 VALUES (".$data['id_operador'].",'".$data['fecha']."','".$data['respuesta_api']."', '".$data['csv']."',  ".$data['estado'].",  ".$data['sala'].")";
                 
        $query = $this->db->query($query);

     return $query;
        
    }

    //Reportes con filtros
    public function getReport($data){        
        
        if($data['error'] == 0){
            if($data['id_sala'] == '0'){
                $array = array( 'reporte.id_operador =' => $data['id_operador'], 'reporte.fecha >= ' => $data['fecha_inicio'], 'reporte.fecha <= ' => $data['fecha_fin']);
            }else{
                $array = array('reporte.sala = ' => $data['id_sala'], 'reporte.id_operador =' => $data['id_operador'], 'reporte.fecha >= ' => $data['fecha_inicio'], 'reporte.fecha <= ' => $data['fecha_fin']);
            }
        }else{            
            if($data['id_sala'] == '0'){
                $array = array( 'reporte.id_operador =' => $data['id_operador']); 
            }else{
                $array = array('reporte.sala = ' => $data['id_sala'], 'reporte.id_operador =' => $data['id_operador']); 
            }           
        }


        $db = \Config\Database::connect();
        $builder = $db->table($this->table)
        ->select('reporte.id as reporte_id, reporte.fecha, reporte.respuesta_api, reporte.csv, reporte.estado, operador.nombre, operador.nit, operador.contacto, operador.correo, reporte.sala')
        ->join('operador', 'operador.operador_id = reporte.id_operador')
        ->where($array)
        ->orderBy('reporte.fecha', 'DESC')
        ->get()->getResult();    
        if(empty($builder)){
           return true;
        }
        return ['error' => false, 'msg' => $builder]; 
    }
    
    public function getToken($data){            
        
        $db = \Config\Database::connect();
        $builder = $db->table($this->table_opera);
        $validate = $builder->select('operador_id')
        ->where('token', $data)
        ->get()->getResult();   

        if(empty($validate)){
           return true;
        }else{
           return false;
        }               
    }
}
