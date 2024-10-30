 <?php
/* 
 *  *************************************
 *           submenu AGREGAR LIBRO
 *  *************************************
 */

function f_plg_info_alcaide_biblioteca_admin_materias() {


    if (current_user_can('administrator')) {

        $info_alcaide_biblioteca_devolver=f_plg_info_alcaide_biblioteca_admin_cabecera();


        if (isset( $_POST["txtMateria"])){
           $pMateria =sanitize_text_field($_POST["txtMateria"]);
           
            $info_alcaide_biblioteca_devolver=f_info_alcaide_biblioteca_admin_agregarMateria($pMateria);
        }
        
    

        //echo f_info_alcaide_biblioteca_cabeceraBootstrap();
        echo $info_alcaide_biblioteca_devolver;
        echo f_plg_info_alcaide_biblioteca_admin_formulario_nuevamateria();
    } 
}
     
function f_plg_info_alcaide_biblioteca_admin_formulario_nuevamateria(){
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    
    return  "
    
    
    <div class='container'>
    <div class='panel panel-primary'><div class='panel-heading'>Nueva Materia</div><div class='panel-body'>
    <form action='$actual_link' method='post'>
    
        <div class='form-group row'>
            <label for='txtMateria' class='col-sm-2 col-form-label'>Materia:</label>
            <div class='col-sm-10'>
            <input type='text' name='txtMateria' value='' class='form-control'>
        </div></div>
        <div class='form-group row'>
        <label for='inputPassword' class='col-sm-2 col-form-label'></label>
        <div class='col-sm-10'>
    <button type='submit' class='btn btn-primary'>Añadir</button>
</div></div>
        </form></div></div></div>". f_info_alcaide_biblioteca_agregar_bootstrap();

}


function f_info_alcaide_biblioteca_admin_agregarMateria($pMateria){	
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";	
    $devolver ="Agregando Materia....";
    global $wpdb;  // Conectar bdd

    $sql="INSERT  INTO " .  info_alcaide_bdd_tabla_biblioteca_materias ."  (materia_nombre) values ('" . $pMateria . "')";

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
 
    return  '<h2>BIBLIOTECA</h2><h3>¡ Agregada nueva materia !</h3>' . $devolver;
}   