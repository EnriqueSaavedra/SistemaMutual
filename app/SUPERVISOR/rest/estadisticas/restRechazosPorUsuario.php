<?php
require_once('./../../../../clases/utilidad/Link.php');
require_once(Link::include_file('clases/DAO/CursoDAO.php'));

header("Content-type: text/json");
$cursoDao = new CursoDAO();
$usuario = $_POST['usuarios'];
$puntos = array();
$x = time() * 1000;
foreach ($usuario as $key => $value) {
    $puntos[] = array($x,$cursoDao->getRechazosHoy($value["id"])) ;
}
echo json_encode($puntos);
//echo json_encode($ret);   
?>