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
                <a href="#" class="list-group-item active" data-chart="<?=Link::getRuta("SUPERVISOR","estadisticas/chartEstadisticas/ingresoDiario")?>" data-rest="<?=Link::getRuta("SUPERVISOR","rest/estadisticas/ingresoDiario")?>">
                    Ingresos Hoy
                </a>
                <a href="#" class="list-group-item" data-chart="" data-rest="">Registros Mes</a>
                <a href="#" class="list-group-item" data-chart="" data-rest="">Ingresos Por Usuario</a>
                <a href="#" class="list-group-item" data-chart="" data-rest="">Rechazos Por Usuario</a>
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
            </div>
        </div>
    </div>
</div>
<script src="recursos/js/highcharts/code/highcharts.js" ></script>
<script>
    window.currentRestData = "<?=Link::getRuta("SUPERVISOR","rest/estadisticas/ingresoDiario")?>";
    window.currenRestChart = "<?=Link::getRuta("SUPERVISOR","estadisticas/chartEstadisticas/ingresoDiario")?>";
    
    function requestData() {
            $.ajax({
                url: currentRestData,
                success: function(point) {
                    var series = chart.series[0],
                        shift = series.data.length > 20;
                    chart.series[0].addPoint(point, true, shift);
                    setTimeout(requestData, 5000);    
                },
                cache: false
            });
        }
    $('#chart').slideUp();
    $(document).ready(function (){
    
        $.ajax({
            url:currenRestChart,
        }).done(function (data, textStatus, jqXHR){
            console.log(data);
            $('#chart').append($(data));
        }).fail(function( jqXHR, textStatus, errorThrown ) {
            $.modalMsj('error',"Error Fatal, favor reportar el problema.");
        });
        $('#loading').slideUp('MEDIUM',function (){
            $('#chart').slideDown('MEDIUN');
        });
        $('.list-group a').click(function (){
            if($(this).hasClass('active')){
                return false;
            }
            $('.list-group a').removeClass('active');
            currentRestData = $(this).data('rest');
//            $(this).addClass('active');
//            $('#chart').children().remove();
        });
    });
</script>