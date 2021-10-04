<style type='text/css'>

    table.page_header {width: 100%;margin-left:53px;  margin-top: 25px;margin-bottom: 25px;  border:none; background-color: #FFFFFF; font-family:times,serif;font-weight: bold; font-size: 14px;}
    table.page_footer {width: 100%; border: none; background-color: #FFF;  padding: 2mm;color:#FFFFFF; font-family:times,serif; font-weight:bold;}
    div.note {border: solid 1mm #DDDDDD;background-color: #EEEEEE; padding: 2mm; border-radius: 2mm; width: 100%; }
    ul.main { width: 95%; list-style-type: square; }
    ul.main li { padding-bottom: 2mm; }
    h1 { text-align: center; font-size: 20mm}
    h3 { text-align:right; font-size: 14px; color:#000080}
    table { vertical-align: middle; }
    tr    { vertical-align: middle; }
    p {margin: 0px 5px 0px 5px;}
    span {margin: 5px;}
    img { border: 1px #000000;}
</style>
<?php
include_once("_core.php");
    $sql_empresa = "SELECT e.*, d.nombre_departamento, m.nombre_municipio FROM empresa as e, departamento as d, municipio as m WHERE d.id_departamento=e.departamento AND m.id_municipio=e.municipio AND e.id_empresa='1'";

    $resultado_emp=_query($sql_empresa);
    $num_rows = _num_rows($resultado_emp);
    $row_emp=_fetch_array($resultado_emp);

    $empresa=utf8_decode($row_emp['nombre']);
    $direccion_emp = Mayu(utf8_decode($row_emp["direccion"].", ".$row_emp["nombre_municipio"].", ".$row_emp["nombre_departamento"]));
    $telefonos=$row_emp['telefono1'].'   '.$row_emp['telefono2'];
    $logo=$row_emp['logo'];
    $mrs = $row_emp["mrs"];
    $mri = $row_emp["mri"];
    if($mri<=0)
    {
        $mri = 3;
    }
    if($mrs<=0)
    {
        $mrs = 4;
    }
    $mri *= 10;
    $mrs *= 10;

    $id_cita = $_REQUEST["id_cita"];
    $id_paciente = $_REQUEST["id_paciente"];

    $query = _query("SELECT r.id_paciente, r.motivo_consulta, r.diagnostico, r.examen, r.medicamento, r.fecha_cita, r.hora_cita, r.peso, CONCAT(d.nombres,' ',d.apellidos) as doctor FROM reserva_cita as r, doctor as d WHERE d.id_doctor=r.id_doctor AND r.id ='$id_cita'");
    $datos = _fetch_array($query);
    $id_paciente = $datos["id_paciente"];
    $doctor = $datos["doctor"];
    $fecha_cita = nombre_dia($datos["fecha_cita"]);
    $fecha = $datos["fecha_cita"];
    $peso = $datos["peso"];
    $hora_cita = hora($datos["hora_cita"]);

    $sql_ed = _query("SELECT fecha_nacimiento FROM paciente WHERE id_paciente='$id_paciente'");
    $datos_ed = _fetch_array($sql_ed);
    $edad = edad($datos_ed["fecha_nacimiento"]);
    $query_receta = _query("SELECT m.* , r.id_medicamento,r.dosis FROM receta as r, ".EXTERNAL.".producto as m WHERE m.id_producto=r.id_medicamento AND r.id_cita='$id_cita' AND r.id_paciente='$id_paciente'");
    $query_aux = _query("SELECT * FROM reserva_cita WHERE id='$id_cita'");
    ?>
<page backtop="<?php echo "62mm"; ?>" backbottom="30mm" backleft="<?php echo $mri."mm"; ?>" backright="20mm" style="font-size: 12pt" backimgx="center" backimgy="bottom" backimgw="100%">
    <page_header>
        <!--<table class="page_header">
            <tr rowspan="3">
                <td style="width: 12%; color: #444444;">
                    <?php
                      //  echo "<img style='width: 12%;' src='./".$logo."'>";
                    ?>
                </td>
            </tr>
            <tr>
                <td align=center style='width:90%;'> <?php echo  Mayu($empresa); ?></td>
            </tr>
            <tr>
                <td align=center style='width:90%;'> <?php echo Mayu($direccion_emp); ?></td>
            </tr>
            <tr>
                <td align=center style='width:90%;'> <?php echo "TEL.  ".$telefonos; ?></td>
            </tr>
            <tr>
                <td align=center style='width:90%;'>RECETA MEDICA</td>
            </tr>
        </table>-->
    </page_header>
    <page_footer>
        <!--<table class="page_footer">
            <tr>
                <td style="width: 80%; text-align: left; color:#000;">
                    Impreso: <?php echo date('d-m-Y')." ".hora(date("H:i:s")) ;?>
                </td>
                <td style="width: 35%; text-align: left; color:#000;">
                    <?php echo "Expediente ".$n_exp; ?>
                </td>
                <td style="width: 20%; text-align: right; color:#000;">
                    P&aacute;gina [[page_cu]]/[[page_nb]]
                </td>
            </tr>
        </table>-->
    </page_footer>
    <?php
            echo "<p style='margin-left:80px; margin-top:18px;>".buscar($id_paciente)."</p><p style='margin-left:120px; margin-top:-15px; float:left;'>".ED($fecha)."</p><br><br>";
            echo "<p style='margin-left:370px; margin-top:-4.5px; float:left;'>".$edad."</p>";
            echo "<p style='margin-left:476px; margin-top:-18px; float:left;'>".$peso."</p>";
            echo "<p style='margin-left:610px; margin-top:-16px; float:left; margin-bottom:60px;'> - </p>";
            while ($row = _fetch_array($query_receta))
            {
                echo "<p style='margin-bottom:75px;'> - ".$row["descripcion"]."<br><br>".$row["dosis"]."</p>";
                //echo "_________________________________________<br>";
            }
            $aux = _fetch_array($query_aux);
            $otros = $aux["medicamento"];
            $otr = explode("|", $otros);
            for ($i=0; $i<count($otr); $i++)
            {
                echo "<p style='margin-bottom:75px;'> - ".$otr[$i]."</p>";
            }

?>
</page>
