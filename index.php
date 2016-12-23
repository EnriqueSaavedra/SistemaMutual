<?php
require_once('./clases/utilidad/Link.php');
require_once(Link::include_file('app/BASE/header.php'));
?>
<div id="contenido-sitio">
    <?php
        $ctx = isset($_GET["ctx"]) ? $_GET["ctx"] : null;
        $app = isset($_GET["app"]) ? $_GET["app"] : null;
        require_once(
                Link::include_file(
                        Link::getRuta($ctx,$app)
                    )
            );
    ?>
</div>
<?php
require_once(Link::include_file('app/BASE/footer.php'));
?>