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
    $usuarios = $usuarioDao->getAllUsuarios();
    
    if($usuarios == null)
        throw new UserException("Sin datos para mostrar", UserException::WARNING);
    
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
                Usuarios
            </div>
            <div class="col-md-1 col-md-offset-5">
                <a href="<?php echo Link::getRutaHref('SUPERVISOR', 'adminUsuario/detalleUsuario/detalleUsuario'); ?>">
                    <button class="btn btn-primary btn-block" id="agregar-participantes" type="button"><span  class="glyphicon glyphicon-plus"></span></button>
                </a>
            </div>
        </div>
    </div>
    <div class="row participantes">
        <div class="col-md-12 col-lg-12">
            <div class="jumbotron jumbotron-white listado-participantes">
                <div class="col-md-12 col-lg-12">
                    <h3 class="text-center">Listado Usuarios</h3>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Grupo Acceso</th>
                            <th>Activo</th>
                        </tr>
                    </thead>
                    <tbody class="cuerpo-listado">
                        <?php
                        if($usuarios == null){
                        ?>
                        <tr class="no-data">
                            <th scope="row" colspan="9" class="text-center">-- SIN DATOS PARA MOSTRAR --</th>
                        </tr>
                        <?php
                        }else{
                            foreach ($usuarios as $key => $value) {
                        ?>
                        <tr <?=$value['activo'] != 1 ? "style='color:grey;'" : null ; ?>>
                            <th>
                            <a href="<?php echo Link::getRutaHref('SUPERVISOR', 'adminUsuario/detalleUsuario/detalleUsuario',array('id' => $value['id'])); ?>">
                                <button class="btn btn-primary" type="button" data-id="<?=$value['id']?>">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </button>
                            </a>
                            </th>
                            <th><?=$value['id']?></th>
                            <th><?=$value['nombre']?></th>
                            <th><?=$value['email']?></th>
                            <th><?=$value['grupo']; ?></th>
                            <th><?=$value['activo'] == 1 ? "Activo" : "Inactivo"; ?></th>
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