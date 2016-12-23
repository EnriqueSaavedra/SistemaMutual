<?php
require_once('./../../../../clases/utilidad/Link.php');
require_once(Link::include_file('clases/utilidad/RestData.php'));
require_once(Link::include_file('clases/utilidad/UserException.php'));
require_once(Link::include_file('clases/DAO/ParticipanteDAO.php'));


$restData = new RestData();

try {
    
    $rut = $_POST['rut'];
    $participanteDao = new ParticipanteDAO();
    $participante = $participanteDao->getParticipanteByRut($rut);
    
    if($participante == null)
        throw new Exception("No se encontró participante.");
    
    $restData->succes = true;
    $restData->mensaje = "";
    $restData->data = $participante;
    $restData->returnRestData();
}catch (Exception $e) {
    $restData->succes = false;
    $restData->mensaje = $e->getMessage();
    $restData->data = null;
    $restData->returnRestData();
}
