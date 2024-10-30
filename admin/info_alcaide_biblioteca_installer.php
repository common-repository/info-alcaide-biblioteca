<?php

function f_info_alcaide_biblioteca_instalando() {
  global $wpdb;  // Conectar bdd
  $sql="
                               
CREATE TABLE  IF NOT EXISTS `" . info_alcaide_bdd_tabla_biblioteca  . "` (
  `id_libro` MEDIUMINT NOT NULL AUTO_INCREMENT,
  `n_registro` char(20) COLLATE latin1_spanish_ci DEFAULT NULL,
  `f_entrada` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `prestado` char(1) COLLATE latin1_spanish_ci DEFAULT NULL,
  `prestable` char(1) COLLATE latin1_spanish_ci DEFAULT NULL,
  `titulo` char(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `autor` char(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CDU` char(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `materias` char(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ISBN` char(60) COLLATE latin1_spanish_ci DEFAULT NULL,
  `edicion` char(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `anyo_publicacion` int(11) DEFAULT NULL,
  `editorial` char(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `resumen` text COLLATE latin1_spanish_ci,  
  `coleccion` char(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `tipo_libro` text COLLATE latin1_spanish_ci,  
  `numero` char(20) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ubicacion` char(1) COLLATE latin1_spanish_ci DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `tipo` char(3) COLLATE latin1_spanish_ci DEFAULT NULL,
  `precio_no_colegiado` float DEFAULT NULL,
  `periodicidad` char(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `imagen` char(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `url` char(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `texto_url` char(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `isbn_13` char(30) COLLATE latin1_spanish_ci DEFAULT NULL,
  `sig` char(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `num_paginas` int(11) DEFAULT NULL,
  `encuadernacion` char(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  PRIMARY KEY (id_libro)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci COMMENT='Biblioteca';

  ";

  $wpdb->query($sql);

  if($wpdb->last_error !== '') {
      $wpdb->print_error();
  }    

 


  $sql="
 
  CREATE TABLE  IF NOT EXISTS `" . info_alcaide_bdd_tabla_biblioteca_materias  . "` (
    `id_materia` MEDIUMINT NOT NULL AUTO_INCREMENT,
    `materia_nombre` char(255) COLLATE latin1_spanish_ci DEFAULT NULL,
    PRIMARY KEY (id_materia)
  ) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci COMMENT='Materias';
  
    ";
  
    $wpdb->query($sql);
  
    if($wpdb->last_error !== '') {
        $wpdb->print_error();
    }  
}