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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

  // Load the Visualization API and the corechart package.
  google.charts.load('current', {'packages':['corechart']});

  // Set a callback to run when the Google Visualization API is loaded.
  google.charts.setOnLoadCallback(drawChart);

  // Callback that creates and populates a data table,
  // instantiates the pie chart, passes in the data and
  // draws it.
  function drawChart() {

    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Topping');
    data.addColumn('number', 'Slices');
    data.addRows([
      ['Mushrooms', 3],
      ['Onions', 1],
      ['Olives', 1],
      ['Zucchini', 1],
      ['Pepperoni', 2]
    ]);

    // Set chart options
    var options = {'title':'How Much Pizza I Ate Last Night',
                   'width':400,
                   'height':300};

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  }
</script>
<div class="container-fluid bienvenida">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">
                Bienvenido al Sistema de Supervisión ->ComiitTest<-
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