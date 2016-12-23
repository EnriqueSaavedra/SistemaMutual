<?php
require_once('./../../../../clases/utilidad/Link.php');
require_once(Link::include_file('clases/utilidad/RestData.php'));
require_once(Link::include_file('clases/utilidad/UserException.php'));
require_once(Link::include_file('clases/DAO/ParticipanteDAO.php'));


$restData = new RestData();

try {
    
    $participanteDao = new ParticipanteDAO();
    $participante = new Participante();
    
    $idUSuario = $_POST['usuario'];
    $idCurso = $_POST['curso'];
    $participante->nombre = $_POST['nombre'];
    $participante->setRutCompleto($_POST['rut']); 
    $participante->email = $_POST['email'];
    $participante->edad = $_POST['edad'];
    $participante->sexo = $_POST['genero'];
    
    
    $respuesta = $participanteDao->crearRelacionPlanilla($participante,$idCurso,$idUSuario);
    
    if($respuesta == null)
        throw new Exception("Problemas al agregar Registro");

    $restData->succes = true;
    $restData->mensaje = "Relacion Creada Exitosamente.";
    $restData->data = $respuesta;
    $restData->returnRestData();
    
    
}catch (Exception $e) {
    $restData->succes = false;
    $restData->mensaje = $e->getMessage();
    $restData->data = null;
    $restData->returnRestData();
    //loger
}
?>