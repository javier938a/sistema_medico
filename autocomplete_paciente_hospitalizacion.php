<?php
include_once "_core.php";
$id_sucursal=$_SESSION["id_sucursal"];
$query = $_REQUEST['query'];
$sql0="SELECT paciente.nombres, paciente.apellidos, paciente.id_paciente, recepcion.id_recepcion, recepcion.evento, recepcion.fecha_de_entrada FROM recepcion INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion WHERE recepcion.deleted is NULL AND recepcion.recepcion_hospitalizacion = '1' AND recepcion.id_estado_recepcion != '4' AND (recepcion.id_estado_recepcion = '1' OR recepcion.id_estado_recepcion = '2') AND recepcion.id_sucursal_recepcion = '$id_sucursal' AND (paciente.nombres LIKE '%$query%' OR paciente.apellidos LIKE '%$query%')";
$result = _query($sql0);
$array_prod = array();

while ($row = _fetch_array($result)) {
    $fecha_entrada = $row['fecha_de_entrada'];
    $fecha_entrada = explode(" ", $fecha_entrada);
    $fecha = ED($fecha_entrada[0]);
    $hora = _hora_media_decode($fecha_entrada[1]);
    $array_prod[] =$row['id_paciente']."| ".$row['nombres']." ".$row['apellidos']." |".$row['evento']." |".$fecha."|".$hora;
}
//echo $array_prod;
echo json_encode ($array_prod);?>
