<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RestData
 *
 * @author Sammy Guergachi <sguergachi at gmail.com>
 */
class RestData {
    public $succes;
    public $mensaje;
    public $data;
    
    public function returnRestData() {
        echo json_encode($this);
    }
}
