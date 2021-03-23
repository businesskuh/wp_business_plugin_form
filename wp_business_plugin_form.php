<?php
/**
 * Plugin Name: wp plugin form businessid
 * Author: vd.tidevelopmx
 * Description : Plugin para crear formulario de publicaciones. utiliza el
 *shortcode [wp-plugin-businessid-form]
 */

 // Cuando el plugin se active se crea la tabla para recoger los datos si no existe
register_activation_hook(__FILE__, 'wp_plugin_business_init');
 
/**
 * Crea la tabla para recoger los datos del formulario
 *
 * @return void
 */
function wp_plugin_business_init() 
{
    global $wpdb; // Este objeto global permite acceder a la base de datos de WP
    // Crea la tabla sólo si no existe
    // Utiliza el mismo prefijo del resto de tablas
    $tabla_aspirantes = $wpdb->prefix . 'business';
    // Utiliza el mismo tipo de orden de la base de datos
    $charset_collate = $wpdb->get_charset_collate();
    // Prepara la consulta
    $query = "CREATE TABLE IF NOT EXISTS $tabla_aspirantes (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        titulo varchar(200) NOT NULL,
        nombre varchar(300) NOT NULL,
        cliente varchar(400) NOT NULL,
        dirurl varchar(400) NOT NULL,
        fecha datetime NOT NULL,
        imagen varchar(600) NOT NULL,
        
        UNIQUE (id)
        ) $charset_collate;";
    // La función dbDelta permite crear tablas de manera segura se
    // define en el archivo upgrade.php que se incluye a continuación
    include_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($query); // Lanza la consulta para crear la tabla de manera segura
}


 add_shortcode('wp_plugin_businessid_form','WP_plugin_businessid_form');
 // Define el shortcode que pinta el formulario
 // Carga esta hoja de estilo para poner más bonito el formulario
 wp_enqueue_style('css_business', plugins_url('style.css', __FILE__));

 


function WP_plugin_businessid_form()
{

    global $wpdb; // Este objeto global permite acceder a la base de datos de WP
    // Si viene del formulario  graba en la base de datos
    // Cuidado con el último igual de la condición del if que es doble
    if ($_POST['titulo'] != ''
        AND is_email($_POST['nombre'])
        AND $_POST['cliente'] != ''
        AND $_POST['dirurl'] != ''
        AND $_POST['fecha'] != ''      
        AND $_POST['imagen'] !== ''
    ) {
        $tabla_publicaciones = $wpdb->prefix . 'publi'; 
        $titulo = sanitize_text_field($_POST['titulo']);
        $nombre = $_POST['nombre'];
        $cliente = $_POST['cliente'];
        $dirurl = $_POST['dirurl'];
        $fecha = date('Y-m-d H:i:s');
        $imagen = $_POST['imagen'];
        
        $wpdb->insert(
            $tabla_publicaciones,
            array(
                'titulo' => $titulo,
                'nombre' => $nombre,
                'cliente' => $cliente,
                'dirurl' => $dirurl,
                'fecha' => $fecha,
                'imagen' => $imagen,
               
            )
        );
        echo "<p class='exito'><b>Tus datos de publicación han sido registrados</b>. Gracias 
            por publicar. En breve se carga los datos.<p>";
    }

    ob_start();
    ?>
    <form action="<?php get_the_permalink(); ?>" method="post" 
       class="cuestionario">
       <div class="form-input">
       <label for="titulo">Título</label>
       <input type="text" name="titulo" id="titulo" required>

       <div class="form-input">
       <label for="nombre">Nombre</label>
       <input type="text" name="nombre" id="nombre" required>
       </div>

       <div class="form-input">
       <label for="cliente">Cliente</label>
       <input type="text" name="cliente" id="cliente" required>
       </div>

       <div class="form-input">
       <label for="url">URL</label>
       <input type="text" name="url" id="url" required>
       </div>

       <div class="form-input">
       <label for="fecha">Fecha Publicación</label>
       <input type="text" name="fecha" id="fehca" required>
       </div>

       <div class="form-input">
       <label for="imagen">Imagen</label>
       <input type="text" name="imagen" id="imagen" required>

             

    <?php
    return ob_get_clean();
}