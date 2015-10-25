<?php
require('../../bootstrap.php');


$profesor = $_SESSION['profi'];


if (isset($_POST['profeso'])) {$profeso = $_POST['profeso'];} elseif (isset($_GET['profeso'])) {$profeso = $_GET['profeso'];} else{$profeso="";}

$profe = explode(", ",$profeso);

include("../../menu.php");
?>
<div
	class="container"><!-- TITULO DE LA PAGINA -->
<div class="page-header">
<form method="post" action="">
<h2><?php echo nomprofesor($profe[1].' '.$profe[0]); ?> <small>Consulta
de horario <?php $result = mysqli_query($db_con, "SELECT DISTINCT prof FROM horw WHERE prof NOT LIKE '' ORDER BY prof ASC"); ?>
<?php if(mysqli_num_rows($result)): ?>
<div class="pull-right"><select
	class="form-control input-small col-sm-3" id="curso" name="profeso"
	onChange="submit()">
	<option value="<?php echo $_POST['profeso']; ?>"><?php echo $_POST['profeso']; ?></option>
	<?php while($row = mysqli_fetch_array($result)): ?>
	<option value="<?php echo $row['prof']; ?>"><?php echo $row['prof']; ?></option>
	<?php endwhile; ?>
	<?php endif; ?>
</select></div>
</small></h2>
</form>
</div>

<!-- SCAFFOLDING -->
<div class="row">

<div class="col-sm-12">

<div class="table-responsive">
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>Lunes</th>
			<th>Martes</th>
			<th>Mi�rcoles</th>
			<th>Jueves</th>
			<th>Viernes</th>
		</tr>
	</thead>
	<tbody>
	<?php $dia = ""; ?>
	<?php $horas = array(1 => "1�", 2 => "2�", 3 => "3�", 4 => "4�", 5 => "5�", 6 => "6�" ); ?>
	<?php foreach($horas as $hora => $desc): ?>
		<tr>
			<th><?php echo $desc; ?></th>
			<?php for($i = 1; $i < 6; $i++): ?>
			<?php $result = mysqli_query($db_con, "SELECT DISTINCT a_asig, asig, a_grupo, a_aula, n_aula FROM horw WHERE prof='$profeso' AND dia='$i' AND hora='$hora'"); ?>
			<td width="20%" style="border-right: 2px solid #ddd;"><?php while($row = mysqli_fetch_array($result)): ?>
			<abbr data-bs="tooltip" title="<?php echo $row['asig']; ?>"><?php echo $row['a_asig']; ?></abbr><br>
			<?php echo (!empty($row['n_aula']) && $row['n_aula'] != 'Sin asignar o sin aula' && $row['n_aula'] != 'NULL') ? '<abbr class="pull-right text-danger" data-bs="tooltip" title="'.$row['n_aula'].'">'.$row['a_aula'].'</abbr>' : ''; ?>
			<?php echo (!empty($row['a_grupo'])) ? '<span class="text-warning">'.$row['a_grupo'].'</span>' : ''; ?><br>
			<?php endwhile; ?> <?php mysqli_free_result($result); ?></td>
			<?php endfor; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</div>

<div class="hidden-print"><a class="btn btn-primary" href="#"
	onclick="javascript:print();">Imprimir</a> <a class="btn btn-default"
	href="chorarios.php">Volver</a></div>

</div>
<!-- /.col-sm-12 --></div>
<!-- /.row --></div>
<!-- /.container -->

		<?php include("../../pie.php"); ?>

</body>
</html>
