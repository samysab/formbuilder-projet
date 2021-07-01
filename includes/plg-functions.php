<?php
// Test
defined('ABSPATH') or die('Accès refusé');


add_action('wp_footer', 'plgesgi_FooterAddText');

function plgesgi_FooterAddText(){
    echo "<i> Mon plugin Toto est activé ! </i>";
}

// Add menu in Admin
add_action('admin_menu', 'plgesgi_addAdminLink');

function plgesgi_addAdminLink(){
    add_menu_page(
        'Plugin projet page',
        'Plugin contact projet',
        'manage_options',
        'pluginformbuilder/includes/plgesgi-acp-page.php',
        '',
        'dashicons-chart-pie'
    );
}

// Shortcodes -[toto]
// Permet d'ajouter du contenu complexe via le plugin
add_shortcode('toto', 'plgesgi_TotoShortcode');

function plgesgi_TotoShortcode(){
    return "<b>Mon shortcode [toto]</b>";
}

// [titi attr="value]
add_shortcode('titi', 'plgesgi_TitiShortcode');

function plgesgi_TitiShortcode($atts){
    $a = shortcode_atts(array(
        'x' => 'un truc',
        'y' => 'un autre'
    ), $atts);
    return "<p> x est {$a['x']} et y est {$a['y']} </p>";
}


// Widget

class PluginFormBuilder_widget extends WP_Widget{

    public function __construct(){

        parent::__constuct(
            'pluginFormBuilder_widget',
            __('Widget Form Builder', 'mypluginlg'),
            array(
                'description' => __('widget simple issu du plugin du form builder', 'monpluginlg')
            )
        );
        add_action('wp_loaded', array($this, 'save_email'));
    }

    public function widget($args, $instance){
        //Front
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        echo $before_widget.$before_title.$title.$after_title.'<form action="" method="post">
                <label for="email_user"> Votre email :</label>
                <input id="email_user" name="email_user" type="email">
                <input type="submit">
              </form>'.
              $after_widget;
    }

    public function form($instance){
        // Back
        $title = isset($instance['title']) ? $instance['title'] : '';

        echo '<p><label for="'.$this->get_field_name('title').'"> Titre : </label>
        <input id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'"></p>
        ';
    }

    public function update($new_instance, $old_instance){
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

    public function save_email(){
        if (isset($_POST['email_user']) && !empty($_POST['email_user'])){
            global $wpdb;
            $email = $_POST['email_user'];

            $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}toto_table WHERE email ='$email'");
            if (is_null($row)){
                $wpdb->insert("{$wpdb->prefix}toto_table", array('email' => $email));
            }
        }
    }
}

add_action('widget_init', 'plgesgi_formWidgets');

function plgesgi_formWidgets(){
    register_widget('PluginFormBuilder_widget');
}