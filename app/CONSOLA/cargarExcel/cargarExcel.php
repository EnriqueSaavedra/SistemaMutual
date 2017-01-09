<?php
require_once('./../../../clases/utilidad/Link.php');
require_once(Link::include_file('clases/utilidad/LectorExcel.php'));

echo "librerias cargadas OK\n";
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$lector = new LectorExcel();
$lector->scanearFichero();
$lector->setArchivoDeCargaActual(Link::include_file(LectorExcel::DIR_FICHEROS.'excel_20170108200504.xlsx'));
$lector->leer();
echo "fin";
