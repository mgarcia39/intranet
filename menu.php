<!DOCTYPE html>  
<html lang="es">  
  <head>  
    <meta charset="iso-8859-1">  
    <title>Intranet</title>  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <meta name="description" content="Intranet del http://<? echo $nombre_del_centro;?>/">  
    <meta name="author" content="">  
    <link href="http://<? echo $dominio;?>/intranet/css/bootstrap.css" rel="stylesheet"> 
    <?php
    if(strstr($_SERVER ['REQUEST_URI'], "index0.php")==TRUE or strstr($_SERVER ['REQUEST_URI'], "xml/index.php")==TRUE){
    ?>
    <link href="http://<? echo $dominio;?>/intranet/css/otros_index.css" rel="stylesheet">
    <script type="text/javascript" src="recursos/js/buscar_alumnos.js"></script>
    <?php 
    } else {
	?>
    <link href="http://<? echo $dominio;?>/intranet/css/otros.css" rel="stylesheet">
    <?php
    }
    ?>
    <link href="http://<? echo $dominio;?>/intranet/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="http://<? echo $dominio;?>/intranet/css/imprimir.css" rel="stylesheet" media="print">
    <link href="http://<? echo $dominio;?>/intranet/js/google-code-prettify/prettify.css" rel="stylesheet">
    <link href="http://<? echo $dominio;?>/intranet/css/datepicker.css" rel="stylesheet" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="http://<? echo $dominio;?>/intranet/css/DataTable.bootstrap.css">          
    <!--
    <link rel="stylesheet" type="text/css" href="http://<? //echo $dominio;?>/intranet/css/font-awesome.min.css"> 
    -->          
    <!--[if lt IE 9]>  
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>  
    <![endif]-->    
</head>

<body>
  
<?
include ("funciones.php");
$idea = $_SESSION ['ide'];
if (strstr($_SERVER['REQUEST_URI'],'index0.php')==TRUE) {$activo1 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'mensajes')==TRUE){ $activo2 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'upload')==TRUE){ $activo3 = ' class="active" ';}
?>
  <!-- Navbar
    ================================================== -->
<div class="navbar navbar-inverse navbar-fixed-top no_imprimir">
  <div class="navbar-inner">
    <div class="container-fluid">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="http://<? echo $dominio;?>/intranet/index0.php">Intranet del <?php echo $nombre_del_centro; ?></a>
      <div class="nav-collapse collapse">
        <ul class="nav">
          <li <? echo $activo1;?>><a href="http://<? echo $dominio;?>/intranet/index0.php">Inicio</a></li>
          <li><a href="http://<? echo $dominio;	?>">P�gina del centro</a></li>
          <li<? echo $activo2;?>><a href="http://<? echo $dominio;	?>/intranet/admin/mensajes/">Mensajes</a></li>
          <li<? echo $activo3;?>><a href="http://<? echo $dominio;	?>/intranet/upload/">Documentos</a></li>
          <li><a href="https://www.juntadeandalucia.es/educacion/seneca/" style="color:#51a351" target="_blank">S�neca</a></li>
        </ul>
        
        <ul class="nav pull-right">
        	<li class="dropdown">
        		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
        			<i class="icon-user icon-white"></i> <? echo $idea; ?> <b class="caret"></b>
        		</a>
        		<ul class="dropdown-menu">
        			<li><a href="http://<? echo $dominio; ?>/intranet/clave.php"><i class="icon-edit"></i> Cambiar contrase�a</a></li>
        			<li class="divider"></li>
        			<li><a href="http://<? echo $dominio;?>/intranet/salir.php"><i class="icon-off"></i> Cerrar sesi�n</a></li>
        		</ul>
        	</li>
        </ul>
      </div>
      </div>
    </div>
  </div>
</div>
    </div>
  </div>
</div>
