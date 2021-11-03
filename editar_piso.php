<?php
include_once "_core.php";
function initial()
{
    $title = 'Editar Piso';
    $_PAGE = array ();
    $_PAGE ['title'] = $title;
    $_PAGE ['links'] = null;
    $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/fileinput/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';
    $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

    include_once "header.php";
    include_once "main_menu.php";
    $id_piso = $_REQUEST["id_piso"];
    $sql = "SELECT * FROM pisos WHERE id_piso = '$id_piso'";
    $consulta = _query($sql);
    $row = _fetch_array($consulta);
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);

    ?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-2">
    </div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <?php
                    //permiso del script
                    if ($links!='NOT' || $admin=='1' ){
                    ?>
                <div class="ibox-title">
                    <h5><?php echo $title; ?></h5>
                </div>
                <div class="ibox-content">
                    <form name="formulario_piso" id="formulario_piso">
                        <div class="form-group has-info single-line">
                            <label>Numero de piso <span style="color:red;">*</span></label>
                            <input type="text" placeholder="Numero de piso" class="form-control" id="numero"
                                name="numero" value="<?php  echo $row['numero_piso']; ?>">
                        </div>
                        <div class="form-group has-info single-line">
                            <label>Descripcion del piso<span style="color:red;">*</span></label>
                            <input type="text" placeholder="Descripcion del piso" class="form-control" id="descripcion"
                                name="descripcion" value="<?php  echo $row['descripcion']; ?>">
                        </div>
                        <input type="hidden" name="id_piso" id='id_piso' value="<?php echo $id_piso; ?>">
                        <input type="hidden" name="process" id="process" value="edited"><br>
                        <div>
                            <input type="submit" id="editar_piso" name="editar_piso" value="Guardar"
                                class="btn btn-primary m-t-n-xs" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
        include_once ("footer.php");
        echo "<script src='js/funciones/funciones_pisos.js'></script>";
    } //permiso del script
    else
    {
        $mensaje = "No tiene permiso para acceder a este modulo";
        echo "<br><br>$mensaje<div><div></div></div</div></div>";
        include "footer.php";
    }
}

function editar()
{
    $id_piso = $_POST['id_piso'];
    $numero=$_POST["numero"];
    $descripcion = $_POST['descripcion'];
    $id_sucursal=$_SESSION["id_sucursal"];
    $sql_exis=  _query("SELECT numero_piso FROM pisos WHERE (numero_piso ='$numero' or descripcion ='$descripcion') AND id_piso != '$id_piso' and deleted is NULL");
    $num_exis = _num_rows($sql_exis);
    if($num_exis > 0)
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Ya existe ese numero de piso registrado!';
    }
    else
    {
        $table = 'pisos';
        $form_data = array(
            'numero_piso' => $numero,
            'descripcion' => $descripcion,
        );
        $where = " id_piso = '$id_piso'";
        $insertar = _update($table,$form_data,$where );
        if($insertar)
        {
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Registro editado con exito!';
            $xdatos['process']='insert';
        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Registro no pudo ser editado!'._error();
        }
    }
    echo json_encode($xdatos);
}
if(!isset($_POST['process']))
{
    initial();
}
else
{
    if(isset($_POST['process']))
    {
        switch ($_POST['process'])
        {
            case 'edited':
                editar();
            break;
        }
    }
}
?>