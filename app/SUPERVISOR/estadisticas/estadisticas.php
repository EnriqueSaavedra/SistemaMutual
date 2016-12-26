<?php
global $chart; 
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
                <a href="<?=Link::getRutaHref('SUPERVISOR', 'estadisticas/estadisticas')?>" class="list-group-item active">Ingresos Hoy</a>
                <a href="<?=Link::getRutaHref('SUPERVISOR', 'estadisticas/chartEstadisticas/ingresoPorUsuario')?>" class="list-group-item" data-chart="" data-rest="">Ingresos por Digitador</a>
                <a href="<?=Link::getRutaHref('SUPERVISOR', 'estadisticas/chartEstadisticas/ingresoMensual')?>" class="list-group-item" >Registros Mes</a>
<!--                <a href="#" class="list-group-item" data-chart="" data-rest="">Ingresos Por Usuario</a>-->
                <a href="<?=Link::getRutaHref('SUPERVISOR', 'estadisticas/chartEstadisticas/rechazosPorUsuario')?>" class="list-group-item" data-chart="" data-rest="">Rechazos Por Usuario</a>
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
    
    function requestData() {
            $.ajax({
                url: "<?=Link::getRuta("SUPERVISOR","rest/estadisticas/restIngresoDiario")?>",
                success: function(point) {  
                    var series = chart.series[0],
                        shift = series.data.length > 20;
                    chart.series[0].addPoint(point, true, shift);
                    setTimeout(requestData, 5000);  
                },
                cache: false
            });
        }
    $(document).ready(function (){ 
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                defaultSeriesType: 'spline',
                events: {
                    load: requestData
                }
            },
            title: {
                text: 'Ingresos Hoy (Vivo)'
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
            series: [{
                name: 'N° Ingresos',
                data: []
            }]
        });   
        $('#loading').slideUp('MEDIUM',function (){
            $('#chart').slideDown('MEDIUN');
        });
    });
</script>