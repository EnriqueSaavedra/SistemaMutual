<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBO
 *
 * @author Sammy Guergachi <sguergachi at gmail.com>
 */
class DBO {
    //put your code here
    public function toArray() {
        $array = array();
        foreach ($this as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }
}
