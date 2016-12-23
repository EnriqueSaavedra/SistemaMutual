<?php
require_once(Link::include_file('clases/DBO/Comuna.php'));
require_once(Link::include_file('clases/BDconn.php'));
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ComunaDAO
 *
 * @author Sammy Guergachi <sguergachi at gmail.com>
 */
class GeoDAO extends BDconn {
    //put your code here
    
    function __construct() {
        parent::__construct();
    }
    
    public function getComunaNombre($id) {
        $sql = "SELECT nombre FROM comuna WHERE id = $id";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        return $query->fetchColumn();
    }
    
    public function getComunaPicker() {
        $sql = "SELECT * FROM comuna;";
        $query = $this->pdo->query($sql);
        if($query->rowCount() <= 0)
            return null;
        
        return $query->fetchAll(PDO::FETCH_CLASS,"Comuna");
    }

}
