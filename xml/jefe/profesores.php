<?
session_start();
include("../../config.php");
if($_SESSION['autentificado']!='1')
{
session_destroy();
header("location:http://$dominio/intranet/salir.php");	
exit;
}
registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);
if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
header("location:http://$dominio/intranet/salir.php");
exit;	
}
?>
<?php
include("../../menu.php");
?>
<div align="center">
<div class="page-header" align="center" style="margin-top:-15px;">
  <h1>Administraci�n <small> Actualizaci�n de los profesores</small></h1>
</div>
<br />
<div class="well-2 well-large" style="width:700px;margin:auto;text-align:left">
<?
if($archivo){ 
mysql_connect ($db_host, $db_user, $db_pass) or die("Error de conexi�n");
mysql_select_db($db);
 
 //Creamos backup de horarios
 mysql_query("drop table horw_seg");
 mysql_query("drop table horw_seg_faltas");
 mysql_query("create table horw_seg select * from horw");
 mysql_query("create table horw_seg_faltas select * from horw_faltas");

 mysql_query("create table if not exists horw_var select * from horw");
mysql_query("truncate table horw_var");
// BacKup de la tabla profesores
mysql_query("drop table profesores_seg");
mysql_query("create table profesores_seg select * from profesores");
 // Creamos Base de datos y enlazamos con ella.
 $base0 = "truncate table profesores";
  mysql_query($base0);

// Importamos los datos del fichero CSV (todos_alumnos.csv) en la tab�a alma.
$handle = fopen ($_FILES['archivo']['tmp_name'] , "r" ) or die
('<div align="center"><div class="alert alert-danger alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
No se ha podido abrir el archivo RelMatProUni.txt. O bien te has olvidado de enviarlo o el archivo est� corrompido.
</div></div><br />
<div align="center">
  <input type="button" value="Volver atr�s" name="boton" onClick="history.back(2)" class="btn btn-inverse" />
</div>'); 
while (($data1 = fgetcsv($handle, 1000, "|")) !== FALSE) 
{
$mat = str_replace("&nbsp;","",$data1[1]);
$datos1 = "INSERT INTO profesores (NIVEL, MATERIA, GRUPO, PROFESOR) VALUES (\"". trim($data1[0]) . "\",\"". trim($mat) . "\",\"". trim($data1[2]) . "\",\"". trim($data1[3]) . "\")";
mysql_query($datos1);
}
fclose($handle);
$borrarvacios = "delete from profesores where MATERIA = ''";
mysql_query($borrarvacios);
mysql_query("delete from profesores where profesor like '%Profesor/a%'");
echo '<div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Tabla <strong>Profesores</strong>: los datos han sido introducidos correctamente.
</div></div><br />';

$base0 = "DROP TABLE ".$db_reservas.".profesores";
mysql_query($base0);
$faltasprofexml = "create table ".$db_reservas.".profesores select NIVEL, MATERIA, GRUPO, PROFESOR from ".$db.".profesores";
mysql_query($faltasprofexml) or die('<div align="center"><div class="alert alert-danger alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
No se ha podido crear la tabla <strong>profesores</strong> en la base de datos <strong>Reservas</strong>.<br> Aseg�rate de que su formato es correcto.
</div></div><br />
<div align="center">
  <input type="button" value="Volver atr�s" name="boton" onClick="history.back(2)" class="btn btn-inverse" />
</div>');
mysql_query("ALTER TABLE ".$db_reservas.".profesores ADD INDEX (  `PROFESOR` )");

// Limpiamos Tabla de Horarios de grupos que no da el profesor
echo "<hr><span class='label label-important' style='text-align:left'>Profesores y Asignaturas de<strong> Horw </strong>que no aparecen en S�neca.</span><br />";

$hor0 = "select id, prof, a_grupo, asig from horw where asig not like 'OPTATIVA EXENTOS'";
$hor1 = mysql_query($hor0);
while($hor = mysql_fetch_array($hor1))
{
$id = $hor[0];
$profesor = $hor[1];
$grupo = substr($hor[2],0,4);
$materia = $hor[3];
if(is_numeric(substr($grupo,0,1)))
{
$prof0 = "select * from profesores where profesor = '$profesor' and grupo = '$grupo'";
$prof1 = mysql_query($prof0);
if(mysql_num_rows($prof1) < 1)
{
echo "<li>Borrado: $profesor => $materia  => $grupo</li>";
mysql_query("delete from horw where id = '$id'");
}
}
}
//$id = "";
// Colocar c�digos y nombre de asignaturas de Horw de acuerdo con S�neca (tabla Profesores)
$sql = mysql_query("select id, prof, a_grupo, a_asig, asig, c_asig from horw where a_grupo not like 'G%' and a_grupo IS NOT NULL");
//echo "select id, prof, a_grupo, a_asig, asig, c_asig from horw where a_grupo not like 'G%' and a_grupo IS NOT NULL<br>";
while($row = mysql_fetch_array($sql))
{
$curso = substr($row[2],0,1);
$id = $row[0];
$prof = $row[1];
$a_grupo = substr($row[2],0,4);
$a_asig = $row[3];
$asig = $row[4];
$c_asig =  $row[5];
$abrev_asig = substr($row[4],0,1);
$cur=mysql_query("select distinct curso from alma where unidad= '$a_grupo'");
$cur0=mysql_fetch_array($cur);
$curs2=$cur0[0];
$asig0 = mysql_query("select distinct materia, abrev, codigo, curso from profesores, asignaturas, horw where profesor = prof and profesores.nivel = asignaturas.curso and materia = nombre and grupo = '$a_grupo' and id= '$id' and abrev not like '%\_%' and curso like '%$curs2%' and a_asig not like 'TUT%'");
$codigo="";
while($asigna = mysql_fetch_array($asig0))
{
$materia= $asigna[0];
$abrev= $asigna[1];
$codigo= $asigna[2];
$curs= $asigna[3];

if(mysql_num_rows($asig0) == 1)
{
	$num+=1;
//	echo "Unidad �nica.<br>";
mysql_query("insert into horw_var select * from horw where id='$id'");
mysql_query("update horw_var set clase='Actualizado' where id='$id'");
mysql_query("update horw set a_asig = '$abrev', c_asig = '$codigo', asig = '$materia' where id= '$id'");
// echo "$id => $prof => $materia => $asig => $abrev => $codigo => $c_asig => $a_asig => $a_grupo => $curs<br>";
}

elseif ($codigo==$c_asig) {
	$num+=1;
//	echo "Codigos iguales<br>";
mysql_query("insert into horw_var select * from horw where id='$id'");
mysql_query("update horw_var set clase='Codigo +' where id='$id'");
mysql_query("update horw set a_asig = '$abrev', asig = '$materia' where id= '$id'");
// echo "$id => $prof => $materia => $asig => $abrev => $codigo => $c_asig => $a_asig => $a_grupo => $curs<br>";

}

else
{
$materia2 = mb_strtoupper($materia);
$asig2 = mb_strtoupper($asig);
similar_text($asig2, $materia2, $percent2);
if($percent2>"75")
{
	$num+=1;
mysql_query("insert into horw_var select * from horw where id='$id'");
mysql_query("update horw_var set clase='$percent2' where id='$id'");
mysql_query("update horw set a_asig = '$abrev', c_asig = '$codigo', asig = '$materia' where id= '$id'");
// echo "Porcentaje +75%<br>";
// echo "$id => $prof => $materia => $asig => $abrev => $codigo => $a_asig => $c_asig => $a_grupo => $percent2;<br>";
$codigo="";
}
else
{
if (!(empty($a_grupo))) {
	//$num+=1;
mysql_query("insert into horw_var select * from horw where id='$id'");
mysql_query("update horw_var set clase='Sin corresp' where id='$id'");
// echo "Sin correspondencia<br>";	
// echo "$id => $prof => $materia => $asig => $abrev => $codigo => $a_asig => $c_asig => $a_grupo => $percent2;<br>";
}
}
}
}
} 
mysql_query("OPTIMIZE TABLE  `horw`");  

// creamos Horw para las Faltas
$base0 = "DROP TABLE horw_faltas";
mysql_query($base0);
mysql_query("create table horw_faltas select * from horw where (a_asig not like '%TTA%' and a_asig not like '%TPESO%')");
//Elimina las horas no lectivas
  $nolectiva = "UPDATE  horw_faltas SET  nivel =  '', a_grupo = '', n_grupo = '' WHERE  a_grupo NOT LIKE '1%' and a_grupo NOT LIKE '2%' and a_grupo NOT LIKE '3%' and a_grupo NOT LIKE '4%' and a_asig not like 'TUT%'";
  mysql_query($nolectiva);
  mysql_query("ALTER TABLE  ".$db."horw_faltas ADD INDEX (`prof`)");
  mysql_query("ALTER TABLE  ".$db."horw_faltas ADD index (`c_asig`)");
  mysql_query("delete from horw_faltas where a_grupo='' or a_grupo is null");
  mysql_query("OPTIMIZE TABLE  `horw_faltas`");  
//Profes que est�n en horw y no en profesores
echo "<hr><span class='label label-warning' style='text-align:left'>Profesores en Horw que no aparecen en la tabla Profesores
creados:</span>";
$pro0 = "select distinct prof from horw where prof not in (select distinct profesor from profesores)";
$pro1 = mysql_query($pro0);
while($pro = mysql_fetch_array($pro1))
{if(!(empty($pro[0])))
echo "<li>$pro[0]</li>";
}
echo '<br /><div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;"><br />
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Tabla <strong>Profesores</strong>: los datos se han introducido correctamente en la Base de datos. Es necesario que actualizes las tablas de Departamentos, una vez actualizados los Profesores.<br>Vuelve a la p�gina de Administraci�n y actualiza los Departamentos inmediatamente.
</div></div><br />';
$base1 = "DROP TABLE ".$db."horw_var";
mysql_query($base1);
}
else{
	echo '<hr><div align="center"><div class="alert alert-danger alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
Parece que te est� olvidando de enviar el archivo con los datos de los Profesores. Aseg�rate de enviar el archivo descargado desde S�neca.
</div></div><br />';
}
?>
<div align="center">
<input type="button" value="Volver atr�s" name="boton" onclick="history.back(2)" class="btn btn-inverse" />
</div>
</div>
</div>
<? include("../../pie.php");?>
</body>
</html>
