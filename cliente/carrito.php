<?php
	session_start();
	if (!(isset($_SESSION['username']) && $_SESSION['username'] != '' && $_SESSION['username'] != 'admin'))
	{
		header ("Location: ../index.php");
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
                            <li><a href="modificar_perfil.php">Perfil</a></li>
                            <li class="divider"></li>
                            <li><a href="logout.php">Log Out</a></li>
                        </ul>
                    </div>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <li><a href="panel.php">Panel de Usuario</a></li>
                            <li class="active"><a href="#">Carrito</a></li>
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
							<li><a href="panel.php">Panel Principal</a></li>
							<li><a href="modificar_perfil.php">Modificar Perfil</a></li>
							<li><a href="historial_compras.php">Historial de Compras</a></li>
							<li><a href="catalogo_eventos.php">Catálogo de Eventos</a></li>
							<li><a href="busqueda.php">Búsqueda</a></li>
							<li><a href="reservar.php">Reserva de Boletos</a></li>
							<li class="active"><a href="#">Carrito de Compras</a></li>
						</ul>
					</div><!--/.well -->
			    </div><!--/span-->
			    
				<!--area para issets-->
			    
			    <div class="span9 columns">
				<h2>Carrito de Compras</h2>
				<p>Detalle de boletos listos para comprar.</p>
				<hr>
				<div class="row-fluid">
					<div class="span8">
						<!--tabla de carrito-->
						<?php
							$conn = oci_connect('dranzer','fabulousmax', 'localhost/XE');
							if($conn)
							{
								//recuperar id_cliente
								$query = "SELECT * FROM CLIENTE WHERE USUARIO ='$user_logged'";
								$stid = oci_parse($conn, $query);
								oci_execute($stid);
								if($row = oci_fetch_array($stid, OCI_BOTH))
								{
									$id_cliente = $row['ID_CLIENTE'];
									
									//recorrer carrito
									$query = oci_parse($conn, "SELECT * FROM CARRITO WHERE ID_CLIENTE = '$id_cliente'");
									oci_execute($query);
									echo "<table class=\"table table-striped\">
											<thead>
												<tr>
													<th>ID Evento</th>
													<th>Nombre</th>
													<th>Cantidad</th>
													<th></th>
												</tr>
											</thead>
											<tbody>";
									while (($row = oci_fetch_array($query, OCI_BOTH))) {
										//nombre_evento
										$ev = $row['ID_EVENTO'];
										$query2 = "SELECT NOMBRE FROM EVENTO WHERE ID_EVENTO ='$ev'";
										$stid2 = oci_parse($conn, $query2);
										oci_execute($stid2);
										$row2 = oci_fetch_array($stid2, OCI_BOTH);
										
										echo 	"<tr>
													<td>".$row['ID_EVENTO']."</td>
													<td>".$row2['NOMBRE']."</td>
													<td>".$row['CANTIDAD']."</td>
													<td><a href=\"borrar.php?id_cl=".$row['ID_CLIENTE']."&id_ev=".$row['ID_EVENTO']."&id_cant=".$row['CANTIDAD']."\">Borrar &rarr;</a></td>
												</tr>";
									}
									echo "</tbody>
										</table>";
								}
								oci_free_statement($stid);
								oci_close($conn);
							}
						?>
						<!--fin tabla-->
					</div><!--/span-->
					<div class="span4">
					<form method="POST" action="elegir_tarjeta.php">
						<p>
							<input type="submit" name="commit" class="btn btn-success btn-large" value="Confirmar Compra &raquo;" />
						</p>
					</div><!--/span-->
			</div><!--/span-->	
			</div><!--/row-->
			    
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
