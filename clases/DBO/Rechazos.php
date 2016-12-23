<?php

require_once(Link::include_file('clases/utilidad/DBO.php'));
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Rechazo
 *
 * @author Sammy Guergachi <sguergachi at gmail.com>
 */
class Rechazos extends DBO{
    public $id_curso_referencia;
    public $fecha_inicio;
    public $comuna;
    public $direccion;
    public $direccion_adicional;
    public $numero_calle;
    public $participantes;
    public $empresa;
    public $relator;
    public $contacto_nombre;
    public $contacto_email;
    public $fecha_proceso;
    public $tipo_curso;
    public $origen;
    public $usuario;
    
    public function transformarCursoRechazo(Curso $curso) {
        $this->id_curso_referencia = $curso->id;
        foreach ($this as $key => $value){
            if(isset($curso->$key)){
                $this->$key = $curso->$key;
            }
        }
    }
    
    function getDireccion_adicional() {
        return $this->direccion_adicional;
    }

    function setDireccion_adicional($direccion_adicional) {
        $this->direccion_adicional = $direccion_adicional;
    }

        
    function getId_curso_referencia() {
        return $this->id_curso_referencia;
    }

    function getFecha_inicio() {
        return $this->fecha_inicio;
    }

    function getComuna() {
        return $this->comuna;
    }

    function getDireccion() {
        return $this->direccion;
    }

    function getNumero_calle() {
        return $this->numero_calle;
    }

    function getParticipantes() {
        return $this->participantes;
    }

    function getEmpresa() {
        return $this->empresa;
    }

    function getRelator() {
        return $this->relator;
    }

    function getContacto_nombre() {
        return $this->contacto_nombre;
    }

    function getContacto_email() {
        return $this->contacto_email;
    }

    function getFecha_proceso() {
        return $this->fecha_proceso;
    }

    function getTipo_curso() {
        return $this->tipo_curso;
    }

    function getOrigen() {
        return $this->origen;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function setId_curso_referencia($id_curso_referencia) {
        $this->id_curso_referencia = $id_curso_referencia;
    }

    function setFecha_inicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    function setComuna($comuna) {
        $this->comuna = $comuna;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    function setNumero_calle($numero_calle) {
        $this->numero_calle = $numero_calle;
    }

    function setParticipantes($participantes) {
        $this->participantes = $participantes;
    }

    function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    function setRelator($relator) {
        $this->relator = $relator;
    }

    function setContacto_nombre($contacto_nombre) {
        $this->contacto_nombre = $contacto_nombre;
    }

    function setContacto_email($contacto_email) {
        $this->contacto_email = $contacto_email;
    }

    function setFecha_proceso($fecha_proceso) {
        $this->fecha_proceso = $fecha_proceso;
    }

    function setTipo_curso($tipo_curso) {
        $this->tipo_curso = $tipo_curso;
    }

    function setOrigen($origen) {
        $this->origen = $origen;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }


}
