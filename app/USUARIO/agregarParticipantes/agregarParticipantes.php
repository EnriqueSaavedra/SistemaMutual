<?php
require_once(Link::include_file('clases/DAO/CursoDAO.php'));
require_once(Link::include_file('clases/DAO/GeoDAO.php'));
require_once(Link::include_file('clases/DAO/ParticipanteDAO.php'));
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app = new MensajeSistema();

try {
    $geoDao = new GeoDAO();
    $cursoDao = new CursoDAO();
    $participanteDao = new ParticipanteDAO();
    
    $idCurso = $_GET['id'];
    $detalleEmpresa = true;
    $detalleRelator = true;
    
    if(empty($idCurso))
        throw new UserException("N° de curso no recibido.", UserException::ERROR);
    
    /*@var $curso Curso*/
    $curso = $cursoDao->getCursoById($idCurso,$detalleEmpresa,$detalleRelator);
    $comuna = $geoDao->getComunaNombre($curso->comuna);
    $origen = $cursoDao->getOrigenNombre($curso->origen);
    $tipoCurso = $cursoDao->getCursoNombre($curso->tipo_curso);
    $participantes = $participanteDao->getAllParticipantes($idCurso);
    
    
    if($curso == null)
        throw new UserException("No se pudo obtener el curso N° $idCurso", UserException::ERROR);
    
    
    if(isset($_POST['Submit'])){
        if($_POST['Submit'] == 'Guardar'){
            $resp = $cursoDao->recalcularParticipantes($curso);
            if(!$resp){
                throw new UserException("Error al recalcular participante", UserException::ERROR);
            }else{
                Link::redirect(
                        'USUARIO',
                        'crearCurso/crearCurso',
                        array(
                            'success' => true
                        )
                    );
            }
        }elseif($_POST['Submit'] == 'Eliminar'){
            $resp = $cursoDao->eliminarCursoDEPRECABLE($idCurso);
            if(!$resp)
                $app->addMessage("Error al Eliminar Curso", UserException::ERROR);
            else{
                Link::redirect(
                       'USUARIO',
                       'misCursos/misCursos'
                   );
            }
        }
    }else{
        if(isset($_GET['rechazado'])){     
            $rechazo = $_GET['rechazado'];
            if($rechazo)
                $app->addMessage("Su Curso Fue ingresado con anterioridad, aún puede verificar los participantes.", UserException::WARNING);
            else
                $app->addMessage ("Curso Creado exitosamente!!.", UserException::SUCCESS);
        }
    }
    
    
    
} catch (UserException $e) {
    $app->addMessage($e->getMessage(), $e->getCode());
} catch (Exception $e){
    //loger
}
if($curso != null){
?>
<input type="hidden" name="usuario" id="usuario" value="<?=$_SESSION['USUARIO']['ID']?>"/>
<div class="container-fluid">
    <div class="pagina-titulo panel panel-default ">
      <div class="panel-body linkeable">
        <span class="glyphicon glyphicon-triangle-bottom"></span>
        Información de curso
        
      </div>
    </div>
    <div class="row curso" style="display: none;">
        <div class="col-md-12 col-lg-12">
            <div class="jumbotron jumbotron-white">      
                <div class="col-md-12 col-lg-12"><h3 class="titulo-form-ingreso">Detalle del Curso</h3></div>       
                <div class="col-md-12 col-lg-12">
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <input type="hidden" value="<?php echo $curso->id; ?>" id="curso"/>
                            <label for="tipoCurso">Nombre de Curso</label>
                            <input type="text" class="form-control" readonly="" value="<?php echo $tipoCurso; ?>" >
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="fechaInicio">Fecha Inicio</label>
                            <input type="text" class="form-control" readonly="" value="<?php $date = new DateTime($curso->fecha_inicio);echo $date->format('Y-m-d'); ?>" >
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="autocomplete">Dirección</label>
                            <input type="text" readonly=""  value="<?php echo $curso->direccion; ?>"  class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="comuna">Comuna</label>
                            <input type="text" class="form-control"  readonly="" value="<?php echo $comuna;?>"/>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="nParticipantes">Numero Participantes</label>
                            <input type="text" value="<?php echo $curso->participantes; ?>" readonly="" class="form-control" >
                        </div>
                    </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="ofParDep">Oficina / Parcela / Departamento</label>
                                <input type="text" class="form-control" value="<?php echo $curso->direccion_adicional;?>" readonly="" min="0" name="ofParDep" id="ofParDep" placeholder="Dato adicional a dirección">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="street_number">N° Calle</label>
                                <input type="text" class="form-control" value="<?php echo $curso->numero_calle;?>" readonly="" min="0" name="dirNumero" id="street_number" placeholder="Número de Calle">
                            </div>
                        </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="origen">Origen</label>
                            <input type="text" readonly="" class="form-control" value="<?php echo $origen; ?>" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12"><h3 class="titulo-form-ingreso">Detalle del Empresa</h3></div>
                <div class="col-md-12 col-lg-12">
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="empresa"  class="control-label">RUT Empresa</label>
                            <input type="text" class="form-control" value="<?php echo $curso->detalleEmpresa->rut.$curso->detalleEmpresa->dv; ?>"  id="empresa" readonly="" />
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="adherente">Adherente</label>
                            <input type="text" class="form-control" value="<?php echo $curso->detalleEmpresa->adherente; ?>" readonly="" >
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="nombreContacto">Nombre de Contacto</label>
                            <input type="text" class="form-control" readonly="" value="<?php echo $curso->contacto_nombre; ?>" >
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="emailContacto">Email de Contacto</label>
                            <input type="text" class="form-control"  value="<?php echo $curso->contacto_email; ?>" readonly="" >
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12"><h3 class="titulo-form-ingreso">Detalle del Relator</h3></div>
                <div class="col-md-12 col-lg-12">
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="relator" class="control-label">Rut Relator</label>
                            <input type="text" class="form-control" readonly="" value="<?php echo $curso->detalleRelator->rut.$curso->detalleRelator->dv; ?>" id="relator" >
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="nRelator">Nombre Relator</label>
                            <input type="text" class="form-control" readonly="" value="<?php echo $curso->detalleRelator->nombre; ?>" >
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="pagina-titulo panel panel-default ">
      <div class="panel-body">
          <div class="col-md-6">
            <span class="glyphicon glyphicon-list"></span>
            Listado de Participantes
          </div>
          <div class="col-md-1 col-md-offset-5">
              <button class="btn btn-primary btn-block" id="agregar-participantes" type="button"><span  class="glyphicon glyphicon-plus"></span></button>
          </div>
        
      </div>
    </div>
    <div class="row add-participante" style="display: none;">
        <div class="col-md-12 col-lg-12">
            <div class="jumbotron jumbotron-white">
                <form method="POST" action="" name="agregar-participante">        
                    <div class="col-md-12 col-lg-12"><h3 class="titulo-form-ingreso">Agregar Participante</h3></div>       
                    <div class="col-md-12 col-lg-12">
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="participanteRut">Rut</label>
                                <input type="text" class="form-control" required="" min="0" name="participanteRut" id="participanteRut" placeholder="Ej: 18247021k">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="participanteNombre">Nombre</label>
                                <input type="text" class="form-control" required="" name="participanteNombre" id="participanteNombre" placeholder="Nombre del Participante">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="autocomplete">Email</label>
                                <input type="email" class="form-control" name="participanteEmail" id="participanteEmail" placeholder="ejemplo@gmail.com">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="participanteEdad">Edad</label>
                                <input type="number"  class="form-control" required=""  name="participanteEdad" id="participanteEdad" placeholder="Edad del Participante">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="participanteGenero">Genero</label>
                                <select class="selectpicker form-control" name="participanteGenero" required="" id="participanteGenero" data-live-search="false">
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="Submit" value="Submit" />
                        <div class="col-md-12 col-lg-12">
                            <div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
                                <button class="btn btn-primary btn-block crear" type="submit"><span class="glyphicon glyphicon-ok"></span> Agregar Participante</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="row participantes">
        <div class="col-md-12 col-lg-12">
            <div class="jumbotron jumbotron-white listado-participantes">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th>N°</th>
                            <th>Rut</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Edad</th>
                            <th>Sexo</th>
                        </tr>
                    </thead>
                    <tbody class="cuerpo-listado">
                        <?php  if($participantes == null){ ?>
                        <tr class="no-data">
                            <th scope="row" colspan="7" class="text-center">-- SIN DATOS PARA MOSTRAR --</th>
                        </tr>
                        <?php  }else{
                            foreach ($participantes as $key => $value) {
                            ?>
                            <tr class="id-<?php echo $value->id;?>">
                                    <th>
                                        <button class="btn btn-danger eliminar-participante" type="button" data-id="<?php echo $value->id;?>"><span class="glyphicon glyphicon-trash"></span></button>
                                    </th>
                                    <th>
                                        <?php echo $value->id; ?>
                                    </th>
                                    <th class="refactor-rut">
                                        <?php echo $value->rut.$value->dv; ?>
                                    </th>
                                    <th>
                                        <?php echo $value->nombre; ?>
                                    </th>
                                    <th>
                                        <?php echo $value->email; ?>
                                    </th>
                                    <th>
                                        <?php echo $value->edad; ?>
                                    </th>
                                    <th>
                                        <?php echo $value->sexo; ?>
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
    <div class="row">
        
        <div class="col-md-4 col-lg-4">
            <form name="eliminar-datos" action="" method="POST">
                <input type="hidden" name="idCurso" value="<?php echo $curso->id; ?>" />
                <input type="hidden" name="Submit" value="Eliminar" />
                <button type="submit" class="btn btn-danger btn-block">Eliminar Curso</button>
            </form>
        </div>
        <div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
            <form name="guardar-datos" action="" method="POST">
                <input type="hidden" name="idCurso" value="<?php echo $curso->id; ?>" />
                <input type="hidden" name="Submit" value="Guardar" />
                <button type="submit" class="btn btn-success btn-block">Guardar Datos</button>
            </form>
        </div>
    </div>
    
</div>
<script src="recursos/js/jquery.rut.min.js"></script>
<script>
    var restAgregarParticipante = "<?php echo Link::getRuta("USUARIO","rest/crearCurso/restAgregarParticipante"); ?>";
    var restEliminarParticipante = "<?php echo Link::getRuta("USUARIO","rest/crearCurso/restEliminarParticipante"); ?>";
    var restTraeParticipante = "<?php echo Link::getRuta("USUARIO","rest/crearCurso/restTraeParticipante"); ?>";
    var validarRut = false;
    $(document).ready(function (){
        $('#empresa').val($.formatRut($('#empresa').val()));
        $('#relator').val($.formatRut($('#relator').val()));
    
        $('#modalMsjJquery').on('shown', function() {
            alert("hola");
        });
        $('#modalMsjJquery').on('shown.bs.modal', function () {
//            alert("caca");
            $('#modalMsjJquery button').focus()
          });
          
        $('form[name="eliminar-datos"]').click(function (){
            if(!confirm("Realmente desea eliminar el curso?.")){
                return false;
            }
        });
        window.eliminarClick = function (e){
            var id = $(e).data('id');
            var curso = $('#curso').val();
            if(!confirm("Realmente desea eliminar la relación?.")){
                return false;
            }
            $.ajax({
                method:'POST',
                url:restEliminarParticipante,
                data:{
                    id:id,
                    curso:curso
                }
            }).done(function (data, textStatus, jqXHR){
                data = JSON.parse(data);
                if(data.succes && data.data != null){
                    $('tr.id-'+id).remove();
                    if($('.cuerpo-listado').find('tr').length == 0){
                        $('.cuerpo-listado').append('<tr class="no-data"><th scope="row" colspan="7" class="text-center">-- SIN DATOS PARA MOSTRAR --</th></tr>');
                    }
                    $.modalMsj('success',data.mensaje);
                }else{
                    $.modalMsj('warning',data.mensaje);
                }
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $.modalMsj('error',"Error Fatal, favor reportar el problema.");
            });
        };
        
        $('#agregar-participantes').click(function (){
            $('.add-participante').slideToggle('MEDIUM');
        });
        
        $('.eliminar-participante').click(function (){
            eliminarClick(this);
        });
        
        $('.linkeable').click(function (){
            $('.curso').slideToggle('MEDIUM',function (e){
                if($('.curso').css('display') == 'none'){
                    $('.linkeable').find('span').removeClass('glyphicon-triangle-top');
                    $('.linkeable').find('span').addClass('glyphicon-triangle-bottom');
                }else{
                    $('.linkeable').find('span').addClass('glyphicon-triangle-top');
                    $('.linkeable').find('span').removeClass('glyphicon-triangle-bottom');
                }
            });
        });
        
    
        $("#participanteRut").rut({
            formatOn: 'blur',
            minimumLength: 8, // validar largo mínimo; default: 2
            validateOn: 'change' // si no se quiere validar, pasar null
        });
        
        $("#participanteRut").rut().on('rutValido', function(e, rut, dv) {
            validarRut = true;
            $('#participanteRut').parent('div').removeClass('has-error');
        });
        
        $("#participanteRut").rut().on('rutInvalido', function(e) {
            validarRut = false;
            $('#participanteRut').parent('div').addClass('has-error');
            $('#participanteRut').val("");
        });
        
        $("#participanteRut").blur(function (){
            if(validarRut){
                var rut = $(this).val();
                $('#participanteNombre').prop("disabled",true);
                $('#participanteEmail').prop("disabled",true);
                $('#participanteEdad').prop("disabled",true);
                $('#participanteGenero').prop("disabled",true);
                $('#participanteGenero').selectpicker('refresh');
                $.ajax({
                    method:'POST',
                    url:restTraeParticipante,
                    data:{
                        rut:rut
                    }
                }).done(function (data, textStatus, jqXHR){
                    data = JSON.parse(data);
                    if(data.succes && data.data != null){
                        $('#participanteNombre').val(data.data.nombre);
                        $('#participanteEmail').val(data.data.email);
                        $('#participanteEdad').val(data.data.edad);
                        //do selected
                        $('#participanteGenero option').removeProp('selected');
                        $('#participanteGenero option').each(function (i,e){
                            if($(this).val() == data.data.sexo){
                                $(this).prop("selected");
                            }
                        });
                        $('#participanteGenero').selectpicker('refresh');
                    }
                    $('#participanteNombre').prop("disabled",false);
                    $('#participanteEmail').prop("disabled",false);
                    $('#participanteEdad').prop("disabled",false);
                    $('#participanteGenero').prop("disabled",false);
                    $('#participanteGenero').selectpicker('refresh');
                    if(data.data == null){
                        $('#participanteNombre').focus();
                    }else{
                        $('button.crear').focus();
                    }
                        
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                    $.modalMsj('error',"Error Fatal, favor reportar el problema.");
                    $('#participanteNombre').prop("disabled",false);
                    $('#participanteEmail').prop("disabled",false);
                    $('#participanteEdad').prop("disabled",false);
                    $('#participanteGenero').prop("disabled",false);
                    $('#participanteGenero').selectpicker('refresh');
                });
            }
        });
        
        
        $('form[name="agregar-participante"]').submit(function (){
            var rut = $('#participanteRut').val();
            var nombre = $('#participanteNombre').val();
            var email = $('#participanteEmail').val();
            var edad = $('#participanteEdad').val();
            var genero = $('#participanteGenero').val();
            var curso = $('#curso').val();
            var usuario = $('#usuario').val();
            $.ajax({
                method:'POST',
                url:restAgregarParticipante,
                data:{
                    usuario:usuario,
                    curso:curso,
                    rut:rut,
                    nombre:nombre,
                    email:email,
                    edad:edad,
                    genero:genero
                }
            }).done(function (data, textStatus, jqXHR){
                console.log(data,"data");
                data = JSON.parse(data);
                if(data.succes && data.data != null){
                    $('.no-data').remove();
                    var btn = $('<th><button class="btn btn-danger eliminar-participante" onclick="eliminarClick(this);" type="button" data-id="'+data.data.id+'"><span class="glyphicon glyphicon-trash"></span></button></th>');
                    var respId = $("<th>"+data.data.id+"</th>");
                    var respRut = $("<th>"+$.formatRut(data.data.rut+data.data.dv)+"</th>");
                    var respNom = $("<th>"+data.data.nombre+"</th>");
                    var respEmail = $("<th>"+data.data.email+"</th>");
                    var respEdad = $("<th>"+data.data.edad+"</th>");
                    var respGen = $("<th>"+data.data.sexo+"</th>");
                    $('.cuerpo-listado')
                            .append("<tr class='id-"+data.data.id+"'></tr>");
                    $('.id-'+data.data.id).append(btn)
                                .append(respId)
                                .append(respRut)
                                .append(respNom)
                                .append(respEmail)
                                .append(respEdad)
                                .append(respGen);
                        
                    $.modalMsj('success',data.mensaje);
                    $('form[name="agregar-participante"]').find('input').val("");
                }else{
                    $.modalMsj('warning',data.mensaje);
                    $('form[name="agregar-participante"]').find('input').val("");
                }
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $.modalMsj('error',"Error Fatal, favor reportar el problema.");
            });
            return false;
        });
    });
</script>

<?php } ?>

