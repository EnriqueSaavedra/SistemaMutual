<?php

require_once(Link::include_file('clases/utilidad/DBO.php'));
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Curso
 *
 * @author Sammy Guergachi <sguergachi at gmail.com>
 */
class Curso extends DBO{
    public $id;
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
    
    /**
     *
     * @var CursoAdicionales
     */
    public $adicionales;
    
    
    /*
     * adicionales
     */
    
    /**
     *
     * @var Empresa
     */
    public $detalleEmpresa;
    
    /**
     *
     * @var Relator
     */
    public $detalleRelator;
    
    function getDireccion_adicional() {
        return $this->direccion_adicional;
    }

    function setDireccion_adicional($direccion_adicional) {
        $this->direccion_adicional = $direccion_adicional;
    }

        
    function getNumero_calle() {
        return $this->numero_calle;
    }

    function setNumero_calle($numero_calle) {
        $this->numero_calle = $numero_calle;
    }

    function getId() {
        return $this->id;
    }

    function getFecha_inicio() {
        return $this->fecha_inicio;
    }

    function getComuna() {
        return $this->comuna;
    }

    function getParticipantes() {
        return $this->participantes;
    }

    function getEmpresa() {
        return $this->empresa;
    }

    function getDireccion() {
        return $this->direccion;
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

    function getAdicionales() {
        return $this->adicionales;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setFecha_inicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    function setComuna($comuna) {
        $this->comuna = $comuna;
    }

    function setParticipantes($participantes) {
        $this->participantes = $participantes;
    }

    function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
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

    function setAdicionales(CursoAdicionales $adicionales) {
        $this->adicionales = $adicionales;
    }
}

class CursoAdicionales extends DBO{
    public $nombre;
    public $value;
    
    function getNombre() {
        return $this->nombre;
    }

    function getValue() {
        return $this->value;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setValue($value) {
        $this->value = $value;
    }


}

class TipoCurso extends DBO{
    //put your code here
    public $id;
    public $nombre;
    public $alias_curso;
    public $objetivo;
    
    function getAlias_curso() {
        return $this->alias_curso;
    }

    function getObjetivo() {
        return $this->objetivo;
    }

    function setAlias_curso($alias_curso) {
        $this->alias_curso = $alias_curso;
    }

    function setObjetivo($objetivo) {
        $this->objetivo = $objetivo;
    }

        
    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }
}

class Origen extends DBO{
    public $id;
    public $nombre;
    public $prioridad;
    
    const RAMA = 1;
    const DOC_FIS = 2;
    const IMAGEN = 3;


    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getPrioridad() {
        return $this->prioridad;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setPrioridad($prioridad) {
        $this->prioridad = $prioridad;
    }
}

class Relator extends DBO{
    public $id;
    public $nombre;
    public $rut;
    public $dv;
    
    public function setRutCompleto($rut) {
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

    function getNombre() {
        return $this->nombre;
    }

    function getRut() {
        return $this->rut;
    }

    function getDv() {
        return $this->dv;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setRut($rut) {
        $this->rut = $rut;
    }

    function setDv($dv) {
        $this->dv = $dv;
    }


}
