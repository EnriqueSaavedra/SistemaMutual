<?php
require_once(Link::include_file('clases/DBO/Usuario.php'));
require_once(Link::include_file('clases/BDconn.php'));

class UsuarioDAO extends BDconn{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function reDirUserHome($usuarioId){
        $sql = "SELECT 
                    gu.nombre 
                FROM 
                    grupo_usuario as gu
                    LEFT JOIN usuario as u ON gu.id = u.tipo_usuario
                WHERE u.id=$usuarioId;";
        $pdo = $this->pdo;
        $query = $pdo->query($sql);
        
        if(!$query || $query->rowCount() <= 0)
            Link::redirect();
        
        $res = $query->fetchColumn();
        
        Link::redirect($res, "home");
    }
    
    public function reDirUser($usuarioId,$app) {
        $sql = "SELECT 
                    gu.nombre 
                FROM 
                    grupo_usuario as gu
                    LEFT JOIN usuario as u ON gu.id = u.tipo_usuario
                WHERE u.id=$usuarioId;";
        $pdo = $this->pdo;
        $query = $pdo->query($sql);
        
        if(!$query || $query->rowCount() <= 0)
            Link::redirect();
        
        $res = $query->fetchColumn();
        
        Link::redirect($res, $app);
    }
    
    public function loginUsuario($email,$clave,$recordar = false){
        $pdo = $this->pdo;
        $clave = md5($clave);
        $sql = "SELECT * FROM usuario WHERE email = '$email' AND clave = '$clave' AND activo = 't';";
        $query = $pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        $usuario = $query->fetchObject("Usuario");
        /*@var $usuario Usuario*/
        $usuario->grupoNombre = $this->getGroupName($usuario->tipo_usuario);
        
        if($usuario->grupoNombre == null)
            Link::redirect ('BASE', 'cerrarSession');
        
        $this->saveLogin($usuario,$recordar);
        return $usuario;
        
    }
    
    public function setSessionByToken($token) {
        $sql = "SELECT * FROM usuario WHERE token_user = '$token';";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return false;
        
        $usuario = $query->fetchObject("Usuario");
        $this->saveLogin($usuario, false);
        return true;
    }
    
    public function addTokenUsuario($token,$id){
        $sql = "UPDATE usuario SET token_usuario = '$token' WHERE id = $id;";
        $exec = $this->pdo->exec($sql);
        if($exec <= 0)
            return false;
        else
            return true;
    }


    public function saveLogin(Usuario $usuario,$mantenerSesion){
        if($mantenerSesion){
            $token = $this->generarToken($usuario);
            if(!$this->addTokenUsuario($token, $usuario->getId())){
                throw new UserException(
                        "Problemas al almacenar Token en BD, puede continuar navegando, pero el sitio no guardará su sesiónn",
                        UserException::INFO
                );
            }
            setcookie("TOKEN_USUARIO", $token);
        }
        
        $this->registrarSession($usuario);
    }
    
    private function registrarSession(Usuario $usuario){
        $_SESSION['USUARIO']['ID']    = $usuario->getId();
        $_SESSION['USUARIO']['NAME']  = $usuario->getNombre();
        $_SESSION['USUARIO']['EMAIL'] = $usuario->getEmail();
        $_SESSION['USUARIO']['GROUP'] = $usuario->tipo_usuario;
        $_SESSION['USUARIO']['CTX'] = $usuario->grupoNombre;
    }

    private function generarToken(Usuario $usuario){
        $date = new DateTime();
        $token = md5($date->format('Ymdhis').$usuario->getEmail().$usuario->getId());
        return $token;
    }
    
    public function getGroupName($grupoId) {
        $sql = "SELECT nombre FROM grupo_usuario WHERE id = $grupoId;";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchColumn();
    }
    
    public function getAllUsuarios() {
        $sql = "SELECT 
                    u.id,
                    u.nombre,
                    u.email,
                    gu.nombre as grupo,
                    u.activo
                FROM
                    usuario as u
                    LEFT JOIN grupo_usuario as gu ON u.tipo_usuario = gu.id";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchAll(PDO::FETCH_ASSOC);
        
    }
    
    public function getAllGrupos() {
        $sql = "SELECT 
                    *
                FROM
                    grupo_usuario";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchAll(PDO::FETCH_CLASS, "Grupo_usuario");
        
    }
    
    public function getUsuarioById($id) {
        $sql = "SELECT * FROM usuario WHERE id = $id";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchObject("Usuario");
    }
    
    public function getUsuarioByEmail($email) {
        $sql = "SELECT * FROM usuario WHERE email='$email';";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchObject("Usuario");
    }
    
    public function almacenarUsuario(Usuario $user) {
        $resp = $this->getUsuarioByEmail($user->email);
        if($resp == null){
            if(empty($user->clave))
                throw new UserException("Debe ingresar una clave para guardar un nuevo usuario", UserException::ERROR);
            else
                $user->clave = md5 ($user->clave);
             
            $sm = new SQLManager($this->pdo, 'usuario', array('email'), $user);
            $sql =  $sm->getInsert();
        }else{
            if(!empty($user->clave))
                $user->clave = md5 ($user->clave);
            
            $sm = new SQLManager($this->pdo, 'usuario', array('id'), $user);
            $sql = $sm->getUpdate();
        }
        return $this->pdo->exec($sql);
    }
    
    public function getAllUsuarioId() {
        $sql = "SELECT 
                    u.id
                FROM 
                    usuario as u LEFT JOIN
                    grupo_usuario as gp ON u.tipo_usuario = gp.id
                WHERE 
                    gp.nombre = '".Grupo_usuario::USUARIO."' AND 
                    u.activo = 't'
                ORDER BY u.id";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllUsuarioNombre() {
        $sql = "SELECT 
                    u.nombre
                FROM 
                    usuario as u LEFT JOIN
                    grupo_usuario as gp ON u.tipo_usuario = gp.id
                WHERE 
                    gp.nombre = '".Grupo_usuario::USUARIO."' AND 
                    u.activo = 't'
                ORDER BY u.id";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUsuarioCarga($nombre) {
        require_once(Link::include_file('clases/utilidad/pojos/CursoExcel.php'));
        
        $nombre = $this->sanitizeNombre(strtolower($nombre));
        $sql = "SELECT id FROM usuario WHERE lower(nombre) like '%$nombre%';";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0){
            $sql = "SELECT id FROM usuario WHERE nombre like '%".CursoExcel::USER_STANDAR."%'";
            $query = $this->pdo->query($sql);
            if(!$query || $query->rowCount() <= 0){
                return null;
            }
        }
        return $query->fetchColumn();
    }
    
    
    
    public function sanitizeNombre($nombre) {
        $nombre = strtolower(trim($nombre));
        $search = array(
                    "Á",
                    "É",
                    "Í",
                    "Ó",
                    "Ú",
                    "Ñ",
                    "  "
        );
        $replace = array(
                    "á",
                    "é",
                    "í",
                    "ó",
                    "ú",
                    "ñ",
                    " "
        );
        $nombre = str_replace($search, $replace, $nombre);
        return $nombre;
    }
}
