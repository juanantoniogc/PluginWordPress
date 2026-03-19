<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/juanantoniogc
 * @since      1.0.0
 *
 * @package    Prueba
 * @subpackage Prueba/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Prueba
 * @subpackage Prueba/includes
 * @author     Juan Antonio García Cabeza <garciacabezajuanantonio@gmail.com>
 */
class Prueba_Activator {
	public static function activate() {
	global $wpdb;
	
	$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas(
	encuestaId INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(45) NULL,
    shortCode VARCHAR(45) NULL,
	tipo VARCHAR(20) NULL,
    PRIMARY KEY (encuestaId));";

	$wpdb->query($sql);

	$sql2 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas_detalle(
	detalleId INT NOT NULL AUTO_INCREMENT,
	encuestaId INT NULL,
	pregunta VARCHAR(150) NULL,
	tipo VARCHAR(45) NULL,
	PRIMARY KEY (detalleId));";

	$wpdb->query($sql2);   

    $sql3 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas_respuesta (
	respuestaId INT NOT NULL AUTO_INCREMENT,
	detalleId INT NULL,
	codigo VARCHAR(45) NULL,
	respuesta VARCHAR(45) NULL,
	PRIMARY KEY (respuestaId));";

    $wpdb->query($sql3);  
	}
}
