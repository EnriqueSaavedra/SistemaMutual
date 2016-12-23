<?php

require_once(Link::include_file('clases/SQLManager.php'));

class BDconn{
    
    const FILE_DIR = "config/database.ini";
    private $username;
    private $passw;
    private $host;
    private $dbName;
    private $port;
    
    /**
     *
     * @var PDO
     */
    protected $pdo;
     

    public function __construct(){
        try {
            $array_ini = parse_ini_file(Link::include_file(self::FILE_DIR),true);
            $this->username = $array_ini["database"]["user"];
            $this->passw = $array_ini["database"]["passw"];
            $this->dbName = $array_ini["database"]["dbName"];
            $this->host = $array_ini["database"]["host"];
            $this->port = $array_ini["database"]["port"];
            $this->init_pdo();
        } catch (Exception $e) {
            throw new Exception("Error iniciando configuracion BD: ".$e->getMessage());
        }
    }
    
//      TERMINAR :D !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!  
//    public function getUpdate($obtArray,$table,$where){
//        $sql = "UPDATE $table SET ";
//        foreach ($obtArray as $key => $value) {
//            $sql .= " $key = '' ";
//            if($value != end($obtArray))
//        }
//    }

    private function init_pdo(){
        try {
            $stringConn = "pgsql:dbname=".$this->dbName.";host=".$this->host;
            $this->pdo = new PDO($stringConn, $this->username,$this->passw);
        } catch (PDOException  $e) {
            throw new Exception("Error conexión BD: ".$e->getMessage());
        }
    }
}