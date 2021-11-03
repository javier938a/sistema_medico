$(document).ready(function(){
    //generar2();

    $("#imprimir_cuenta").click(function(evt){
        evt.preventDefault();
        var id_factura=$("#id_factura").val();
        var no_referencia=$("#referencia").val();
        var nombre_paciente=$("#nombre_paciente").val();
        let url_estado="ver_estado_cuenta_pdf.php?&id_factura="+id_factura+"&nombre_paciente="+nombre_paciente+"&no_referencia="+no_referencia;
        window.open(url_estado, '_blank');

    });

});

