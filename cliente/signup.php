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
                    <div class="nav-collapse">
                        <ul class="nav">
                            <li><a href="../index.php">Inicio</a></li>
                            <li class="active"><a href="#">Registrarse</a></li>
                            <li><a href="#about">Acerca de</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>
        
        <?php 
                            if (isset($_POST['commit']))
                            {
                                $conn = oci_connect('dranzer','fabulousmax', 'localhost/XE');
                                if($conn)
                                {
                                    $nombre = $_POST['nombre'];
                                    $apellido = $_POST['apellido'];
                                    $cid = $_POST['cid'];
                                    $telefono = $_POST['telefono'];
                                    $direccion = $_POST['direccion'];
                                    $tarjeta_ref = $_POST['tarjeta_referencia'];
                                    $email = $_POST['email'];
                                    $usuario = $_POST['usuario'];
                                    $password = $_POST['password'];
                                    
                                    $query = "SELECT * FROM CLIENTE WHERE USUARIO = '".$usuario."'";
                                    $stid = oci_parse($conn, $query);
                                    oci_execute($stid);
                                    
                                    if($row = oci_fetch_array($stid, OCI_BOTH))
                                    {
                                        ?>
                                        <div class="alert alert-block alert-error fade in">
										<button type="button" class="close" data-dismiss="alert">×</button>
										<h4 class="alert-heading">¡Ups! Ha habido un error.</h4>
										<p>El usuario que ingresaste ya existe. Intenta con otro.</p>
										</div>
                                        <?php
                                    }
                                    else
                                    {
                                        $result = oci_parse($conn, "BEGIN PROC_INSERTAR_NUEVO_CLIENTE(
															'$nombre', 
															'$apellido',
															'$cid',
															'$telefono',
															'$direccion',
															'$tarjeta_ref',
															'$email',
															'$usuario',
															'$password'
															);
															end;"
															);
										$insertado=oci_execute($result);
										if ($insertado) 
										{
											session_start();
																$_SESSION['username']=$_POST['usuario'];
											?>
											<div class="alert alert-block alert-info fade in">
											<button type="button" class="close" data-dismiss="alert">×</button>
											<h4 class="alert-heading">¡Enhorabuena!.</h4>
											<p>Tu cuenta fue creada con éxito. En un momento te redireccionará al panel principal.</p>
											</div>
											<script type="text/javascript">
											var pagina = 'panel.php';
											var segundos = 4000;
											function redireccion() {
												document.location.href=pagina;
											}
											setTimeout("redireccion()",segundos);
											</script>
											<?php
										}
										else
										{
											?>
											<div class="alert alert-block alert-error fade in">
											<button type="button" class="close" data-dismiss="alert">×</button>
											<h4 class="alert-heading">¡Ups! Ha habido un error.</h4>
											<p>El registro no pudo ser ingresado a la base de datos.</p>
											</div>
											<?php
											
										}
                                    }
                                }else
                                {
									?>
                                        <div class="alert alert-block alert-error fade in">
										<button type="button" class="close" data-dismiss="alert">×</button>
										<h4 class="alert-heading">¡Ups! Ha habido un error.</h4>
										<p>Error de conexión. No se puede establecer comunicación con el servidor.</p>
										</div>
                                        <?php
                                }
                                
                                oci_free_statement($stid);
                                oci_close($conn);
                            }
                        ?>
        
        <div class="container">
            <div class="row-fluid">
                <div class="hero-unit">
            <div id='login'>
                <div class='block center login small'>
                    <div class='block_head'>
                        <div class='bheadl'></div>
                        <div class='bheadr'></div>
                        <h1>
                        Regístrate
                        </h1>
                    </div>
                    <div class='block_content'>
                        <h2>Crea tu cuenta gratuítamente</h2>
                        <p>Rellena los campos requeridos.</p>
                        <hr />
                        <form accept-charset="UTF-8" action="" class="new_user" id="new_user" method="post">
                            
                            <label>Nombre:</label>
                            <input class="span4" name="nombre" size="30" type="text" />
                            
                            <label>Apellido:</label>
                            <input class="span4" name="apellido" size="30" type="text" />
                            
                            <label>CID:</label>
                            <input class="span4" name="cid" size="30" type="text" />
                            
                            <label>Teléfono:</label>
                            <input class="span4" name="telefono" size="30" type="text" />
                            
                            <label>Dirección:</label>
                            <input class="span4" name="direccion" size="30" type="text" />
                            
                            <label>Tarjeta de Referencia:</label>
                            <input class="span4" name="tarjeta_referencia" size="30" type="text" />
                            
                            <label>E-mail:</label>
                            <input class="span4" name="email" size="30" type="text" />
                            
                            <label>Usuario:</label>
                            <input class="span4" name="usuario" size="30" type="text" />
                            
                            <label>Contraseña:</label>
                            <input class="span4" name="password" size="30" type="password" />
                            
                            <div class='form-actions'>
                                <input class="btn btn-primary btn-large" data-disable-with="Procesando..." name="commit" type="submit" value="Crear Cuenta" />
                            </div>
                            <p class='ar'>
                            <i class='icon-user'></i>
                            ¿Ya posees una cuenta?
                            <a href="../index.php">Inicia sesión aquí &rarr;</a>
                            </p>
                        </form>
                    </div>
                    <div class='bendl'></div>
                    <div class='bendr'></div>

                </div>

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

    </body>
</html>
