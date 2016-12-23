<?php

require_once(Link::include_file('clases/utilidad/DBO.php'));
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Empresa
 *
 * @author Sammy Guergachi <sguergachi at gmail.com>
 */
class Empresa extends DBO{
    public $id;
    public $rut;
    public $dv;
    public $adherente;
    
    
    public function setRutCompleto($rut){
        $rutTemporal = "";
        $dvTemporal = "";

        $rutTemporal = str_replace('.','',$rut);
        $dvTemporal  = substr($rutTemporal, -1,1);
        $rutTemporal = substr($rutTemporal, 0,strlen($rutTemporal)-2) ;
        
        $this->rut = $rutTemporal;
        $this->dv = $dvTemporal;
        
        return ($rutTemporal && $dvTemporal);
    }
    
    function getId() {
        return $this->id;
    }

    function getRut() {
        return $this->rut;
    }

    function getDv() {
        return $this->dv;
    }

    function getAdherente() {
        return $this->adherente;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setRut($rut) {
        $this->rut = $rut;
    }

    function setDv($dv) {
        $this->dv = $dv;
    }

    function setAdherente($adherente) {
        $this->adherente = $adherente;
    }


}
