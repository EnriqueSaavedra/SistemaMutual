<?php

require_once(Link::include_file('clases/BDconn.php'));
require_once(Link::include_file('clases/DBO/Participante.php'));
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
class ParticipanteDAO extends BDconn{
    //put your code here
    
    public function __construct() {
        parent::__construct();
    }
    
    
    public function separarRut($rut){
        $rutTemporal = "";

        $rutTemporal = str_replace('.','',$rut);
        $rutTemporal = substr($rutTemporal, 0,strlen($rutTemporal)-2) ;

        return $rutTemporal;
    }
    
    public function getParticipanteIDByRut($rut) {
        $sql = "SELECT id FROM participante WHERE rut = $rut";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchColumn();
    }
    
    public function getParticipanteByRut($rut) {
        $rut = $this->separarRut($rut);
        $sql = "SELECT * FROM participante WHERE rut = $rut";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchObject("Participante");
    }
    
    public function crearNuevoParticipante(Participante $participante){
        // do insert
        $sm = new SQLManager($this->pdo, 'participante', array('id'), $participante);
        $sql = $sm->getInsert()." RETURNING id;";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            throw new Exception("Error al crear participante. ".$sql);

        return $query->fetchColumn();
    }
    
    public function verificaRelacionPlanilla($idParticipante, $idCurso){
        $sql = "SELECT id FROM planilla WHERE participante = $idParticipante AND curso = $idCurso";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return true;
        
        return false;
    }

    public function actualizarParticipante(Participante $parti) {
        $sm = new SQLManager($this->pdo, 'participante', array('id'), $parti);
        $sql = $sm->getUpdate();
        if($this->pdo->exec($sql))
            return true;
        
        return false;
    }

    public function crearRelacionPlanilla(Participante $participante,$idCurso,$user) {
        $this->pdo->beginTransaction();
        
        try {
            $id = $this->getParticipanteIDByRut($participante->rut);
            if($id == null){
                $participante->id = $this->crearNuevoParticipante($participante);
                $planilla = new Planilla();
                $planilla->curso = $idCurso;
                $planilla->participante = $participante->id;
                $planilla->creado_por = $user;
                $planilla->estado = 1;
                $sm = new SQLManager($this->pdo, 'planilla', array('id'), $planilla);
                $sql = $sm->getInsert();
                if(!$this->pdo->exec($sql)){
                    throw new Exception("Imposible crear registro relacion planilla.");
                }
            }else{
                $participante->id = $id;
                if(!$this->actualizarParticipante($participante))
                    throw new Exception("Imposble actualizar Participante.");
                
                if(!$this->verificaRelacionPlanilla($id, $idCurso))
                    throw new Exception("Relacion duplicada.");
                
                $planilla = new Planilla();
                $planilla->curso = $idCurso;
                $planilla->creado_por = $user;
                $planilla->participante = $id;
                $planilla->estado = 1;
                $sm = new SQLManager($this->pdo, 'planilla', array('id'), $planilla);
                $sql = $sm->getInsert();
                if(!$this->pdo->exec($sql)){
                    throw new Exception("Imposible crear registro relacion planilla.");
                }
            }
            $this->pdo->commit();
            return $participante;
        } catch (Exception $exc) {
            $this->pdo->rollBack();
            throw new Exception($exc->getMessage());
        }
        
    }
    
    public function eliminarRelacionPlanilla($id,$idCurso) {
        $sql = "DELETE FROM planilla WHERE participante = $id AND curso= $idCurso";
        if(!$this->pdo->exec($sql))
            return false;
        
        return true;
    }
    
    public function getAllParticipantes($idCurso) {
        $sql = "SELECT
                p.id,
                p.nombre,
                p.rut,
                p.dv,
                p.email,
                p.edad,
                p.sexo
                FROM participante as p LEFT JOIN 
                    planilla as pl ON pl.participante = p.id
                WHERE 
                    pl.curso = $idCurso";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchAll(PDO::FETCH_CLASS,"Participante");
    }
    
    public function getParticipanteByCurso($idCurso) {
        $sql = "SELECT 
                    p.id,
                    (p.rut || p.dv) rut,
                    p.nombre 
                FROM 
                    participante as p
                    LEFT JOIN planilla as pl ON p.id = pl.participante
                WHERE
                    pl.curso = $idCurso";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
