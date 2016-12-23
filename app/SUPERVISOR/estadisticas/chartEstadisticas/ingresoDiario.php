<div id="container" style="width:100%; height:400px;"></div>      
<script>
$(document).ready(function() {
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
});
</script>