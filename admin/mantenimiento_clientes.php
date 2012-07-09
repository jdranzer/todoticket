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
                            <li class="active"><a href="#">Mantenimiento de Clientes</a></li>
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
					<li class="active"><a href="#">Mantenimiento de Clientes</a></li>
					<li><a href="agregar_evento.php">Admin. de  Eventos</a></li>
					<li><a href="bitacora.php">Bitácora</a></li>
				</ul>
				</div><!--/.well -->
			</div><!--/span-->
			
			<?php
				if (isset($_POST['modificar_permiso']))
				{
					$conn = oci_connect('dranzer','fabulousmax', 'localhost/XE');
					if($conn)
					{
						$id_cliente = $_POST['id_cliente_selected'];
						$nombre = $_POST['nombre'];
						$habilitado = $_POST['habilitado_selected'];
						if($habilitado=='Deshabilitado')
						{
							$habilitado=0;
						}elseif($habilitado=='Habilitado')
						{
							$habilitado=1;
						}
						
						//actualizar
						$result = oci_parse($conn, "BEGIN PROC_UPDATE_PCLIENTE('$id_cliente','$habilitado'); END;");
						$insertado = oci_execute($result);
						if ($insertado) 
						{
							//registrar en bitacora
								$registro = oci_parse($conn, "BEGIN PROC_INSERTAR_BITACORA('admin','admin','".date('d/m/Y')."','Se ha actualizado los permisos del cliente: $nombre [$habilitado].'); END;");
								$registrado = oci_execute($registro);
							?>
							<div class="alert alert-block alert-info fade in span5">
								<button type="button" class="close" data-dismiss="alert">×</button>
								<h4 class="alert-heading">¡Enhorabuena!.</h4>
								<p>Permisos de usuario actualizados con éxito.</p>
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
					}
				}
			?>
			
			<div class="span9 columns">
				<h2>Mantenimiento de Clientes</h2>
				<p>Cambie los permisos de los clientes.</p>
				<div class="row-fluid">
					
					<div class="span8">
						<form class="form-horizontal form-search" method="post">
							<select class="search-query" name="id_cliente">
								<?php
									$conn = oci_connect('dranzer','fabulousmax', 'localhost/XE');
									if($conn)
									{
										$query = "SELECT ID_CLIENTE, NOMBRE, APELLIDO FROM CLIENTE";
										$stid = oci_parse($conn, $query);
										oci_define_by_name($stid, 'ID_CLIENTE', $id_cl);
										oci_define_by_name($stid, 'NOMBRE', $nm);
										oci_define_by_name($stid, 'APELLIDO', $ap);
										oci_execute($stid);
										while (oci_fetch($stid))
										{
											echo "<option value=\"$id_cl\"> $id_cl. $nm $ap </option>";
										}
									}
								?>
							</select>
							<button type="submit" class="btn" name="mostrar">Mostrar</button>
						</form>
						<hr>
						<?php
							if(isset($_POST['mostrar']))
							{
								$selected = $_POST['id_cliente'];
								$conn = oci_connect('dranzer','fabulousmax', 'localhost/XE');
								if($conn)
								{
									$query = "SELECT * FROM CLIENTE WHERE ID_CLIENTE ='$selected'";
									$stid = oci_parse($conn, $query);
									oci_execute($stid);
									if($row = oci_fetch_array($stid, OCI_BOTH))
		                               {
										?>
										<form class="form-horizontal" method="post">
										<fieldset>
										<div class="control-group">
											<label class="control-label">ID Cliente</label>
											<div class="controls">
												<label class="control-label"><?php echo $row['ID_CLIENTE']; ?></label>
												<input type="text" class="input-xlarge invisible" name="id_cliente_selected" value="<?php echo $row['ID_CLIENTE']; ?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Nombre</label>
											<div class="controls">
												<input type="text" class="input-xlarge" name="nombre" value="<?php echo $row['NOMBRE']." ".$row['APELLIDO']; ?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Habilitado</label>
											<div class="controls">
												<select class="span4" name="habilitado_selected">
													<option <?php if($row['HABILITADO']==1) echo "value=\"1\" selected";?>>Habilitado</option>
													<option <?php if($row['HABILITADO']==0) echo "value=\"0\" selected";?>>Deshabilitado</option>
												</select>
											</div>
										</div>
										<div class="control-group">
											<div class="controls">
												<input type="submit" name="modificar_permiso" class="btn btn-primary btn-large" value="Modificar Permiso" />
											</div>
										</div>
										</fieldset>
										</form>
										<?php
									}
								}
							}
						?>
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
