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
                            <li class="active"><a href="#">Bitácora</a></li>
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
					<li><a href="admin_tarjetas.php">Admin. de Tarjetas</a></li>
					<li><a href="mantenimiento_clientes.php">Mantenimiento de Clientes</a></li>
					<li><a href="agregar_evento.php">Admin. de  Eventos</a></li>
					<li class="active"><a href="#">Bitácora</a></li>
				</ul>
				</div><!--/.well -->
			</div><!--/span-->
			
			<div class="span9 columns">
				<h2>Bitácora de Clientes</h2>
				<p>Consulta de todas las operaciones ejecutadas.</p>
				<div class="row-fluid">
					
					<div class="span8">
						<hr>
						<!--Construir tabla para reporte de bitácora-->
						<?php
							$conn = oci_connect('dranzer','fabulousmax', 'localhost/XE');
							if($conn)
							{
								$query = "SELECT * FROM BITACORA WHERE ROL = 'cliente'";
								$stid = oci_parse($conn, $query);
								
								oci_define_by_name($stid, 'ROL', $rol);
								oci_define_by_name($stid, 'USUARIO', $usuario);
								oci_define_by_name($stid, 'FECHA', $fecha);
								oci_define_by_name($stid, 'DESCRIPCION', $descripcion);
								
								oci_execute($stid);
								
								echo "<table class=\"table table-striped\">
										<thead>
											<tr>
												<th>Rol</th>
												<th>Usuario</th>
												<th>Fecha</th>
												<th>Descripción</th>
											</tr>
										</thead>
										<tbody>";
								
								while (oci_fetch($stid)) {
									echo "<tr><td>$rol</td><td>$usuario</td><td>$fecha</td><td>$descripcion</td></tr>";
								}
								echo "</tbody>
									</table>";
								oci_free_statement($stid);
								oci_close($conn);
							}
						?>
						<!--fin tabla-->
					</div><!--/span-->
					<div class="span4">
						<p><a class="btn btn-large btn-primary" href="bitacora.php">Bitácora de Administrador &raquo;</a></p>
					</div><!--/span-->
					<div class="span4">
						<p><a class="btn btn-large btn-inverse" href="#">Bitácora de Clientes &raquo;</a></p>
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
