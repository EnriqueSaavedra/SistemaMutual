<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SQLManager{
    
    /**
     *
     * @var PDO
     */
    private $pdo;
    
    CONST DATA_TYPE = array(
        
    );
    
    /**
     *
     * @var array
     */
    private $estructura;
    private $tableName;
    private $identificadores;
    
    private $obj;
    
    public function __construct(PDO $pdo,$tableName,array $identificadores,DBO $obj) {
        if(!is_object($obj))
            throw new Exception("No se entregó un objeto.");
        
        
        $this->pdo = $pdo;
        
        $estructura = $this->getEstructura($tableName);
        
        if($estructura == null)
            throw new Exception("Imposibe obtener estructura de la tabla: \"$tableName\"");
        
        $this->estructura = $estructura;
        $this->obj = $this->array_filter($obj->toArray());
        $this->tableName = $tableName;
        $this->identificadores = $identificadores;
        
    }
    
    private function array_filter($array) {
        foreach ($array as $key => $value) {
            if($value === "" || $value === null){
                unset($array[$key]);
            }
        }
        return $array;
    }
    
    public function getInsert() { 
        $sql = "INSERT INTO $this->tableName (";
        $estrucArray = $this->estructura;
        $objArray = $this->obj;
        ksort($estrucArray);
        ksort($objArray);
        foreach ($objArray as $key => $value) {
            if(array_key_exists($key,$estrucArray)){
                if(end(array_keys($objArray)) == $key)
                    $sql .= " $key ";
                else
                    $sql .= " $key, ";
            }
        }
        $sql.=")VALUES(";
        foreach ($objArray as $key => $value) {
            if(array_key_exists($key,$estrucArray)){
                if(end(array_keys($objArray)) == $key){
                    $sql.= " ".$this->valueReturnSql($value, $estrucArray[$key]).") ";
                }else{
                    $sql.= " ".$this->valueReturnSql($value, $estrucArray[$key]).", ";
                }
            }
        }
        return $sql;
    }
    
    public function getUpdate() {
        $estrucArray = $this->estructura;
        $objArray = $this->obj;
        ksort($estrucArray);
        ksort($objArray);
        
        $sql = "UPDATE $this->tableName SET ";
        $supObjArray = $objArray;
        foreach ($this->identificadores as $value) {
            unset($supObjArray[$value]);
        }
        foreach ($supObjArray as $key => $value) {
            if(array_key_exists($key,$estrucArray)){
                if(end(array_keys($supObjArray)) == $key){
                    $sql.= " $key = ".$this->valueReturnSql($value, $estrucArray[$key])." ";
                }else{
                    $sql.= " $key = ".$this->valueReturnSql($value, $estrucArray[$key]).", ";
                }
            }
        }
        $sql.=" WHERE ";
        foreach ($this->identificadores as $value) {
            $sql .= " $value = ".$this->valueReturnSql($objArray[$value], $estrucArray[$value]);
        }
        return $sql;
    }
    
    private function getEstructura($tabla) {
        $sql = "SELECT column_name, data_type FROM information_schema.columns where table_name = '$tabla';";
        $query = $this->pdo->query($sql);
        if(!$query || $query->rowCount() <= 0)
            return null;
        
        $return = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)){
            $return[$res['column_name']] = $res['data_type'];
        }
        return $return;
    }
    
    private function valueReturnSql($valor,$dataType){
        switch ($dataType) {
            case 'smallint':
            case 'integer':
            case 'int2':
                if(empty($valor))
                    return 0;
                else
                    return $valor;
                break;
            case 'character varying':
            case 'date':
            case 'timestamp without time zone':
            case 'timestamp':
            case 'boolean':
                    return " '$valor' ";
            default:
                break;
        }
    }
}