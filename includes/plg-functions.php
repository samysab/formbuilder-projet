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

add_action('widget_init', 'plgesgi_register_formWidgets');

function plgesgi_register_formWidgets(){
    register_widget('pluginFormBuilder_widget');
}


function plgFormBuilder_register_widget() {
    register_widget( 'plgFormBuilder' );
}

add_action( 'widgets_init', 'plgFormBuilder_register_widget' );

class plgFormBuilder extends WP_Widget {

    function __construct() {
        parent::__construct(
            'plgFormBuilder',
            __('Widget Form builder', ' hstngr_widget_domain'),
            array( 'description' => __( 'Widget Form builder', 'hstngr_widget_domain' ), )
        );
        add_action('wp_loaded', array($this, 'save_email'));
    }
    public function widget($args, $instance){
        //Front
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        echo $before_widget.$before_title.$title.$after_title.'<form action="" method="post">
                <label for="email_user"> Description :</label>
                <input id="email_user" name="email_user" type="email">
                <input type="submit">
              </form>'.
            $after_widget;
    }

    public function form($instance){
        // Back
        $input1 = isset($instance['input1']) ? $instance['input1'] : '';
        $select1 = isset($instance['select1']) ? $instance['select1'] : '';
        $check1 = isset($instance['check1']) ? $instance['check1'] : '';

        echo '<p><label for="'.$this->get_field_name('input1').'"> Nom : </label>
        <input id="'.$this->get_field_id('input1').'" name="'.$this->get_field_name('input1').'" type="" value="'.$input1.'">
        <select name="'.$this->get_field_name('select1').'">
            <option value="text" '.(($select1=='text')?'selected="selected"':"").'>Texte</option>
            <option value="email" '.(($select1=='email')?'selected="selected"':"").'>Email</option>
            <option value="number" '.(($select1=='number')?'selected="selected"':"").'>Nombre</option>
            <option value="date" '.(($select1=='date')?'selected="selected"':"").'>Date</option>
        </select> 
        <input id="'.$this->get_field_id('check1').'" name="'.$this->get_field_name('check1').'" type="checkbox" '.(($select1=='date')?'checked':"").'>
        Activer</p>';
    }

    public function update($new_instance, $old_instance){
        $instance = array();
        if ($new_instance['check1'] === true) {
            $instance['input1'] = (!empty($new_instance['input1'])) ? strip_tags($new_instance['input1']) : '';
            $instance['select1'] = (!empty($new_instance['select1'])) ? strip_tags($new_instance['select1']) : '';
        }
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
