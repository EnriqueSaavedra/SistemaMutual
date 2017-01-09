<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CursoExcel
 *
 * @author Sammy Guergachi <sguergachi at gmail.com>
 */
class CursoExcel {
    
    const USER_STANDAR = 'CargaExcel';
    
    public $correlativo;
    public $fecha_proceso;
    public $mes_proceso;
    public $fechaini;
    public $glscurso;
    public $cod;
    public $n_participante;
    public $comuna;
    public $cod_comuna;
    public $direccion;
    public $numero;
    public $rut_participante;
    public $nombre_completo;
    public $sexo;
    public $cod_sexo;
    public $edad;
    public $adherente;
    public $rut_empresa;
    public $rut_relator;
    public $nombre_relator;
    public $nombre_contacto_empresa_rpl;
    public $email;
    public $detalle;
    public $origen;
    public $comentario;
    public $ejecutivo;
    
    public static function sinitizeNombres($nombre) {
        $sinEspacios = trim($nombre);
        $minuscula = strtolower($sinEspacios);
        $sinNumero = str_replace("º","",$minuscula);
        $sinPunto = strlen($sinNumero) == (strpos($sinNumero,"." )+1) ? str_replace(".","",$sinNumero) : str_replace(".","_",$sinNumero);
        $nombreAux = str_replace(" ","_",$sinPunto);
        
        return $nombreAux;
    }
}
