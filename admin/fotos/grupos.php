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
?>
<!DOCTYPE html>  
<html lang="es">  
  <head>  
    <meta charset="iso-8859-1">  
    <title>Intranet</title>  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <meta name="description" content="Intranet del http://<? echo $nombre_del_centro;?>/">  
    <meta name="author" content="">  
    <link href="http://<? echo $dominio;?>/intranet/css/bootstrap.css" rel="stylesheet"> 
     <link href="http://<? echo $dominio;?>/intranet/css/otros.css" rel="stylesheet">     
    <link href="http://<? echo $dominio;?>/intranet/css/bootstrap-responsive.css" rel="stylesheet">   
  </head>  
  <body style="margin-top:-70px;"> 
     <div align=center>
  <div class="page-header" align="center" style="margin-top:-15px">
  <h1>I.E.S. Monterroso <small><br />Alumnos de <? echo " $curso ($curso_actual)";?></small></h1>
</div>
<br />
  
<?

echo "<div class='container well-transparent'>";
$gr=mysql_query("select claveal, apellidos, nombre from alma where unidad='$curso'");
	while ($al_gr=mysql_fetch_array($gr)) {	
	$num=$num+1;
	if($num=="1" or $num=="7" or $num=="13" or $num=="19" or $num=="25" or $num=="31" or $num=="36"){echo "<div class='row-fluid'>";}	
		$claveal=$al_gr[0];
		if (strlen($al_gr[1])>'17') {
				$apellidos = substr($al_gr[1],0,16).".";
				}	
				else {
				$apellidos = $al_gr[1];
				}
		if (strlen($al_gr[2])>'17') {
				$nombre = substr($al_gr[2],0,16).".";
				}	
				else {
				$nombre = $al_gr[2];
				}
		echo "<div class='span2'><img src='../../xml/fotos/$claveal.jpg' style='width:auto' height='119' align='center' /><br><h6 align='center'><small>$apellidos, <br />$nombre<br /></small></h6></div>";
				if($num=="6" or $num=="12" or $num=="18" or $num=="24" or $num=="30" or $num=="36" or $num=="42"){echo "</div>";}	
	}
echo "</div>";
mysql_close();
?>
</body>
</html>