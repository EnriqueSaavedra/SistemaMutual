<?php
require_once(Link::include_file('clases/BDconn.php'));

//POJOS
require_once(Link::include_file('clases/DBO/Curso.php'));
require_once(Link::include_file('clases/DBO/Participante.php'));
require_once(Link::include_file('clases/DBO/Empresa.php'));
require_once(Link::include_file('clases/DBO/Rechazos.php'));
require_once(Link::include_file('clases/utilidad/pojos/CursoExcel.php'));

//DAOS
require_once(Link::include_file('clases/DAO/GeoDAO.php'));
require_once(Link::include_file('clases/DAO/ParticipanteDAO.php'));
require_once(Link::include_file('clases/DAO/UsuarioDAO.php'));
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
    
    const ERROR_CREAR_CURSO = 1;
    const ERROR_CREAR_PARTICIPANTE = 2;
    const ERROR_CREAR_RELACION = 3;
    const ERROR_COMUNA = 4;
    const ERROR_USUARIO = 5;
    const ERROR_CODIGO_CURSO = 6;
    const ERROR_CREAR_EMPRESA = 7;
    const ERROR_CREAR_RELATOR = 8;
    
    
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
    
    public function separarRut($rut,$conDv = false){
        $rutTemporal = "";
        $dvTemporal = "";
        $rutTemporal = str_replace('.','',$rut);
        $dvTemporal = substr($rutTemporal, strlen($rutTemporal)-1);
        $rutTemporal = substr($rutTemporal, 0,strlen($rutTemporal)-2);
        if($conDv == FALSE)
            return $rutTemporal;
        else
            return array(
                    "rut" => $rutTemporal,
                    "dv" => $dvTemporal
            );
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
    
    public function validarCursoRepetido($codigoCurso,$fechaInicio,$comuna,$empresa,$relator){
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
                    ;";
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
            if(!$query || $query->rowCount() <= 0){
                return null;
            }

            return $query->fetchColumn();
        }
    }

    public function crearNewCurso(Curso $curso) {
        $empresaID = $this->crearNewEmpresa($curso->detalleEmpresa);
        $relatorID = $this->crearNewRelator($curso->detalleRelator);
        if($curso->fecha_proceso != ""){
            $fechaAux = str_replace("/","-",$curso->fecha_proceso);
            if(strlen($fechaAux) <= 9){
                $posYear = strpos($fechaAux,"-", 3);
                $posMonth  = strpos($fechaAux,"-");
                $monthAux = substr($fechaAux, ($posMonth+1), $posYear-3);
                $yearAux = substr($fechaAux, ($posYear+1));
                $yearAux = "20".$yearAux;
                $dateAux = $monthAux."-".substr($fechaAux, 0,($posMonth+1)).$yearAux;
                $fechaAux = $dateAux;
            }
            $dateProceso = new DateTime($fechaAux);
            $curso->fecha_proceso = $dateProceso->format('Y/m/d');
        }
        if($curso->fecha_inicio != ""){
            $fechaAux = str_replace("/","-",$curso->fecha_inicio);
            if(strlen($fechaAux) <= 9){
                $posYear = strpos($fechaAux,"-", 3);
                $posMonth  = strpos($fechaAux,"-");
                $monthAux = substr($fechaAux, ($posMonth+1), $posYear-3);
                $yearAux = substr($fechaAux, ($posYear+1));
                $yearAux = "20".$yearAux;
                $dateAux = $monthAux."-".substr($fechaAux, 0,($posMonth+1)).$yearAux;
                $fechaAux = $dateAux;
            }
            $dateIni = new DateTime($fechaAux);
        }
        
        $curso->usuario = $_SESSION['USUARIO']['ID'];
        $curso->empresa = $empresaID;
        $curso->relator = $relatorID;
        $curso->fecha_inicio = $dateIni->format('Y/m/d H:i:s');
        $idCursoRepetido = $this->validarCursoRepetido(
                $curso->tipo_curso,
                $curso->fecha_inicio,
                $curso->comuna, 
//                $curso->direccion,
                $curso->empresa, 
                $curso->relator);
        if(empty($idCursoRepetido)){
            $sm = new SQLManager($this->pdo, 'curso', array('id'), $curso);
            $sql = $sm->getInsert()." RETURNING id;";
            $query = $this->pdo->query($sql);
            if(!$query || $query->rowCount() <= 0)
                throw new UserException("Imposible Crear Curso, Problemas con Base de Datos. $sql", UserException::ERROR);
            
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
                throw new UserException("Imposible Crear Curso, Problemas con Base de Datos. $sql", UserException::ERROR);
            
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
    
    public function getOrigenIDByNombre($nombre) {
        $geoDao = new GeoDAO();
        $nombre = $geoDao->sanitizeNombre(strtolower($nombre));
        $sql = "SELECT id FROM origen WHERE lower(nombre) like '%$nombre%'";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return  null;
        
        return $query->fetchColumn();
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
    
    public function procesarDatoExcel($arrayDatosExcel) {
        $cursoExcel = new CursoExcel();
        $errores = array();
        if(is_array($arrayDatosExcel[1])){
            foreach ($arrayDatosExcel[1] as $key => $value) {
                $valSup = CursoExcel::sinitizeNombres($value);
                $cursoExcel->$valSup = $key;
            }

            unset($arrayDatosExcel[1]);
            $correlativoAux = "";
            $conDv = true;
            
            $geoDao = new GeoDAO();
            $usuarioDao = new UsuarioDAO();
            $participanteDao = new ParticipanteDAO();
            $codCurso = null;
            $codParticipante = null;
            
            foreach ($arrayDatosExcel as $key => $value) {
                try{
                    //diferencia Cursos
                    if($correlativoAux != $value[$cursoExcel->correlativo]){
                        $curso = new Curso();
                        $empresa = new Empresa();
                        $relator = new Relator();
                        //evaluamos los que son criticos, así cortamos la ejecución lo antes posible
                        /*@var $empresaSup Empresa*/
                        $comunaSup = $geoDao->matchComunaNombre($value[$cursoExcel->comuna]);
                        $usuarioSup = $usuarioDao->getUsuarioCarga($value[$cursoExcel->ejecutivo]);
                        $cursoSup = $this->getCursoNombre($value[$cursoExcel->cod]);
                        $empresaSup = $this->getEmpresaByAdherente($value[$cursoExcel->adherente]);
                        $relatorSup = $this->getRelatorByRut($this->separarRut($value[$cursoExcel->rut_relator]));
                        $fechaAux = $value[$cursoExcel->fecha_proceso];
                        
                        if($comunaSup == null)
                            throw new Exception("Comuna inexistente ".$value[$cursoExcel->comuna], self::ERROR_COMUNA);
                        if($usuarioSup == null)
                            throw new Exception("Problema de sistema, usuario ------------>", self::ERROR_USUARIO);
                        if($cursoSup == null)
                            throw new Exception("Curso inexistente ".$value[$cursoExcel->cod],self::ERROR_CODIGO_CURSO);
                        if($empresaSup == null){
                            $empresaRutSeparado = $this->separarRut($value[$cursoExcel->rut_empresa], $conDv);
                            $empresa->rut = $empresaRutSeparado["rut"];
                            $empresa->dv = $empresaRutSeparado["dv"];
                            $empresa->adherente = $value[$cursoExcel->adherente];
                            $empresaSup = $empresa;
                        }
                        if($relatorSup == null){
                            $relatorRutSepardo = $this->separarRut($value[$cursoExcel->rut_relator], $conDv);
                            $relator->rut = $relatorRutSepardo["rut"];
                            $relator->dv = $relatorRutSepardo["dv"];
                            $relator->nombre = $value[$cursoExcel->nombre_relator];
                            $relatorSup = $relator;
                        }
                        $fechaAux = str_replace("/","-",$fechaAux);
                        if(strlen($fechaAux) >= 9){
                            $posYear = strpos($fechaAux,"-", 3);
                            $posMonth  = strpos($fechaAux,"-");
                            $monthAux = substr($fechaAux, ($posMonth+1), $posYear-3);
                            $yearAux = substr($fechaAux, ($posYear+1));
                            $yearAux = "20".$yearAux;
                            $dateAux = $monthAux."-".substr($fechaAux, 0,($posMonth+1)).$yearAux;
                            $fechaAux = $dateAux;
                        }
                        
                        $origenSup = $this->getOrigenIDByNombre($value[$cursoExcel->origen]);
                        
                        $curso->fecha_inicio = $value[$cursoExcel->fechaini];
                        $curso->direccion = str_replace("'","", $value[$cursoExcel->direccion]);
                        $curso->numero_calle = $value[$cursoExcel->numero];
                        $curso->participantes = $value[$cursoExcel->n_participante];
                        $curso->contacto_nombre = $value[$cursoExcel->nombre_contacto_empresa_rpl];
                        $curso->contacto_email = $value[$cursoExcel->email];
                        $curso->fecha_proceso = $fechaAux;                    
                        $curso->usuario = $usuarioSup;
                        $curso->tipo_curso = $value[$cursoExcel->cod];  
                        $curso->comuna = $comunaSup;
                        
                        $curso->origen = ($origenSup != null) ? $origenSup : 1 ;
                        $curso->detalleEmpresa = $empresaSup;
                        $curso->detalleRelator = $relatorSup;
                        if($curso->numero_calle == null || trim($curso->numero_calle) == "")
                            $curso->numero_calle = 0;
                        //insertarCurso :D
                        try {
                            $respuesta = $this->crearNewCurso($curso);
                        } catch (Exception $exc) {
                            throw new Exception("Error al crear Curso ".$exc->getMessage(), self::ERROR_CREAR_CURSO);
                        }

                        
                        if($respuesta instanceof Curso)
                            $codCurso = $respuesta->id;
                        elseif($respuesta instanceof Rechazos)
                            $codCurso = $respuesta->id_curso_referencia;
                        else
                            throw new Exception("No se encuentra respuesta de Curso");
                        
                        $correlativoAux = $value[$cursoExcel->correlativo];
                    }
                    //Ingreso Participantes
                    $participante = new Participante();
                    
                    $rutSeparado = $this->separarRut($value[$cursoExcel->rut_participante], $conDv);
                    
                    $rutSup = $rutSeparado["rut"];
                    $dvSup = $rutSeparado["dv"];
                    $participanteSup = $participanteDao->getParticipanteByRut($rutSup);
                    
                    if($participanteSup == null){
                        $participante->rut = $rutSup;
                        $participante->dv = $dvSup;
                        $participante->nombre = str_replace("'","",$value[$cursoExcel->nombre_completo]);
                        $participante->edad = $value[$cursoExcel->edad];
                        $participante->sexo = ($value[$cursoExcel->sexo] == 'MASCULINO') ? 'M' : 'F';

                        
                        try {
                            $participanteDao->crearNuevoParticipante($participante);
                        } catch (Exception $exc) {
                            throw new Exception("Error con el participante rut: ".$value[$cursoExcel->rut_participante], self::ERROR_CREAR_PARTICIPANTE);
                        }

                    }else{
                        /*@var $participanteSup Participante*/
                        $participante->nombre = $value[$cursoExcel->nombre_completo];
                        $participante->edad = $value[$cursoExcel->edad];
                        $participante->sexo = ($value[$cursoExcel->sexo] == 'MASCULINO') ? 'M' : 'F';
                        try {
                            $participanteDao->actualizarParticipante($participanteSup);
                        } catch (Exception $exc) {
                            printArray($exc->getMessage());
                            throw new Exception("Error con el participante rut: ".$value[$cursoExcel->rut_participante], self::ERROR_CREAR_PARTICIPANTE);
                        }
                        $participante = $participanteSup;
                    }
                    
                    
                    if($codCurso == null || $participante == null)
                        throw new Exception("Uno de los dos codigos no paso");
                    try {
                        $participanteDao->crearRelacionPlanilla($participante, $codCurso, $curso->usuario);
                    } catch (Exception $exc) {
                        throw new Exception("Error al crear Relacion de Curso ", self::ERROR_CREAR_RELACION);
                    }

                    
                } catch (Exception $ex) {
                    $errores[$ex->getCode()][] = $ex->getMessage()." - ".$value[$cursoExcel->correlativo]."<br>";
                }
            }
        }else{
            throw new Exception("Sin cabecera.");
        }
        return $errores;
    }
    
}
