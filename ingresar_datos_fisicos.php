<?php
include("_core.php");

function initial(){
    
    
    $id_usuario=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri=$_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_usuario, $filename);
    $id_recepcion=$_GET['idRecepcion'];
    //variables donde se almacenara los datos ficicos en dado caso hay una cita ya generada
    $id_paciente=0;
    $altura="";
    $motivo="";
    $hx="";
    $antecedente_paciente="";
    $antecedente_familiar="";
    $ta="";
    $fc="";
    $fr="";
    $temp="";
    $peso="";
    $dx="";
    $plan="";
    //echo "esto..... ."._num_rows(_query($sql_recepcion));
        //obteniendo el id del paciente en recepcion para meterlo en un hidden
    $sql_recepcion="SELECT recepcion.id_tipo_recepcion, tipo_recepcion.tipo , paciente.id_paciente, paciente.nombres, paciente.apellidos, recepcion.evento FROM recepcion INNER JOIN paciente on recepcion.id_paciente_recepcion = paciente.id_paciente LEFT JOIN tipo_recepcion on recepcion.id_tipo_recepcion=tipo_recepcion.id_tipo_recepcion WHERE recepcion.id_recepcion =$id_recepcion";
    $query_recepcion=_query($sql_recepcion);
    if(_num_rows($query_recepcion)>0){
        
        $datos_recepcion=_fetch_array($query_recepcion);
    
        $id_paciente=$datos_recepcion['id_paciente'];
        //verificando si el cliente ya se transfirio a consulta
        $fecha_cita=date('Y-m-d');
        $sql_reserva_cita="SELECT * FROM reserva_cita WHERE id_paciente=$id_paciente AND fecha_cita='$fecha_cita'";
        $query_cita=_query($sql_reserva_cita);
        //verificando si hay una cita para este dia con este paciente
        if(_num_rows($query_cita)>0){

            $datos_cita=_fetch_array($query_cita);
            $altura=$datos_cita['altura'];
            $motivo=$datos_cita['motivo_consulta'];
            $hx=$datos_cita['hx'];
            $antecedente_paciente=$datos_cita['antecedente_personal'];
            $antecedente_familiar=$datos_cita['antecedente_familiar'];
            $ta=$datos_cita['ta'];
            $fc=$datos_cita['fc'];
            $fr=$datos_cita['fr'];
            $temp=$datos_cita['t_o'];
            $peso=$datos_cita['peso'];
            $dx=$datos_cita['dx'];
            $plan=$datos_cita['plan'];


        }

    }
    


?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-navy">Transferir a Consulta:</h4>
    </div>
    <div class="modal-body">
        <div class="wrapper wrapper-content  animated fadeInRight">
            <h4 class="modal-tittle text-navy"><center>Datos fisicos previos a la consulta</center></h4>
            <div class="row" id="row1">
                <div class="col-lg-12">
                    <?php	if ($links!='NOT' || $admin=='1' ){ ?><?php //verificando si tiene privilegios ?>
                        <form class="needs-validation" name="datos_fisicos_previos" id="datos_fisicos_previos">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group has-info text-center">
                                        <label for="">Medico a enviar</label>
                                        <?php 
                                            $sql_medicos="SELECT * FROM doctor WHERE 1";
                                            $query_medicos=_query($sql_medicos);
                                            if(_num_rows(_query($sql_medicos))==1){                                
                                                echo '<select class="select" 
                                                    name="id_medico" id="id_medico" disabled>';    
                                                while($_medico=_fetch_array($query_medicos)){  
                                                        echo '<option value='.$_medico['id'].' selected>'.$_medico['nombres'].' '. $_medico['apellidos'].'</option>';
                                                    }
                                                echo '</select>';
                                            }else{
                                                echo '<select class="select" 
                                                    name="id_medico" id="id_medico">';
                                                    while($_medico=_fetch_array($query_medicos)){
                                                            
                                                        echo '<option value='.$_medico['id'].'>'.$_medico['nombres'].' '. $_medico['apellidos'].'</option>';
                                                    }
                                                echo '</select>';
                                            }
                                        ?>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group has-info text-center">
                                            <label>Estatura</label>
                                            <input type="text" class="form-control" 
                                                name="txt_estatura" id='txt_estatura' 
                                            value="<?php echo $altura  ?>" required>
                                            <div class="valid-feedback">
                                                Correcto!
                                            </div>
                                            <div class="invalid-feedback">
                                                Debe de llenar este campo
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group has-info text-center">
                                            <label>Peso</label>
                                            <input type="text" class="form-control" 
                                                name="txt_peso" id="txt_peso"
                                                value="<?php echo $peso ?>"
                                             required>
                                            <div class="valid-feedback">
                                                Correcto!
                                            </div>
                                            <div class="invalid-feedback">
                                                Debe de llenar este campo
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group has-info text-center">
                                            <label>Motivo de Consulta</label>
                                            <input type="text" class="form-control" 
                                                name="txt_motivo" id="txt_motivo"
                                                value="<?php echo $motivo ?>"
                                             required>
                                            <div class="valid-feedback">
                                                Correcto!
                                            </div>
                                            <div class="invalid-feedback">
                                                Debe de llenar este campo
                                            </div>
                                    </div>
                                </div>                            
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                        <div class="form-group has-info text-center">
                                            <label for="">Hx.</label>
                                            <textarea rows="4" class="from-control col-lg-12" 
                                                name="txt_hx"id="txt_hx" required>
                                                <?php echo $hx ?>
                                            </textarea>
                                            <div class="valid-feedback">
                                                Correcto!
                                            </div>
                                            <div class="invalid-feedback">
                                                Debe de llenar este campo
                                            </div>
                                        </div>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group has-info text-center">
                                            <label for="">Antecedentes del Paciente..</label>
                                            <textarea rows="4" class="from-control col-lg-12" 
                                                name="txt_antecedentes"id="txt_antecedentes" required>
                                                <?php echo $antecedente_paciente ?>
                                            </textarea>
                                            <div class="valid-feedback">
                                                Correcto!
                                            </div>
                                            <div class="invalid-feedback">
                                                Debe de llenar este campo
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group has-info text-center">
                                            <label for="">Antecedentes familiares..</label>
                                            <textarea rows="4" class="from-control col-lg-12" 
                                                name="txt_antecentes_fam"id="txt_antecentes_fam" required>
                                                <?php echo $antecedente_familiar ?>
                                            </textarea>
                                            <div class="valid-feedback">
                                                Correcto!
                                            </div>
                                            <div class="invalid-feedback">
                                                Debe de llenar este campo
                                            </div>
                                    </div> 
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group has-info text-center">
                                        <label for="">TA</label>
                                        <input type="text" class="form-control col-lg-12" 
                                            name="txt_ta"id="txt_ta"
                                            value="<?php echo $ta ?>"
                                         required>
                                        <div class="valid-feedback">
                                                Correcto!
                                        </div>
                                        <div class="invalid-feedback">
                                                Debe de llenar este campo
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group has-info text-center">
                                        <label for="">FC</label>
                                        <input type="text" class="form-control col-lg-12" 
                                            name="txt_fc"id="txt_fc"
                                            value="<?php echo $fc ?>"
                                         required>
                                        <div class="valid-feedback">
                                                Correcto!
                                        </div>
                                        <div class="invalid-feedback">
                                                Debe de llenar este campo
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group has-info text-center">
                                        <label for="">FR</label>
                                        <input type="text" class="form-control col-lg-12" 
                                            name="txt_fr"id="txt_fr"
                                            value="<?php echo $fr ?>"
                                         required>
                                        <div class="valid-feedback">
                                                Correcto!
                                        </div>
                                        <div class="invalid-feedback">
                                                Debe de llenar este campo
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group has-info text-center">
                                    <label for="">T°</label>
                                    <input type="text" class="form-control col-lg-12" 
                                        name="txt_temp"id="txt_temp"
                                        value="<?php echo $temp ?>"
                                     required>
                                    <div class="valid-feedback">
                                        Correcto!
                                    </div>
                                    <div class="invalid-feedback">
                                        Debe de llenar este campo
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group has-info text-center">
                                    <label for="">Dx..</label>
                                            <textarea rows="4" class="from-control col-lg-12" 
                                                name="txt_dx"id="txt_dx" required>
                                                <?php echo $dx ?>
                                            </textarea>
                                            <div class="valid-feedback">
                                                Correcto!
                                            </div>
                                            <div class="invalid-feedback">
                                                Debe de llenar este campo
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group has-info text-center">
                                            <label for="">Plan..</label>
                                            <textarea rows="4" class="from-control col-lg-12" 
                                                name="txt_plan"id="txt_plan" required>
                                                <?php echo $plan ?>
                                            </textarea>
                                            <div class="valid-feedback">
                                                Correcto!
                                            </div>
                                            <div class="invalid-feedback">
                                                Debe de llenar este campo
                                            </div>
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="" id="id_paciente" 
                                value="<?php echo $id_paciente ?>">
                                <input type="hidden" name="" id="id_usuario" 
                                value="<?php echo $id_usuario ?>">
                                <input id="btn_trans" type="submit" id="submit1" name="submit1" 
                                value="Guardar" class="btn btn-primary m-t-n-xs pull-right"/>
                            </div>
                        </form> 
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn_agregar_cita">Transferir a consulta</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
    
<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>

<?php
echo "<script type='text/javascript' src='js/funciones/add_datos_fisicos_modal.js'></script>";
}else{
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
}

}

function reservar_cita_hoy(){

}


if(!isset($_REQUEST['process'])){
    
    initial();
    
}else{
    if(isset($_REQUEST['process'])){
        switch($_REQUEST['process']){
            
        }
    }
}
?>

