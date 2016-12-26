<?php
require_once(Link::include_file('clases/DAO/UsuarioDAO.php'));
require_once(Link::include_file('clases/DAO/CursoDAO.php'));


$app = new MensajeSistema();
try {
    $usuarioDao = new UsuarioDAO();
    $cursoDAO = new CursoDAO();
    
    $usuarios = $usuarioDao->getAllUsuarioNombre();
    $usuariosId = $usuarioDao->getAllUsuarioId();
//    printArray($usuarios);
    if($usuarios == null || $usuariosId == null)
        throw new UserException("No se encuentran usuarios digitadores creados", UserException::ERROR);
    
   
//    $cursoDAO->getIngresosChart($usuarios);
    
    
    
    
} catch(UserException $e){
    $app->addMessage($e->getMessage(), $e->getCode());
}catch (Exception $e) {
    echo $e->getTraceAsString();
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div class="container-fluid">
    <div class="pagina-titulo panel panel-default ">
      <div class="panel-body">
        <span class="glyphicon glyphicon-dashboard"></span>
        Estadisticas
        
      </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-lg-3">
            <div class="list-group">
                <a href="<?=Link::getRutaHref('SUPERVISOR', 'estadisticas/estadisticas')?>" class="list-group-item">Ingresos Hoy</a>
                <a href="<?=Link::getRutaHref('SUPERVISOR', 'estadisticas/chartEstadisticas/ingresoPorUsuario')?>" class="list-group-item active">Ingresos por Digitador</a>
                <a href="<?=Link::getRutaHref('SUPERVISOR', 'estadisticas/chartEstadisticas/ingresoMensual')?>" class="list-group-item" >Registros Mes</a>
<!--                <a href="#" class="list-group-item" >Ingresos Por Usuario</a>-->
                <a href="<?=Link::getRutaHref('SUPERVISOR', 'estadisticas/chartEstadisticas/rechazosPorUsuario')?>" class="list-group-item" >Rechazos Por Usuario</a>
            </div>
        </div>
        <div class="col-md-9 col-lg-9" id="chart-container">
            <div id="loading">
                <div class="pagina-titulo panel panel-default">
                    <div class="panel-body text-center">
                        <img src="recursos/imagenes/loading.gif" width="120px" />
                    </div>  
                </div>
            </div>
            <div id="chart">
                    <div id="container" style="width:100%; height:400px;"></div>  
            </div>
        </div>
    </div>
</div>
<script src="recursos/js/highcharts/code/highcharts.js" ></script>
<script>
    $('#chart').slideUp();
    
    function requestData(usuarios) {
            $.ajax({
                method:'POST',
                url: "<?=Link::getRuta("SUPERVISOR","rest/estadisticas/restIngresoPorUsuario")?>",
                data:{
                    usuarios:usuarios
                },
                success: function(point) {  
                    jQuery.each(point,function (i,e){
                        var series = chart.series[i],
                            shift = series.data.length > 20;
                        chart.series[i].addPoint(e, true, shift);
                        console.log(e);
                    });
                    setTimeout(function(){ requestData(usuarios); }, 10000);
                    //revisar el tema de la fecha (GTM)
                },
                cache: false
            });
        }
    $(document).ready(function (){ 
        var auxData = <?=json_encode($usuarios)?>;
        var usuariosId = <?=json_encode($usuariosId)?>;
        var content = new Array();
        jQuery.each(auxData,function (i,e){
           content[i] = {'name': e.nombre ,'data' : [] };
        });
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                defaultSeriesType: 'spline',
                events: {
                    load: requestData(usuariosId)
                }
            },
            title: {
                text: 'Registros por Usuario Hoy (Vivo)'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150,
                maxZoom: 20 * 1000
            },
            yAxis: {
                minPadding: 0.2,
                maxPadding: 0.2,
                title: {
                    text: 'Ingresos',
                    margin: 80
                }
            },
            series: content
        });   
        $('#loading').slideUp('MEDIUM',function (){
            $('#chart').slideDown('MEDIUN');
        });
    });
</script>