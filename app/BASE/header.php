<?php
require_once(Link::include_file('clases/utilidad/UserException.php'));
require_once(Link::include_file('clases/utilidad/MensajeSistema.php'));
require_once(Link::include_file('clases/DAO/UsuarioDAO.php'));
/*
 * Metodos globales
 */

session_start();
try {
    $usuarioDao = new UsuarioDAO();
} catch (Exception $e) {
    echo $e->getMessage();
}

function printArray($array){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Sistema Mutual</title>
        <link href="recursos/css/bootstrap.min.css" rel="stylesheet">
        <link href="recursos/css/signin.css" rel="stylesheet">
        <link href="recursos/css/header.css" rel="stylesheet">
        <link href="recursos/css/bootstrap-select.min.css" rel="stylesheet">
    </head>
<body>
    <script src="recursos/js/jquery.min.js"></script>
    <script src="recursos/js/bootstrap.min.js"></script>
    <script src="recursos/js/bootstrap-select.min.js"></script>
    <script src="recursos/js/jquery.modalMsj.js"></script>
    <div class="modal fade" id="modalMsjJquery" tabindex="-1" role="dialog" aria-labelledby="modalMsjJqueryLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content tipo">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title titulo" id="modalMsjJqueryLabel"></h4>
                </div>
                <div class="modal-body mensaje">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" autofocus>Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--header pagina-->
<?php
    if(isset($_SESSION['USUARIO']) && isset($_SESSION['USUARIO']['GROUP'])){
        $grupo = $usuarioDao->getGroupName($_SESSION['USUARIO']['GROUP']);
        
        if($grupo == Grupo_usuario::USUARIO){ ?>
            <link rel="stylesheet" href="recursos/css/usuario_home.css" >
            <header>
                <nav class="navbar navbar-default navbar-static-top adn-navbar">
                    <div class="container">
                        <div class="navbar-header">
                            <a href="<?php echo Link::getRutaHref('USUARIO', 'home'); ?>">
                                <img src="recursos/imagenes/logo_adn_01.png" class="adn-logo" alt="">
                            </a>
                        </div>
                        <div class="collapse navbar-collapse adn-nav">
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="<?php echo Link::getRutaHref('USUARIO', 'crearCurso/crearCurso'); ?>" class="page-scroll adn-nav-title">CREAR CURSO</a></li>
                                <li><a href="<?php echo Link::getRutaHref('USUARIO', 'misCursos/misCursos'); ?>" class="page-scroll adn-nav-title">MIS CURSOS INGRESADOS</a></li>
                                <li><a href="#nosotros" class="page-scroll adn-nav-title"><del>Notificaciones <span class="badge">0</span></del></a></li>
                                <!--<li><a href="#servicios" class="page-scroll adn-nav-title">servicios</a></li>-->
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle adn-nav-title" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Micuenta<span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#personas" class="page-scroll"><del>Mis Datos</del></a></li>
                                        <li><a href="<?php echo Link::getRutaHref('BASE', 'cerrarSession'); ?>" class="page-scroll">Cerrar Sesión</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
        <?php
            
        }elseif ($grupo == Grupo_usuario::SUPERVISOR) { ?>
            <link rel="stylesheet" href="recursos/css/usuario_home.css" >
            <header>
                <nav class="navbar navbar-default navbar-static-top adn-navbar">
                    <div class="container">
                        <div class="navbar-header">
                            <a href="<?php echo Link::getRutaHref('SUPERVISOR', 'home'); ?>">
                                <img src="recursos/imagenes/logo_adn_01.png" class="adn-logo" alt="">
                            </a>
                        </div>
                        <div class="collapse navbar-collapse adn-nav">
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="<?php echo Link::getRutaHref('SUPERVISOR', 'formularioSabana/formularioSabana'); ?>" class="page-scroll adn-nav-title">EXTRAER SABANA</a></li>
                                <li><a href="<?php echo Link::getRutaHref('SUPERVISOR', 'adminUsuario/adminUsuario'); ?>" class="page-scroll adn-nav-title">ADMIN USUARIOS</a></li>
                                <li><a href="#" class="page-scroll adn-nav-title"><del>ESTADISTICAS DEL EQUIPO</del></a></li>
                                <li><a href="#nosotros" class="page-scroll adn-nav-title"><del>Notificaciones <span class="badge">0</span></del></a></li>
                                <!--<li><a href="#servicios" class="page-scroll adn-nav-title">servicios</a></li>-->
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle adn-nav-title" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Micuenta<span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#personas" class="page-scroll"><del>Mis Datos</del></a></li>
                                        <li><a href="<?php echo Link::getRutaHref('BASE', 'cerrarSession'); ?>" class="page-scroll">Cerrar Sesión</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
                    
    <?php }
    }
?>