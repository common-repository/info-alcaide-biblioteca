<?php

/* 
 *  *************************************
 *           menu Principal biblioteca  
 *  *************************************
 */

function f_plg_info_alcaide_biblioteca_admin() {
  /*  $actual_link = "http://$_SERVER[HTTP_HOST]";
    $actual_link_page = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";	*/
    $info_alcaide_biblioteca_devolver="";
    $continuar='Si';

   // $info_alcaide_biblioteca_id_libro = $_GET["borrar_libro"];
    $info_alcaide_biblioteca_id_libro =sanitize_text_field($_GET["borrar_libro"]);
    $info_alcaide_biblioteca_link_admin =sanitize_text_field($_GET["link"]);
    $info_alcaide_biblioteca_id_libro=f_info_alcaide_biblioteca_limpiarCaracteres($info_alcaide_biblioteca_id_libro);
    if(is_numeric($info_alcaide_biblioteca_id_libro)) {
   //   $info_alcaide_biblioteca_devolver= '<H2><a href="' . $actual_link_page . '&borrar_libro_ok=' . $info_alcaide_biblioteca_id_libro . '">ESTÁ SEGURO QUE QUIERE BORRAR EL LIBRO DEFINITIVAMENTE</a></H2>';
        $info_alcaide_biblioteca_devolver= "<H2><a   href='{$info_alcaide_biblioteca_link_admin}&borrar_libro_ok={$info_alcaide_biblioteca_id_libro}'>ESTÁ SEGURO QUE QUIERE BORRAR EL LIBRO DEFINITIVAMENTE</a></H2>";
      
        $continuar='No';
 	 }


    //¿BORRAR LIBRO?
    if ($continuar=='Si'){
       // $info_alcaide_biblioteca_id_libro = $_GET["borrar_libro_ok"];
        $info_alcaide_biblioteca_id_libro =sanitize_text_field($_GET["borrar_libro_ok"]);
        $info_alcaide_biblioteca_id_libro=f_info_alcaide_biblioteca_limpiarCaracteres($info_alcaide_biblioteca_id_libro);
        if(is_numeric($info_alcaide_biblioteca_id_libro)) {
            $info_alcaide_biblioteca_devolver=f_info_alcaide_biblioteca_admin_borrarLibro($info_alcaide_biblioteca_id_libro);
            
        }
    }
    
     echo f_plg_info_alcaide_biblioteca_admin_cabecera();
        echo $info_alcaide_biblioteca_devolver;
    if ($continuar=='Si'){
      
        $sql="SELECT * FROM " .  info_alcaide_bdd_tabla_biblioteca ."   ORDER BY Titulo " ;
        echo f_info_alcaide_biblioteca_admin_listado($sql);
    }
}
 /* 
 *  *************************************
 *           submenu AGREGAR LIBRO
 *  *************************************
 */

function f_plg_info_alcaide_biblioteca_admin_submenu() {
    
    if (current_user_can('administrator')) {

        $info_alcaide_biblioteca_devolver=f_plg_info_alcaide_biblioteca_admin_cabecera();


        if (isset( $_POST["txtTitulo"])){
            $pTitulo =sanitize_text_field($_POST["txtTitulo"]);
            $pAutor = sanitize_text_field($_POST["txtAutor"]);  
            $pMateria = sanitize_text_field($_POST["txtMateria"]); 
            $pEdicion = sanitize_text_field($_POST["txtEdicion"]); 
            $pAnio = sanitize_text_field($_POST["txtAnio"]); 
            $pISBN = sanitize_text_field($_POST["txtISBN"]); 
            $pSignatura = sanitize_text_field($_POST["txtSignatura"]); 
            $pNpagina = sanitize_text_field($_POST["txtNpagina"]); 
            $pDescripcion = sanitize_text_field($_POST["txtDescripcion"]); 


            $info_alcaide_biblioteca_devolver=f_info_alcaide_biblioteca_admin_agregarlibro($pTitulo,$pAutor,$pMateria,$pEdicion,$pAnio ,$pISBN, $pSignatura,  $pNpagina, $pDescripcion);
        }
        
    

        //echo f_info_alcaide_biblioteca_cabeceraBootstrap();
        echo $info_alcaide_biblioteca_devolver;
        echo f_plg_info_alcaide_biblioteca_admin_formulario_nuevolibro();
    } 
  }


  
// agregar libro
function f_info_alcaide_biblioteca_admin_agregarlibro($pTitulo,$pAutor,$pMateria,$pEdicion,$pAnio ,$pISBN, $pSignatura,  $pNpagina, $pDescripcion){	
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";	
    $devolver ="Agregando Libro....";
    global $wpdb;  // Conectar bdd

    $sql="INSERT  INTO " .  info_alcaide_bdd_tabla_biblioteca ."  (titulo,autor,materias,edicion,anyo_publicacion,ISBN,sig,num_paginas,resumen) values ('" . $pTitulo . "','" . $pAutor . "','" . $pMateria . "','" . $pEdicion . "'
    ,'" . $pAnio . "','" . $pISBN . "','" . $pSignatura . "','" . $pNpagina . "','" . $pDescripcion . "')";

    $wpdb->query($sql);

    $devolver = "";

    if($wpdb->last_error !== '') {
        $str   = htmlspecialchars( $wpdb->last_result, ENT_QUOTES );
        $query = htmlspecialchars( $wpdb->last_query, ENT_QUOTES );

        $devolver= "<div id='error'>
        <p class='wpdberror'><strong>WordPress database error:</strong> [$str]<br />
        <code>$query</code></p>
        </div>";
    }    
 
    return  '<h2>BIBLIOTECA</h2><h3>¡ Agregado nuevo libro !</h3>' . $devolver;
}    

 // borrar libro
function f_info_alcaide_biblioteca_admin_borrarLibro($id){
  
    global $wpdb;  // Conectar bdd
    
    $sql="DELETE FROM " .  info_alcaide_bdd_tabla_biblioteca ."  WHERE id_libro = " . $id;
 
     $wpdb->query($sql);
    if($wpdb->last_error !== '') {
        $wpdb->print_error();
    } 

    return  '<h2>BIBLIOTECA</h2><h3>¡Libro Borrado!</h3>';
}

 /* 
 *  *************************************
 *           Funciónes PRINCIPALES
 *  *************************************
 */
function f_info_alcaide_biblioteca_flexbox(){
    return "
    <style>
        .info_alcaide_biblioteca_css_flex_container {
            display: flex;
            justify-content: flex-start;
        }

        .info_alcaide_biblioteca_css_flex_container > div {        
            margin: 10px;
  
            line-height: 40px;
            color: black;           
        }
        </style>
    ";
}
function f_plg_info_alcaide_biblioteca_admin_cabecera(){
    return "<h1>PLUGIN BIBLIOTECA</h1>
  
    <p>  
   <br>* Plugin URI: " . info_alcaide_biblioteca_uri . "
   <br>* Description: ". info_alcaide_biblioteca_title . "
   <br>* Version: " . info_alcaide_biblioteca_version . "
   <br>* Author: <a href='" . info_alcaide_biblioteca_author . "' target=_blank>" . info_alcaide_biblioteca_author . "<a></p>";
 
}


function f_plg_info_alcaide_biblioteca_admin_formulario_nuevolibro(){
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

return  "


<div class='container'>
<div class='panel panel-primary'><div class='panel-heading'>Nuevo Libro</div><div class='panel-body'>
<form action='$actual_link' method='post'>

    <div class='form-group row'>
        <label for='txtTitulo' class='col-sm-2 col-form-label'>Titulo:</label>
        <div class='col-sm-10'>
        <input type='text' name='txtTitulo' value='{$info_alcaide_biblioteca_autor}' class='form-control'>
    </div></div>

    <div class='form-group row'>
        <label for='txtAutor' class='col-sm-2 col-form-label'>Autor:</label>
        <div class='col-sm-10'>
        <input type='text' name='txtAutor' value='{$info_alcaide_biblioteca_autor}' class='form-control'>
    </div></div>

    <div class='form-group row'>
        <label for='txtMateria' class='col-sm-2 col-form-label'>Materia:</label>
        <div class='col-sm-6' >
            <select name='txtMateria' id='txtMateria' class='col-sm-12'>
            <option value='{$info_alcaide_biblioteca_materia}'  selected>{$info_alcaide_biblioteca_materia}</option>
            " . f_info_alcaide_biblioteca_comboMaterias() .  "
            </select>
        </div> 
       

        <label for='txtEdicion'  class='col-sm-1 col-form-label' >Edición:</label>  
        <div class='col-sm-1'><input type='text' name='txtEdicion' value='{$info_alcaide_biblioteca_autor}' class='form-control'>    </div>
       
        <label for='txtAnio' class='col-sm-1 col-form-label'>Año:</label> 
        <div class='col-sm-1'>                     
            <input type='text' name='txtAnio' value='{$info_alcaide_biblioteca_autor}' class='form-control'>
        </div>
    </div>

    <div class='form-group row'> 
        <label for='txtISBN' class='col-sm-2 col-form-label' >ISBN:</label> 
        <div class='col-sm-2'><input type='text' name='txtISBN' value='{$info_alcaide_biblioteca_autor}' class='form-control'>    </div>
        
        <label for='txtSignatura'  class='col-sm-2 col-form-label' >Nº de Signatura:</label>  
        <div class='col-sm-2'><input type='text' name='txtSignatura' value='{$info_alcaide_biblioteca_autor}' class='form-control'>    </div>
       
        <label for='txtNpagina' class='col-sm-2 col-form-label'>Nº de páginas:</label> 
        <div class='col-sm-2'>                     
            <input type='text' name='txtNpagina' value='{$info_alcaide_biblioteca_autor}' class='form-control'>
        </div>
    </div> 

    <div class='form-group row'>
    <label for='txtDescripcion' class='col-sm-2 col-form-label'>Descripción:</label>
    <div class='col-sm-10'>
    <textarea name='txtDescripcion' value='{$info_alcaide_biblioteca_autor}' class='form-control' rows='8'></textarea>
    </div></div>

    <div class='form-group row'>
			<label for='inputPassword' class='col-sm-2 col-form-label'></label>
			<div class='col-sm-10'>
		<button type='submit' class='btn btn-primary'>Añadir</button>
    </div></div>
        
</form></div></div></div>". f_info_alcaide_biblioteca_agregar_bootstrap();

}

// function only return 1 nodo
  function f_info_alcaide_biblioteca_admin_listado($sql){
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";	
	global $wpdb;  // Conectar bdd
	$info_alcaide_biblioteca_resultado = "Ningúna coincidencia encontrada";
	$info_alcaide_biblioteca_cont=0;
	 
	
	$info_alcaide_biblioteca_vars = $wpdb->get_results($sql );

	if ( null !== $info_alcaide_biblioteca_vars ) {
		
		$info_alcaide_biblioteca_resultado="";
		foreach ( $info_alcaide_biblioteca_vars as $info_alcaide_biblioteca_var1 ) 
		{			 
		  $info_alcaide_biblioteca_resultado = $info_alcaide_biblioteca_resultado . "<tr><td><a href='{$actual_link}&borrar_libro={$info_alcaide_biblioteca_var1->id_libro}&link={$actual_link}'>
			  <i class='material-icons' style='font-size:36px;color:#337ab7'>delete_forever</i></a></td>
			  <td>{$info_alcaide_biblioteca_var1->titulo} </td>
			  <td> {$info_alcaide_biblioteca_var1->autor}</td>
			  <td> {$info_alcaide_biblioteca_var1->materias}</td>
			  </tr>";
		  $info_alcaide_biblioteca_cont=$info_alcaide_biblioteca_cont+1;
		}
	
	} 
	
			$info_alcaide_biblioteca_resultado_cab =  "<div class='panel panel-primary'><div class='panel-heading'>Encontrados {$info_alcaide_biblioteca_cont}</div><div class='panel-body'> 
			<div class='table-responsive'><table class='table'> <thead>      <tr> <th></th><th>Título</th><th>Autor</th><th>Materia</th></tr>    </thead><tbody> ";
    
 
   return "<br>{$info_alcaide_biblioteca_resultado_cab} {$info_alcaide_biblioteca_resultado}" . f_info_alcaide_biblioteca_agregar_bootstrap() . "</tbody></table></div></div></div>"; 
}	