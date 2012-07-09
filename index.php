<?php
session_destroy();
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
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
        
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le fav and touch icons -->
        <link rel="shortcut icon" href="bootstrap/ico/favicon.ico">
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
                            <li><a href="cliente/signup.php">Registrarse</a></li>
                            <li><a href="#about">Acerca de</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>¡Bienvenid@ a TodoTicket.com!</h1>
                <p>El sitio donde puedes comprar entradas a <b>cualquier</b> evento: desde conferencias, ¡hasta grandes conciertos!</p>
            </div>
      
            <div class="hero-unit" align="center">
                <div class="content">
                    <div class="row">
                        <div class="login-form">
                            <h2>Iniciar sesión</h2>
                            <form name="" class="well" method="POST" action="index.php">
                                <fieldset>
                                    <div class="clearfix">
                                        <input type="text" name="username" placeholder="Usuario">
                                    </div>
                                    <div class="clearfix">
                                        <input type="password" name="password" placeholder="Contraseña">
                                    </div>
                                    <!--<button class="btn btn-primary btn-large" type="submit">Login</button>-->
                                    <input type="submit" name="submit_login" class="btn btn-primary btn-large" value="Login" />
                                </fieldset>
                            </form>
                        </div>
                        <?php 
                            if (isset($_POST['submit_login'])) {

                                $conn = oci_connect('dranzer','fabulousmax', 'localhost/XE');
                                if($conn)
                                {
                                    //echo "it works~";
                                    $user_prev = $_POST['username'];
                                    $pass_prev = $_POST['password'];
                                    
                                    $user = trim($user_prev);
                                    $pass = trim($pass_prev);
                                    
                                    if($user == "admin" && $pass == "admin")
                                    {
                                    	session_start();
                                    	$_SESSION['username']="admin";
                                    	?>
											<script type="text/javascript">
												window.location = "admin/panel_admin.php"
											</script>
										<?php
                                    }
                                    else
                                    {
		                                $query = "SELECT * FROM CLIENTE WHERE USUARIO = '".$user."'";
		                                $stid = oci_parse($conn, $query);
		                                oci_execute($stid);

		                                if($row = oci_fetch_array($stid, OCI_BOTH))
		                                {
											$id_cliente = $row['ID_CLIENTE'];
		                                    $dat_user = $row['USUARIO'];
		                                    $dat_pass = $row['PASSWORD'];
		                                    $dat_habilitado = $row['HABILITADO'];
		                                    $dat_num_intentos = $row['NUM_INTENTOS'];
		                                    //echo $dat_num_intentos;
		                                    //echo $dat_user."_".$dat_pass;
		                                    if($dat_pass ==  $pass)
		                                    {
												if($dat_habilitado==1 && $dat_num_intentos<3)
												{
													//echo "Hello ".$dat_user;
													session_start();
													$_SESSION['username']=$_POST['username'];
													$_SESSION['tipo_sesion']="cliente";

													?>
													<script type="text/javascript">
														window.location = "cliente/panel.php"
													</script>
													<?php
												}
												else
												{
													?>
													<div class="alert alert-block alert-error fade in">
														<button type="button" class="close" data-dismiss="alert">×</button>
														<h4 class="alert-heading">¡Ups! Ha habido un error.</h4>
														<p>Tu usuario está deshabilitado. Comunícate con el administrador del sitio para más información.</p>
													</div>
													<?php
												}
		                                    }else
		                                    {
		                                        ?>
												<div class="alert alert-block alert-error fade in">
													<button type="button" class="close" data-dismiss="alert">×</button>
													<h4 class="alert-heading">¡Ups! Ha habido un error.</h4>
													<p>La contraseña es incorrecta. Intenta de nuevo.</p>
												</div>
												<?php
												//aumentar en 1 la cantidad de veces fallidas de logueo
												$conn1 = oci_connect('dranzer','fabulousmax', 'localhost/XE');
												if($conn1)
												{
													$newnum = $dat_num_intentos + 1;
													//echo $newnum;
													$result1 = oci_parse($conn1, "BEGIN PROC_ACTUALIZAR_INTENTOS('$id_cliente','$newnum'); END;");
													$insertado1 = oci_execute($result1);
												}oci_close($conn1);
		                                    }
		                                }else
		                                {
		                                    ?>
		                                    <div class="alert alert-block alert-error fade in">
												<button type="button" class="close" data-dismiss="alert">×</button>
												<h4 class="alert-heading">¡Ups! Ha habido un error.</h4>
												<p>El usuario que ingresaste no existe. Intenta con otro.</p>
												</div>
		                                    <?php
		                                }
		                                oci_free_statement($stid);
		                                oci_close($conn);
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <hr>

            <footer>
                <p>&copy; TodoTicket.com 2012</p>
            </footer>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="bootstrap/js/jquery.js"></script>
        <script src="bootstrap/js/bootstrap-transition.js"></script>
        <script src="bootstrap/js/bootstrap-alert.js"></script>
        <script src="bootstrap/js/bootstrap-modal.js"></script>
        <script src="bootstrap/js/bootstrap-dropdown.js"></script>
        <script src="bootstrap/js/bootstrap-scrollspy.js"></script>
        <script src="bootstrap/js/bootstrap-tab.js"></script>
        <script src="bootstrap/js/bootstrap-tooltip.js"></script>
        <script src="bootstrap/js/bootstrap-popover.js"></script>
        <script src="bootstrap/js/bootstrap-button.js"></script>
        <script src="bootstrap/js/bootstrap-collapse.js"></script>
        <script src="bootstrap/js/bootstrap-carousel.js"></script>
        <script src="bootstrap/js/bootstrap-typeahead.js"></script>

    </body>
</html>

