<?php
	include ("_core.php");
	// Page setup
	$_PAGE = array ();
	$title = 'Administrar Usuarios';
	$_PAGE ['title'] = $title;
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
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';	
	include_once "header.php";
	include_once "main_menu.php";
	
 	$sql="SELECT * FROM usuario WHERE id_usuario >0";
	$result=_query($sql);
	$count=_num_rows($result);
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	//permiso del script
	if ($links!='NOT' || $admin=='1' ){ 
?>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<?php 
				//if ($admin=='t' && $active=='t'){
				echo "<div class='ibox-title'>";
				$filename='agregar_usuario.php';
				$link=permission_usr($id_user,$filename);
				if ($link!='NOT' || $admin=='1' )
					echo "<a href='agregar_usuario.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar Usuario</a>";
				echo "</div>";
				
				?>
				<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h3 style="color:#194160;"><i class="fa fa-user"></i> <b><?php echo $title;?></b></h3>
					</header>
					<section>
						<table class="table table-striped table-bordered table-hover"id="editable">
							<thead>
								<tr>
									<th>Id Usuario</th>
									<th>Nombre</th>
									<th>Usuarios</th>
									<th>Tipo usuario</th>
									<th>Estado</th>
									<th>Acci&oacute;n</th>
								</tr>
							</thead>
							<tbody> 
				<?php	
 					if ($count>0){
						for($i=0;$i<$count;$i++){
							$row=_fetch_array($result);
							$tipo_usuario=$row["tipo_usuario"];
							$activo=$row["activo"];
							if($tipo_usuario==1)
			                    {
			                      $tipo_usuario="Administrador";
			                    }
			                    else
			                    {
			                      $tipo_usuario="Usuario normal";
			                    }
			                if($activo==1)
			                    {
			                      $estado="Activo";
			                    }
			                    else
			                    {
			                      $estado="Inactivo";
			                    }
							echo "<tr>";
							echo"<td>".$row['id_usuario']."</td>
								<td>".$row['nombre']."</td>
								<td>".$row['usuario']."</td>
								<td>".$tipo_usuario."</td>
								<td>".$estado."</td>
								";

							echo"<td><div class=\"btn-group\">
								<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
								<ul class=\"dropdown-menu dropdown-primary\">";
								$filename='editar_usuario.php';
								$link=permission_usr($id_user,$filename);
								if ($link!='NOT' || $admin=='1' )	
									echo "<li><a href=\"editar_usuario.php?id_usuario=".$row['id_usuario']."\"><i class=\"fa fa-pencil\"></i> Editar</a></li>";
								echo "<li><a href=\"permiso_usuario.php?id_usuario=".$row['id_usuario']."\"><i class=\"fa fa-lock\"></i> Permisos</a></li>";
								$filename='borrar_usuario.php';
								$link=permission_usr($id_user,$filename);
								if ($link!='NOT' || $admin=='1' )
									echo "<li><a data-toggle='modal' href='borrar_usuario.php?id_usuario=".$row['id_usuario']."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-trash\"></i> Eliminar</a></li>";	
							echo "	</ul>
										</div>
										</td>
										</tr>";
						}
					}
		
				?>			
							</tbody>		
						</table>
						 <input type="hidden" name="autosave" id="autosave" value="false-0">	
					</section>   
					<!--Show Modal Popups View & Delete -->
					<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	
					<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content modal-sm'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	
               	</div><!--div class='ibox-content'-->
       		</div><!--<div class='ibox float-e-margins' -->
		</div> <!--div class='col-lg-12'-->
	</div> <!--div class='row'-->  
</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->			
<?php    
	include("footer.php");
	echo" <script type='text/javascript' src='js/funciones/funciones_usuarios.js'></script>"; 	                         	     
		} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}	
?>
