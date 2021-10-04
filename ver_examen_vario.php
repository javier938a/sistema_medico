<?php
include ("_core.php");
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	
	$filename='ver_examen.php';
	$links=permission_usr($id_user,$filename);

	$id_examen_paciente = $_REQUEST["id_examen"];
    $query = _query("SELECT e_v.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_varios AS e_v, examen_paciente AS e_p WHERE e_v.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $muestra = $datos["muestra"];
        $examen = $datos["examen"];
        $resultado = $datos["resultado"];
        $id_examen = $datos["id_examen_vario"];
        $fecha_lectura = ED($datos["fecha_lectura"]);
        $existe = true;
    }
    else
    {
    	$existe = false;
    }

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Resultados: Examen Varios</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
					<?php 
					//permiso del script
					if ($links!='NOT' || $admin=='1' )
					{
						if($existe){
					?>
						<table class="table table-bordered">
                            <tr>
                                <th class="col-lg-4">
                                    <label>Examen Realizado</label></th><th class="text-center"><?php echo $examen; ?></th>
                            </tr>
                            <tr>
                                <td><label>Muestra</label></td><td><?php echo $muestra; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><label>Resultado</label></td>
                            </tr> 
                            <tr>
                                <td colspan="2"><p style="text-align: justify;"><?php echo $resultado; ?></p></td>
                            </tr>
                            <tr>
                            	<td>
                                    <label>Fecha de lectura</label></td><td><?php echo $fecha_lectura; ?>
                                </td>
                            </tr>
                        </table>
                    <?php    
						}	
						else
						{
							echo "<div class='alert alert-warning'>No se han agregado resultados.</div>";
						}
					?>
				</div>
			</div>
		</div>
	</div>
    <input type="hidden" name="act" id="act" value="0">
<div class="modal-footer">
<?php

	echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
	</div><!--/modal-footer -->";	
	} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}	  
?>
