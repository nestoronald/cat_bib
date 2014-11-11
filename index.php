<?php

	require ('../class/ClassCombo.php');
	require ('../class/ClassForm.php');
	require ('../class/ClassPaginado.php');
    require ('../class/ClassPaginator.php');
	require ('../class/dbconnect.php');
	require ('../class/xajax_core/xajax.inc.php');
    require ('../class/smarty/Smarty.class.php');
	$xajax=new xajax();
	$xajax->configure('javascript URI', 'js/');
 	date_default_timezone_set('America/Lima');

	require("indexSearch.php");
	// require("indexStatistics.php");

	//Ejecutamos el modelo
    require("adminModel.php");

	session_start();

    function ini(){
        $objResponse     = new xajaxResponse();
        $_SESSION["origin"]="frond";
        $objResponse    ->script("
                            xajax_searchCategory('frond')");

        $objResponse   ->script("$('[rel=propover]').popover({
                                        animation : 0.05 ,
                                        placement : 'top',
                                        trigger: 'hover',
                                        title:'Contáctenos',
                                        html:'true',
                                        content :\"Cualquier consulta escribanos a <span class='emailigp'>web@igp.gob.pe</span>\"
                                        });
                                ");
        // $objResponse->alert(print_r($_SESSION,true));
        return $objResponse   ;
    }
    function menu_main(){
        $objResponse   = new xajaxResponse();
        // $menu.="<li><a href='#' onclick='xajax_formConsultaShow(\"$idfrom\",\"admin\",\"$idarea\"); return false;' ><img width='12px;' style='vertical-align:middle;' src='img/iconos/search_16.png' /> Consultas</a></li>";
        $menu.="<li><a href='#Catalogo-busqueda' onclick='xajax_searchCategory(); return false;' > Inicio</a></li>
                <!--li><a href='#reserva' onclick='xajax_Reservation($idbook); return false;' > Lista temporal</a></li-->
                <li><a href='#reserva' onclick='xajax_abstractShow(\"searchCat\"); xajax_abstractShow(\"consultas\"); xajax_formConsultaShow(\"\",\"admin\",\"\",\"b_libros\"); return false;' > Consulta</a></li>
                <li><a href='#help' onclick='xajax_help(); return false;' > Ayuda</a></li>";

        $objResponse   ->assign("menu", "innerHTML", $menu);
        return $objResponse   ;
    }
    // function languaje_details($val_idioma){
    //     $diccionary = file_get_contents("js/diccionary.json");
    //     $diccionary_a = json_decode($diccionary,TRUE);
    //     foreach ($diccionary_a["languaje"] as $id => $languaje) {
    //        if (eregi($id, $val_idioma)) {
    //           $html = $languaje;
    //        }
    //     }
    //     $html = (trim($html)!="")?$html:$val_idioma;
    //     return $html;
    // }
    // function show_details($idbook=0){
    //     $objResponse   = new xajaxResponse();
    //     // $objResponse->alert(print_r($_SESSION["r_count"],TRUE));
    //     $result=searchPublication_iddataSQL(0,"",$idbook);

    //     $diccionary = file_get_contents("js/diccionary.json");
    //     $diccionary_a = json_decode($diccionary,TRUE);

    //     $data_array = xmlToArray($result["book_data"][0]);
    //     $objResponse->alert(print_r($data_array,true));

    //     $data_oculta = ["tipo","NumIng","FxIng","NumLC","Class_IGP","Catalogador","ModAdqui","NumDewey","UbicFis","NumEjem"];
    //     $html_details=""; $html_img="";
    //     foreach ($diccionary_a["campos"] as $key => $value) {
    //         if (isset($data_array[$key])) {

    //             if (!in_array($key, $data_oculta)) {
    //                 if (is_array($data_array[$key])) {
    //                     if($key=="authorPRI") {
    //                        $idauthor = $data_array["authorPRI"]["idauthor0"];
    //                        $result_author = searchAuthorID($idauthor);
    //                        $html_details .= "<p class='container-fluid'><span class='block_2'><b>".$value."</b></span> :
    //                                             <a href='#' title='Verifique si el author tiene mas registro bibliográficos' id='author_details'>
    //                                             ".$result_author["author_surname"][0].", ".$result_author["author_name"][0]."
    //                                             <input type='hidden' value=$idauthor  name='idauthor'/>
    //                                             </a>
    //                                         </p>";
    //                     }
    //                     elseif ($key=="state") {
    //                         foreach ($data_array[$key] as $id_state => $state) {
    //                             // $html_state[$id_state] = ($state==100 and ($state!=1 or $state!=2))?"Disponible":"No disponible";
    //                             if ($state==1 or $state==2) {
    //                                 $html_state[$id_state]="No Disponible";
    //                             }else{
    //                                 $html_state[$id_state] =  "Disponible";
    //                             }
    //                             // $html_state[$id_state] = ($state==1 or $state==2)?"No Disponible":"Disponible";
    //                         }
    //                     }
    //                     else{
    //                         $html_details .= "<p class='container-fluid'><span class='block_2'><b>".$value."</b></span> : ";
    //                         $h=1;
    //                         $j=1;
    //                         foreach ($data_array[$key] as $key1 => $value1) {
    //                             if ($key=="languaje") {
    //                                 $html_details .= languaje_details($data_array[$key][$key1]);
    //                                 $html_details .=(count($data_array[$key])>$h)?", ":"";
    //                                 $h++;
    //                             }
    //                             else{
    //                                 if ($key =="Theme") {

    //                                     foreach ($data_array[$key][$key1] as $key2 => $value2) {
    //                                         if ($key2=="detalle") {
    //                                             $html_details .= $data_array[$key][$key1][$key2]." ";
    //                                             $html_details .=(count($data_array[$key][$key1])>$j)?", ".count($data_array[$key][$key1])." ":"";
    //                                             // if (count($data_array[$key][$key1])>$j) {
    //                                             //    $html_details .= ", ";
    //                                             // }
    //                                             $j++;
    //                                         }
    //                                     }

    //                                 }
    //                                 else{
    //                                     $html_details .= $data_array[$key][$key1]." ";
    //                                 }

    //                             }

    //                         }
    //                         $html_details .="</p>";
    //                     }

    //                 }
    //                 else{
    //                     // $html_details .= "<p class='container-fluid'><span class='block_2'><b>".$value."</b></span> : ";

    //                     if ($key=="state") {
    //                         // $html_state .= "<p class='center'>Estado: <span class='state'>";
    //                         // $html_state_1 .= ($state==100 and ($state!=1 or $state!=2))?"Disponible":"No disponible";
    //                         $html_state_1 = ($data_array[$key]==1 or $data_array[$key]==2)?"No Disponible":"Disponible";
    //                         // if ($data_array[$key]==1 or $data_array[$key]==2) {
    //                         //     $html_state_1="No Disponible";
    //                         // }else{
    //                         //     $html_state_1 =  "Disponible";
    //                         // }
    //                         // $html_state .= "</p>";
    //                     }
    //                     elseif ($key=="languaje") {
    //                         $html_details .= "<p class='container-fluid'><span class='block_2'><b>".$value."</b></span> : ";
    //                         $html_details .= languaje_details($data_array[$key]);
    //                         $html_details .= " </p>";
    //                     }
    //                     elseif ($key=="ax_files") {
    //                         $nombre_fichero = "files/uploaded/".$data_array[$key];
    //                         if (file_exists($nombre_fichero)) {
    //                             $html_img ="<div id='d-img' class='cell'><img src='".$nombre_fichero."' /></div>";
    //                         }
    //                     }

    //                     else{
    //                         $html_details .= "<p class='container-fluid'><span class='block_2'><b>".$value."</b></span> : ";
    //                         $html_details .= $data_array[$key];
    //                         $html_details .= " </p>";
    //                     }

    //                 }
    //             }
    //         }
    //     }

    //     $html = "<div class='divDetails'>
    //                 <span id='type'><h3>".$data_array["tipo"]."</h3></span>
    //                 <span class='fright'><a href='#' class='txt-back' onclick='xajax_abstractShow(\"consultas\"); xajax_abstractShow(\"divformSearch\"); xajax_abstractHide(\"conte_details\"); xajax_abstractHide(\"resultSearch1\"); xajax_abstractHide(\"paginator\"); '> </a></span>
    //                 <h3 class='txt-azul'> ".$data_array["title"]."</h3>
    //                 <div clas='d-conte'>
    //                     <div id='d-info' class='cell'>".$html_details."</div>
    //                     ".$html_img."
    //                 </div>
    //                 <table class='table table-striped table-bordered'>
    //                     <thead>
    //                         <tr>
    //                             <th>N° de Clasificación Dewey</th>
    //                             <th>Localización</th>
    //                             <th>Ejemplares </th>
    //                             <th>Estado</th>
    //                         </tr>
    //                     </thead>
    //                     <tbody>";
    //     $state_int = "<tr>
    //                     <td>".$data_array["NumDewey"]."</td>
    //                     <td>".$data_array["UbicFis"]."</td>
    //                     <td>1</td>
    //                     <td>".$html_state_1."</td>
    //                 </tr>";
    //     if (isset($data_array["NumEjem"])) {
    //         if (is_array($data_array["state"])) {
    //             for ($i=0; $i < $data_array["NumEjem"]; $i++) {
    //                 $html .="<tr>
    //                         <td>".$data_array["NumDewey"]." - Ejm".($i+1)."</td>
    //                         <td>".$data_array["UbicFis"]."</td>
    //                         <td>1</td>
    //                         <td>".$html_state[$i]."</td>
    //                     </tr>";
    //             }
    //         }
    //         else{
    //             $html .=$state_int;
    //         }
    //     }
    //     else{
    //         $html .=$state_int;
    //     }

    //     $html .= "
    //                     <tbody>
    //                 </table>
    //                 <div class='plusReserva'><a onclick='xajax_Reservation(".$idbook."); return false;' href='#' class='reseva'><i class='icon-plus'></i>Reservar</a></div>
    //                <!--div class='nav'>";
    //                // if ($idbook!=1) {
    //                //    $html .= "<a href='#' onclick='xajax_show_details(".($idbook-1)."); return false;' class='btn small'>Anterior</a>";
    //                // }
    //                // $html .="<a href='#' onclick='xajax_show_details(".($idbook+1)."); return false;' class='btn small fright'>Siguiente</a>
    //                // </div>
    //      $html .=" </div>";
    //     // $objResponse->alert(print_r($data_array,TRUE));

    //     $objResponse->assign('paginator','style.display','none');
    //     $objResponse->assign('resultSearch1','style.display','none');
    //     $objResponse->assign('conte_details','style.display','block');
    //     $objResponse->assign("conte_details", "innerHTML", $html);
    //     $resultAuthor = searchBookSQL($form, $currentPage, $pageSize,$idauthor);
    //     if (isset($idauthor)) {
    //         if ($resultAuthor["Count"]>1) {

    //             $objResponse->script('
    //                     $("#author_details").click(function() {
    //                         xajax_searchPublicationShow(xajax.getFormValues(\'formSearch\'),\'2\',\'1\',\'20\',\'0\','.$idauthor.','.$idbook.');
    //                         $("#conte_details").html("");
    //                     }).append("<span class=\'count\'> (+'.($resultAuthor["Count"]-1).')</span>")
    //                     .attr("title","Busque mas registros bibliográficos de este author");
    //                 ');
    //         }
    //         else{
    //             $objResponse->script('$("#author_details").attr("title","Este author no tiene mas registros bibliograficos")');
    //         }
    //     }
    //     $objResponse->script('
    //                         $(".block_2").each(function(index){
    //                             a=$(this).parents("p").height();
    //                             if (a>80) {
    //                                  a=a+21;
    //                                  if (a>138) {
    //                                      a=a+41;
    //                                  }
    //                              }
    //                             $(this).css("min-height",a+"px")
    //                         });

    //                         ');
    //     return $objResponse;
    // }

    $xajax->registerFunction('ini');
    $xajax->registerFunction('menu_main');
    // $xajax->registerFunction('show_details');

	$xajax->processRequest();

	//Mostramos la pagina
	// require("indexView.php");
    $smarty = new Smarty;
    $smarty->assign("xajax",$xajax->printJavascript());
    $smarty->display('index.tpl');

?>