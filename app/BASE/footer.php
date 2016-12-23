        
        <footer>
            <h4>--DEBUG--</h4>
            <?php
            $debug = array(
                'SESSION' => $_SESSION,
                'COOKIE'  => $_COOKIE,
                'POST'    => $_POST,
                'GET'     => $_GET,
                'SERVER'  => $_SERVER
            ); 
            printArray($debug);
            ?>
        </footer>
    <!--js-->
  </body>
</html>
