<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/juanantoniogc
 * @since             1.0.0
 * @package           Prueba
 *
 * @wordpress-plugin
 * Plugin Name:       Prueba
 * Plugin URI:        https://github.com/juanantoniogc/PluginWordPress
 * Description:       Plugin de prueba
 * Version:           1.0.0
 * Author:            Juan Antonio García Cabeza
 * Author URI:        https://github.com/juanantoniogc/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       prueba
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PRUEBA_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-prueba-activator.php
 */
function activate_prueba() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-prueba-activator.php';
	Prueba_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-prueba-deactivator.php
 */
function deactivate_prueba() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-prueba-deactivator.php';
	Prueba_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_prueba' );
register_deactivation_hook( __FILE__, 'deactivate_prueba' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-prueba.php';
require_once plugin_dir_path(__FILE__) . 'public/codigo.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_prueba() {

	$plugin = new Prueba();
	$plugin->run();

}
run_prueba();

// Segundo video del curso 

add_action('admin_menu','crearMenu');


function crearMenu() {
	add_menu_page(
		'Encuestas', //Titulo pag
		'Encuestas Menu', //Titulo menu
		'manage_options', //capability
		'encuestas_menu', //slug
		'mostrarContenido', // funcion del contenido 
		'dashicons-analytics', // icono del plugin en el menu, se puede hacer con una imagen
							   // descargada por nosotros 
							   // plugin_dir_url( __FILE__ ) . 'admin/img/icono.ico',
							   // o por los nombres de esta pagina
							   // https://developer.wordpress.org/resource/dashicons
							   // 
		'1' // posicion en el menu
		);

		add_submenu_page(
			"encuestas_menu",
			"Ajustes",
			"Ajustes",
			"manage_options",
			"ajustes_encuestas",
			"mostrarAjustes"
		);
} 

function mostrarContenido() {
	require plugin_dir_path(__FILE__) . 'public/partials/principal.php';
}
function mostrarAjustes() {
	require plugin_dir_path(__FILE__) . 'public/partials/ajustes.php';
}

// Encolar Bootstrap en el admin
function encolarBootstrap($hook) {
    if ($hook != "toplevel_page_encuestas_menu") {
        return;
    }

    wp_enqueue_style(
        'bootstrapCss',
        plugins_url('admin/bootstrap/css/bootstrap.min.css', __FILE__),
        array(),
        '5.4.2'
    );

    wp_enqueue_script(
        'bootstrapJs',
        plugins_url('admin/bootstrap/js/bootstrap.min.js', __FILE__),
        array('jquery'),
        '5.4.2',
        true
    );

    wp_localize_script('prueba', 'SolicitudesAjax', [
        'url' => admin_url('admin-ajax.php'),
        'seguridad' => wp_create_nonce('seg')
    ]);
}
add_action('admin_enqueue_scripts', 'encolarBootstrap');

function eliminarEncuesta(){
    $nonce = $_POST['nonce'];
    if(!wp_verify_nonce($nonce, 'seg')){
        die('no tiene permisos para ejecutar ese ajax');
    }

    $id = $_POST['id'];
    global $wpdb;
    $tabla = "{$wpdb->prefix}encuestas";
    $tabla2 = "{$wpdb->prefix}encuestas_detalle";
    $wpdb->delete($tabla,array('encuestaId' =>$id));
    $wpdb->delete($tabla2,array('encuestaId' =>$id));
     return true;
}

add_action('wp_ajax_peticioneliminar','eliminarEncuesta');

//shortcode

function imprimirshortcode($atts){
    $_short = new codigocorto;
    //esto es para obtener el id como parametro
    $id= $atts['id'];
    //programo las acciones del boton
    if(isset($_POST['btnguardar'])){
        $listadePreguntas = $_short->ObtenerEncuestaDetalle($id);
        $codigo = uniqid();
        foreach ($listadePreguntas as $key => $value) {
           $idpregunta = $value['detalleId'];
           if(isset($_POST[$idpregunta])){
               $valortxt = $_POST[$idpregunta];
               $datos = [
                   'detalleId' => $idpregunta,
                   'codigo' => $codigo,
                   'respuesta' => $valortxt
               ];
               $_short->GuardarDetalle($datos);
           }
        }
        return " Encuesta enviada exitosamente";
    }
    //Imprimir el formulario
    $html = $_short->Armador($id);
    return $html;
}


add_shortcode("ENC","imprimirshortcode");