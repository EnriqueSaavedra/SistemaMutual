<?php
require_once('./../../../../clases/utilidad/Link.php');
require_once(Link::include_file('clases/utilidad/RestData.php'));
require_once(Link::include_file('clases/utilidad/UserException.php'));
require_once(Link::include_file('clases/DAO/CursoDAO.php'));

$restData = new RestData();

try {
    // hacer alguna wea
    $rut = $_POST['rut'];
    $adherente = $_POST['adherente'];

    	
    $cursoDao = new CursoDAO();
    
    if(!empty($rut)){
        if(strlen($rut) <= 5)
            throw new Exception("Rut Demasiado Corto ".($rut));
        $empresa = $cursoDao->getEmpresaByRut($rut);
    }elseif(!empty($adherente)){
        $empresa = $cursoDao->getEmpresaByAdherente($adherente);
    }else{
        throw new Exception("Error inesperado, no se envió rut ni adherente.");
    }
    
    if($empresa == null)
        throw new Exception("No se pudo conseguir datos de empresa, podría ser que esta no existiera");

    $restData->succes = true;
    $restData->mensaje = "";
    $restData->data = $empresa;
    $restData->returnRestData();
    
    
}catch (Exception $e) {
    $restData->succes = false;
    $restData->mensaje = $e->getMessage();
    $restData->data = null;
    $restData->returnRestData();
    //loger
}
?>