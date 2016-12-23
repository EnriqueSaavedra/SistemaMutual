/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/* jQuery.rut.js */
(function ($){

    $.modalMsj = function (tipo,mensaje){
        var titulo = "";
        var tipoClass = "";
        switch (tipo) {
            case 'success':
                titulo = "Completado";
                tipoClass="alert alert-success";
                break;
            case 'info':
                titulo = "Información";
                tipoClass = "alert alert-info";
                break;
            case 'warning':
                titulo = "Problema";
                tipoClass = "alert alert-warning";
                break;
            case 'error':
                titulo = "Error";
                tipoClass = "alert alert-danger";
                break;
        }
        
        $('#modalMsjJquery').find('.mensaje').text(mensaje);
        $('#modalMsjJquery').find('.tipo').addClass(tipoClass);
        $('#modalMsjJquery').find('.titulo').text(titulo);
        $('#modalMsjJquery').modal('show');
    }
})(jQuery);