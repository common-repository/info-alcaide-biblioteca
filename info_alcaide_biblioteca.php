<?php

/**
 * Plugin Name: Biblioteca Alcaide
 * Plugin URI: https://www.alcaide.info
 * Description: Library plugin
 * Version: 20.1.6
 * Author: Angel Alcaide
 * Author URI: https://www.alcaide.info
 */ 

 /* 
 *  *************************************  ************************************* 
 *           Varibles globales de las Tablas MySQL
 *  *************************************  *************************************
 */

global $wpdb;
 
define('info_alcaide_bdd_tabla_biblioteca_materias',$wpdb->prefix . 'info_alcaide_biblioteca_tabla_materias');
define('info_alcaide_bdd_tabla_biblioteca',$wpdb->prefix . 'info_alcaide_biblioteca');
define('info_alcaide_biblioteca_plugin_name', 'info-alcaide-biblioteca');
define('info_alcaide_biblioteca_version', '20.1.6');
define('info_alcaide_biblioteca_author', 'Angel Alcaide');
define('info_alcaide_biblioteca_title', 'Biblioteca Alcaide');
define('info_alcaide_biblioteca_uri', 'https://www.alcaide.info');






 /* 
 *  *************************************  ************************************* 
 *           funciones instalación
 *  *************************************  *************************************
 */
function f_info_alcaide_biblioteca_installer() {
	require_once('admin/info_alcaide_biblioteca_installer.php');
	f_info_alcaide_biblioteca_instalando();
}


register_activation_hook( __file__, 'f_info_alcaide_biblioteca_installer' );

/* 
 *  *************************************  ************************************* 
 *           funciones shortcode y ficheros auxiliares
 *  *************************************  *************************************
 */

require_once('admin/info_alcaide_biblioteca_admin.php');
require_once('admin/info_alcaide_biblioteca_admin_materias.php');
add_shortcode('plg_info_alcaide_biblioteca', 'f_plg_info_alcaide_biblioteca');


/* 
 *  *************************************
 *           Función ADMINISTRADOR
 *  *************************************
 */

add_action("admin_menu", "plg_info_alcaide_biblioteca_admin");

function plg_info_alcaide_biblioteca_admin() {

	if (current_user_can('administrator')) {
	// 1º grupo de opciones, puede ser una descripción del plugin
	// parámetro 1º titulo en la url del navegador
	// parámetro 2º titulo del menu en la barra administración
	// parámetro 3º 
	// parámetro 4º otros submenús
	// parámetro 5º función que escribe la página del menú actual
	// parámetro 1º 


 /* add_menu_page('My Custom Page', 'My Custom Page', 'manage_options', 'f_plg_info_alcaide_biblioteca_admin');
add_submenu_page( 'f_plg_info_alcaide_biblioteca_admin', 'My Custom Page'        , 'My Custom Page'        ,    'manage_options', 'f_plg_info_alcaide_biblioteca_admin');
add_submenu_page( 'my-top-level-slug', 'My Custom Submenu Page', 'My Custom Submenu Page',    'manage_options', 'my-secondary-slug');

	add_menu_page('Biblioteca', 'Biblioteca', 'manage_options', 'f_plg_info_alcaide_biblioteca_admin_submenu', 'f_plg_info_alcaide_biblioteca_admin');
	 
	//  2º grupo de opciones la primera función de verdad
	add_submenu_page('f_plg_info_alcaide_biblioteca_admin_submenu', 'Agregar Libros', 'Agregar Libro', 'manage_options', 'info_alcaide_biblioteca_admin_submenu', 'f_plg_info_alcaide_biblioteca_admin_submenu');
	//  2º grupo de opciones la primera función de verdad
	add_submenu_page('f_plg_info_alcaide_biblioteca_admin_submenu', 'Agregar Materias', 'Agregar Materia', 'manage_options', 'info_alcaide_biblioteca_admin_submenu', 'f_plg_info_alcaide_biblioteca_admin_materias');

 */

 	add_menu_page('Biblioteca', 'Biblioteca', 'manage_options', 'submenu', 'f_plg_info_alcaide_biblioteca_admin');
	add_submenu_page('submenu', 'Agregar Libros', 'Agregar Libro', 'manage_options', 'submenu2', 'f_plg_info_alcaide_biblioteca_admin_submenu');
	add_submenu_page('submenu', 'Agregar Materias', 'Agregar Materia', 'manage_options', 'submenu3', 'f_plg_info_alcaide_biblioteca_admin_materias');
	 
	 


}

}

/* 
 *  *************************************
 *           Función ESTILOS Y JAVASCRIPT
 *  *************************************
 */
add_action( 'wp_enqueue_scripts', 'f_info_alcaide_biblioteca_cabeceraBootstrap' );

  /* 
 *  *************************************
 *           Función PRINCIPAL
 *  *************************************
 */
 
 function f_plg_info_alcaide_biblioteca(){
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $info_alcaide_biblioteca_titulo =sanitize_text_field($_POST["txttitulo"]);
    $info_alcaide_biblioteca_autor = sanitize_text_field($_POST["txtautor"]);
	$info_alcaide_biblioteca_materia = sanitize_text_field($_POST["cmbmateria"]);
	$info_alcaide_biblioteca_id_libro =sanitize_text_field($_GET["id_libro"]);
 
	$info_alcaide_biblioteca_titulo=f_info_alcaide_biblioteca_limpiarCaracteres($info_alcaide_biblioteca_titulo);
	$info_alcaide_biblioteca_autor=f_info_alcaide_biblioteca_limpiarCaracteres($info_alcaide_biblioteca_autor);
	$info_alcaide_biblioteca_materia=f_info_alcaide_biblioteca_limpiarCaracteres($info_alcaide_biblioteca_materia);
	$info_alcaide_biblioteca_id_libro=f_info_alcaide_biblioteca_limpiarCaracteres($info_alcaide_biblioteca_id_libro);
	
	//$info_alcaide_biblioteca_devolver="";
	 if(($info_alcaide_biblioteca_titulo!="")||($info_alcaide_biblioteca_autor!="")||($info_alcaide_biblioteca_materia!="")){
		$info_alcaide_biblioteca_devolver=f_info_alcaide_biblioteca_buscarporfiltros($info_alcaide_biblioteca_titulo,$info_alcaide_biblioteca_autor,$info_alcaide_biblioteca_materia);
	 }
	 if (empty($info_alcaide_biblioteca_devolver)){
		 if(is_numeric($info_alcaide_biblioteca_id_libro)) {
		$info_alcaide_biblioteca_devolver=f_info_alcaide_biblioteca_buscarporId($info_alcaide_biblioteca_id_libro);
	 }
	}

 if (empty($info_alcaide_biblioteca_devolver)){
		$info_alcaide_biblioteca_devolver=f_info_alcaide_biblioteca_buscarporId(0);
	 }
	
	return $info_alcaide_biblioteca_devolver;
	
	}
	
// function only return 1 nodo
function f_info_alcaide_biblioteca_buscarporId($info_alcaide_biblioteca_id_libro){	
	global $wpdb;  // Conectar bdd
	$info_alcaide_biblioteca_resultado = "Ningúna coincidencia encontrada";
	
	$sql="SELECT * FROM " .  info_alcaide_bdd_tabla_biblioteca ." where id_libro = " . $info_alcaide_biblioteca_id_libro;
	$info_alcaide_biblioteca_vars = $wpdb->get_results($sql );
	if ( null !== $info_alcaide_biblioteca_vars ) {
		
		$info_alcaide_biblioteca_resultado="";
		foreach ( $info_alcaide_biblioteca_vars as $info_alcaide_biblioteca_var1 ) 
		{
			if ( null !== $info_alcaide_biblioteca_var1->edicion ) {$edicion=" - Edición: " . $info_alcaide_biblioteca_var1->edicion;}
			if ( null !== $info_alcaide_biblioteca_var1->anyo_publicacion ) {$anyo_publicacion="  - Año Publicación: " . $info_alcaide_biblioteca_var1->anyo_publicacion;}
			if ( null !== $info_alcaide_biblioteca_var1->sig ) {$sig=" -  Signatura: " . $info_alcaide_biblioteca_var1->sig;}
			if ( null !== $info_alcaide_biblioteca_var1->num_paginas ) {$paginas=" - Número de páginas: " . $info_alcaide_biblioteca_var1->num_paginas;}
			if ( null !== $info_alcaide_biblioteca_var1->resumen ) {$resumen=$info_alcaide_biblioteca_var1->resumen;}		
			
			$info_alcaide_biblioteca_resultado="<div class='panel panel-primary'><div class='panel-heading'>ISBN:{$info_alcaide_biblioteca_var1->ISBN}</div><div class='panel-body'> 
				<div class='table-responsive'><table class='table'>
				Titulo: <b>{$info_alcaide_biblioteca_var1->titulo}</b> <br> 
				Autor: <b>{$info_alcaide_biblioteca_var1->autor} </b><br>
				Materia: <b>{$info_alcaide_biblioteca_var1->materias}</b>{$edicion}{$anyo_publicacion}{$sig}{$paginas}<br>
				<br> {$resumen}  
				</table></div></div></div>
				";
		}
	} 
	$info_alcaide_biblioteca_formulario=f_info_alcaide_biblioteca_formulario("","","");
	return  "{$info_alcaide_biblioteca_formulario}<br>{$info_alcaide_biblioteca_resultado}";
}	

// function return multiple nodo
function f_info_alcaide_biblioteca_buscarporfiltros($info_alcaide_biblioteca_titulo,$info_alcaide_biblioteca_autor,$info_alcaide_biblioteca_materia){	
	$coma="";
	$where="";
	$vsql_titulo="";
	$vsql_autor="";
	$vsql_materia="";

	if ($info_alcaide_biblioteca_titulo!="") {
		$vsql_titulo=$coma . "titulo like '%" . $info_alcaide_biblioteca_titulo . "%'";
		$coma=" or ";
		$where=" WHERE ";
	}
	if ($info_alcaide_biblioteca_autor!="") {
		$vsql_autor=$coma . "autor like '%" . $info_alcaide_biblioteca_autor . "%'";
		$coma=" or ";
		$where=" WHERE ";
	}
	if ( $info_alcaide_biblioteca_materia!="")   {
		$vsql_materia=$coma . "materias like '%" . $info_alcaide_biblioteca_materia . "%'";
		$coma=" or ";
		$where=" WHERE ";
	}
if ( $coma!="")   {
	global $wpdb;  // Conectar bdd
	$info_alcaide_biblioteca_resultado = "Ningúna coincidencia encontrada";
	$info_alcaide_biblioteca_cont=0;
	 
	$sql="SELECT * FROM " .  info_alcaide_bdd_tabla_biblioteca   . $where
		. $vsql_titulo .   $vsql_autor  . $vsql_materia 
		. " ORDER BY Titulo " ;
	$info_alcaide_biblioteca_vars = $wpdb->get_results($sql );

	if ( null !== $info_alcaide_biblioteca_vars ) {
		
		$info_alcaide_biblioteca_resultado="";
		foreach ( $info_alcaide_biblioteca_vars as $info_alcaide_biblioteca_var1 ) 
		{			 
		  $info_alcaide_biblioteca_resultado = $info_alcaide_biblioteca_resultado . "<tr><td><a href='{$actual_link}?id_libro={$info_alcaide_biblioteca_var1->id_libro}'>
			  <i class='material-icons' style='font-size:36px;color:#337ab7'>find_in_page</i></a></td>
			  <td>{$info_alcaide_biblioteca_var1->titulo} </td>
			  <td> {$info_alcaide_biblioteca_var1->autor}</td>
			  <td> {$info_alcaide_biblioteca_var1->materias}</td>
			  </tr>";
		  $info_alcaide_biblioteca_cont=$info_alcaide_biblioteca_cont+1;
		}
	
	} 
	
			$info_alcaide_biblioteca_resultado_cab =  "<div class='panel panel-primary'><div class='panel-heading'>Encontrados {$info_alcaide_biblioteca_cont}</div><div class='panel-body'> 
			<div class='table-responsive'><table class='table'> <thead>      <tr> <th></th><th>Título</th><th>Autor</th><th>Materia</th></tr>    </thead><tbody> ";
}
	$info_alcaide_biblioteca_formulario=f_info_alcaide_biblioteca_formulario($info_alcaide_biblioteca_titulo,$info_alcaide_biblioteca_autor,$info_alcaide_biblioteca_materia);
	return "{$info_alcaide_biblioteca_formulario}<br>{$info_alcaide_biblioteca_resultado_cab} {$info_alcaide_biblioteca_resultado}</tbody></table></div></div></div>"; 
}


 
function f_info_alcaide_biblioteca_formulario($info_alcaide_biblioteca_titulo,$info_alcaide_biblioteca_autor,$info_alcaide_biblioteca_materia){
	$actual_link = site_url(). "$_SERVER[REQUEST_URI]";
	return  "
 <div class='panel panel-primary'><div class='panel-heading'>Filtros</div><div class='panel-body'>
	<form action='$actual_link' method='post'>
		<div class='form-group row'>
			<label for='inputPassword' class='col-sm-2 col-form-label'>Titulo:</label>
			<div class='col-sm-10'>
			<input type='text' name='txttitulo' value='{$info_alcaide_biblioteca_titulo}' class='form-control'>
		</div></div>

		<div class='form-group row'>
			<label for='txtautor' class='col-sm-2 col-form-label'>Autor:</label>
			<div class='col-sm-10'>
			<input type='text' name='txtautor' value='{$info_alcaide_biblioteca_autor}' class='form-control'>
		</div></div>
	
		<div class='form-group row'>
			<label for='cmbmateria' class='col-sm-2 col-form-label'>Materia:</label>
			<div class='col-sm-10'>
			<select name='cmbmateria' id='cmbmateria' >
			<option value='{$info_alcaide_biblioteca_materia}'  selected>{$info_alcaide_biblioteca_materia}</option>
			 " . f_info_alcaide_biblioteca_comboMaterias() .  "
			</select>
		</div></div>

		<div class='form-group row'>
			<label for='inputPassword' class='col-sm-2 col-form-label'></label>
			<div class='col-sm-10'>
		<button type='submit' class='btn btn-primary'>Buscar</button>
		</div></div>

	</form></div></div>
		
	" . f_info_alcaide_biblioteca_agregar_bootstrap();

}

/*****************************************************
**														
**		Funciones combo de materias					
**													
******************************************************/
function f_info_alcaide_biblioteca_comboMaterias(){
	$devolver="";
	$sql="SELECT materia_nombre FROM " .  info_alcaide_bdd_tabla_biblioteca_materias ."  ORDER BY materia_nombre";



	global $wpdb;  // Conectar bdd
	$info_alcaide_biblioteca_vars = $wpdb->get_results($sql );
	if ( null !== $info_alcaide_biblioteca_vars ) {
		foreach ( $info_alcaide_biblioteca_vars as $info_alcaide_biblioteca_var1 ) 
		{
		  $devolver = $devolver .  
		  "<option value='{$info_alcaide_biblioteca_var1->materia_nombre}'>{$info_alcaide_biblioteca_var1->materia_nombre}</option>";
		}
	
	} 
	return $devolver;
}
/*****************************************************
**														
**		Funciones Auxiliares						
**													
******************************************************/

/**
* Quita caracteres innecesarios de las URLS
* @param string url
* @return string url codificada
*/
function f_info_alcaide_biblioteca_limpiarCaracteres($cadena){
$caracteres = '{ } & \ " % À Â Ã Ä Å Æ Ç È Ê Ë Ì Î Ï Ð Ñ Ò Ô Õ Ö Ø Ù Û Ü Ý Þ ß à â ã ä å æ ç è ê ë ì î ï ð ñ ò ô õ ö ø ù û ü ý þ ÿ ¡ ¢ £ ¤ ¥ ¦ § ¨ © ª « ¬ ® ¯ ° ± ² ³´ µ ¶ · ¸ ¹ º » ¼ ½ ¾ ¿ × ÷ ” \’ "';
$caracteres = explode(' ',$caracteres);
$cadena = str_replace($caracteres,'',$cadena);
return $cadena;
}

function f_info_alcaide_biblioteca_cabeceraBootstrap(){

	  //REGISTRAMOS FUENTES
  	wp_deregister_style('Material+Icons'); 
  	wp_register_style('Material+Icons','https://fonts.googleapis.com/icon?family=Material+Icons', array(), null, 'all');
	wp_enqueue_style('Material+Icons'); 
	
	
}


function f_info_alcaide_biblioteca_agregar_bootstrap(){
	return "
	<link rel='stylesheet' href='" . plugins_url() . "/" . info_alcaide_biblioteca_plugin_name . "/css/bootstrap.min.css'>
	<script src='" . plugins_url() . "/" . info_alcaide_biblioteca_plugin_name . "/js/jquery.min.js'></script>
	<script src='" . plugins_url() . "/" . info_alcaide_biblioteca_plugin_name . "/js/bootstrap.min.js'></script>
	<link href='https://fonts.googleapis.com/icon?family=Material+Icons' rel='stylesheet'>
	";
}