<?php
require_once(Link::include_file('clases/DAO/CursoDAO.php'));

try {
    $cursoDao = new CursoDAO();
    //estadisticas de todos ;)
    $diariosUsers = $cursoDao->getIngresosHoyAllUsuarios();
}catch (UserException $e) {
    $app->addMessage($e->getMessage(), $e->getCode());
} catch (Exception $e){
    //loger
}
?>
<div class="container-fluid bienvenida">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">
                Bienvenido al Sistema de Supervisión 
            </h1>
        </div>
        <div class="col-md-6">
            <ul class="list-group" style="margin-top: 180px;width: 60%;">
                <?php
                foreach ($diariosUsers as $key => $value) { ?>
                    <li class="list-group-item">Cursos Hoy: <?php echo $value['nombre']. " (".$value['total'].") "; ?></li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
    
</div>