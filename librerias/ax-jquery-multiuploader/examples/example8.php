<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Real AJAX Mutiple Upload</title>
	
	<meta name="keywords" content="ajax,upload,ajax upload, html5 upload" />
	<meta name="description" content="Ajax uploader" />
	<meta name="author" content="AlbanX" />


	<!-- SET UP AXUPLOADER  -->
	<script src="jslibs/jquery.js" type="text/javascript"></script>
	<script src="jslibs/ajaxupload.js" type="text/javascript"></script>
	
	<link rel="stylesheet" href="css/classicTheme/style.css" type="text/css" media="all" />
	<!-- /SET UP AXUPLOADER  -->
	
	<link rel="stylesheet" href="css/body.css" type="text/css" media="all" />
	
	
        <!--
        <link rel="stylesheet" href="css/shCore.css" type="text/css" media="all" />
        <link rel="stylesheet" href="css/shThemeEclipse.css" type="text/css" media="all" />
	<link rel="stylesheet" href="css/shCoreDefault.css" type="text/css"/>
        -->
        
        <!-- SET UP LECTOR DE CÓDIGO  
	<script src="jslibs/shCore.js" type="text/javascript"></script>
	<script src="jslibs/shBrushJScript.js"  type="text/javascript" ></script>
	<script src="jslibs/shBrushXml.js"  type="text/javascript" ></script>
        
	<script type="text/javascript">
	SyntaxHighlighter.all({toolbar:false});
	</script>
        <!-- SET UP LECTOR DE CÓDIGO  -->
        
</head>

<body>
    
    <h1>Real Ajax Multi Uploader</h1>

<h2>Examples 1</h2>
<table class="options">
<thead>
	<tr>
		<th>Carga de Archivos</th>
		<th>Listado de Archivos Cargados</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>
			<div id="demo1" style="width:500px"></div>
                        <div id="report" style="overflow:auto;width:300px;height:200px;"></div>
			<script type='text/javascript'>
			$("#demo1").ajaxupload({
				url:"upload.php",
                                allowExt:["png","gif","jpg","pdf","doc","docx","xls","xlsx","pps"],
				remotePath:"uploaded/",
                                finish:function(files)
                                    {
                                        alert("Todas las archivos han sido subidos");
                                    },
                                success:function(fileName)
                                    {
                                        $("#report").append("<p>"+fileName+" uploaded.</p>");
                                    }
			});
			</script>
		</td>

		<td>
                    <table>
                      <tr>
                        <td class="infsub">
                            <?php 
                            
                            $lista = lista_archivos();
                            echo $lista;
                            ?>	
                        </td>
                      </tr>
                    </table>
		</td>
	</tr>
</tbody>
</table>



</body>
</html>

<?php 
function lista_archivos(){
    $html="";
    if ($gestor = opendir('uploaded')) {
            //echo "<ul>";
        $html.="<ul>";
        while (false !== ($arch = readdir($gestor))) {
               if ($arch != "." && $arch != "..") {
                       //echo "<li><a href=\"uploaded/".$arch."\" class=\"linkli\">".$arch."</a></li>\n";
                       $html.="<li><a href=\"uploaded/".$arch."\" class=\"linkli\">".$arch."</a></li>\n";
               }
        }
        closedir($gestor);
            //echo "</ul>";
        $html.="</ul>";
    }
    
    return $html;
}
?>	
