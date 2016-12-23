<?php
require_once(Link::include_file('clases/DAO/CursoDAO.php'));
require_once(Link::include_file('clases/DAO/ParticipanteDAO.php'));
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app = new MensajeSistema();
try {
    $cursoDao = new CursoDAO();
    $participanteDao = new ParticipanteDAO();
    
    $cursos = $cursoDao->getCursosByUsuario(true);
    if($cursos == null)
        $app->addMessage ("Sin Cursos Creados", UserException::INFO);
    
} catch (UserException $e) {
    $app->addMessage($e->getMessage(), $e->getCode());
} catch (Exception $e){
    //loger
}
?>
<div class="container-fluid">
    <div class="pagina-titulo panel panel-default ">
      <div class="panel-body">
        <span class="glyphicon glyphicon-list"></span>
        Mis Cursos
        
      </div>
    </div>
    <div class="row participantes">
        <div class="col-md-12 col-lg-12">
            <div class="jumbotron jumbotron-white listado-participantes">
                <div class="col-md-12 col-lg-12">
                    <h3 class="text-center">Listado Cursos</h3>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th>N°</th>
                            <th>Código Curso</th>
                            <th>Empresa</th>
                            <th>Relator</th>
                            <th>Origen</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Ingreso</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="cuerpo-listado">
                        <?php  if($cursos == null){ ?>
                        <tr class="no-data">
                            <th scope="row" colspan="9" class="text-center">-- SIN DATOS PARA MOSTRAR --</th>
                        </tr>
                        <?php  }else{
                            foreach ($cursos as $key => $value) {
                                $fechaInicio = new DateTime($value['fecha_inicio']);
                                $fechaProceso = new DateTime($value['fecha_proceso']);
                            ?>
                                <tr class="padre" data-identi="<?php echo $value['id'] ;?>">
                                    <th>
                                        <a href="<?php echo Link::getRutaHref('USUARIO', 'agregarParticipantes/agregarParticipantes',array('id' => $value['id'])); ?>">
                                            <button class="btn btn-primary" type="button" data-id="<?php echo $value['id'];?>">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </button>
                                        </a>
                                    </th>
                                    <th>
                                        <?=$value['id']?>
                                    </th>
                                    <th>
                                        <?=$value['tipo_curso']?>
                                    </th>
                                    <th class="refactor-rut">
                                        <?=$value['empresa']?>
                                    </th>
                                    <th class="refactor-rut">
                                        <?=$value['relator']?>
                                    </th>
                                    <th>
                                        <?=$value['nombre']?>
                                    </th>
                                    <th>
                                        <?=$fechaInicio->format('d-m-Y')?>
                                    </th>
                                    <th>
                                        <?=$fechaInicio->format('d-m-Y H:i:s')?>
                                    </th>
                                </tr>
                            <?php
                            }    
                        }
                        ?>
                    </tbody>
                </table>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<script src="recursos/js/jquery.Rut.min.js"></script>
<script>
    $(document).ready(function (){
        $('.refactor-rut').each(function (i,e){
            var supTxt = $(e).text().trim();
            supTxt = $.formatRut(supTxt);
            $(e).text(supTxt);
        });
//        $('.hijo').slideUp();
//        $('.padre').click(function (){
//            var val = $(this).data('identi');
//            $('.hijo.id-'+val).slideToggle('FAST');
//        });
    });
</script>


