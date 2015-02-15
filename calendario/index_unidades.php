<?php
session_start();
include("../config.php");
include_once('../config/version.php');

$GLOBALS['db_con'] = $db_con;

// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	
	if(isset($_SERVER['HTTPS'])) {
	    if ($_SERVER["HTTPS"] == "on") {
	        header('Location:'.'https://'.$dominio.'/intranet/salir.php');
	        exit();
	    } 
	}
	else {
		header('Location:'.'http://'.$dominio.'/intranet/salir.php');
		exit();
	}
}

if($_SESSION['cambiar_clave']) {
	if(isset($_SERVER['HTTPS'])) {
	    if ($_SERVER["HTTPS"] == "on") {
	        header('Location:'.'https://'.$dominio.'/intranet/clave.php');
	        exit();
	    } 
	}
	else {
		header('Location:'.'http://'.$dominio.'/intranet/clave.php');
		exit();
	}
}


registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);


$result = mysqli_query($db_con, "SELECT DISTINCT unidad FROM alma ORDER BY unidad ASC LIMIT 1");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['unidad'])) {
	$unidad = $_POST['unidad'];
}
elseif(isset($_GET['unidad'])) {
	$unidad = $_GET['unidad'];
}
else {
	$unidad = $row['unidad'];
}

// CALENDARIO
$dia_actual = date('d');

$dia  = isset($_GET['dia'])  ? $_GET['dia']  : date('d');
$mes  = isset($_GET['mes'])  ? $_GET['mes']  : date('n');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

$semana = 1;

for ($i = 1; $i <= date('t', strtotime($anio.'-'.$mes)); $i++) {
	
	$dia_semana = date('N', strtotime($anio.'-'.$mes.'-'.$i));
	
	$calendario[$semana][$dia_semana] = $i;
	if ($dia_semana == 7) $semana++;
	
}


// NAVEGACION
$mes_ant = $mes - 1;
$anio_ant = $anio;

if ($mes == 1) {
	$mes_ant = 12;
	$anio_ant = $anio - 1;
}


$mes_sig = $mes + 1;
$anio_sig = $anio;

if ($mes == 12) {
	$mes_sig = 1;
	$anio_sig = $anio + 1;
}

// HTML CALENDARIO MENSUAL
function vista_mes ($calendario, $dia, $mes, $anio, $unidad) {
	
	// Correcci�n en mes
	($mes < 10) ? $mes = '0'.$mes : $mes = $mes;
	
	echo '<div class"table-responsive">';
	echo '<table id="calendar" class="table table-bordered">';
	echo '	<thead>';
	echo '		<tr>';
	echo '			<th class="text-center">Lunes</th>';
	echo '			<th class="text-center">Martes</th>';
	echo '			<th class="text-center">Mi�rcoles</th>';
	echo '			<th class="text-center">Jueves</th>';
	echo '			<th class="text-center">Viernes</th>';
	echo '			<th class="text-center">S�bado</th>';
	echo '			<th class="text-center">Domingo</th>';
	echo '		</tr>';
	echo '	</thead>';
	echo '	<tbody>';
	
	foreach ($calendario as $dias) {
		echo '		<tr>';
	
		for ($i = 1; $i <= 7; $i++) {
			
			if ($i > 5) {
				if (isset($dias[$i]) && ($mes == date('m')) && ($dias[$i] == date('d'))) {
					echo '			<td class="text-muted today" width="14.28%">';
				}
				else {
					echo '			<td class="text-muted" width="14.28%">';
				}
			}
			else {
				if (isset($dias[$i]) && ($mes == date('m')) && ($dias[$i] == date('d'))) {
					echo '			<td class="today" width="14.28%">';
				}
				else {
					echo '			<td width="14.28%">';
				}
			}
			
			if (isset($dias[$i])) {

				echo '				<p class="lead text-right">'.$dias[$i].'</p>';
				
				// Correcci�n en d�a
				($dias[$i] < 10) ? $dia0 = '0'.$dias[$i] : $dia0 = $dias[$i];
				
				
				// Consultamos los calendarios privados
				$result_calendarios = mysqli_query($GLOBALS['db_con'], "SELECT id, color FROM calendario_categorias WHERE espublico=0");
				while ($calendario = mysqli_fetch_assoc($result_calendarios)) {
					$result_eventos = mysqli_query($GLOBALS['db_con'], "SELECT id, nombre, descripcion, fechaini FROM calendario WHERE categoria='".$calendario['id']."' AND YEAR(fechaini)='$anio' AND MONTH(fechaini)='$mes' AND unidades LIKE '%$unidad%'");
					
					while ($eventos = mysqli_fetch_assoc($result_eventos)) {
						if ($eventos['fechaini'] == $anio.'-'.$mes.'-'.$dia0) {
							echo '<div class="label" style="background-color: '.$calendario['color'].';" data-bs="tooltip" title="'.$eventos['descripcion'].'">'.$eventos['nombre'].'</div>';
						}
					}
					mysqli_free_result($result_eventos);
				}
				mysqli_free_result($result_calendarios);
				
				// Consultamos los calendarios p�blicos
				$result_calendarios = mysqli_query($GLOBALS['db_con'], "SELECT id, color FROM calendario_categorias WHERE espublico=1");
				while ($calendario = mysqli_fetch_assoc($result_calendarios)) {
					
					$result_eventos = mysqli_query($GLOBALS['db_con'], "SELECT id, nombre, descripcion, fechaini, fechafin FROM calendario WHERE categoria='".$calendario['id']."' AND unidades LIKE '%$unidad%'");
					
					while ($eventos = mysqli_fetch_assoc($result_eventos)) {
						if ($anio.'-'.$mes.'-'.$dia0 >= $eventos['fechaini'] && $anio.'-'.$mes.'-'.$dia0 <= $eventos['fechafin']) {
							echo '<div class="label" style="background-color: '.$calendario['color'].';" data-bs="tooltip" title="'.$eventos['descripcion'].'">'.$eventos['nombre'].'</div>';
						}
					}
					mysqli_free_result($result_eventos);
				}
				mysqli_free_result($result_calendarios);
				
				// FESTIVOS
				$result = mysqli_query($GLOBALS['db_con'], "SELECT fecha, nombre FROM festivos");
				while ($festivo = mysqli_fetch_assoc($result)) {
					
					if ($festivo['fecha'] == $anio.'-'.$mes.'-'.$dia0) {
						echo '<div class="label label-danger hidden_calendario_festivo visible" data-bs="tooltip" title="'.$festivo['nombre'].'">'.$festivo['nombre'].'</div>';
					}
				}
				mysqli_free_result($result);
				unset($festivo);
				
				
			}
			else {
				echo '&nbsp;';
			}
			
			echo '			</td>';
		}
		echo '		</tr>';
	}
	
	echo '	</tbody>';
	echo '</table>';
	echo '</div>';

}
?>
<?php include("../menu.php"); ?>

	<div class="container">
		
		<style type="text/css">
		table>tbody>tr>td {
			height: 103px !important;
		}
		.label {
			display: block;
			white-space: normal;
			text-align: left;
			margin-top: 5px;
			text-decoration: none !important;
			font-size: 0.9em;
			font-weight: 400;
		}
		
		p.lead {
			margin-bottom: 0;
		}
		
		@media print {
			html, body {
				width: 100%;
			}
			
			.container, .col-md-12 {
				width: 100%;
			}
			
		}
		</style>
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Calendario <small><?php echo strftime('%B, %Y', strtotime($anio.'-'.$mes)); ?></small></h2>
			<h3 class="visible-print"><?php echo $unidad; ?></h3>
		</div>
		
		
		
		<!-- SCAFFOLDING -->
		<div class="row">
			
			<!-- COLUMNA CENTRAL -->
			<div class="col-md-12">
				
				<!-- BUTTONS -->
				<div class="hidden-print">
					
					<form class="form-horizontal pull-left col-xs-4 col-md-3" method="post" action="index_unidades.php?mes=<?php echo $mes; ?>&anio=<?php echo $anio; ?>">
						<div class="form-group">
							<div class="col-xs-12">
								<select class="form-control" id="unidad" name="unidad" onchange="submit()">
									<?php if (stristr($_SESSION['cargo'],'1')): ?>
									<?php $result = mysqli_query($db_con, "SELECT DISTINCT unidad FROM alma ORDER BY unidad ASC"); ?>
									<?php else: ?>
									<?php $result = mysqli_query($db_con, "SELECT DISTINCT grupo AS unidad FROM profesores WHERE profesor='".$_SESSION['profi']."'"); ?>
									<?php endif; ?>
									<?php while($row = mysqli_fetch_assoc($result)): ?>
									<option value="<?php echo $row['unidad']; ?>" <?php echo (isset($unidad) && $unidad == $row['unidad']) ? 'selected' : ''; ?>><?php echo $row['unidad']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
					</form>
					
					<div class="pull-right">
						<a href="#" onclick="javascrip:print()" class="btn btn-default"><span class="fa fa-print fa-fw"></span></a>
						
						<div class="btn-group">
						  <a href="?mes=<?php echo $mes_ant; ?>&anio=<?php echo $anio_ant; ?>&unidad=<?php echo $unidad; ?>" class="btn btn-default">&laquo;</a>
						  <a href="?mes=<?php echo date('n'); ?>&anio=<?php echo date('Y'); ?>&unidad=<?php echo $unidad; ?>" class="btn btn-default">Hoy</a>
						  <a href="?mes=<?php echo $mes_sig; ?>&anio=<?php echo $anio_sig; ?>&unidad=<?php echo $unidad; ?>" class="btn btn-default">&raquo;</a>
						</div>
					</div>
				</div>
				
				<br class="hidden-print">
				
				<?php vista_mes($calendario, $dia, $mes, $anio, $unidad); ?>
				
			</div><!-- /.col-md-12 -->
			
		</div><!-- /.row -->
		
	</div><!-- /.container -->

<?php include("../pie.php"); ?>

</body>
</html>
