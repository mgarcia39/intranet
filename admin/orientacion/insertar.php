<?php 
// Control de errores
if (!$fecha or !$observaciones or !$causa or !$accion)
{
echo '<div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCIÓN:</h5>
No has introducido datos en alguno de los campos , y <strong> todos son obligatorios</strong>.<br> Vuelve atrás e inténtalo de nuevo.
</div></div><br />';
exit();
}
foreach($accion as $tipos)
{
$completo .= $tipos."; ";
}

if (empty($prohibido)){$prohibido = "0";}
$tutor = "Departamento de Orientación";
$tr = explode(" --> ",$alumno);
$al = $tr[0];
$clave = $tr[1];
$trozos = explode (", ", $al);
$apellidos = $trozos[0];
$nombre = $trozos[1];

$dia = explode("-",$fecha);
$fecha2 = "$dia[2]-$dia[1]-$dia[0]";
		$query="insert into tutoria (apellidos, nombre, tutor,nivel,grupo,observaciones,causa,accion,fecha, orienta, prohibido,claveal) values 
		('".$apellidos."','".$nombre."','".$tutor."','".$nivel."','".$grupo."','".$observaciones."','".$causa."','".$completo."','".$fecha2."','1','".$prohibido."','".$clave."')";
mysql_query($query);
echo '<div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Los datos se han introducido correctamente.
</div></div><br />';
?>