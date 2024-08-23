<?php

namespace App\Models;

use CodeIgniter\Model;

class OperadorModel extends Model
{
    // protected $DBGroup          = 'default';
    protected $table            = 'operador';
    protected $primaryKey       = 'operador_id';
    protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = [
        'operador_id' , 'usuario', 'nombre', 'nit', 'direccion', 'contacto', 'token', 'correo', 'telefono', 'tipo', 'estado', 'password'
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

    public function findOperadorByContrato(string $contrato){
        $operador = $this->asArray()->where(['contrato' => $contrato])->first();

        if( !$operador){
            throw new \Exception('El operador no existe.');
        }

        return $operador;
    }
    public function getToken($data){         
        
            $db = \Config\Database::connect();
            $builder = $db->table($this->table);
            $validate = $builder->select('token')
            ->where('token', $data['token'])
            ->get()->getResult();  
   
            if($db->resultID->num_rows == 0){
               return 0;
            }else{
               return true;
            }               
    }
    public function getEmailValidate($data){ 
            $db = \Config\Database::connect();
            $builder = $db->table($this->table);
            $builder->select('operador_id')
            ->where('correo', $data)
            ->get()->getResult();   
            
            if($db->resultID->num_rows == 0){
               return 0;
            }else{
               return 1;
            }               
    }

    public function getIdOperadorByEmail($data){
            $db = \Config\Database::connect();
            $builder = $db->table($this->table);
            $user= $builder->select('nombre, nit, direccion, contacto, correo, telefono, tipo, usuario, estado')
            ->where('correo', $data)
            ->get()->getResult();  
            if(empty($user)){
                return false;
            }else{
                return $user[0]; 
            }       
    }

    public function getIdOperadorByToken($data){
            $db = \Config\Database::connect();
            $builder = $db->table($this->table);
            $user= $builder->select('operador_id, sala, nombre, contacto')
            ->where('token', $data['token'])
            ->get()->getResult();     
         return $user[0];        
    }

	public function getByTokenAllInfoUser($data){
            $db = \Config\Database::connect();
            $builder = $db->table($this->table);
            $user= $builder->select('*')
            ->where('token', $data['token'])
            ->get()->getResult();            
         return $user[0];        
    }

	public function getAllUser(){
            $db = \Config\Database::connect();
            $builder = $db->table($this->table);
            $user= $builder->select('*')
            ->get()->getResult(); 
            
        $data = [ 'response'=> true,  'users' => $user];        
         return $data;
        
    }
    

	public function updateToken($data){
        $query = "UPDATE ".$this->table." SET token = '".$data['token']."' WHERE usuario = '".$data['usuario']."'";	
        $query = $this->db->query($query);
     return $query;    
    }

    public function loginUser($data){ 
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $user= $builder
        ->select('operador_id')
        ->select('nit')
        ->select('nombre')
        ->select('direccion')
        ->select('contacto')
        ->select('correo')
        ->select('telefono')
        ->select('tipo')
        ->select('sala')
        ->where('usuario', $data['usuario'])
        ->where('password', $data['password'])
        ->get()->getResult();   
        if(empty($user)){
           return $user[0] = false;
        }
        return $user[0];         
    }


	public function editUser($data){
        $query = "UPDATE ".$this->table." SET usuario = '".$data['usuario']."' ,nombre = '".$data['nombre']."' ,nit = '".$data['nit']."' ,contacto = '".$data['contacto']."' ,direccion = '".$data['direccion']."' ,telefono = '".$data['telefono']."' ,tipo = ".$data['tipo'].", estado = ".$data['estado']." WHERE correo = '".$data['correo']."'";
        $query = $this->db->query($query);
        if($query){
            return true;
        }else{
            return false;
        }
    }
    public function addUser($data){ 
  
        $db = \Config\Database::connect();   
        $builder = $db->table($this->table);

        
        $base_64 = base64_encode($data['password']);
        $url_param = rtrim($base_64, '=');
        $is_Pw = $url_param . str_repeat('=', strlen($url_param) % 4);

        $data = [
            'usuario' => $data['usuario'],
            'nombre' => $data['nombre'],
            'nit' => $data['nit'],
            'direccion' => $data['direccion'],
            'contacto' => $data['contacto'],
            'correo' => $data['correo'],
            'telefono' => $data['telefono'],
            'tipo' => $data['tipo'],
            'estado' => $data['estado'],
            'password' => $is_Pw
        ];

        
        $builder->insert($data);
        return 1;
    }
}
