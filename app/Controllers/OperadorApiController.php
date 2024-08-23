<?php

namespace App\Controllers;

use App\Models\OperadorModel;
use App\Models\ReporteModel;
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

use Firebase\JWT\JWT;
use Firebase\JWT\KEY;


class OperadorApiController extends BaseController
{

    use ResponseTrait;
	
	public function __construct(){

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Methods: *');
	}

    // peticion para login curl desde coljuegos
    protected function perform_http_request( $url, $data = false) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS =>$data,
        CURLOPT_HEADER => FALSE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: text/plain'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;


    }

    // peticion para solicitar RSA
    protected function perform_http_request_send( $url,  $data = false) {

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => true,        
        CURLOPT_HEADER => false,
        CURLOPT_POSTFIELDS =>$data,
        CURLOPT_SSL_VERIFYPEER => FALSE,   
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
    ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    
    // peticion a coljuegos para encriptar RSA
    protected function perform_http_request_enc_send( $url, $token, $data = false) {

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => true,        
        CURLOPT_HEADER => false,
        CURLOPT_POSTFIELDS =>$data,
        CURLOPT_SSL_VERIFYPEER => FALSE,   
        CURLOPT_HTTPHEADER => array(
            'TOKEN: '.$token,
            'Content-Type: application/json',
            'Cookie: FGTServer=66CF7A99D12BD9EF5D4A2878179197B9633940DD43E742F12A6ADD5535172C7B0CCF98A312FC0286CB882E99557ADE214639'
          ),
    ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    
    // login y autenticacion del sistema con coljuegos
    public function login()
    {
        // validacion de recibir parametros
        if(empty($_POST)){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo no esta recibiendo parametros']);
        }

        $is_User = $_POST['usuario'];
        $is_Pw = $_POST['password'];
        $base_64 = base64_encode($is_Pw);
        $url_param = rtrim($base_64, '=');
        $is_Pw = $url_param . str_repeat('=', strlen($url_param) % 4);

            $model = new OperadorModel();  
            $data = ['usuario' => $is_User, 'password' => $is_Pw];  
            $userResponse = $model->loginUser($data);            

            if($userResponse === false){
                return json_encode(['error' => true, 'status' => 400, 'msg' => 'Usuario o clave incorrectos']);
            }

            if($is_User){

                $url = 'https://sclmpluspr.coljuegos.gov.co/SCLMMetOnline-war/JWTAuth';
                $data = '{"usuario":"'.$is_User.'"}';
                $response = json_decode($this->perform_http_request($url, $data));
                
                if(is_null($response)){
                    $response = json_decode($this->perform_http_request($url, $data));
                }

                $key = $response->key;
                $token = $response->token;
                $this->updateToken($is_User, $token);

                $key = getenv('JWT_SECRET');
                
                    $payload = [
                        'iss' => 'https://sclmpluspr.coljuegos.gov.co/SCLMMetOnline-war/JWTAuth',
                        'aud' => $_SERVER['REDIRECT_URL'],
                        'data' => [
                            'usuario' => $is_User,
                        ]
                    ];
                    
                    $jwt = $token;
                    return $this->respondCreated(['error' => false, 'status' => 200, 'jwt' => $jwt,'message' => 'Usuario autenticado', 'usuario' => $userResponse]);
                
            } else{
                return $this->respondCreated(['error' => true, 'status' => 400, 'message' => 'No se pudo autenticar']);
            }     
    }


    public function validaToken(){

        // validacion de recibir parametros
        if(empty($_POST)){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo no esta recibiendo parametros']);
        }

        $token = $_POST['token'];   


        $data = ['token' => $token];
        $model = new OperadorModel();
        $validateToken = $model->getToken($data);  

        if($validateToken == 0){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo la validacion del token, usuario no autenticado']);
        }else if($validateToken == 1){
            $operador = $model->getIdOperadorByToken($data);
            return json_encode(['error' => false, 'status' => 200, 'msg' => $operador]);
        }
    }

    // controlador para traer a todos los usuarios
    public function allUser(){

        $model = new OperadorModel();

        $response = $model->getAllUser();
        
        if($response['response'] === true){
            return json_encode(['error' => false, 'status' => 200, 'msg' => $response['users']]);
        }else{
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo la busqueda de usuarios']);
        }
    }

    // controlador para hacer el proceso del archivo X18
    public function JWTEncrypt(){            

      
        // validacion de recibir parametros
        if(empty($_POST)){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo no esta recibiendo parametros']);
        }

        $token = $_POST['token'];
        $data = ['token' => $token];
        $model = new OperadorModel();
        $validateToken = $model->getToken($data);  

        if($validateToken == 0){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo la validacion del token, usuario no autenticado']);
        }


        $jSon = $this->reportCSV();

        $operador = $model->getIdOperadorByToken($data);
        
        $url = 'https://sclmpluspr.coljuegos.gov.co/SCLMMetOnline-war/JWTEncrypt';
        
        $RSA = json_decode($this->perform_http_request_enc_send($url, $token, $jSon));
        $encSend = $this->encryptSend($RSA);       
        $insert = $this->insertReport($operador, $jSon, $encSend);

        // insertar en la base de datos la respuesta
        return $insert; 

    }

    // añadir usuario
    public function addUser(){

        // validacion de recibir parametros
        if(empty($_POST)){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo no esta recibiendo parametros']);
        }
    

        $data = ['token' => $_POST['token']];



        // se envia a token al modelo
        $model = new OperadorModel();
        $validateToken = $model->getToken($data);  

        if($validateToken == 0){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo la validacion del token, usuario no autenticado']);
        }

        $emailValidate = $model->getEmailValidate($_POST['correo']);

        if($emailValidate == 1){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo, el usuario ya existe']);
        }
        
        $createUser = $model->addUser($_POST);  
        if($createUser == 1){
            return json_encode(['error' => false, 'status' => 200, 'msg' => 'Usuario creado']);
        }

    }

    // editar usuario
    public function editUser(){
        
        // validacion de recibir parametros
        if(empty($_POST)){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo no esta recibiendo parametros']);
        }

        $data = ['token' => $_POST['token']];

        $model = new OperadorModel();
        $validateToken = $model->getToken($data);
        
        if($validateToken == 0){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo la validacion del token, usuario no autenticado']);
        }

        $emailValidate = $model->getIdOperadorByEmail($_POST['correo']);
   
        if($emailValidate === false){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo, el usuario no existe']);
        }

        $editUser = $model->editUser($_POST);   
        if($editUser === true){
            return json_encode(['error' => false, 'status' => 200, 'msg' => 'Usuario actualizado']);
        }else{            
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Usuario no actualizo']);
        }

    }

    //obtener usuario
    public function getUserById(){
        $token = 0;
        $data = [  'token' => $token];

        $model = new OperadorModel();
        $validateToken = $model->getToken($data);
        
        if($validateToken == 0){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo la validacion del token, usuario no autenticado']);
        }

        $emailValidate = $model->getIdOperadorByEmail($_POST['correo']);
   
        if($emailValidate === false){
            return json_encode(['error' => true, 'status' => 400, 'msg' => 'Fallo, el usuario no existe']);
        }else{
            return json_encode(['error' => false, 'status' => 200, 'msg' => $emailValidate]);
        }

    }

    // actualizacion de token en la base de datos
    public function updateToken($is_User, $token){
        $model = new OperadorModel();

        $data = [   'usuario'=> $is_User, 
                    'token' => $token];
        $response = $model->updateToken($data);
        
        if($response === true){
            return json_encode(['error' => false, 'status' => 200, 'msg' => 'Se actualizo token']);
        }else{
            return json_encode(['error' => true, 'status' => 400,'msg' => 'No se actualizo token']);
        }
    }

    // insertar reporte en la base de datos
    public function insertReport($operador, $jSon, $response){

        $xml = simplexml_load_string($response);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        if(isset($array['success']['code']))
        {
            $status = 1;
            $arrayResponse = $array['error']['msn'];
        }
        else if(isset($array['error']['code']))
        {
            $status = 0;
            $arrayResponse = $array['error']['msn'];
        }
        
        $model = new ReporteModel();
        $data = [   'id_operador'=> $operador->operador_id, 
                    'fecha' =>date('Y-m-d H:i:s'),
                    'respuesta_api' => $arrayResponse,
                    'csv' => $jSon,
                    'estado' => $status,
                    'sala' => $operador->sala];
        $response = $model->insertReport($data);
        if($response == 1){
            return json_encode(['error' => false, 'status' => 200, 'msg' => 'Se inserto en la base de datos'], 'ColjuegosRes' => $arrayResponse);
        }else{
            return json_encode(['error' => true, 'status' => 400,'msg' => 'No se inserto en la base de datos']);
        }
    }

    // Peticion para encriptar RSA
    public function encryptSend($RSA){
        $url = 'https://sclmpluspr.coljuegos.gov.co/SCLMMetOnline-war/api/sclm/enviarDatosReporteSclmPlus';
        
        $response = $this->perform_http_request_send($url, $RSA);        
        return $response;
    }

    // peticion para transformar CSV a JSON
    public function reportCSV()
    {
        $file = $_FILES['filename']['tmp_name'];
        $sha = sha1_file($_FILES['filename']['tmp_name']);

       
        $handle = fopen($file,"r");
        while (($row = fgetcsv($handle, 10000, ",")) != FALSE) //get row vales
        {//rows in array

                $fileR = explode(";", $row[0]);
                switch($fileR[0]){                   
                    case 'RI':
                        $RI =  $this->jsonRI($fileR,$file);
                        $RC =  $this->jsonRCPri($file);
                         $finalArray = json_encode(array("numeroContrato" => $RC,"codigoSHA" =>[["codigoSHA" => $sha]], "f18Data" => $RI));                 
                    break;
                }     
            }
            return $finalArray;  
    }

    protected function jsonRI($RI, $file){
        $arrC = array();
        $handle = fopen($file,"r");
        while (($row = fgetcsv($handle, 10000, ",")) != FALSE) //get row vales
        {//rows in array

                $fileR = explode(";", $row[0]);
                switch($fileR[0]){                   
                    case 'RC':
                        $RC =  $this->jsonRC($file);
                        
                        foreach($RC[0] as $rowRC){
                            array_push($arrC, $rowRC);
                        }
                        return array('ri' => ["fechaReporte" => $RI[1], "nit"=>$RI[2], "formato"=>$RI[3], "clave"=>$RI[4]], "rc"=>$arrC[0], "rf"=>$arrC[1]);
                        break; 
                               
                    }
                }
    }

    protected function array_RDRC($q, $arrD)
    {       
        $arrDFinal = array();
        for($i = $q ; $i < count($arrD); $i++){ 
            if(!isset($arrDFinal[$i])){    
                array_push($arrDFinal,  $arrD[$i]);
            }
        }   
        return $arrDFinal;
    }


    protected function jsonRC($file){
        $handle = fopen($file,"r");
        $arrD = array();
        $arrE = array();
        $arrF = array();
        $qty = array();
        $arrTotal = array();
        $arrTotalFinal = array();
        $result = array(); 
        $q = 0; 
        while (($row = fgetcsv($handle, 10000, ",")) != FALSE) 
                {
                $fileR = explode(";", $row[0]);
                if($fileR[0] == 'RE'){
                    array_push($qty, $fileR[3]);             
                }
                    switch($fileR[0]){              
                        case 'RD':
                            // echo '*pasa RD*<br>';      
                                $arD = $this->jsonRD($fileR); 
                                array_push($arrD, $arD);  
                        break;              
                        case 'RF':
                            $RF = $this->jsonRF($fileR);
                            array_push($arrF, $RF);
                        break;  
                        case 'RE':
                            // echo '*pasa RE*<br>';
                            $arE = $this->jsonRE($fileR); 
                            array_push($arrE, $arE);
                            if(!empty($arrE)){

                                foreach($arrE as $t) {
                                    $repeat=false;
                                    for($i=0;$i<count($result);$i++)
                                    {
                                        if($result[$i]['totalRD']==$t['totalRD'])
                                        {
                                            $result[$i]['codigoLocal']+=$t['codigoLocal'];
                                            $repeat=true;
                                            break;
                                        }
                                    }
                                    if($repeat==false){
                                        $result[] = array('contrato' => $t['contrato'], 'codigoLocal' => $t['codigoLocal'],'totalRD' => $t['totalRD']);
                                        
                                        $arrEE = end($result); 
                                        $q = count($arrD)- $arrEE['totalRD'];
                                        $rd = $this->array_RDRC($q, $arrD);
                                        $re = $arrEE;
                                        $arrayPre = array('contrato' => $t['contrato'], 'codigoLocal' => $t['codigoLocal'], "rd" => $rd, "re" => $re);                                        
                                    }
                                }
                                array_push($arrTotal, $arrayPre);
                            }
                            break;    
                    }          
                }
                $arraySubTotal = $arrTotal[0];                
                $arrayPreFinal =  ["rc" => array($arraySubTotal), "rf" => $arrF[0]];
                array_push($arrTotalFinal, $arrayPreFinal);                 
                return $arrTotalFinal;
    }
    protected function jsonRCPri($file){             
        $handle = fopen($file,"r");
        while (($row = fgetcsv($handle, 10000, ",")) != FALSE) 
                {
                $fileR = explode(";", $row[0]);
                if($fileR[0] == 'RC'){                      
                    $arC =  $fileR[1];         
                }
             }
            return $arC;
        }

    protected function jsonRE($RE){       
        $arE = [
            "contrato" => $RE[1],
            "codigoLocal" => $RE[2],
            "totalRD" => $RE[3]
        ]; 
        return $arE;
    }

    protected function jsonRD($RD){
        $arD =  [ 
            "nuc" => $RD[1],
            "nuid" => $RD[2],
            "serial" => $RD[3],
            "coinIn" => $RD[4],
            "coinOut" => $RD[5],
            "jackPot" => $RD[6],
            "handPaid" => $RD[7],
            "billIn" => $RD[8],
            "gamesPlayed" => $RD[9],
            "porcentajeTeorico" => $RD[10],
            "eventSignificativo" => $RD[11],
            "fechaEventSignificativo" => $RD[12]
            ]; 
            return $arD;                
        }
    
    protected function jsonRF($RF){       
        
        $model = new OperadorModel();
        $token = 0;
        $data = [  'token' => $token];
        $user = $model->getByTokenAllInfoUser($data);

        $arF = [ 
            "fechaReporte" => $RF[1],
            "nit" =>  $RF[2],
            "formato" => [$RF[3]],
            "totalGlogalRegistrosListaRD" =>  $RF[4],
            "correoFabricante" =>  $user->correo
            ]; 
            return $arF;     
    }
}
