<?php
session_start();
if (!(isset($_SESSION['username']) && $_SESSION['username'] != '' && $_SESSION['username']=='admin')) {
    header ("Location: ../index.php");
    exit;
}
    $user_logged = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>TodoTicket.com</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le fav and touch icons -->
        <link rel="shortcut icon" href="../bootstrap/ico/favicon.ico">
    </head>
    
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">TodoTicket.com</a>
                    <div class="btn-group pull-right">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="icon-user"></i> <?php echo $user_logged; ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="logout.php">Log Out</a></li>
                        </ul>
                    </div>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <li><a href="panel_admin.php">Panel Principal</a></li>
                            <li class="active"><a href="#">Admin. Tarjetas</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>
        
		<div class="container-fluid">
			<div class="row-fluid">
			<div class="span3">
				<div class="well sidebar-nav">
				<ul class="nav nav-list">
					<li class="nav-header">Menú Principal</li>
					<li><a href="panel_admin.php">Panel Principal</a></li>
					<li><a href="mantenimiento_boletos.php">Mantenimiento de Boletos</a></li>
					<li class="active"><a href="#">Admin. de Tarjetas</a></li>
					<li><a href="mantenimiento_clientes.php">Mantenimiento de Clientes</a></li>
					<li><a href="agregar_evento.php">Admin. de Eventos</a></li>
					<li><a href="bitacora.php">Bitácora</a></li>
				</ul>
				</div><!--/.well -->
			</div><!--/span-->
			
			<?php
				if (isset($_POST['crear_tarjeta']))
				{
					$conn = oci_connect('dranzer','fabulousmax', 'localhost/XE');
					if($conn)
					{
						$nombre = $_POST['nombre'];
						$tipo = $_POST['tipo'];
						
						$query = "SELECT * FROM TIPO_TARJETA WHERE NOMBRE = '$nombre' AND TIPO = '$tipo'";
						$stid = oci_parse($conn, $query);
						
						oci_execute($stid);
						if($row = oci_fetch_array($stid, OCI_BOTH))
						{
							?>
							<div class="alert alert-block alert-error fade in span5">
								<button type="button" class="close" data-dismiss="alert">×</button>
								<h4 class="alert-heading">¡Ups! Ha habido un error.</h4>
								<p>Los datos de la tarjeta que ingresaste ya existen. Ingresa valores diferentes.</p>
							</div>
							<?php
						}else
						{
							$result = oci_parse($conn, "BEGIN PROC_INS_NEW_TIPOTARJETA('$nombre','$tipo'); END;");
							$insertado = oci_execute($result);
							if ($insertado)
								{
									//registrar en bitacora
									$registro = oci_parse($conn, "BEGIN PROC_INSERTAR_BITACORA('admin','admin','".date('d/m/Y')."','Se ha creado un nuevo tipo de tarjeta: $nombre [$tipo].'); END;");
									$registrado = oci_execute($registro);
									
									?>
									<div class="alert alert-block alert-info fade in span5">
										<button type="button" class="close" data-dismiss="alert">×</button>
										<h4 class="alert-heading">¡Enhorabuena!.</h4>
										<p>Tipo de tarjeta creado con éxito.</p>
									</div>
									<?php
								}else
								{
									?>
									<div class="alert alert-block alert-error fade in span5">
										<button type="button" class="close" data-dismiss="alert">×</button>
										<h4 class="alert-heading">¡Ups! Ha habido un error.</h4>
										<p>El registro no pudo ser ingresado a la base de datos.</p>
									</div>
									<?php
								}
						}
						oci_free_statement($stid);
		                oci_close($conn);
					}
				}
			?>
			
			<div class="span9 columns">
				<h2>Crear Tarjeta de Crédito/Débito</h2>
				<p>Cree una tipo de tarjeta en el sistema.</p>
				<div class="row-fluid">
					
					<div class="span8">
						<form class="form-horizontal" method="post">
							<fieldset>
								<div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" class="input-xlarge" name="nombre">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Tipo</label>
									<div class="controls">
										<select class="span4" name="tipo">
											<option>Credito</option>
											<option>Debito</option>
										</select>
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<input type="submit" name="crear_tarjeta" class="btn btn-primary btn-large" value="Crear Tarjeta" />
									</div>
								</div>
							</fieldset>
						</form>
					</div><!--/span-->
					
					<div class="span4">
						<p><a class="btn btn-large btn-inverse" href="#">Crear Tarjeta &raquo;</a></p>
					</div><!--/span-->
					<div class="span4">
						<p><a class="btn btn-large btn-primary" href="modificar_tarjeta.php">Modificar Tarjeta &raquo;</a></p>
					</div><!--/span-->
				</div><!--/row-->
			</div><!--/span-->	
			</div><!--/row-->
			<hr>

			<footer>
				<p>&copy; TodoTicket.com 2012</p>
			</footer>
		</div><!--/.fluid-container-->
        
        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="../bootstrap/js/jquery.js"></script>
        <script src="../bootstrap/js/bootstrap-transition.js"></script>
        <script src="../bootstrap/js/bootstrap-alert.js"></script>
        <script src="../bootstrap/js/bootstrap-modal.js"></script>
        <script src="../bootstrap/js/bootstrap-dropdown.js"></script>
        <script src="../bootstrap/js/bootstrap-scrollspy.js"></script>
        <script src="../bootstrap/js/bootstrap-tab.js"></script>
        <script src="../bootstrap/js/bootstrap-tooltip.js"></script>
        <script src="../bootstrap/js/bootstrap-popover.js"></script>
        <script src="../bootstrap/js/bootstrap-button.js"></script>
        <script src="../bootstrap/js/bootstrap-collapse.js"></script>
        <script src="../bootstrap/js/bootstrap-carousel.js"></script>
        <script src="../bootstrap/js/bootstrap-typeahead.js"></script>

    </body>
</html>
