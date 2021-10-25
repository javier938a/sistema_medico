<?php
	include ("_core.php");
	// Page setup
	$title='Estado de cuenta del paciente';
	$_PAGE = array ();
	$_PAGE ['title'] =$title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/tour/bootstrap-tour.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
	include_once "header.php";
	include_once "main_menu.php";
	

	$id_hospitalizacion=$_GET['id_hospitalizacion'];
 	$sql_hospitalizacion="SELECT id_recepcion FROM hospitalizacion AS h WHERE h.id_hospitalizacion=$id_hospitalizacion";
	$query_hospitalizacion=_query($sql_hospitalizacion);
	$row_hospitalizacion=_fetch_array($query_hospitalizacion);
	$id_recepcion=$row_hospitalizacion['id_recepcion'];
	//echo $id_recepcion;
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

    
    //echo $id_recepcion;
	
	//mysql_query("SET NAMES 'utf8'");
?>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<?php  
						//permiso del script
						if ($links!='NOT' || $admin=='1' ){
					
				echo"<div class='ibox-title'>";
				$filename='agregar_paciente.php';
				$link=permission_usr($id_user,$filename);
				
				?>
				<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h3 style="color:#194160;"><i class="fa fa-user"></i> <b><?php echo $title;?></b></h3>
					</header>
					<section>
						<div class="btn-group" role="group" aria-label="...">
							<button type="button" class="btn btn-default">Generar</button>
						</div>
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id="editable2">
								<thead>
									<tr>
										<th class="col-lg-1">Id</th>
										<th class="col-lg-3">producto</th>
										<th class="col-lg-1">cantidad</th>
										<th class="col-lg-1">total</th>
										<th class="col-lg-1">Acción</th>
									</tr>
								</thead>
								<tbody> 
									<?php
										$sql_insumos="SELECT ie.id_insumo, p.descripcion AS producto, sh.descripcion AS servicio, 
										ie.cantidad, ie.total FROM insumos_emergencia AS ie LEFT JOIN ".EXTERNAL.".producto AS
										p ON p.id_producto=ie.id_producto LEFT JOIN  ".EXTERNAL.".servicios_hospitalarios AS 
										sh on ie.id_servicio=sh.id_servicio WHERE ie.id_recepcion=$id_recepcion";
										$query_insumos=_query($sql_insumos);
										while($row_insumos=_fetch_array($query_insumos)){
											$id=$row_insumos['id_insumo'];
											$producto=$row_insumos['producto'];
											$servicio=$row_insumos['servicio'];
											$cantidad=$row_insumos['cantidad'];
											$total=$row_insumos['total'];
											$producto_servicio="";

											if($producto!=""){
												$producto_servicio=$producto;
											}else{
												$producto_servicio=$servicio;
											}

											$body="<tr>
												<td>$id</td>
												<td>$producto_servicio</td>
												<td>$cantidad</td>
												<td>$total</td>
												<td><a class=\"btn btn-danger\" href=\"eliminar_insumo.php?&id_insumo=$id&id_hospitalizacion=$id_hospitalizacion\">Eliminar</a></td>
												</tr>";

											echo $body;
										}

										$sql_total="SELECT  ROUND(SUM(ie.total), 2) AS total FROM insumos_emergencia AS ie LEFT JOIN ".EXTERNAL.".producto AS
										p ON p.id_producto=ie.id_producto LEFT JOIN  ".EXTERNAL.".servicios_hospitalarios AS 
										sh on ie.id_servicio=sh.id_servicio WHERE ie.id_recepcion=$id_recepcion";
										$query_total=_query($sql_total);
										$row_total=_fetch_array($query_total);
										$suma_total=$row_total['total'];
										echo "<tr>
												<td colspan=\"2\">Total<td>
												<td>$suma_total</td>
											</tr>";
									?>
									
								</tbody>		
							</table>
						</div>

						 <input type="hidden" name="autosave" id="autosave" value="false-0">	
					</section>   
					<p><a href="admin_hospitalizaciones.php" class="btn btn-primary" role="button">Regresar</a> 
					</p>

					<!-- MODAL PARA DETALLE-->
					<!--Show Modal Popups View & Delete -->
					<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	
					<!-- MODAL PARA DETALLE-->

					<!-- MODAL PARA BORRAR-->
					<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content '></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	
					<!-- MODAL PARA BORRAR-->

               	</div><!--div class='ibox-content'-->
       		</div><!--<div class='ibox float-e-margins' -->
		</div> <!--div class='col-lg-12'-->
	</div> <!--div class='row'-->  
</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->			
</div>		
<?php    
include("footer.php");

echo" <script type='text/javascript' src='js/funciones/funciones_cuenta_insumos_hospitalizacion.js'></script>"; 
} //permiso del script
else
{
	echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}	                         	     
?>
