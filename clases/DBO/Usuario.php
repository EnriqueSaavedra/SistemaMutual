<?php

require_once(Link::include_file('clases/utilidad/DBO.php'));

class Usuario extends DBO{
    public $id;
    public $nombre;
    public $email;
    public $clave;
    public $tipo_usuario;
    public $token_user;
    public $activo;
    
    public $grupoNombre;
    
    function getActivo() {
        return $this->activo;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    
    function getGrupoNombre() {
        return $this->grupoNombre;
    }

    function setGrupoNombre($grupoNombre) {
        $this->grupoNombre = $grupoNombre;
    }

        
    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getEmail() {
        return $this->email;
    }

    function getClave() {
        return $this->clave;
    }

    function getTipo_usuario() {
        return $this->tipo_usuario;
    }

    function getToken_user() {
        return $this->token_user;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }

    function setTipo_usuario($tipo_usuario) {
        $this->tipo_usuario = $tipo_usuario;
    }

    function setToken_user($token_user) {
        $this->token_user = $token_user;
    }



}

class Grupo_usuario extends DBO{
    
    const USUARIO = 'USUARIO';
    const SUPERVISOR = 'SUPERVISOR';
    const ADMINISTRADOR = 'ADMIN';
    
    public $id;
    public $nombre;
    
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

