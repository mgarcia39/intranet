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
<?
include("../../menu.php");
echo '<div align="center"><h2>Libros de Texto</h2><br />';		
echo "<h3>
			Borrar un Libro de la Base de Datos</h3><br />";
 
$connection = mysql_connect($db_host, $db_user, $db_pass) or die ("No es posible conectar con la base de datos!");

mysql_select_db($db) or die ("No es posible conectar con la base de datos!");

$query = "DELETE FROM Textos WHERE id = '$id'";
$result = mysql_query($query) or die ("Error en la Consulta: $query. " . mysql_error());

mysql_close($connection);

echo '<br /><div align="center"><div class="alert alert-success alert-block fade in" style="width:auto;max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			El Libro de Texto ha sido borrado de la Base de datos.
		</div></div><br />';
?>
 <? include("../../pie.php");?>		
</body>
</html>
