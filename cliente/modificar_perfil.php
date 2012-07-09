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
                            <li class="active"><a href="#">Modificar Perfil</a></li>
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
							<li class="active"><a href="#">Modificar Perfil</a></li>
							<li><a href="historial_compras.php">Historial de Compras</a></li>
							<li><a href="catalogo_eventos.php">Catálogo de Eventos</a></li>
							<li><a href="busqueda.php">Búsqueda</a></li>
							<li><a href="reservar.php">Reserva de Boletos</a></li>
							<li><a href="carrito.php">Carrito de Compras</a></li>
						</ul>
					</div><!--/.well -->
			    </div><!--/span-->
			    
				<?php
					if(isset($_POST['modificar_perfil']))
					{
						$conn = oci_connect('dranzer','fabulousmax', 'localhost/XE');
						if($conn)
						{
							//obtener valores
							$id_cliente = $_POST['id_cliente'];
							$nombre = $_POST['nombre'];
							$apellido = $_POST['apellido'];
							$cid = $_POST['cid'];
							$telefono = $_POST['telefono'];
							$direccion = $_POST['direccion'];
							$tarjeta_referencia = $_POST['tarjeta_referencia'];
							$email = $_POST['email'];
							$usuario = $_POST['usuario'];
							$password = $_POST['password'];
							
							//actualizar
							$result = oci_parse($conn, "BEGIN PROC_ACTUALIZAR_CLIENTE('$id_cliente','$nombre','$apellido','$cid','$telefono','$direccion','$tarjeta_referencia','$email','$usuario','$password'); END;");
							$insertado = oci_execute($result);
							if ($insertado) 
							{
								//registrar en bitacora
								$registro = oci_parse($conn, "BEGIN PROC_INSERTAR_BITACORA('cliente','$usuario','".date('d/m/Y')."','Se ha actualizado los datos del cliente: $nombre $apellido [$id_cliente].'); END;");
								$registrado = oci_execute($registro);
								?>
								<div class="alert alert-block alert-info fade in span5">
									<button type="button" class="close" data-dismiss="alert">×</button>
									<h4 class="alert-heading">¡Enhorabuena!.</h4>
									<p>Datos del usuario actualizados con éxito.</p>
								</div>
								<?php
							}else
							{
								?>
								<div class="alert alert-block alert-error fade in span5">
									<button type="button" class="close" data-dismiss="alert">×</button>
									<h4 class="alert-heading">¡Ups! Ha habido un error.</h4>
									<p>El registro no pudo ser actualizado en la base de datos.</p>
								</div>
								<?php
							}
						}oci_close($conn);
					}
				?>
			    
			    <div class="span9 columns">
				<h2>Modificar Perfil</h2>
				<p>Modifica los datos de tu perfil.</p>
				<hr>
				<div class="row-fluid">
					<div class="span8">
						<form class="form-horizontal" method="post">
							<fieldset>
							<?php
								$conn = oci_connect('dranzer','fabulousmax', 'localhost/XE');
								if($conn)
								{
									$query = "SELECT * FROM CLIENTE WHERE USUARIO ='$user_logged'";
									$stid = oci_parse($conn, $query);
									oci_execute($stid);
									if($row = oci_fetch_array($stid, OCI_BOTH))
									{
										?>
										<div class="control-group">
											<label class="control-label">Nombre</label>
											<div class="controls">
												<input type="text" class="input-xlarge" name="nombre" value="<?php echo $row['NOMBRE']; ?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Apellido</label>
											<div class="controls">
												<input type="text" class="input-xlarge" name="apellido" value="<?php echo $row['APELLIDO']; ?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">CID</label>
											<div class="controls">
												<input type="text" class="input-xlarge" name="cid" value="<?php echo $row['CID']; ?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Teléfono</label>
											<div class="controls">
												<input type="text" class="input-xlarge" name="telefono" value="<?php echo $row['TELEFONO']; ?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Dirección</label>
											<div class="controls">
												<input type="text" class="input-xlarge" name="direccion" value="<?php echo $row['DIRECCION']; ?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Tarjeta Referencia</label>
											<div class="controls">
												<input type="text" class="input-xlarge" name="tarjeta_referencia" value="<?php echo $row['TARJETA_REFERENCIA']; ?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">E-mail</label>
											<div class="controls">
												<input type="text" class="input-xlarge" name="email" value="<?php echo $row['EMAIL']; ?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Usuario</label>
											<div class="controls">
												<input type="text" class="input-xlarge" name="usuario" value="<?php echo $row['USUARIO']; ?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Contraseña</label>
											<div class="controls">
												<input type="password" class="input-xlarge" name="password" value="<?php echo $row['PASSWORD']; ?>">
												<input type="text" class="input-xlarge invisible" name="id_cliente" value="<?php echo $row['ID_CLIENTE']; ?>">
											</div>
										</div>
										<div class="control-group">
											<div class="controls">
												<input type="submit" name="modificar_perfil" class="btn btn-primary btn-large" value="Modificar" />
											</div>
										</div>
										<?php
									}
								}
								oci_close($conn);
							?>
							</fieldset>
						</form>
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
