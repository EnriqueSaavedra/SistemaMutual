<?php
require_once(Link::include_file('clases/DAO/CursoDAO.php'));
require_once(Link::include_file('clases/DAO/GeoDAO.php'));


$app = new MensajeSistema();
try {
    $geoDao = new GeoDAO();
    $cursoDao = new CursoDAO();
    
    
    $tiposCuros = $cursoDao->getTipoCursoPicker();
    $comunas = $geoDao->getComunaPicker();
    $origen = $cursoDao->getOrigenPicker();
    
    if($comunas == null)
        throw new UserException("No se encuentras comunas ingresadas en el Sistema", UserException::ERROR);
    
    if($tiposCuros == null)
        throw new UserException("No se encuentras Tipos de Curso ingresados en el Sistema", UserException::ERROR);
    
    if($origen == null)
        throw new UserException("No se encuentras Origenes de Documento ingresados en el Sistema", UserException::ERROR);
    
    
    
    
    
    /**
     * POST
     */
    if(isset($_POST['Submit'])){
        $curso = new Curso();
        $empresa = new Empresa();
        $relator = new Relator();
        
        $curso->tipo_curso = $_POST['cursoNombre'];
        $curso->fecha_inicio = $_POST['fechaInicio'];
        $curso->numero_calle = $_POST['dirNumero'];
        $curso->direccion = $_POST['direccion'];
        $curso->direccion_adicional = $_POST['ofParDep'];
        $curso->comuna  = $_POST['comuna'];
        $curso->contacto_nombre = $_POST['nombreContacto'];
        $curso->contacto_email = $_POST['emailContacto'];
        $curso->origen = $_POST['origen'];
        
        if(!$empresa->setRutCompleto($_POST['empresa']))
            throw new Exception("No se pudo extraer informacion de empresa.");
        
        $empresa->adherente = $_POST['adherente'];
        $curso->detalleEmpresa = $empresa;
        
        if(!$relator->setRutCompleto($_POST['relator']))
            throw new Exception("No se pudo extraer informacion de relator.");
        
        $relator->nombre = $_POST['nRelator'];
        $curso->detalleRelator = $relator;
        
        
        $respuesta = $cursoDao->crearNewCurso($curso);
        if($respuesta instanceof Curso){
            Link::redirect(
                    'USUARIO',
                    'agregarParticipantes/agregarParticipantes',
                    array(
                        'id' => $respuesta->id,
                        'rechazado' => false
                    )
                );
        } elseif($respuesta instanceof Rechazos){
            Link::redirect(
                    'USUARIO',
                    'agregarParticipantes/agregarParticipantes',
                    array(
                        'id' => $respuesta->id_curso_referencia,
                        'rechazado' => true
                    )
                );
        }else{
            throw new UserException("Error inesperado, no se ha creado el curso", UserException::ERROR);
        }
    }
    
    /**
     * 
     */
        if(!empty($_GET['success'])){
            $app->addMessage("Curso Actualizado.", UserException::SUCCESS);
        }
} catch (UserException $e) {
    $app->addMessage($e->getMessage(), $e->getCode());
} catch (Exception $e){
    //loger
    echo $e->getMessage();
}


?>
<link href="recursos/css/crearCurso.css" rel="stylesheet">
<div class="container-fluid cuerpo-cargado" style="display: none;">
    <div class="pagina-titulo panel panel-default">
      <div class="panel-body">
        <span class="glyphicon glyphicon-pencil"></span>
        Crear Nuevo Curso
      </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="jumbotron jumbotron-white">
                <form method="POST" action="">        
                    <div class="col-md-12 col-lg-12"><h3 class="titulo-form-ingreso">Detalle del Curso</h3></div>       
                    <div class="col-md-12 col-lg-12">
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="tipoCurso">Nombre de Curso</label>
                                <select class="selectpicker form-control" name="cursoNombre" required="" id="cursoNombre" data-live-search="true">
                                    <option value="">Seleccione...</option>
                                    <?php 
                                    if($tiposCuros != null){
                                        foreach ($tiposCuros as $key => $value) {
                                            /*@var $value TipoCurso*/
                                        ?>
                                    <option value="<?php echo $value->getId(); ?>"><?php echo $value->getAlias_curso(); ?></option>
                                        <?php 
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="fechaInicio">Fecha Inicio</label>
                                <input type="date" class="form-control"  name="fechaInicio" required="" id="fechaInicio" placeholder="Fecha Inicio">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="autocomplete">Dirección</label>
                                <input id="autocomplete" name="direccion" placeholder="Dirección" class="form-control" onFocus="geolocate()" required="" type="text"></input>
                                <input type="hidden" name="dirCalle" id="route" />
                                <input type="hidden" name="dirComuna" id="locality"/>
                                <input type="hidden" name="dirRegion" id="administrative_area_level_1" />
                                <input type="hidden" name="dirCodPostal" id="postal_code" />
                                <input type="hidden" name="dirPais"  id="country" />
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="comuna">Comuna</label>
                                <select class="selectpicker form-control" name="comuna" required="" id="comuna" data-live-search="true">
                                    <option data-texto="Seleccione..." value="">Seleccione...</option>
                                    <?php 
                                    if($comunas != null){
                                        foreach ($comunas as $key => $value) {
                                            /*@var $value Comuna*/
                                        ?>
                                    <option data-texto="<?php echo $value->getNombre(); ?>" value="<?php echo $value->getId(); ?>"><?php echo $value->getNombre(); ?></option>
                                        <?php 
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="ofParDep">Oficina / Parcela / Departamento</label>
                                <input type="text" class="form-control" min="0" name="ofParDep" id="ofParDep" placeholder="Dato adicional a dirección">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="street_number">N° Calle</label>
                                <input type="number" class="form-control" required="" min="0" name="dirNumero" id="street_number" placeholder="Número de Calle">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="origen">Origen</label>
                                <select class="selectpicker form-control" name="origen" required="" id="origen" data-live-search="true">
                                    <option value="">Seleccione...</option>
                                    <?php 
                                    if($origen != null){
                                        foreach ($origen as $key => $value) {
                                            /*@var $value Origen*/
                                        ?>
                                    <option  value="<?php echo $value->getId(); ?>"><?php echo $value->getNombre(); ?></option>
                                        <?php 
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group" id="adicional">
                                <label for="adicional" id="nombe-adicional"></label>
                                <span id="campo-adicional"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12"><h3 class="titulo-form-ingreso">Detalle del Empresa</h3></div>
                    <div class="col-md-12 col-lg-12">
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="empresa"  class="control-label">RUT Empresa</label>
                                <input type="text" class="form-control" required="" name="empresa" id="empresa" placeholder="Ej: 774311238">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="adherente">Adherente</label>
                                <input type="number" class="form-control" required="" name="adherente" id="adherente" placeholder="Adherente Empresa">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="nombreContacto">Nombre de Contacto</label>
                                <input type="text" class="form-control" name="nombreContacto" id="nombreContacto" placeholder="Nombre de Contacto">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="emailContacto">Email de Contacto</label>
                                <input type="email" class="form-control" name="emailContacto" id="emailContacto" placeholder="Email Contacto">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12"><h3 class="titulo-form-ingreso">Detalle del Relator</h3></div>
                    <div class="col-md-12 col-lg-12">
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="relator" class="control-label">Rut Relator</label>
                                <input type="text" class="form-control" name="relator" required="" id="relator" placeholder="Ej: 18247021k">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="nRelator">Nombre Relator</label>
                                <input type="text" class="form-control" name="nRelator" required="" id="nRelator" placeholder="Nombre Relator">
                            </div>
                        </div>
                        <input type="hidden" name="Submit" value="Submit" />
                        <div class="col-md-12 col-lg-12">
                            <div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
                                <button class="btn btn-success btn-block crear" type="submit"><span class="glyphicon glyphicon-ok"></span> Crear</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid cuerpo-pre-carga">
    <div class="pagina-titulo panel panel-default">
      <div class="panel-body text-center">
        <img src="recursos/imagenes/loading.gif" width="120px" />
      </div>
    </div>
</div>
<script src="recursos/js/jquery.Rut.min.js"></script>
<script>
    var rama = "<?php echo Origen::RAMA; ?>";
    var docFis = "<?php echo Origen::DOC_FIS; ?>";
    var imagen = "<?php echo Origen::IMAGEN; ?>";
    var restEmpresa = "<?php echo Link::getRuta("USUARIO","rest/crearCurso/restCrearEmpresa"); ?>";
    var restRelator = "<?php echo Link::getRuta("USUARIO","rest/crearCurso/restCrearRelator"); ?>";
    var restValidarCurso = "<?php echo Link::getRuta("USUARIO","rest/crearCurso/restValidarCurso"); ?>";
    var rutRmpresaCorrecto = false;
    var rutRelatoCorrecto = false;
    
    $(document).ready(function (){
        $('#adicional').hide('FAST');
        $('.cuerpo-pre-carga').slideUp('FAST',function (){
            $('.cuerpo-cargado').slideDown('FAST');
        });
        
        $("#empresa").rut({
            formatOn: 'blur',
            minimumLength: 8, // validar largo mínimo; default: 2
            validateOn: 'change' // si no se quiere validar, pasar null
        });
        
        $("#empresa").rut().on('rutValido', function(e, rut, dv) {
            rutRmpresaCorrecto = true; 
            $('#empresa').parent('div').removeClass('has-error');
        });
        
        $("#empresa").rut().on('rutInvalido', function(e) {
            rutRmpresaCorrecto = false; 
            $('#empresa').parent('div').addClass('has-error');
            $('#empresa').val("");
        });
    
        $("#relator").rut({
            formatOn: 'blur',
            minimumLength: 8, // validar largo mínimo; default: 2
            validateOn: 'change' // si no se quiere validar, pasar null
        });
        
        $("#relator").rut().on('rutValido', function(e, rut, dv) {
            rutRelatoCorrecto = true; 
            $('#relator').parent('div').removeClass('has-error');
        });
        
        $("#relator").rut().on('rutInvalido', function(e) {
            rutRelatoCorrecto = false; 
            $('#relator').parent('div').addClass('has-error');
            $('#relator').val("");
        });
        
    
        $('#nParticipantes').keypress(function (){
            var valuekey = event.charCode;
            if(valuekey == 45)
                return false;
        });
        
        $('#nParticipantes').bind('paste', function (e) {
            e.preventDefault();
        });    
        
        
        /**
         * AJAX
         */
        $('#empresa,#adherente').blur(function (){
            var valorEmpresa = $('#empresa').val();
            var adherente = $('#adherente').val();
            
            if((valorEmpresa != "" && adherente == "") ||  (valorEmpresa == "" && adherente != "")){
                $.ajax({
                    method:'POST',
                    url:restEmpresa,
                    data:{
                        rut:valorEmpresa,
                        adherente:adherente
                    }
                }).done(function (data, textStatus, jqXHR){
                    data = JSON.parse(data);
                    if(data.succes && data.data != null){
                        $('#empresa').val("");
                        var rut=data.data.rut+data.data.dv;
                        $('#empresa').val($.formatRut(rut));
                        $('#adherente').val(data.data.adherente);
                        $('#empresa').parent('div').removeClass('has-error');
                    }else{
                        $('#adherente').val("");
                    }
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                    console.log("Fail");
                });
            }
        });
        
        $('#relator').blur(function (){
            var valorRelator = $('#relator').val();
            
            if(!rutRelatoCorrecto)
                return false;
            
            $.ajax({
                method:'POST',
                url:restRelator,
                data:{
                    rut:valorRelator
                }
            }).done(function (data, textStatus, jqXHR){
                data = JSON.parse(data);
                if(data.succes && data.data != null){
                    $('#relator').val("");
                    var rut=data.data.rut+data.data.dv;
                    $('#relator').val($.formatRut(rut));
                    $('#nRelator').val(data.data.nombre);
                    $('#relator').parent('div').removeClass('has-error');
                }else{
                    $('#nRelator').val("");
                }
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                console.log("Fail");
            });
        });
        
        /**
        * FIN AJAX
        */


        //adicional es el div y campo-adicional donde debes meter el input
        $('#origen').change(function (){
            $('#nombe-adicional').text("");
            $('#campo-adicional').find('input').remove();
            var currentVal = $(this).val();
            if(currentVal == rama){
                $('#nombe-adicional').text("Adjuntar Archivo");
                $('#campo-adicional').delay(1000).append("<input type=\"file\" name=\"adicional\" class=\"form-control\" id=\"adicional\">");
            }else if(currentVal == docFis){
                $('#nombe-adicional').text("Ubicacion del Archivo");
                $('#campo-adicional').delay(1000).append("<input type=\"text\" name=\"adicional\" class=\"form-control\" id=\"adicional\">");
            }else if(currentVal == imagen){
                $('#nombe-adicional').text("Adjuntar Imagen");
                $('#campo-adicional').delay(1000).append("<input type=\"file\" name=\"adicional\" class=\"form-control\" id=\"adicional\">");
            }else{
                $('#adicional').hide('FAST');
                return;
            }
            $('#adicional').show('FAST');
        });
    });
</script>
<script>
    var placeSearch, autocomplete;
    var componentForm = {
      street_number: 'short_name',
      route: 'long_name',
      locality: 'long_name',
      administrative_area_level_1: 'short_name',
      country: 'long_name',
      postal_code: 'short_name'
    };

    function initAutocomplete() {
      // Create the autocomplete object, restricting the search to geographical
      // location types.
      autocomplete = new google.maps.places.Autocomplete(
          /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
          {types: ['geocode']});

      // When the user selects an address from the dropdown, populate the address
      // fields in the form.
      autocomplete.addListener('place_changed', fillInAddress);
    }

    // [START region_fillform]
    function fillInAddress() {
        $('#street_number').prop("readonly","readonly");
        $('#comuna').prop('disabled', true);
        $('#comuna').find('option').removeAttr('selected');
        $('#comuna').selectpicker('refresh');
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
            document.getElementById(component).value = '';
            document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                document.getElementById(addressType).value = val;
                if(addressType == 'locality'){
                    $('#comuna').find('option').filter(function (){
                        return $(this).data("texto").toLowerCase() == val.toLowerCase();
                    }).attr('selected','selected');
                    $('#comuna').find('option').removeProp('readonly');
                    $('#comuna').selectpicker('refresh');
                }
            }
        }
        $('#comuna').prop('disabled', false);
        $('#street_number').removeProp("readonly");
    }
    // [END region_fillform]

    // [START region_geolocation]
    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var geolocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          };
          var circle = new google.maps.Circle({
            center: geolocation,
            radius: position.coords.accuracy
          });
          autocomplete.setBounds(circle.getBounds());
        });
      }
    }
// [END region_geolocation]
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOyAbfyC9sEwndReut4ZCeTpraR7XX6nU&libraries=places&callback=initAutocomplete"
        async defer></script>
