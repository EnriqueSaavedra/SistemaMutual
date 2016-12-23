<?php
require_once('../../../clases/utilidad/Link.php');
require_once('../../../clases/DAO/CursoDAO.php');
require_once('../../../clases/utilidad/UserException.php');

try{

    $cursoDao = new CursoDAO();

    $fechIni      = $_POST['fechaInicio'];
    $fechTer      = $_POST['fechaTermino'];
    $tipoDescarga = $_POST['tipoDescarga'];

    $sabana = $cursoDao->getSabana($fechIni,$fechTer,$tipoDescarga);


    $Name = 'Sabana_'.$fechIni.' - '.$fechTer.'.csv';
    $FileName = "./$Name";
    $Datos = "";
    foreach ($sabana[0] as $key => $value){
        if(end(array_keys($sabana[0])) == $key)
            $Datos .= "$key";
        else
            $Datos .= "$key;";
    }
    $Datos .= "\r\n";

    //Descarga el archivo desde el navegador
    header('Expires: 0');
    header('Cache-control: private');
    header('Content-Type: application/x-octet-stream'); // Archivo de Excel
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Last-Modified: '.date('D, d M Y H:i:s'));
    header('Content-Disposition: attachment; filename="'.$Name.'"');
    header("Content-Transfer-Encoding: binary");

    /**1.  consultar sap_maestro_cartera_v3 **/

    foreach ($sabana as $value) {
        $Datos .=   strtoupper($value['id_curso']).";".
                    strtoupper($value['fecha_proceso']).";".
                    strtoupper($value['vigencia_capacitacion']).";".
                    strtoupper($value['fecha_inicio']).";".
                    strtoupper($value['fecha_termino']).";".
                    strtoupper($value['desc1']).";".
                    strtoupper($value['cod1']).";".
                    strtoupper($value['desc2']).";".
                    strtoupper($value['cod2']).";".
                    strtoupper($value['desc3']).";".
                    strtoupper($value['cod3']).";".
                    strtoupper($value['desc4']).";".
                    strtoupper($value['cod4']).";".
                    strtoupper($value['desc5']).";".
                    strtoupper($value['cod5']).";".
                    strtoupper(utf8_decode($value['gsl_curso'])).";".
                    strtoupper($value['cod6']).";".
                    strtoupper($value['costo_act']).";".
                    strtoupper($value['participantes']).";".
                    strtoupper($value['costo_unit']).";".
                    strtoupper($value['horas']).";".
                    strtoupper($value['gsl_modalidad']).";".
                    strtoupper($value['cod7']).";".
                    strtoupper($value['comuna']).";".
                    strtoupper($value['cod_comuna']).";".
                    strtoupper($value['calle']).";".
                    strtoupper($value['direccion']).";".
                    strtoupper($value['numero']).";".
                    strtoupper($value['rut']).";".
                    strtoupper($value['nombre_completo']).";".
                    strtoupper($value['sexo']).";".
                    strtoupper($value['cod_sexo']).";".
                    strtoupper($value['edad']).";".
                    strtoupper($value['categoria']).";".
                    strtoupper($value['cod_categoria']).";".
                    strtoupper($value['grupo']).";".
                    strtoupper($value['cod_grupo']).";".
                    strtoupper($value['aprobacion']).";".
                    strtoupper($value['cod_aprobacion']).";".
                    strtoupper($value['cod_ciuo']).";".
                    strtoupper($value['adherente']).";".
                    strtoupper($value['rut_empresa']).";".
                    strtoupper($value['motivo_no_aprobacion']).";".
                    strtoupper($value['rut_relator']).";".
                    strtoupper($value['r_nombre_completo']).";".
                    strtoupper($value['grado']).";".
                    strtoupper($value['cod_grado']).";".
                    strtoupper($value['c_nombre_completo']).";".
                    strtoupper($value['c_email']).";".
                    strtoupper($value['cert_capa']).";".
                    strtoupper($value['cod_cert']).";".
                    strtoupper($value['orig_cod_cert']).";".
                    strtoupper($value['grad_curso']).";".
                    strtoupper($value['materias']).";".
                    strtoupper($value['origen']).";".
                    strtoupper($value['digitador']).";";
        $Datos .= "\r\n"; 
      }

    echo $Datos;
} catch(Exception $e){
    echo "No se pudo exportar a CSV: ".$e->getMessage();
}