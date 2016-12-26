<?php
require_once('./../../../../clases/utilidad/Link.php');
require_once(Link::include_file('clases/DAO/CursoDAO.php'));
require_once(Link::include_file('clases/DBO/Usuario.php'));

header("Content-type: text/json");
$cursoDao = new CursoDAO();
$cursos = $cursoDao->getAllCursosMes();
$ingresos = $cursoDao->getAllIngresosMes();
$x = time() * 1000;
$puntos = array(array($x,$cursos),array($x,$ingresos));
echo json_encode($puntos);
//echo json_encode($ret);   
?>