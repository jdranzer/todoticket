<?php
session_start();
if (!(isset($_SESSION['username']) && $_SESSION['username'] != '' && $_SESSION['username']=='admin')) {
    header ("Location: ../index.php");
    exit;
}
    $user_logged = $_SESSION['username'];
    
    $tab_agregar = "class=\"active\"";
	$tab_modificar = "";
	$fade2 = "";
	$fade1 = "in active";
    
    if($_SESSION['tab'] == 'modificar')
    {
		$tab_agregar = "";
		$tab_modificar = "class=\"active\"";
		$fade2 = "in active";
		$fade1 = "";
	}elseif($_SESSION['tab'] == 'crear')
	{
		$tab_agregar = "class=\"active\"";
		$tab_modificar = "";
		$fade2 = "";
		$fade1 = "in active";
	}
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
        <link href="../bootstrap/css/datepicker.css" rel="stylesheet">

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
                            <li class="active"><a href="#">Admin. de Eventos</a></li>
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
						<li class="active"><a href="agregar_evento.php">Admin. de Eventos</a></li>
						<li><a href="bitacora.php">Bitácora</a></li>
					</ul>
					</div><!--/.well -->
				</div><!--/span-->
				
				<?php 
					if (isset($_POST['crear_evento']))
					{
						$_SESSION['tab']="crear";
						$conn = oci_connect('dranzer','fabulousmax', 'localhost/XE');
						if($conn)
						{
							//obtener variables
							$nombre = $_POST['nombre'];
							$tipo  = $_POST['tipo'];
							$num_participantes = $_POST['num_participantes'];
							$lim_asistentes = $_POST['lim_asistentes'];
							$fecha_inicio = $_POST['fecha_inicio'];
							$fecha_fin = $_POST['fecha_fin'];
							$precio = $_POST['precio'];
							$duracion_dias = $_POST['duracion_dias'];
							
							//revisar si existe un evento con el mismo nombre
							$query = "SELECT * FROM EVENTO WHERE NOMBRE = '".$nombre."'";
							$stid = oci_parse($conn, $query);
							
							oci_execute($stid);
							if($row = oci_fetch_array($stid, OCI_BOTH))
							{
								?>
								<div class="alert alert-block alert-error fade in span5">
									<button type="button" class="close" data-dismiss="alert">×</button>
									<h4 class="alert-heading">¡Ups! Ha habido un error.</h4>
									<p>El nombre que usaste para el evento ya existe. Ingresa uno diferente.</p>
								</div>
								<?php
							}else
							{
								//Insertar
								$result = oci_parse($conn, "BEGIN PROC_INSERTAR_NUEVO_EVENTO(
												'$nombre', 
												'$tipo',
												'$num_participantes',
												'$lim_asistentes',
												'$fecha_inicio',
												'$fecha_fin',
												'$precio',
												'$duracion_dias'
												);
											end;");
								$insertado=oci_execute($result);
								if ($insertado) 
								{
									//registrar en bitacora
									$registro = oci_parse($conn, "BEGIN PROC_INSERTAR_BITACORA('admin','admin','".date('d/m/Y')."','Se ha creado un nuevo evento: $nombre [$tipo].'); END;");
									$registrado = oci_execute($registro);
									?>
									<div class="alert alert-block alert-info fade in span5">
										<button type="button" class="close" data-dismiss="alert">×</button>
										<h4 class="alert-heading">¡Enhorabuena!.</h4>
										<p>Evento creado con éxito.</p>
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
					
					if (isset($_POST['commit']))
					{
						$conn = oci_connect('dranzer','fabulousmax', 'localhost/XE');
						if($conn)
						{
							//obtener variables
							$id_evento = $_POST['iid_evento'];
							$inombre = $_POST['inombre'];
							$itipo  = $_POST['itipo'];
							$inum_participantes = $_POST['inum_participantes'];
							$ilim_asistentes = $_POST['ilim_asistentes'];
							$ifecha_inicio = $_POST['ifecha_inicio'];
							$ifecha_fin = $_POST['ifecha_fin'];
							$iprecio = $_POST['iprecio'];
							$ihabilitado = $_POST['ihabilitado'];
							$iduracion_dias = $_POST['iduracion_dias'];
							//Actualizar
							$iresult = oci_parse($conn, "BEGIN PROC_ACTUALIZAR_EVENTO(
												'$id_evento',
												'$inombre', 
												'$itipo',
												'$inum_participantes',
												'$ilim_asistentes',
												'$ifecha_inicio',
												'$ifecha_fin',
												'$iprecio',
												'$ihabilitado',
												'$iduracion_dias'
												);
											end;");
							$iinsertado=oci_execute($iresult);
							if ($iinsertado) 
							{
								//registrar en bitacora
								$registro = oci_parse($conn, "BEGIN PROC_INSERTAR_BITACORA('admin','admin','".date('d/m/Y')."','Se ha actualizado un evento: $inombre [$id_evento, $itipo].'); END;");
								$registrado = oci_execute($registro);
								?>
								<div class="alert alert-block alert-info fade in span5">
									<button type="button" class="close" data-dismiss="alert">×</button>
									<h4 class="alert-heading">¡Enhorabuena!.</h4>
									<p>Evento actualizado con éxito.</p>
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
							oci_close($conn);
						}
					}
				?>
				
				<div class="span9 columns">
					<h2>Agregar Eventos</h2>
					<p>Haga clic en cada pestaña para elegir las opciones disponibles.</p>
					<ul id="myTab" class="nav nav-tabs">
						<li <?php echo $tab_agregar; ?>><a href="#agregar" data-toggle="tab">Agregar Evento</a></li>
						<li <?php echo $tab_modificar; ?> ><a href="#modificar" data-toggle="tab">Modificar Evento</a></li>
					</ul>
					<div id="myTabContent" class="tab-content">
						<div class="tab-pane fade <?php echo $fade1?>" id="agregar">
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
										<!--<input type="text" class="input-xlarge" name="tipo">-->
										<select class="span4" name="tipo">
											<option>Concierto</option>
											<option>Conferencia</option>
											<option>Seminario</option>
											<option>Charla</option>
											<option>Partido</option>
											<option>Pelicula</option>
											<option>Recital</option>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Num. Participantes</label>
									<div class="controls">
										<input type="text" class="input-xlarge" name="num_participantes">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Lim. Asistentes</label>
									<div class="controls">
										<input type="text" class="input-xlarge" name="lim_asistentes">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Fecha Inicio</label>
									<div class="controls">
										<input type="text" class="span4" value="07/07/12" data-date-format="dd/mm/yy" id="dp2" name="fecha_inicio">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Fecha Fin</label>
									<div class="controls">
										<input type="text" class="span4" value="07/07/12" data-date-format="dd/mm/yy" id="dp3" name="fecha_fin">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Precio</label>
									<div class="controls">
										<input type="text" class="input-xlarge" name="precio">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Duración Días</label>
									<div class="controls">
										<input type="text" class="input-xlarge" name="duracion_dias">
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<input type="submit" name="crear_evento" class="btn btn-primary btn-large" value="Crear Evento" />
									</div>
								</div>
							</fieldset>
							</form>
						</div><!--/span-->
					</div>
						<div class="tab-pane fade <?php echo $fade2?>" id="modificar"> <!--tab-pane fade modificar_evento-->
							<form class="form-horizontal" method="post">
								<fieldset>
									<div class="control-group">
										<label class="control-label">Seleccione un evento</label>
										<div class="controls">
											<select id="evento_seleccionado" class=span4 name="evento_selected">
												<?php
												$conn = oci_connect('dranzer','fabulousmax', 'localhost/XE');
												if($conn)
												{
													$query = "SELECT ID_EVENTO, NOMBRE FROM EVENTO";
													$stid = oci_parse($conn, $query);
													oci_define_by_name($stid, 'ID_EVENTO', $id_evento);
													oci_define_by_name($stid, 'NOMBRE', $n_evento);
													oci_execute($stid);
													while (oci_fetch($stid))
													{
														echo "<option> $id_evento</option>";
													}
												}
												oci_close($conn);
												?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<div class="controls">
											<input type="submit" name="seleccionar_evento" class="btn btn-primary btn-large" value="Seleccionar" />
										</div>
									</div>
								</fieldset>
							</form>
							<?php
								if(isset($_POST['seleccionar_evento']))
								{
									$_SESSION['tab']="modificar";
									$selected = $_POST['evento_selected'];
									$conn = oci_connect('dranzer','fabulousmax', 'localhost/XE');
									if($conn)
									{
										$query = "SELECT * FROM EVENTO WHERE ID_EVENTO ='$selected'";
										$stid = oci_parse($conn, $query);
										oci_execute($stid);
										if($row = oci_fetch_array($stid, OCI_BOTH))
		                                {
											//creamos campos y agregamos valores en ellos
											?>
											
											<div class="span8">
											<form class="form-horizontal" method="post">
											<fieldset>
												<div class="control-group">
													<div class="controls">
														<input type="text" class="input-xlarge invisible" name="iid_evento" value="<?php echo $row['ID_EVENTO']; ?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Nombre</label>
													<div class="controls">
														<input type="text" class="input-xlarge" name="inombre" value="<?php echo $row['NOMBRE']; ?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Tipo</label>
													<div class="controls">
														<select class="span4" name="itipo">
															<option <?php if($row['TIPO']=='Concierto') echo "selected";?>>Concierto</option>
															<option <?php if($row['TIPO']=='Conferencia') echo "selected";?>>Conferencia</option>
															<option <?php if($row['TIPO']=='Seminario') echo "selected";?>>Seminario</option>
															<option <?php if($row['TIPO']=='Charla') echo "selected";?>>Charla</option>
															<option <?php if($row['TIPO']=='Partido') echo "selected";?>>Partido</option>
															<option <?php if($row['TIPO']=='Pelicula') echo "selected";?>>Pelicula</option>
															<option <?php if($row['TIPO']=='Recital') echo "selected";?>>Recital</option>
														</select>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Num. Participantes</label>
													<div class="controls">
														<input type="text" class="input-xlarge" name="inum_participantes" value="<?php echo $row['NUM_PARTICIPANTES']; ?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Lim. Asistentes</label>
													<div class="controls">
														<input type="text" class="input-xlarge" name="ilim_asistentes" value="<?php echo $row['LIMITE_ASISTENTES']; ?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Fecha Inicio</label>
													<div class="controls">
														<input type="text" class="span4" value="<?php echo $row['FECHA_INICIO']; ?>" data-date-format="dd/mm/yy" id="dp2" name="ifecha_inicio">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Fecha Fin</label>
													<div class="controls">
														<input type="text" class="span4" value="<?php echo $row['FECHA_FIN']; ?>" data-date-format="dd/mm/yy" id="dp3" name="ifecha_fin">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Precio</label>
													<div class="controls">
														<input type="text" class="input-xlarge" name="iprecio" value="<?php echo $row['PRECIO']; ?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Habilitado</label>
													<div class="controls">
														<input type="text" class="input-xlarge" name="ihabilitado" value="<?php echo $row['HABILITADO']; ?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Duración Días</label>
													<div class="controls">
														<input type="text" class="input-xlarge" name="iduracion_dias" value="<?php echo $row['DURACION_DIAS']; ?>">
													</div>
												</div>
												<div class="control-group">
													<div class="controls">
														<input type="submit" name="commit" class="btn btn-primary btn-large" value="Modificar Evento" />
													</div>
												</div>
											</fieldset>
											</form>
											</div><!--/span-->
											
											<?php
											
										}
									}
									oci_free_statement($stid);
									oci_close($conn);
								}
							?>
						</div><!--tab-pane fade modificar_evento-->
					</div>
				</div>
			</div>
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
		<script src="../bootstrap/js/bootstrap-datepicker.js"></script>
		<script>
		$(function(){
			window.prettyPrint && prettyPrint();
			$('#dp1').datepicker({
				format: 'mm-dd-yyyy'
			});
			$('#dp2').datepicker();
			$('#dp3').datepicker();
			
			
			var startDate = new Date(2012,1,20);
			var endDate = new Date(2012,1,25);
			$('#dp4').datepicker()
				.on('changeDate', function(ev){
					if (ev.date.valueOf() > endDate.valueOf()){
						$('#alert').show().find('strong').text('The start date can not be greater then the end date');
					} else {
						$('#alert').hide();
						startDate = new Date(ev.date);
						$('#startDate').text($('#dp4').data('date'));
					}
					$('#dp4').datepicker('hide');
				});
			$('#dp5').datepicker()
				.on('changeDate', function(ev){
					if (ev.date.valueOf() < startDate.valueOf()){
						$('#alert').show().find('strong').text('The end date can not be less then the start date');
					} else {
						$('#alert').hide();
						endDate = new Date(ev.date);
						$('#endDate').text($('#dp5').data('date'));
					}
					$('#dp5').datepicker('hide');
				});
		});
	</script>
    </body>
</html>
