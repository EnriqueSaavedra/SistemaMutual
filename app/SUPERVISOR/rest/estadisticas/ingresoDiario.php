<?php
require_once('./../../../../clases/utilidad/Link.php');
require_once(Link::include_file('clases/DAO/CursoDAO.php'));

header("Content-type: text/json");

$cursoDao = new CursoDAO();

$x = time() * 5000;
$y = $cursoDao->getAllIngresosHoy();
$ret = array($x, $y);
echo json_encode($ret);
?>