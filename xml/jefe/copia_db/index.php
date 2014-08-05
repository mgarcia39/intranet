<?
session_start();
include("../../../config.php");
if($_SESSION['autentificado']!='1')
{
session_destroy();
header("location:http://$dominio/intranet/salir.php");	
exit;
}

if($_SESSION['cambiar_clave']) {
	header('Location:'.'http://'.$dominio.'/intranet/clave.php');
}

registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);


$profe = $_SESSION['profi'];
if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
header("location:http://$dominio/intranet/salir.php");
exit;	
}

function copia_bd($host,$user,$pass,$name) {
   
   $backup_file = 'db-backup_'.$name.'_'.date('YmdHis').'.sql.gz';
   
   $command = "mysqldump --opt -h $host -u $user -p$pass $name | gzip -9 > $backup_file";
    
   // ejecuci�n y salida de �xito o errores
   system($command,$output);
   return $output;   
}

function mb_file($bytes) {
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}

// CREAR COPIA DE SEGURIDAD
if(isset($_GET['action']) && $_GET['action']=="crear") {
	$result = copia_bd($db_host, $db_user, $db_pass, $db);
	
	if($result) {
		$msg_error = "No ha sido posible crear la copia de seguridad. Aseg�rese de que el directorio <?php echo __DIR__; ?>/ tiene permiso de escritura.";
	}
	else {
		$msg_success = "Se ha creado una nueva copia de seguridad.";
	}
}

// DESCARGA DE ARCHIVO
if((isset($_GET['action']) && $_GET['action']=="descargar") && (isset($_GET['archivo']) && file_exists($_GET['archivo']))) {
	header("Content-disposition: attachment; filename=".$_GET['archivo']."");
	header("Content-type: application/octet-stream");
	readfile($_GET['archivo']);
}

// ELIMINAR COPIA DE SEGURIDAD
if((isset($_GET['action']) && $_GET['action']=="eliminar") && (isset($_GET['archivo']) && file_exists($_GET['archivo']))) {
	unlink($_GET['archivo']);
}


include("../../../menu.php");
?>

<div class="container">
	
	<div class="page-header">
	  <h2>Administraci�n <small> Copia de seguridad de la base de datos</small></h2>
	</div>
	
	<?php if(isset($msg_success)): ?>
	<div class="alert alert-success">
		<?php echo $msg_success; ?>
	</div>
	<?php endif; ?>
	
	<?php if(isset($msg_error)): ?>
	<div class="alert alert-danger">
		<?php echo $msg_error; ?>
	</div>
	<?php endif; ?>
	
	<div class="row">
		
		<div class="col-sm-12">
		
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Archivo</th>
						<th>Tama�o</th>
						<th>Fecha</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<?php $directorio = opendir(__DIR__); ?>
				<?php while (($archivo = readdir($directorio)) !== false): ?>
				<?php if(!in_array($archivo, array('.','..','index.php','restaurar.php','php.ini'))): ?>
				<tbody>
					<tr>
						<td><?php echo $archivo; ?></td>
						<td><?php echo mb_file(filesize($archivo)); ?></td>
						<td><?php echo date("d-m-Y H:i:s", filectime($archivo)); ?></td>
						<td>
							<a href="restaurar.php?archivo=<?php echo $archivo; ?>" rel="tooltip" title="Restaurar"><span class="fa fa-undo fa-lg fa-fw"></span></a>
							<a href="index.php?action=restaurar&archivo=<?php echo $archivo; ?>" rel="tooltip" title="Descargar"><span class="fa fa-cloud-download fa-lg fa-fw"></span></a>
							<a href="index.php?action=eliminar&archivo=<?php echo $archivo; ?>" data-bb="confirm-delete" rel="tooltip" title="Eliminar"><span class="fa fa-trash-o fa-lg fa-fw"></span></a>
						</td>
					</tr>
				</tbody>
				<?php endif; ?>
				<?php endwhile; ?>
			</table>
			
			<a class="btn btn-primary" href="index.php?action=crear">Crear copia</a>
			<a class="btn btn-default" href="restaurar.php">Restaurar desde archivo</a>
			<a class="btn btn-default" href="../../index.php">Volver</a>
			
		</div>
		
	</div>
	
</div>

<?php include("../../../pie.php"); ?>

</body>
</html>
