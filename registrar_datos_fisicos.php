<?php
    include_once "_core.php";
    function initial(){
        $title='Registrar Dato';
        $_PAGE = array ();
        $_PAGE ['title'] = $title;
        $_PAGE ['links'] = null;
        $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/select2/select2-bootstrap.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/tour/bootstrap-tour.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
        $_PAGE ['links'].=  '<link href="css/estilos.css", rel="stylesheet">';
  
        include_once "header.php";
        include_once "main_menu.php";

        //permiso del script
        $id_user=$_SESSION["id_usuario"];
        $admin=$_SESSION["admin"];
        $uri = $_SERVER['SCRIPT_NAME'];
        $filename=get_name_script($uri);
        $links=permission_usr($id_user,$filename);	

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
<div class="wrapper wrapper-content  animated fadeInRight" >
    <div class="row">
        <div class="col-lg-12" >
            <div class="ibox">
            <?php 
    		//permiso del script
            if ($links!='NOT' || $admin=='1' ){
            ?>
            <div class="ibox-title">
                <h3 style="color:#194160;"><i class="fa fa-user"></i> <b><?php echo $title;?></b></h3> (Los campos marcados con <span style="color:red;">*</span> son requeridos)
            </div>
            <div class="ibox-content">
                <form name="formulario_datos_fisicos" id="formulario_datos_fisicos" autocomplete='off'>
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
                                                        echo "<option value='".$_medico["id_doctor"]."' selected>".$_medico["nombres"]." ".$_medico['apellidos']."</option>";
                                    
                                                    }
                                                echo '</select>';
                                            }else{
                                                echo '<select class="select" 
                                                    name="id_medico" id="id_medico">';
                                                    $i=0;
                                                    while($_medico=_fetch_array($query_medicos)){
                                                        $selected="";
                                                        if($i==0){
                                                            $selected="selected";
                                                        }
                                                        $i++;

                                                        echo "<option value='".$_medico["id_doctor"]."' ".$selected.">".$_medico["nombres"]." ".$_medico['apellidos']."</option>";
                                                       
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
                                            value="<?php echo $altura  ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group has-info text-center">
                                            <label>Peso</label>
                                            <input type="text" class="form-control" 
                                                name="txt_peso" id="txt_peso"
                                                value="<?php echo $peso ?>"
                                            >
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
                                            >
                                    </div>
                                </div>                            
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                        <div class="form-group has-info text-center">
                                            <label for="">Hx.</label>
                                            <textarea rows="4" class="from-control col-lg-12" 
                                                name="txt_hx"id="txt_hx">
                                                <?php echo $hx ?>
                                            </textarea>
                                        </div>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group has-info text-center">
                                            <label for="">Antecedentes del Paciente..</label>
                                            <textarea rows="4" class="from-control col-lg-12" 
                                                name="txt_antecedentes"id="txt_antecedentes">
                                                <?php echo $antecedente_paciente ?>
                                            </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group has-info text-center">
                                            <label for="">Antecedentes familiares..</label>
                                            <textarea rows="4" class="from-control col-lg-12" 
                                                name="txt_antecentes_fam"id="txt_antecentes_fam">
                                                <?php echo $antecedente_familiar ?>
                                            </textarea>
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
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group has-info text-center">
                                        <label for="">FC</label>
                                        <input type="text" class="form-control col-lg-12" 
                                            name="txt_fc"id="txt_fc"
                                            value="<?php echo $fc ?>"
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group has-info text-center">
                                        <label for="">FR</label>
                                        <input type="text" class="form-control col-lg-12" 
                                            name="txt_fr"id="txt_fr"
                                            value="<?php echo $fr ?>"
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group has-info text-center">
                                    <label for="">T°</label>
                                    <input type="text" class="form-control col-lg-12" 
                                        name="txt_temp"id="txt_temp"
                                        value="<?php echo $temp ?>"
                                    >
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group has-info text-center">
                                    <label for="">Dx..</label>
                                            <textarea rows="4" class="from-control col-lg-12" 
                                                name="txt_dx"id="txt_dx">
                                                <?php echo $dx ?>
                                            </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group has-info text-center">
                                            <label for="">Plan..</label>
                                            <textarea rows="4" class="from-control col-lg-12" 
                                                name="txt_plan"id="txt_plan">
                                                <?php echo $plan ?>
                                            </textarea>
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <label for=""></label>
                            </div>
                            <div class="row">
                                <input type="hidden" name="" id="id_paciente" 
                                value="<?php echo $id_paciente ?>">
                                <input type="hidden" name="" id="id_usuario" 
                                value="<?php echo $id_user ?>">
                                <input id="btn_trans" type="submit" id="submit1" name="submit1" 
                                value="transferir  a consulta" class="btn btn-primary m-t-n-xs pull-right"/>
                            </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>


<?php
    
    include_once ("footer.php");
    echo "<script src='js/funciones/funciones_agregar_datos_fisicos.js'></script>";
        }else{
            echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
        }
    }

    function transferir_consulta(){
        $estatura=$_REQUEST['estatura'];
        $peso=$_REQUEST['peso'];
        $motivo=$_REQUEST['motivo'];
        $hx=$_REQUEST['hx'];
        $antecedente_paciente=$_REQUEST['antecedente'];
        $antecedente_familiar=$_REQUEST['antecedente_fam'];
        $tx=$_REQUEST['tx'];
        $fc=$_REQUEST['fc'];
        $fr=$_REQUEST['fr'];
        $temp=$_REQUEST['temp'];
        $dx=$_REQUEST['dx'];
        $plan=$_REQUEST['plan'];
        $id_paciente=$_REQUEST['id_paciente'];
        $id_usuario=$_REQUEST['id_usuario'];
        $id_doctor=$_REQUEST['id_doctor'];
        $fecha_hoy=date('Y-m-d');

        $table="reservar_cita";
        $form_data=array(
            'altura'=>$estatura,
            'peso'=>$peso,
            'motivo_consulta'=>$motivo,
            'hx'=>$hx,
            'antecedente_personal'=>$antecedente_paciente,
            'antecente_familiar'=>$antecedente_familiar,
            'tx'=>$tx,
            'fc'=>$fc,
            't_o'=>$temp,
            'plan'=>$plan,
            'id_paciente'=>$id_paciente,
            'id_doctor'=>$id_doctor,
            'id_usuario'=>$id_usuario,
            'fecha_cita'=>$fecha_cita
        );

        //falta insertar...
    }



    if(!isset($_REQUEST['process'])){
        initial();
    }else{
        switch($_REQUEST['process']){
            case 'trans_consulta':
                break;
        }
    }
?>