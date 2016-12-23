<?php
require_once(Link::include_file('clases/DAO/UsuarioDAO.php'));
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app = new MensajeSistema();
try {
    $usuarioDao = new UsuarioDAO();
    $grupos = $usuarioDao->getAllGrupos();
    $usuarioActual = new Usuario();
    if(isset($_POST['Submit']) && $_POST['Submit'] == 'Submit'){
        $usuarioActual->id = $_POST['id'];
        $usuarioActual->nombre = $_POST['userName'];
        $usuarioActual->email = $_POST['userEmail'];
        $usuarioActual->clave = $_POST['userPass'];
        $usuarioActual->tipo_usuario = $_POST['userGroup'];
        $usuarioActual->activo = $_POST['activo'];
        
        $resp = $usuarioDao->almacenarUsuario($usuarioActual);
        if($resp)
            $app->addMessage("Usuario Almacenado", UserException::SUCCESS);
        else
            $app->addMessage("Error al Almacenar Usuario", UserException::WARNING);
        
    }else{
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $usuarioActual = $usuarioDao->getUsuarioById($_GET['id']);
        }
    }
    
} catch (UserException $e) {
    $app->addMessage($e->getMessage(), $e->getCode());
} catch (Exception $e){
    //loger
}
?>
<div class="container-fluid">
    <div class="pagina-titulo panel panel-default ">
        <div class="panel-body">
            <div class="col-md-6">
                <span class="glyphicon glyphicon-cog"></span>
                Detalle de Usuario
            </div>
        </div>
    </div>
    <div class="row add-participante">
        <div class="col-md-12 col-lg-12">
            <div class="jumbotron jumbotron-white">
                <form method="POST" action="" name="agregar-usuario">  
                    <input type="hidden" name="id" value="<?=$usuarioActual->id?>"/>
                    <div class="col-md-12 col-lg-12">
                        <h3 class="titulo-form-ingreso">
                            Usuario
                        </h3>
                    </div>       
                    <div class="col-md-12 col-lg-12">
                        <div class="col-md-4 col-lg-4 col-md-offset-2">
                            <div class="form-group">
                                <label for="userName">Nombre</label>
                                <input type="text" class="form-control" required="" value="<?=$usuarioActual->nombre?>" name="userName" id="userName" placeholder="Nombre completo del Usuario">
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="userEmail">Email</label>
                                <input type="email" class="form-control" required="" <?=!empty($usuarioActual->email) ? "readonly=\"readonly\"": null ;?> value="<?=$usuarioActual->email?>" name="userEmail" id="userEmail" placeholder="ejemplo@gmail.com">
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-md-offset-2">
                            <div class="form-group">
                                <label for="userPass">Clave</label>
                                <input type="password"   class="form-control" autocomplete="off" value=""  name="userPass" id="userPass" placeholder="Clave del Usuario">
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="userGroup">Grupo</label>
                                <select class="selectpicker form-control" name="userGroup" required="" id="userGroup" data-live-search="false">
                                    <option value="">Seleccione...</option>
                                    <?php
                                        if($grupos != null){
                                            foreach ($grupos as $key => $value) {
                                    ?>
                                    <option value="<?=$value->id; ?>" <?=($value->id == $usuarioActual->tipo_usuario) ? "Selected=\"selected\"" : null ;?>><?=$value->nombre?></option>
                                                    
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-md-offset-4">
                            <div class="col-md-12 col-lg-12 text-center">
                                <h5><b>Estado</b></h5>
                            </div>
                            <div class="col-md-12 col-lg-12 text-center">
                                <label>
                                    Activo <input type="radio" name="activo" <?=($usuarioActual->activo == 't') ? "checked=\"\"" : null ;?> value="t">
                                </label>
                            </div>
                            <div class="col-md-12 col-lg-12 text-center">
                                <label>
                                    Inactivo <input type="radio" name="activo" <?=($usuarioActual->activo == 'f') ? "checked=\"\"" : null ;?>  value="f">
                                </label>
                            </div>
                        </div>
                        <input type="hidden" name="Submit" value="Submit" />
                        <div class="col-md-12 col-lg-12">
                            <div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
                                <button class="btn btn-primary btn-block crear" type="submit"><span class="glyphicon glyphicon-ok"></span> Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>