<?php
    include("_core.php");

    $requestData= $_REQUEST;
    $fechai= MD($_REQUEST['fechai']);
	  $fechaf= MD($_REQUEST['fechaf']);

    require('ssp.customized.class.php');
    // DB table to use
    $table = 'recepcion';
    // Table's primary key
    $primaryKey = 'id_recepcion';

    // MySQL server connection information
    $sql_details = array(
      'user' => $usuario,
      'pass' => $clave,
      'db'   => $dbname,
      'host' => $servidor
    );

    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename);

    $joinQuery = "
        FROM recepcion INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion
        LEFT JOIN doctor on doctor.id_doctor = recepcion.id_doctor_recepcion
        INNER JOIN estado_recepcion on estado_recepcion.id_estado_recepcion = recepcion.id_estado_recepcion
	  ";
      $extraWhere = " recepcion.fecha_de_entrada BETWEEN '$fechai 00:00:00' AND '$fechaf 23:59:59'";
      
      $columns = array(
      array( 'db' => "recepcion.id_recepcion", 'dt' => 0, 'field' => 'id_recepcion'  ),
      array( 'db' => "CONCAT(paciente.nombres,' ', COALESCE(paciente.apellidos,'') )", 'dt' => 1, 'field' => "paciente", 'as'=>'paciente'),
      array( 'db' => "CONCAT(doctor.nombres,' ',doctor.apellidos)", 'dt' => 2, 'field' => "doctor", 'as'=>'doctor'),
      array( 'db' => "recepcion.fecha_de_entrada", 'dt' => 3, 'formatter' => function($fecha_ingreso){
        return  ED($fecha_ingreso);
      }, 'field' => 'fecha_de_entrada'),
      array( 'db' => "recepcion.evento", 'dt' => 4, 'field' => 'evento'),
		  array( 'db' => "estado_recepcion.estado", 'dt' => 5, 'formatter' => function($estadoRecepcion){
            $estado = $estadoRecepcion;
						return $estado;
			}, 'field' => 'estado'),
      array( 'db' => "recepcion.id_recepcion", 'dt' => 6, 'formatter' => function ($idRecepcion) {

      

      $query=_query("SELECT estado_recepcion.id_estado_recepcion, estado_recepcion.estado FROM estado_recepcion INNER JOIN recepcion on estado_recepcion.id_estado_recepcion = recepcion.id_estado_recepcion WHERE recepcion.id_recepcion = $idRecepcion");
      $row=_fetch_array($query);
      $estadoRecepcion=$row['estado'];

      $id_user=$_SESSION["id_usuario"];
    	$admin=$_SESSION["admin"];

      $table="<div class='btn-group'>
        <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
        <ul class='dropdown-menu dropdown-primary'>";
        $var1=0;
          if($estadoRecepcion == "PENDIENTE"){
            $filename='realizar_recepcion.php';
            $link=permission_usr($id_user,$filename);
            if ($link!='NOT' || $admin=='1' ){
              $table.= "<li><a data-toggle='modal' href='$filename?idRecepcion=".$idRecepcion."' data-target='#realizarModal1' data-refresh='true'><i class='fa fa-eye'></i> Realizar</a></li>";
            }
          }
          if($estadoRecepcion != 'ANULADO' && $estadoRecepcion != 'FINALIZADO' && $estadoRecepcion != 'FACTURADO'){
            $filename='editar_recepcion.php';
            $link=permission_usr($id_user,$filename);
            if ($link!='NOT' || $admin=='1' ){
              $table.="<li><a href='$filename?idRecepcion=".$idRecepcion."'><i class='fa fa-edit'></i> Editar</a></li>";
              $var1++;
            }
          }
          $filename='anular_recepcion.php';
          $link=permission_usr($id_user,$filename);
          if ($estadoRecepcion!='ANULADO' && $estadoRecepcion!='FACTURADO' && $estadoRecepcion!='FINALIZADO'){
              if ($link!='NOT' || $admin=='1'){
              //	echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
              $table.= "<li><a data-toggle='modal' href='$filename?idRecepcion=".$idRecepcion."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
            }
          }
          if ($estadoRecepcion=='ANULADO'){
            if ($link!='NOT' || $admin=='1'){
            //	echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
            $table.= "<li><a data-toggle='modal' href='$filename?idRecepcion=".$idRecepcion."&process=recuperar' data-target='#deleteModal' data-refresh='true'><i class='fa fa-undo'></i> Recuperar</a></li>";
          }
          }
          $filename='registrar_datos_fisicos.php';//aqui se utilizara para abrir un modal y poder ingresar los datos fisicos del paciente
          if($link!="NOT" || $admin=='1'){
              $table.="<li><a  href='$filename?idRecepcion=".$idRecepcion."'>
              <i class='fa fa-user-md'></i> Agregar Datos fisicos </a></li>";
          }
          $filename='transferir_recepcion.php';
          if ($estadoRecepcion =='REALIZADO'){
            if ($link!='NOT' || $admin=='1'){
              // echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
              $table.= "<li><a  data-toggle='modal' href='$filename?idRecepcion=".$idRecepcion."' data-target='#transferenciaModal' data-refresh='true'><i class='fa fa-upload'></i> Transferir </a></li>";            }
          }
          $filename='transferir_recepcion.php';
          $link=permission_usr($id_user,$filename);
          if ($estadoRecepcion =='REALIZADO'){
              if ($link!='NOT' || $admin=='1'){
                // echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
                $table.= "<li><a  data-toggle='modal'  href='finalizar_recepcion_nuevo.php?idRecepcion=".$idRecepcion."' data-target='#deleteModal' data-refresh='true'><i class='fa fa-arrow-right'></i> Finalizar </a></li>";
              }
          }
          $table.="<li><a href='estado_cuenta.php?id_recepcion=".$idRecepcion."' target='_blank'><i class=\"fa fa-print\"></i> Estado de cuenta</a></li>";

          /*
          $filename='agregar_servicio_recepcion.php';
          $link=permission_usr($id_user,$filename);
          if ($estadoRecepcion=='REALIZADO'){
              if ($link!='NOT' || $admin=='1'){
              //	echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
              $table.= "<li><a  href='$filename?idRecepcion=".$idRecepcion."&process=agregar_servicio'><i class='fa fa-plus'></i> Gestionar Servicios</a></li>";
            }
          }*/
            $table.= "</ul>
            </div>
            ";

            return $table;
    } , 'field' => 'id_recepcion' )

    );
    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
    );

?>