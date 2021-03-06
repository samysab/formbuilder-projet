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
        global $wpdb;
        $row = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}contact_plugin WHERE status = 0"));

        extract($args);
        $title = apply_filters('widget_title', $instance['title']);

        //Formulaire
        echo $before_widget.$before_title.$title.$after_title.'<form action="" method="post">';

        foreach ($row as $key => $input){
        echo '  <h2 class="widget-title"> ';  echo $input->name;  echo ' </h2>
                <input class="postform" id="'; echo $input->id; echo '" name="input'; echo $input->id; echo '" type="'; echo $input->type; echo'" placeholder=" ';  echo $input->type;  echo' " >';
        }

        echo '<h2 class="widget-title"> &nbsp; </h2>
               <input type="submit" name="submit">
              </form>'.
            $after_widget;

        //Insertion en BDD

        //Verifications
        if ($_POST['submit']){
            for ($i = 1; $i <= 4; $i++){
                if (isset($_POST['input'.$i]) && !empty($_POST['input'.$i]) ){
                    $inputs[$i] = $_POST['input'.$i];
                }
            }
            $wpdb->insert("{$wpdb->prefix}contact_data", array('field_1' => $inputs["1"], 'field_2' => $inputs["2"], 'field_3' => $inputs["3"], 'field_4' => $inputs["4"]));
        }

    }

    public function form($instance){
        // Back
        global $wpdb;
        $row = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}contact_plugin"));

        $input1 = isset($instance['input1']) ? $instance['input1'] : '';

        for ($i=0; $i <4 ; $i++) { 

            $this->createInput('name'.$i,'select'.$i,'status'.$i, $row[$i]->name, $row[$i]->type, $row[$i]->status);
            if (isset($_POST['name'.$i]) && !empty($_POST['name'.$i])) {
                
                $name = $_POST['name'.$i];
                $type = $_POST['select'.$i];
                $status = $_POST['status'.$i];

                $wpdb->insert("{$wpdb->prefix}contact_plugin", array('name' => $name, 'type' => $type, 'status' => $status));

            }
        }

    }


    private function createInput($input,$select,$check, $nameValue, $typeValue, $checkValue){
        echo '<p><label for="'.$this->get_field_name($input).'"> Nom : </label>
        <input id="'.$input.'" name="'.$input.'" type="" value="'.$nameValue.'">
        <select name="'.$select.'">
            <option value="text" '.(($typeValue=='text')?'selected="selected"':"").'>Texte</option>
            <option value="email" '.(($typeValue=='email')?'selected="selected"':"").'>Email</option>
            <option value="number" '.(($typeValue=='number')?'selected="selected"':"").'>Nombre</option>
            <option value="date" '.(($typeValue=='date')?'selected="selected"':"").'>Date</option>
        </select> 
        <input id="'.$check.'" name="'.$check.'" type="checkbox"'.(($checkValue != null)?'checked':"").'>
        Activer</p>'
        ;

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

    public function display_fields(){
        global $wpdb;
        $rows = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}contact_data"));


        foreach($rows as $key => $value){
            echo "<tr>";
                echo "<td>$value->field_1</td>";
                echo "<td>$value->field_2</td>";
                echo "<td>$value->field_3</td>";
                echo "<td>$value->field_4</td>";
                echo "<td>$value->field_5</td>";
            echo "</tr>";
        }

    }
}
