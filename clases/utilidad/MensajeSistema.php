<?php

class MensajeSistema {
    
    public function addMessage($mensaje,$code) {
        $tipo="";
        $titulos = "";
        $body="";
        $titulo = "";
        $script="";
        
        switch ($code) {
            case UserException::SUCCESS:
                $titulo = "Completado";
                $tipo="alert alert-success";
                break;
            case UserException::INFO:
                $titulo = "Información";
                $tipo = "alert alert-info";
                break;
            case UserException::WARNING:
                $titulo = "Problema";
                $tipo = "alert alert-warning";
                break;
            case UserException::ERROR:
                $titulo = "Error";
                $tipo = "alert alert-danger";
                break;
        }
        
        $body ="<div class=\"modal fade\" id=\"modalSistema\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"modalSistemaLabel\">
                    <div class=\"modal-dialog\" role=\"document\">
                      <div class=\"modal-content $tipo\">
                        <div class=\"modal-header\">
                          <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
                          <h4 class=\"modal-title\" id=\"modalSistemaLabel\">$titulo</h4>
                        </div>
                        <div class=\"modal-body\">
                          $mensaje
                        </div>
                        <div class=\"modal-footer\">
                          <button type=\"button\" class=\"btn btn-default btn-modal-cerrar\" autofocus data-dismiss=\"modal\">Cerrar</button>
                        </div>
                      </div>
                    </div>
                </div>";
       
        $script = '<script>
                    $(\'#modalSistema\').modal(\'show\');
                  </script>';
        
        echo $body.$script;
    }
}
