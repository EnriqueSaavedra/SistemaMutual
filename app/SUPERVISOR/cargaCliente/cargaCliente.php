<?php
require_once(Link::include_file('clases/DAO/UsuarioDAO.php'));
require_once(Link::include_file('clases/DAO/CursoDAO.php'));
require_once(Link::include_file('clases/utilidad/LectorExcel.php'));
require_once(Link::include_file('clases/utilidad/pojos/CursoExcel.php'));

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300);
$app = new MensajeSistema();
try {
    
  /*ahora co la funcion move_uploaded_file lo guardaremos en el destino que queramos*/
    if(isset($_POST['Submit']) && $_POST['Submit'] == 'SubirArchivo'){
        $lector = new LectorExcel();
        
        $lector->scanearFichero();
        if(!$lector->cargarExcel($_FILES))
            throw new UserException("Error al cargar archivo", UserException::ERROR);
        
        $lectura = $lector->leer();

        $cursoDao = new CursoDAO();
        
        $errores = $cursoDao->procesarDatoExcel($lectura);
        if($errores != ""){
            $mensaje = "";
            foreach ($errores as $errKey => $errVal) {
                foreach ($errVal as $value) {
                    $mensaje .= $value;
                }
            }
            throw new UserException($mensaje, UserException::WARNING);
        }
        
    }
    
} catch (UserException $e) {
    $app->addMessage($e->getMessage(), $e->getCode());
} catch (Exception $e){
    printArray($e->getMessage());
    //loger
}
?>
<div class="container-fluid">
    <div class="pagina-titulo panel panel-default ">
        <div class="panel-body">
            <div class="col-md-6">
                <span class="glyphicon glyphicon-cog"></span>
                Cargar Excel
            </div>
        </div>
    </div>
    <div class="row add-participante">
        <div class="col-md-12 col-lg-12">
            <div class="jumbotron jumbotron-white">
                <form method="POST" action="#" enctype="multipart/form-data">  
                    <div class="col-md-12 col-lg-12">
                        <h3 class="titulo-form-ingreso">
                            Archivo
                        </h3>
                    </div>       
                    <div class="col-md-12 col-lg-12">
                        <div class="col-md-8 col-lg-8 col-md-offset-2">
                            <div class="form-group">
                                <label for="excel">Archivo</label>
                                <input type="file" name="excel" class="form-control" id="excel">
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <input type="hidden" name="Submit" value="SubirArchivo"/>
                            <div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
                                <button class="btn btn-primary btn-block crear" type="submit"><span class="glyphicon glyphicon-ok"></span> Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>