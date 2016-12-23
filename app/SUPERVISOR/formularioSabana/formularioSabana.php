<?php
require_once(Link::include_file('clases/DAO/CursoDAO.php'));


$app = new MensajeSistema();
try {
    
} catch (UserException $e) {
    $app->addMessage($e->getMessage(), $e->getCode());
} catch (Exception $e){
    //loger
    echo $e->getMessage();
}


?>
<div class="container-fluid">
    <div class="pagina-titulo panel panel-default">
      <div class="panel-body">
        <span class="glyphicon glyphicon-pencil"></span>
        Crear Nuevo Curso
      </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="jumbotron jumbotron-white">
                <form method="POST" action="./app/SUPERVISOR/formularioSabana/sabana.php" target="_blank">        
                    <div class="col-md-12 col-lg-12"><h3 class="titulo-form-ingreso">Filtro de Extracción</h3></div>       
                    <div class="col-md-6 col-lg-6">
                        <div class="col-md-6 col-lg-6 col-md-offset-6 col-md-offset-6">
                            <div class="form-group">
                                <label for="fechaInicio">Fecha Inicio Busqueda</label>
                                <input type="date" class="form-control"  name="fechaInicio"  id="fechaInicio" placeholder="Fecha Inicio">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-md-offset-6 col-md-offset-6">
                            <div class="form-group">
                                <label for="fechaTermino">Fecha Termino Busqueda</label>
                                <input type="date" class="form-control"  name="fechaTermino"  id="fechaTermino" placeholder="Fecha Inicio">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="fechaTermino">Modo Cursos a Descargar</label>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="tipoDescarga" id="optionsRadios1" value="1" checked>
                                    Solo Descargados
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="tipoDescarga" id="optionsRadios2" value="2">
                                    Solo Sin Descargar
                                  </label>
                                </div>
                                <div class="radio disabled">
                                  <label>
                                    <input type="radio" name="tipoDescarga" id="optionsRadios3" value="3" disabled>
                                    Mixto
                                  </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12 text-center">
                        <input type="hidden" name="Submit" value="Descargar"/>
                        <button class="btn btn-primary" type="submit">
                            <span class="glyphicon glyphicon-floppy-disk"></span> 
                            Descargar
                        </button>
                    </div>
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>