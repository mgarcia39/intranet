<?
if (isset($_POST['submit1']) and $_POST['submit1']=="Enviar Datos") {
	include("rellenainf.php");
	exit;
}
session_start();
include("../../config.php");
// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	header('Location:'.'http://'.$dominio.'/intranet/salir.php');	
	exit();
}
$pr = $_SESSION['profi'];

if($_SESSION['cambiar_clave']) {
	header('Location:'.'http://'.$dominio.'/intranet/clave.php');
}

registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);



include("../../menu.php");
include("menu.php");
?>
<div class="container">
<div class="row">
<div class="page-header">
  <h2>Informes de Tutor�a <small> Redactar Informe por asignatura</small></h2>
</div>
<br>

<div class="col-md-6 col-md-offset-3">	
        
<?php

$alumno=mysql_query("SELECT infotut_alumno.CLAVEAL, infotut_alumno.APELLIDOS, infotut_alumno.NOMBRE, infotut_alumno.unidad, infotut_alumno.id, curso FROM infotut_alumno, alma WHERE alma.claveal=infotut_alumno.claveal and ID='$id'");
$dalumno = mysql_fetch_array($alumno);
$n_cur=$dalumno[5];
if (empty($dalumno[0])) {
	echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>ATENCI�N:</legend>
Debes seleccionar un alumno en primer lugar.<br>Vuelve atr�s e int�ntalo de nuevo
<br><br /><input type="button" onClick="history.back(1)" value="Volver" class="btn btn-primary">
		</div></div>';
	exit;	
}
?>
<div class="well well-large">
 <form name="informar" method="POST" action="informar.php?id=<? echo $id;?>"> 
<?
echo "<input type='hidden'  name='ident' value='$id'>";
echo "<input type='hidden'  name='profesor' value='$pr'>";
$claveal=trim($dalumno[0]);
if (empty($dalumno[0])) {
	echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>ATENCI�N:</legend>
Debes seleccionar un alumno en primer lugar.<br>Vuelve atr�s e int�ntalo de nuevo.<br /><br /
><input type="button" onClick="history.back(1)" value="Volver" class="btn btn-danger">
</div></div><hr>';
	exit();	
}

echo "<p align=center class='lead'>$dalumno[2] $dalumno[1] ( $dalumno[3] )</p>";
   	$foto = '../../xml/fotos/'.$claveal.'.jpg';
	if (file_exists($foto) and !(empty($dalumno[0]))) {
		echo "<div style='width:150px;margin:auto;'>";
		echo "<img src='../../xml/fotos/$claveal.jpg' border='2' width='100' height='119' class='img-responsive' />";
		echo "</div>";
	}
echo "<br />";

$coinciden = mysql_query("SELECT materia FROM profesores WHERE profesor='$pr' and grupo = '$dalumno[3]'");
while($coinciden0 = mysql_fetch_row($coinciden)){
$asignatur = $coinciden0[0];
$asignatur = str_replace("nbsp;","",$asignatur);
$asignatur = str_replace("&","",$asignatur);
}

$as=mysql_query("SELECT COMBASI FROM alma WHERE CLAVEAL='$claveal' ");
$asi=mysql_fetch_array($as);
$asi1 = substr($asi[0],0,strlen($asi[0]) -1);
$coinciden = mysql_query("SELECT distinct materia, codigo FROM profesores, asignaturas WHERE asignaturas.nombre = profesores.materia and asignaturas.curso = profesores.nivel and grupo = '$dalumno[3]' and asignaturas.curso='$n_cur' and abrev not like '%\_%' and profesor = '$pr'");
if(mysql_num_rows($coinciden)<1 and stristr($_SESSION['cargo'],'1') == TRUE){
$coinciden = mysql_query("SELECT distinct materia, codigo FROM profesores, asignaturas WHERE asignaturas.nombre = profesores.materia and asignaturas.curso = profesores.nivel and grupo = '$dalumno[3]' and asignaturas.curso='$n_cur' and abrev not like '%\_%'");	
}
echo "<div class='form-group'><label>Asignatura</label><select name='asignatura' class='form-control' required>";
echo"<OPTION></OPTION>";
while($coinciden0 = mysql_fetch_row($coinciden)){
$n_asig = $coinciden0[0];
$cod = $coinciden0[1];
if (strstr($asi1,$cod)==TRUE) {
				if($n_asig == $asignatur){
				$materia = $n_asig;
				echo "<OPTION selected='selected'>$n_asig </OPTION>";
				}
				else {echo"<OPTION>$n_asig</OPTION>";}
}
}

echo "</select></div>";

$ya_hay=mysql_query("select informe from infotut_profesor where asignatura = '$materia' and id_alumno = '$id'");
$ya_hay1=mysql_fetch_row($ya_hay);
$informe=$ya_hay1[0];
echo "<div class='form-group'><label>Informe</label><textarea rows='6' name='informe' class='form-control' required>$informe</textarea></div>";
?>
<input name="submit1" type=submit value="Enviar Datos" class="btn btn-primary btn-block">
</form>
</div>
</div>
</div>
</div>

<? include("../../pie.php");?>		
</body>
</html>
