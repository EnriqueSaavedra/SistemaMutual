<?php

require_once(Link::include_file('recursos/excel/Classes/PHPExcel/IOFactory.php'));
//set_include_path(get_include_path() . PATH_SEPARATOR . Link::include_file('recursos/excel/Classes/'));


class LectorExcel{
    
    const DIR_FICHEROS = 'recursos/ficherosCargados/';
    const FILE_NAME = 'name';
    const FILE_TYPE = 'type';
    const FILE_TMP_NAME = 'tmp_name';
    const FILE_ERROR = 'error';
    const FILE_SIZE = 'size';
    const TYPE_EXCEL = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    /**
     *
     * @var PHPExcel
     */
    public $lector;
    public $scan = false;
    private $archivoDeCargaActual;
    
    
    function getArchivoDeCargaActual() {
        return $this->archivoDeCargaActual;
    }
    
    function setArchivoDeCargaActual($archivoDeCargaActual) {
        $this->archivoDeCargaActual = $archivoDeCargaActual;
    }
 
        
    function __construct() {
        
    }
    
    public function scanearFichero() {
        $ruta = Link::include_file(self::DIR_FICHEROS);
        if(!is_dir($ruta)){
            throw new Exception("No esta creada la carpeta contenedora de excel");
        }
        $this->scan = true;
    }
    
    public function leer($archivoServidor = false) {
        if($this->scan == false)
            throw new Exception("Se debe escanear previamente el fichero");
        
        if($this->archivoDeCargaActual == null || trim($this->archivoDeCargaActual) == "")
            throw new Exception("Directorio no puede ser nulo.");
        
        
        if($archivoServidor && !file_exists($this->archivoDeCargaActual))
            throw new Exception("Archivo Inexistente");
        
        $this->lector = PHPExcel_IOFactory::load($this->archivoDeCargaActual);
        $sheetData = $this->lector->getActiveSheet()->toArray(null,true,true,true);
        return $sheetData;
        
    }
    
    private function validarDoc($file){
        if(empty($file))
            throw new Exception("Imposible subir archivo");
            
        $file = $file['excel'];
        if ($file[self::FILE_ERROR] > 0){
            throw new Exception("Error al cargar Excel: ".$file[self::FILE_ERROR]);
        }else{
            if($file[self::FILE_TYPE] != self::TYPE_EXCEL)
                throw new Exception("El formato del archivo es incompatible.");
        }
    }
    
    public function cargarExcel($file){
        $this->validarDoc($file);
        $file = $file['excel'];
        $date = new DateTime();
        $fechaActual = $date->format('YmdHis');
        $nombreNuevoFile = "excel_".$fechaActual.".xlsx";
        $this->archivoDeCargaActual = self::DIR_FICHEROS.$nombreNuevoFile;
        
        return move_uploaded_file($file[self::FILE_TMP_NAME], self::DIR_FICHEROS.$nombreNuevoFile);
        
    }
        

}
