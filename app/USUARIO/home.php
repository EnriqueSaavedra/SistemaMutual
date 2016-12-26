<?php
require_once(Link::include_file('clases/DAO/CursoDAO.php'));

try {
    $cursoDao = new CursoDAO();
    $userID = $_SESSION['USUARIO']['ID'];
    $ingresosDiarios   = $cursoDao->getIngresosHoy($userID);
    $ingresosMes = $cursoDao->getIngresosMes($userID);
    $ingresosAnno   = $cursoDao->getIngresosAnno($userID);
    $ingresosTotales   = $cursoDao->getIngresosTotales($userID);
    
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
                Bienvenido al Sistema de Ingreso
            </h1>
        </div>
        <div class="col-md-6">
            <ul class="list-group" style="margin-top: 180px;width: 60%;">
                <li class="list-group-item">Cursos Hoy: <?php echo $ingresosDiarios; ?></li>
                <li class="list-group-item">Cursos Mensuales: <?php echo $ingresosMes; ?></li>
                <li class="list-group-item">Cursos Anuales: <?php echo $ingresosAnno; ?></li>
                <li class="list-group-item active">Cursos Totales: <?php echo $ingresosTotales; ?></li>
                <!--<li class="list-group-item active">Promedio Diario: <?php echo "" ?></li>-->
            </ul>
        </div>
    </div>
    
</div>