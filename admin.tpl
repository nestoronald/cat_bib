<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
<head>
<link href="img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BIBLIOTECA - ADMIN</title>
    <!--     Boostrap de twitter -->
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap-responsive.min.css">

    <!-- Framework CSS -->
    <!-- <link href="css/estilos.css" rel="stylesheet" type="text/css" /> -->

    <link href="css/ui-lightness/jquery-ui-1.8.17.custom.css" rel="Stylesheet" type="text/css" />

    <link rel="stylesheet" href="css/blueprint/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="css/blueprint/print.css" type="text/css" media="print">


    <!-- Import fancy-type plugin for the sample page. -->
    <link rel="stylesheet" href="css/blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection">
    <script type="text/javascript" src="js/jquery-1.7.2.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery.maskedinput-1.2.2-co.min.js"></script>
    <script src="js/js_DataTables1.9.4/jquery.dataTables.min.js" language="javascript" type="text/javascript"></script>

    <script type="text/javascript" language="javascript" src="js/jquery.dropdownPlain.js"></script>
    <!-- <link rel="stylesheet" href="css/style_dropdowns.css" type="text/css" media="screen, projection"/> -->
    <link rel="stylesheet" href="css/style.css" type="text/css">



    <script src="js/jquery.si.js" type="text/javascript"></script>

    <link href="css/css_DataTables1.9.4/demo_table_jui.css" rel="stylesheet" type="text/css" />
    <!--Estilo del botón-->


    <script type="text/javascript" src="js/jquery-ui-datepicker-es.js"></script>
    <script type="text/javascript" src="js/prueba.js"></script>


    <script type="text/javascript" src="js/jquery.gdocsviewer.js"></script>

    <script src="js/jquery.jclock_es.js" type="text/javascript"></script>
	<!-- SET UP AXUPLOADER  -->
	<script src="librerias/ax-jquery-multiuploader/examples/jslibs/ajaxupload.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/biblio.min.js"></script>

	<link rel="stylesheet" href="librerias/ax-jquery-multiuploader/examples/css/classicTheme/style.css" type="text/css" media="all" />
    {$xajax}
</head>


<body onload="xajax_inicio(); Javascript:history.go(1); "  onunload="Javascript:history.go(1);">

<div id="form" name="form"></div>

    <div class = "container main-bibblio">

	    <div id="header" class="cabecera">
            <div class="row">
	    		<div class="span5"><br><img src="img/logo-minan-igp_2012.png"></div>

	    		<div class="span2 offset5"><img src="img/igp-trans.png"></div>
            </div>

            <div class="container main-menu">

                    <div class="navbar navbar-inverse">
                        <div class="navbar-inner">
                            <button type="button" class="btn btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
                                <span class="caret"></span>
                               <span>Menu</span>
                            </button>
                            <div class="nav-collapse collapse">
                                <ul id="menu" class=" nav"></ul>
                                <ul class="navigp" id="menu_rigth"></ul>
                            </div>
                        </div>
                    </div>


			</div>
                        <div id="saludo"></div>

        </div>
        <div class="container main-title">
                            <div class="row" >

                                        <div class="span12">
                                            <div class="container-fluid"> <h1 class="cblanco fcenter"> Catálogo Virtual - Biblioteca Central</h2></div>
                                        </div>

                            </div>
        </div>
        <div class="container div-login-main">


                <div id="divformlogin"  name="formlogin">

                </div>
	    </div>
	      <hr class="space">

	      <div class="last container-fluid">
                <div class="row-fluid">

                    <div class=" span12">
                                <hr class="space">
                                <div id="imghome">
                                    <table>
                                        <tr>
                                            <td style="text-align: center;">
                                            <img src="img/biblioteca.png" />
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            <div id="author_section"></div>
                            <div id="dictionary_section"></div>
                            <div id="paginatorAuthor"></div>
                            <div id="ListReserva"></div>
                            <div id="DivReserva" style="display:none">
                                <div class="block">
                                    <h3>Lista de reserva</h3>
                                    <a href="#" onclick="xajax_FormReservar(); return false;" title="Click aqui para procesar su reserva" class="btn">Reservar</a>
                                </div>
                            </div>
                            <div id="option_category"></div>
                            <div id="formulario"></div>
                            <div id="searchCat" nam="searchCat"></div>
                            <div id="consultas" class="row-fluid"></div>
                            <div id="resultSearch1"></div>
                            <div class="paginacion">
                                <div id="paginator" class="wp-pagenavi"></div>
                            </div>
                            <div id="conte_details"></div>
                            <div id="about_admin"></div>
                            {block name=content}{/block}
                    </div>
                </div>
        </div>
        <div class="container">
            <div class="row">
             <div class="span12 contenedor-pie">
                          <br>
                          <p> © 2014 Instituto Geofísico del Perú
                          </p><br>
             </div>
             </div>
        </div>

</div>
<script src="./bootstrap/js/bootstrap.min.js"></script>
<!-- <script src="./bootstrap/js/bootstrap-collapse.js"></script>

<script src="./bootstrap/js/bootstrap-popover.js"></script> -->
<!-- <script src="./bootstrap/js/bootstrap-tooltip.js"></script> -->
<script src="./bootstrap/js/typeahead.js"></script>
<script src="./bootstrap/js/modal.js"></script>
<script src="./bootstrap/js/button.js"></script>
<!-- <script src="./bootstrap/js/bootstrap-scrollspy.js"></script> -->
</body>
</html>
