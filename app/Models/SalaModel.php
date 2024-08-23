<?php

namespace App\Models;

use CodeIgniter\Model;

class SalaModel extends Model
{
    // protected $DBGroup          = 'default';
    protected $table            = 'sala';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = [
        'id' , 'operador_id', 'sala'
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
    public function findSalaById($id){
        $sala = $this->asArray()->where(['id' => $id])->first();
        if( !$sala){
            throw new \Exception('La sala no existe.');
        }

        return $sala;
        
    }
}
