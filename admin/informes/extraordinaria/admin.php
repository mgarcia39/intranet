<?php
require('../../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1, 2, 4));

if (isset($_SESSION['mod_tutoria']['unidad'])) {
	$extra_tutor= "and alma.unidad= '".$_SESSION['mod_tutoria']['unidad']."'";
}

include("../../../menu.php");
include("menu.php");
?>
	
	<div class="container">
		
		<div class="page-header">
			<h2>Informe individual para la evaluación extraordinaria <small> <br>Administración de informes</small></h2>
		</div>

		<?php if(isset($msg_error) && $msg_error): ?>
		<div class="alert alert-danger alert-fadeout">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>

		<div class="row">
			<?php 
			$cursos = array('E.S.O.','Bachillerato','Otros');
			foreach ($cursos as $valor_curso) {
				if($valor_curso == "E.S.O."){ $extra_curso = "and alma.curso like '%E.S.O.%'";}
				elseif ($valor_curso == "Bachillerato"){ $extra_curso = "and alma.curso like '%Bachillerato%'";}
				elseif ($valor_curso == "Otros"){ $extra_curso = "and alma.curso not like '%Bachillerato%' and alma.curso not like '%E.S.O.%'";}
			?>
			<div class="col-sm-4">

				<table class="table table-striped" style="width:100%;">
					   <caption><?php echo $valor_curso; ?></caption>
					<thead>
						<tr>
							<th>Alumno</th>
							<th>Unidad</th>
							<th ></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						// OBTENEMOS INFORMES
						$al = mysqli_query($db_con,"select distinct informe_extraordinaria_alumnos.claveal, alma.unidad, apellidos, nombre from informe_extraordinaria_alumnos, alma where informe_extraordinaria_alumnos.claveal = alma.claveal $extra_tutor $extra_curso order by alma.unidad, apellidos, nombre");
						while ($alumno_informe = mysqli_fetch_array($al)) {
						?>						
						<tr>
							<td><?php echo $alumno_informe['nombre']." ".$alumno_informe['apellidos']; ?></td>
							<td><?php echo $alumno_informe['unidad']; ?></td>
							<td class="pull-right">
								<a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/extraordinaria/pdf.php?claveal=<?php echo $alumno_informe['claveal']; ?>" target="_blank" data-bs="tooltip" title="Imprimir PDF con informe de materias pendientes."><span class="fas fa-print fa-fw fa-lg"></span></a>&nbsp;
								
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>

			</div><!-- /.col-sm-4 -->

		<?php } ?>

		</div><!-- /.row -->
		


	</div>

	<?php include("../../../pie.php"); ?>

</body>
</html>