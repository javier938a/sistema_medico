<?php
    include_once "_core.php";
    function initial(){
        $title='Registrar Datos';
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
        $hora_cita="";
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
                $hora_cita=$datos_cita['hora_cita'];
    
    
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
                                <div class="col-lg-4">
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
                                <div class="col-lg-4">
                                    <div class="form-group has-info text-center">
                                        <label for="">Consultorios</label>
                                        <?php
                                            $sql_consultorios="SELECT * FROM espacio";
                                            $query_consultorios=_query($sql_consultorios);
                                            if(_num_rows($query_consultorios)==1){
                                                echo '<select class="select" id="id_consultorio" name="id_consultorio" disabled>';
                                                    while($_consultorio=_fetch_array($query_consultorios)){
                                                        echo '<option value='.$_consultorio['id_espacio'].' selected>'.$_consultorio['descripcion'].'</option>';
                                                    }
                                                echo '</select>';
                                            }else{
                                                echo '<select class = "select" id="id_consultorio" name="id_consultorio">';
                                                $i=0;    
                                                while($_consultorio=_fetch_array($query_consultorios)){
                                                    $selected='';
                                                    if($i==0){
                                                        $selected='selected';                                                       
                                                    }   
                                                    echo '<option value='.$_consultorio['id_espacio'].' '.$selected.'>'.$_consultorio['descripcion'].'</option>';
                                                    $i++;
                                                }
                                                echo '</select>';
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group has-info text-center">
                                        <label for="">Hora</label>
                                        <input type="text" id="hora_cita" name="hora_cita" class="form-control timepicker"
                                        value="<?php echo $hora_cita; ?>">
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
                                                name="txt_antecedentes_fam"id="txt_antecedentes_fam">
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
                                <input id="id_recepcion" name="id_recepcion" value="<?php echo $id_recepcion ?>" type="hidden" name="">        
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

    function transferir_a_consulta(){
        $estatura=$_POST['estatura'];
        $peso=$_POST['peso'];
        $motivo=$_POST['motivo'];
        $hx=$_POST['hx'];
        $antecedente_paciente=$_POST['antecedente'];
        $antecedente_familiar=$_POST['antecedente_fam'];
        $ta=$_POST['ta'];
        $fc=$_POST['fc'];
        $fr=$_POST['fr'];
        $temp=$_POST['temp'];
        $dx=$_POST['dx'];
        $plan=$_POST['plan'];
        $id_paciente=$_POST['id_paciente'];
        $id_usuario=$_POST['id_usuario'];
        $id_doctor=$_POST['id_doctor'];
        $id_recepcion=$_POST['id_recepcion'];
        $fecha_hoy=date('Y-m-d');
        $estado=1;
        $id_espacio=$_POST['id_consultorio'];
        $hora_cita=$_POST['hora_cita'];
        list($dato, $letra)=explode(" ", $hora_cita);
        list($h, $m)=explode(":", $dato);
        if($letra=="PM"){
            if($h<12){
                $h+=12;
            }
        }

        $hora= "$h:$m:00";

        $table='reserva_cita';
        $form_data1=array(
            'altura'=>$estatura,
            'peso'=>$peso,
            'motivo_consulta'=>$motivo,
            'hx'=>$hx,
            'antecedente_personal'=>$antecedente_paciente,
            'antecedente_familiar'=>$antecedente_familiar,
            'id_espacio'=>$id_espacio,
            'ta'=>$ta,
            'fc'=>$fc,
            'dx'=>$dx,
            't_o'=>$temp,
            'plan'=>$plan,
            'id_paciente'=>$id_paciente,
            'id_doctor'=>$id_doctor,
            'id_usuario'=>$id_usuario,
            'fecha_cita'=>$fecha_hoy,
            'observaciones' => '',
            'diagnostico' => '',
            'examen' => '',
            'medicamento' => '',
            'p' => '',
            'fr' =>$fr,
            'estado'=>$estado,
            'hora_cita'=>$hora
            
        );
        //primero se verifica si existe ya una cita reservada
        $sql_existe_cita="SELECT * FROM reserva_cita WHERE id_paciente=$id_paciente AND fecha_cita='$fecha_hoy'";
        $query_existe_cita=_query($sql_existe_cita);
        $xdatos=array();
        //si no existe se inserta una nueva cita

        if(_num_rows($query_existe_cita)==0){
            $xdatos['table']=$table;
            $insertar = _insert($table,$form_data1 );
            //$insertar=_insert($table, $form_data);
            $xdatos['res']=$insertar;
            if($insertar){
                $table_re="recepcion";
                $form_data_re=array(
                    'id_estado_recepcion'=>"6"
                );
                $where_clau='id_recepcion='.$id_recepcion;
                $update_recepcion=_update($table_re, $form_data_re, $where_clau);
                //para ingresarlo a cola es necesario obtener el id de la cita que se acaba de insertar
                //para insertarlo a la cola de espera y le aparesca al doctor
                $sql_obtener_cita="SELECT id FROM reserva_cita AS r WHERE r.id_paciente=$id_paciente AND 
                    r.fecha_cita='$fecha_hoy'";

                $query_cita=_query($sql_obtener_cita);//obteniendo la cita recien insertada
                $id_cita='';
                $i=0;
                while($row_cita=_fetch_array($query_cita)){
                    if($i==0){
                        $id_cita=$row_cita['id'];//obteniendo el id de esta cita
                    }
                    $i++;
                }
                //$xdatos['id_cita']=$id_cita;

                    
                //agregandolo a la cola y dandole prioridad

                //verificando si existe en la cola primero
                $sql_existe_cola="SELECT * FROM cola_dia WHERE id_cita=$id_cita AND fecha='$fecha_hoy' ";
                $query_existe_cola=_query($sql_existe_cita);
                if(_num_rows($query_existe_cola)==0){
                    $table_cola='cola_dia';
                    $form_data_cola=array(
                        'id_cita'=>$id_cita,
                        'id_doctor'=>$id_doctor,
                        'prioridad'=>'1',
                        'fecha'=>$fecha_hoy
                    );
                    $xdatos['form_cita']=$form_data_cola;
                    /// hay que corregir....
                    /*
                    $insert_cola=_insert($table_cola, $form_data_cola);
                    if($insert_cola){
                        $xdatos['msg3']='Paciente ingresado a cola exitosamente..';
                    }else{
                        $xdatos['msg3']='El paciente no fue ingresado a cola';
                    }*/
                }
                

                $xdatos['typeinfo']='success';
                $xdatos['msg2']='Recepcion actualizada';
                $xdatos['msg']='cita para hoy registrada exitosamente!';
                $xdatos['process']='insert';
            }else{
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Error al registrar la cita, no se pudo registrar la cita';
                $xdatos['date']=$form_data1;
            }

        }else{//si existe se actualiza con los nuevos datos
            $where_clause = "id_paciente=$id_paciente AND fecha_cita='$fecha_hoy'";
            $update=_update($table, $form_data1, $where_clause);
            if($update){
                $table_re="recepcion";
                $form_data_re=array(
                    'id_estado_recepcion'=>"6"
                );
                $where_clau='id_recepcion='.$id_recepcion;
                $update_recepcion=_update($table_re, $form_data_re, $where_clau);
                $xdatos['msg2']='Recepcion actualizada';
                $xdatos['typeinfo']='Success';
                $xdatos['msg']='Cita actualizada exitosamente';
                
            }else{
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Error al actualizar la cita';
            }
        }

        echo json_encode($xdatos);

        //falta insertar...
    }



    if(!isset($_REQUEST['process'])){
        initial();
    }else{
        switch($_REQUEST['process']){
            case 'trans_consulta':
                transferir_a_consulta();
                break;
        }
    }
?>