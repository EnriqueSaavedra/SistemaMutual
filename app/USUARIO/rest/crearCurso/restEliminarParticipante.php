<?php
require_once('./../../../../clases/utilidad/Link.php');
require_once(Link::include_file('clases/utilidad/RestData.php'));
require_once(Link::include_file('clases/utilidad/UserException.php'));
require_once(Link::include_file('clases/DAO/ParticipanteDAO.php'));


$restData = new RestData();

try {
    
    $id = $_POST['id'];
    $idCurso = $_POST['curso'];
    
    if(empty($id) || empty($idCurso))
        throw new Exception("No se envió un ID de participante.");
    
    $participanteDao = new ParticipanteDAO();
    
    $resp = $participanteDao->eliminarRelacionPlanilla($id,$idCurso);
    
    if(!$resp)
        throw new Exception("Problemas al eliminar relacion.");
    
    $restData->succes = true;
    $restData->mensaje = "Relacion Eliminada Exitosamente.";
    $restData->data = true;
    $restData->returnRestData();
    
    
}catch (Exception $e) {
    $restData->succes = false;
    $restData->mensaje = $e->getMessage();
    $restData->data = null;
    $restData->returnRestData();
    //loger
}
?>