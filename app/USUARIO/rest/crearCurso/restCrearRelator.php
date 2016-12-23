<?php
require_once('./../../../../clases/utilidad/Link.php');
require_once(Link::include_file('clases/utilidad/RestData.php'));
require_once(Link::include_file('clases/utilidad/UserException.php'));
require_once(Link::include_file('clases/DAO/CursoDAO.php'));


$restData = new RestData();

try {
    
    $relator = $_POST['rut'];
    $cursoDao = new CursoDAO();
    
    $relator = $cursoDao->getRelatorByRut($relator);
    
    if(empty($relator))
        throw new Exception("Relator inexistente.");
    
    $restData->succes = true;
    $restData->mensaje = "";
    $restData->data = $relator;
    $restData->returnRestData();
}catch (Exception $e) {
    $restData->succes = false;
    $restData->mensaje = $e->getMessage();
    $restData->data = null;
    $restData->returnRestData();
}
