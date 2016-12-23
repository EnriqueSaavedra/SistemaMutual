<?php
$app = new MensajeSistema();

try {
    $usuarioDao = new UsuarioDAO();
    if(!empty($_POST['Submit'])){
        $email = $_POST['email'];
        $clave = $_POST['passw'];
        $recordar = !empty($_POST['recordar']) ? true : false; 
        $usuario = $usuarioDao->loginUsuario($email, $clave,$recordar);
        if(empty($usuario))
            throw new UserException("Usuario o Contraseña Incorrectos.", UserException::WARNING);
        
        $usuarioDao->reDirUserHome($_SESSION['USUARIO']['ID']);        
    }else{
        if(!empty($_COOKIE['TOKEN_USUARIO']) && $usuarioDao->setSessionByToken($_COOKIE['TOKEN_USUARIO']))
            $usuarioDao->reDirUserHome($_SESSION['USUARIO']['ID']);
        
        if(!empty($_SESSION['USUARIO']))
            $usuarioDao->reDirUserHome($_SESSION['USUARIO']['ID']);
            
    }
} catch(UserException $e){
    $app->addMessage($e->getMessage(), $e->getCode());
}catch (Exception $exc) {
    echo $exc->getTraceAsString();
    //loger
}

?>
<div class="container">
    <form class="form-signin" name="form1" action="index.php" method="POST">
        <h2 class="form-signin-heading">Ingresar</h2>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email" required autofocus>
        <input type="password" name="passw" id="inputPassword" class="form-control" placeholder="Contraseña" required>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="recordar" > Recordar
            </label>
        </div>
        <input type="hidden" name="Submit" value="Submit" />
        <button class="btn btn-lg btn-primary btn-block" type="submit">Ingresar</button>
    </form>
</div> 