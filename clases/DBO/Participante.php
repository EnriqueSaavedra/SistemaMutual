<?php

require_once(Link::include_file('clases/utilidad/DBO.php'));
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Participante
 *
 * @author Sammy Guergachi <sguergachi at gmail.com>
 */
class Participante extends DBO{
    //put your code here
    public $id;
    public $rut;
    public $dv;
    public $nombre;
    public $email;
    public $edad;
    public $sexo;
    
    function getId() {
        return $this->id;
    }

    function getRut() {
        return $this->rut;
    }

    function getDv() {
        return $this->dv;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getEmail() {
        return $this->email;
    }

    function getEdad() {
        return $this->edad;
    }

    function getSexo() {
        return $this->sexo;
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

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setEdad($edad) {
        $this->edad = $edad;
    }

    function setSexo($sexo) {
        $this->sexo = $sexo;
    }

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

}

class Planilla extends DBO{
    public $id;
    public $curso;
    public $participante;
    public $estado;
    public $create_time;
    public $update_time;
    
    function getId() {
        return $this->id;
    }

    function getCurso() {
        return $this->curso;
    }

    function getParticipante() {
        return $this->participante;
    }

    function getEstado() {
        return $this->estado;
    }

    function getCreate_time() {
        return $this->create_time;
    }

    function getUpdate_time() {
        return $this->update_time;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCurso($curso) {
        $this->curso = $curso;
    }

    function setParticipante($participante) {
        $this->participante = $participante;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setCreate_time($create_time) {
        $this->create_time = $create_time;
    }

    function setUpdate_time($update_time) {
        $this->update_time = $update_time;
    }


}