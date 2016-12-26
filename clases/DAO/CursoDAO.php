<?php
require_once(Link::include_file('clases/BDconn.php'));
require_once(Link::include_file('clases/DBO/Curso.php'));
require_once(Link::include_file('clases/DBO/Empresa.php'));
require_once(Link::include_file('clases/DBO/Rechazos.php'));
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CursoDAO
 *
 * @author Sammy Guergachi <sguergachi at gmail.com>
 */
class CursoDAO extends BDconn {
    //put your code here
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getCursoNombre($id) {
        $sql = "SELECT nombre FROM tipo_curso WHERE id = $id";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchColumn();
        
    }
    
    public function getOrigenNombre($id) {
        $sql = "SELECT nombre FROM origen WHERE id = $id";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchColumn();
        
    }
    
    public function getTipoCursoPicker() {
        $sql = "SELECT * FROM tipo_curso;";
        $query = $this->pdo->query($sql);
        if($query->rowCount() <= 0)
            return null;
        
        return $query->fetchAll(PDO::FETCH_CLASS,"TipoCurso");   
    }
    
    public function getOrigenPicker() {
        $sql = "SELECT * FROM origen;";
        $query = $this->pdo->query($sql);
        if($query->rowCount() <= 0)
            return null;
        
        return $query->fetchAll(PDO::FETCH_CLASS,"Origen");
    }
    
    public function separarRut($rut){
        $rutTemporal = "";

        $rutTemporal = str_replace('.','',$rut);
        $rutTemporal = substr($rutTemporal, 0,strlen($rutTemporal)-2) ;

        return $rutTemporal;
    }

    public function getEmpresaByRut($rut) {
        $rut = $this->separarRut($rut);
        $sql = "SELECT * FROM empresa WHERE rut = $rut";
        $query = $this->pdo->query($sql);
        
        if($query->rowCount() <= 0)
            return null;

        return $query->fetchObject("Empresa");
    }
    
    public function getEmpresaByAdherente($adherente){
        $sql = "SELECT * FROM empresa WHERE adherente = $adherente";
        $query = $this->pdo->query($sql);
        
        if(!$query || $query->rowCount() <= 0)
            return null;

        return $query->fetchObject("Empresa");
    }
    
    public function getRelatorByRut($rut){
        $rut = $this->separarRut($rut);
        $sql = "SELECT * FROM relator WHERE rut = $rut";
        $query = $this->pdo->query($sql);
        
        if(!$query || $query->rowCount() <= 0)
            return null;

        return $query->fetchObject("Relator");
    }
    
    public function validarCursoRepetido($codigoCurso,$fechaInicio,$comuna,$direccion,$empresa,$relator){
        $sql = "SELECT
                    id
                FROM 
                    curso 
                WHERE 
                    tipo_curso = $codigoCurso AND 
                    fecha_inicio = '$fechaInicio' AND 
                    comuna = $comuna AND 
                    empresa = $empresa AND
                    relator = $relator AND
                    direccion = '$direccion';";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchColumn();
    }
    
    public function getIDEmpresaByRut($rut) {
        $sql = "SELECT id FROM empresa WHERE rut = $rut;";
        $query = $this->pdo->query($sql);
        
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchColumn();
    }
    
    public function eliminarCursoDEPRECABLE($idCurso) {
        $sql = "DELETE FROM curso WHERE id = $idCurso";
        if(!$this->pdo->exec($sql))
            return false;
        return true;
    }
    
    public function getIDRelatorByRut($rut) {
        $sql = "SELECT id FROM relator WHERE rut = $rut;";
        $query = $this->pdo->query($sql);
        
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchColumn();
    }
    
    public function getCursosByUsuario($participantes = false) {
        $sql = "SELECT
                    c.id,
                    c.tipo_curso,
                    (e.rut || e.dv) as empresa,
                    (r.rut || r.dv) as relator,
                    o.nombre,
                    c.fecha_inicio,
                    c.fecha_proceso
                    
                FROM 
                    curso as c 
                    LEFT JOIN
                        empresa as e ON c.empresa = e.id
                    LEFT JOIN
                        relator as r ON c.relator = r.id
                    LEFT JOIN
                        origen as o ON c.origen = o.id
                WHERE
                    usuario = ".$_SESSION['USUARIO']['ID']."
                ORDER BY c.fecha_proceso";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        if($participantes){
            require_once(Link::include_file('clases/DAO/ParticipanteDAO.php'));
            $participanteDao = new ParticipanteDAO();
        }
        $return = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)){
            if($participantes)
                $res['participantes'] = $participanteDao->getParticipanteByCurso($res['id']);
            
            $return[] = $res;
        }
        return $return;
    
    }
    
    public function crearNewEmpresa(Empresa $empresa) {
        $id = $this->getIDEmpresaByRut($empresa->rut);
        if($id != null)
            return $id;
        else{
            $sm = new SQLManager($this->pdo, 'empresa', array('id'), $empresa);
            $sql = $sm->getInsert()." RETURNING id;";
            $query = $this->pdo->query($sql);
            if(!$query || $query->rowCount() <= 0)
                return null;

            return $query->fetchColumn();
        }
            
    }
    
    public function crearNewRelator(Relator $relator) {
        $id = $this->getIDRelatorByRut($relator->rut);
        if($id != null)
            return $id;
        else{
            $sm = new SQLManager($this->pdo, 'relator', array('id'), $relator);
            $sql = $sm->getInsert()." RETURNING id;";
            $query = $this->pdo->query($sql);
            if(!$query || $query->rowCount() <= 0)
                return null;

            return $query->fetchColumn();
        }
    }

    public function crearNewCurso(Curso $curso) {
        $empresaID = $this->crearNewEmpresa($curso->detalleEmpresa);
        $relatorID = $this->crearNewRelator($curso->detalleRelator);
        $dateIni = new DateTime($curso->fecha_inicio);
        $dateNow = new DateTime();
        $curso->usuario = $_SESSION['USUARIO']['ID'];
        $curso->empresa = $empresaID;
        $curso->relator = $relatorID;
        $curso->fecha_inicio = $dateIni->format('Y/m/d H:i:s');
        $idCursoRepetido = $this->validarCursoRepetido(
                $curso->tipo_curso,
                $curso->fecha_inicio,
                $curso->comuna, 
                $curso->direccion,
                $curso->empresa, 
                $curso->relator);
        if(empty($idCursoRepetido)){
            $sm = new SQLManager($this->pdo, 'curso', array('id'), $curso);
            $sql = $sm->getInsert()." RETURNING id;";
            $query = $this->pdo->query($sql);
            if(!$query || $query->rowCount() <= 0)
                throw new UserException("Imposible Crear Curso, Problemas con Base de Datos.$sql", UserException::ERROR);
            
            $curso->id = $query->fetchColumn();
            return $curso;
        } else {
            $dateActual = new DateTime();
            $curso->id = $idCursoRepetido;
            $rechazos = new Rechazos();
            $rechazos->transformarCursoRechazo($curso);
            $rechazos->fecha_proceso = $dateActual->format('Y-m-d H:i:s');
            $sm = new SQLManager($this->pdo, 'rechazos', array('id'), $rechazos);
            $sql = $sm->getInsert();
            if(!$this->pdo->query($sql))
                throw new UserException("Imposible Crear Curso, Problemas con Base de Datos.", UserException::ERROR);
            
            return $rechazos;
        }
    }
    
    public function getEmpresaById($idEmpresa) {
        $sql = "SELECT * FROM empresa WHERE id = $idEmpresa";
        $query = $this->pdo->query($sql);
        
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchObject("Empresa");
    }
    
    public function getRelatorById($idRelator) {
        $sql = "SELECT * FROM relator WHERE id = $idRelator";
        $query = $this->pdo->query($sql);
        
        if(!$query || $query->rowCount() <=0)
            return null;
        
        return $query->fetchObject("Relator");
    }
    
    public function getCursoById($idCurso,$empresa = false,$relator = false) {
        $sql = "SELECT * FROM curso WHERE id = $idCurso";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        /*@var $curso Curso*/
        $curso = $query->fetchObject("Curso");
        
        if($empresa)
            $curso->detalleEmpresa = $this->getEmpresaById($curso->empresa);
        if($relator)
            $curso->detalleRelator = $this->getRelatorById ($curso->relator);
        
        return $curso;
    }
    
    public function recalcularParticipantes(Curso $curso) {
        $cantidad = $this->contarParticipantes($curso->id);
        if($cantidad == null)
            throw new Exception("No es posible contar participantes.");
        
        $curso->participantes = $cantidad;
        $sm = new SQLManager($this->pdo, 'curso', array('id'), $curso);
        $sql = $sm->getUpdate();
        if(!$this->pdo->exec($sql))
            return false;
        
        return true;
    }
    
    public function contarParticipantes($idCurso) {
        $sql = "SELECT 
                    COUNT(1) 
                FROM 
                    planilla as pl 
                    LEFT JOIN participante as p ON pl.participante = p.id
                WHERE
                    pl.curso = $idCurso";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchColumn();
    }
    
    /*
     * Estadisticas Personales
     */
    public function getIngresosHoy($userID) {
        $dateActual = new DateTime();
        
        $sql = "SELECT 
                    COUNT(1) 
                FROM 
                    planilla as p LEFT JOIN
                    curso as c ON c.id = p.curso
                WHERE
                    c.fecha_proceso >= '".$dateActual->format('Y-m-d 00:00:00')."' AND
                    p.creado_por = ".$userID;
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount()<= 0)
            return null;
        
        return $query->fetchColumn();
    }
    
    public function getIngresosMes($userID) {
        $dateActual = new DateTime();
        
        $sql = "SELECT 
                    COUNT(1) 
                FROM 
                    planilla as p LEFT JOIN
                    curso as c ON c.id = p.curso
                WHERE
                    EXTRACT(YEAR FROM c.fecha_proceso) = '".$dateActual->format('Y')."' AND
                    EXTRACT(MONTH FROM c.fecha_proceso) = '".$dateActual->format('m')."' AND
                    p.creado_por = ".$userID;
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount()<= 0)
            return null;
        
        return $query->fetchColumn();
        
    }
    
    public function getIngresosAnno($userID) {
        $dateActual = new DateTime();
        
        $sql = "SELECT 
                    COUNT(1) 
                FROM 
                    planilla as p LEFT JOIN
                    curso as c ON c.id = p.curso
                WHERE
                    EXTRACT(YEAR FROM c.fecha_proceso) = '".$dateActual->format('Y')."' AND
                    p.creado_por = ".$userID;
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount()<= 0)
            return null;
        
        return $query->fetchColumn();
    }
    
    public function getIngresosTotales($userID) {
        $dateActual = new DateTime();
        
        $sql = "SELECT 
                    COUNT(1) 
                FROM 
                    planilla as p LEFT JOIN
                    curso as c ON c.id = p.curso
                WHERE
                    p.creado_por = ".$userID;
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount()<= 0)
            return null;
        
        return $query->fetchColumn();
    }
    
    public function getIngresosHoyAllUsuarios() {
        require_once(Link::include_file('clases/DBO/Usuario.php'));
        $dateActual = new DateTime();
        $sql = "SELECT
                    u.nombre,
                    (SELECT
                        COUNT(1)
                    FROM
                        planilla as p LEFT JOIN
                        curso as c ON p.curso = c.id
                    WHERE 
                        c.fecha_proceso >= '".$dateActual->format('Y-m-d 00:00:00')."' AND
                        p.creado_por = u.id
                    ) as total
                FROM 
                    usuario as u 
                    LEFT JOIN grupo_usuario as gu ON u.tipo_usuario = gu.id
                WHERE
                    gu.nombre = '".Grupo_usuario::USUARIO."' AND
                    activo = 't';";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /*
     * Fin estadisticas personales
     */
    
    public function getSabana($fechIni,$fechTer,$tipoDescarga) {
        $fechIni = new DateTime($fechIni);
        $fechIni = $fechIni->format('Y-m-d');
        $fechTer = new DateTime($fechTer);
        $fechTer = $fechTer->format('Y-m-d');
        $sql = "SELECT
                    c.id as id_curso,
                    c.fecha_proceso as fecha_proceso,
                    (c.fecha_inicio + interval '3 years') as vigencia_capacitacion,
                    c.fecha_inicio,
                    c.fecha_inicio as fecha_termino,
                    'Curso' as desc1,
                    '3' as cod1,
                    'SEDE EMPRESA' as desc2,
                    '2' as cod2,
                    'No Programada' as desc3,
                    '2' as cod3,
                    'EVALUADOS CON NOTA' as desc4,
                    '2' as cod4,
                    'TODOS' as desc5,
                    '5' as cod5,
                    tc.nombre as gsl_curso,
                    tc.id as cod6,
                    '2' as costo_act,
                    c.participantes,
                    '' as costo_unit,
                    '2' as horas,
                    'PRESENCIAL' as gsl_modalidad,
                    '1' as cod7,
                    com.nombre as comuna,
                    com.id as cod_comuna,
                    'Calle' as calle,
                    c.direccion as direccion,
                    c.numero_calle as numero,
                    (p.rut || '-' || p.dv) as rut,
                    p.nombre as nombre_completo,
                    CASE
                        WHEN p.sexo = 'M' THEN 'MASCULINO'
                        ELSE 'FEMENINO'
                    END as sexo,
                    CASE
                        WHEN p.sexo = 'M' THEN 1
                        ELSE 2
                    END as cod_sexo,
                    p.edad as edad,
                    'Trabajador dependiente' as categoria,
                    '2' as cod_categoria,
                    'OTROS' as grupo,
                    '4' as cod_grupo,
                    'APROBADO' as aprobacion,
                    '1' as  cod_aprobacion,
                    '09622' as cod_ciuo,
                    e.adherente as adherente,
                    (e.rut || '-' || e.dv) as rut_empresa,
                    'N/A' as motivo_no_aprobacion,
                    (r.rut || '-' || r.dv) as rut_relator,
                    r.nombre as r_nombre_completo,
                    'GRADO ACADEMICO' as grado,
                    '1' as cod_grado,
                    c.contacto_nombre as c_nombre_completo,
                    c.contacto_email as c_email,
                    '1' as cert_capa,
                    'N/A' as cod_cert,
                    '1' as orig_cod_cert,
                    '3' as grad_curso,
                    tc.objetivo as materias,
                    o.nombre as origen,
                    u.nombre as digitador
                FROM
                    curso as c 
                    LEFT JOIN tipo_curso as tc ON c.tipo_curso = tc.id
                    LEFT JOIN comuna as com ON c.comuna = com.id
                    LEFT JOIN planilla as pl ON pl.curso = c.id
                    LEFT JOIN participante as p ON pl.participante = p.id
                    LEFT JOIN empresa as e ON e.id = c.empresa
                    LEFT JOIN relator as r ON r.id = c.relator
                    LEFT JOIN origen as o ON o.id = c.origen
                    LEFT JOIN usuario as u ON c.usuario = u.id
                WHERE 
                    c.participantes > 0 AND
                    (c.fecha_proceso,c.fecha_proceso) OVERLAPS('$fechIni'::DATE, '$fechTer'::DATE)
                ORDER BY c.id ASC;
            ";
            try{
                $query = $this->pdo->query($sql);
                if(!$query || $query->rowCount() <= 0)
                    throw new Exception("--Sin Resultado--");
                    
                
                return $query->fetchAll(PDO::FETCH_ASSOC);
            }catch(Exception $e){
                throw new Exception("Problemas al cruzar data: ".$e->getMessage());
                
            }
    }
    
    public function getAllIngresosHoy() {
        $dateActual = new DateTime();
        
        $sql = "SELECT 
                    COUNT(1) 
                FROM 
                    planilla as p LEFT JOIN
                    curso as c ON c.id = p.curso
                WHERE
                    c.fecha_proceso >= '".$dateActual->format('Y-m-d 00:00:00')."'";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount()<= 0)
            return null;
        
        return $query->fetchColumn();
    }
    
    public function getAllIngresosMes() {
        $dateActual = new DateTime();
        $sql = "SELECT 
                    COUNT(1) as ingresos
                FROM
                    planilla as p LEFT JOIN
                    curso as c ON p.curso = c.id LEFT JOIN
                    usuario as u ON c.usuario = u.id LEFT JOIN
                    grupo_usuario as gp ON u.tipo_usuario = gp.id
                WHERE
                    u.activo = 't' AND
                    (EXTRACT(YEAR FROM c.fecha_proceso) = '".$dateActual->format('Y')."' AND EXTRACT(MONTH FROM c.fecha_proceso) = '".$dateActual->format('m')."') AND
                    gp.nombre = '".Grupo_usuario::USUARIO."'";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchColumn();
    }
    
    public function getAllCursosMes() {
        $dateActual = new DateTime();
        $sql = "SELECT
                    COUNT(1) as curso
                FROM
                    curso as c LEFT JOIN
                    usuario as u ON c.usuario = u.id LEFT JOIN
                    grupo_usuario gp ON u.tipo_usuario = gp.id
                WHERE
                    u.activo = 't' AND
                    (EXTRACT(YEAR FROM c.fecha_proceso) = '".$dateActual->format('Y')."' AND EXTRACT(MONTH FROM c.fecha_proceso) = '".$dateActual->format('m')."') AND
                    gp.nombre = '".Grupo_usuario::USUARIO."'";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchColumn();
    }
    
    public function getRechazosHoy($userID) {
        $dateActual = new DateTime();
        
        $sql = "SELECT 
                    COUNT(1) 
                FROM 
                    rechazos as r
                WHERE
                    r.fecha_proceso >= '".$dateActual->format('Y-m-d 00:00:00')."' AND
                    r.usuario = ".$userID;
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount()<= 0)
            return null;
        
        return $query->fetchColumn();
    }
    
    
}
