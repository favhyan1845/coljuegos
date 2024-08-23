<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\OperadorModel;
use App\Models\ReporteModel;

class ReporteApiController extends BaseController
{
    use ResponseTrait;
    
    public function __construct(){

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Methods: *');
    }

    // controlador para traer a todos los reportes de usuarios
    public function Report(){

        // validacion de recibir parametros
        if(empty($_POST)){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo no esta recibiendo parametros']);
        }
        //conversion Json
        $token = $_POST['token'];
        $data = ['token' => $token];
        $model = new OperadorModel();
        $validateToken = $model->getToken($data);
      
        if($validateToken == 0){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo la validacion del token, usuario no autenticado']);
        }

        $idSala = $_POST['idSala'];
        $idOperador = $_POST['idOperador'];
        $accion = $_POST['accion'];

        if($_POST['fechaInicio'] != 0){
        $fecha_inicio = $_POST['fechaInicio'].' 00:00:00';
        $fecha_fin = $_POST['fechaFin'].' 00:00:00';
            $data = ['error'=> 0, 'id_sala' => $idSala, 'id_operador' => $idOperador, 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin];
        }else{
            $data = ['error' => 1, 'id_sala' => $idSala, 'id_operador' => $idOperador];            
        }
              
        $model = new ReporteModel();   

            $response = $model->getReport($data);
        

        if($response['error'] === false){
            return json_encode(['error' => false, 'status' => 200, 'msg' => $response['msg']]);
        }else{
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Error al mostrar registros']);
        }
    }

}
