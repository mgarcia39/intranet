<?
session_start();
include("../../config.php");
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


if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_GET['pag'])) $pag = $_GET['pag']; else $pag = 0;

if(isset($_GET['id']) && isset($_GET['borrar']) && $_GET['borrar']) {
	$result = mysql_query("DELETE FROM noticias WHERE id='$id' LIMIT 1");
	
	if(!$result) $msg_error = "No se ha podido eliminar la noticia. Error: ".mysql_error();
	else $msg_success = "La noticia ha sido eliminada.";
}


$result = mysql_query("SELECT id FROM noticias");
$total = mysql_num_rows($result);
mysql_free_result($result);


$limit = 20;
$limit_ini = $pag * $limit;
$n_paginas = round($total / $limit)-1;
$pag_sig = $pag+1;
$pag_ant = $pag-1;


include("../../menu.php");
include("menu.php");
?>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Noticias <small>Noticias y novedades del centro</small></h2>
		</div>
		
		<!-- MENSAJES -->
		<?php if(isset($msg_error)): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		
		<?php if(isset($msg_success)): ?>
		<div class="alert alert-success" role="alert">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>
		
		
		<div class="row">
			
			<div class="col-sm-12">
				
				<?php $result = mysql_query("SELECT id, slug, timestamp, contact, pagina FROM noticias ORDER BY timestamp DESC LIMIT $limit_ini, $limit"); ?>
				
				<?php if (mysql_num_rows($result)): ?>
					
					<style class="text/css">
						a.link-msg, a.link-msg:hover { color: #444; display: block; text-decoration:none; }
					</style>
					
					<div class="table-responsive">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>T�tulo</th>
									<th nowrap>Fecha publicaci�n</th>
									<th>Autor</th>
									<th>Int.</th>
									<th>Ext.</th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<?php while ($row = mysql_fetch_array($result)): ?>
									<tr>
										<td><a class="link-msg" href="noticia.php?id=<?php echo $row['id']; ?>"><?php echo $row['id']; ?></a></td>
										<td><a class="link-msg" href="noticia.php?id=<?php echo $row['id']; ?>"><?php echo (strlen($row['slug']) > 60) ? substr($row['slug'],0,60).'...' : $row['slug']; ?></a></td>
										<td><a class="link-msg" href="noticia.php?id=<?php echo $row['id']; ?>"><?php echo strftime('%d-%m-%G',strtotime($row['timestamp'])); ?></a></td>
										<td><a class="link-msg" href="noticia.php?id=<?php echo $row['id']; ?>"><?php echo $row['contact']; ?></a></td>
										<td class="text-center">
											<span class="fa <?php echo (strstr($row['pagina'],'1')==TRUE) ? 'fa-star' : 'fa-star-o'; ?> fa-lg" rel="tooltip" title="Publicada en la intranet"></span>
										</td>
										<td class="text-center">
											<span class="fa <?php echo (strstr($row['pagina'],'2')==TRUE) ? 'fa-star' : 'fa-star-o'; ?> fa-lg" rel="tooltip" title="Publicada en la p�gina externa"></span>
										</td>
										<td nowrap>
											<?php if(stristr($_SESSION['cargo'],'1') == TRUE || $_SESSION['profi'] == $row['contact']): ?>
											<a href="redactar.php?id=<?php echo $row['id']; ?>"><span class="fa fa-edit fa-fw fa-lg" rel="tooltip" title="Editar"></span></a>
											<a href="index.php?id=<?php echo $row['id']; ?>&borrar=1" onclick="return confirmacion();"><span class="fa fa-trash-o fa-fw fa-lg" rel="tooltip" title="Eliminar"></span></a>
											<?php endif; ?>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
						<tfoot>
							<tr>
								<td colspan="5">
									<div class="text-right text-muted">Mostrando <?php echo mysql_num_rows($result); ?> de <?php echo $limit; ?>. Total: <?php echo $total; ?> resultados</div>
								</td>
							</tr>
						</tfoot>
					</div>
					
					<ul class="pager">
					  <li class="previous<?php echo ($pag == $n_paginas) ? ' disabled' : ''; ?>"><a href="<?php echo ($pag == $n_paginas) ? '#' : 'index.php?pag='.$pag_sig; ?>">&larr; Antiguas</a></li>
					  <li class="next<?php echo ($pag == 0) ? ' disabled' : '' ?>"><a href="<?php echo ($pag == 0) ? '#' : 'index.php?pag='.$pag_ant; ?>">Recientes &rarr;</a></li>
					</ul>
					
					<?php mysql_free_result($result); ?>
					
				<?php else: ?>
					
					<h3>No se ha redactado ninguna noticia.</h3>
					
					
				<?php endif; ?>
				
			</div><!-- /.col-sm-12 -->
					
		</div><!-- /.row -->
		
	</div><!-- /.container -->

<?php include("../../pie.php"); ?>

</body>
</html>
