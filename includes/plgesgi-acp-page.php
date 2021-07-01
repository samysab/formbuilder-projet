<?php

defined('ABSPATH') or die('Accès refusé');

wp_enqueue_script('script', plugin_dir_url(__FILE__) . 'script.js');


echo "<h1> Bienvenue sur le plugin de contact </h1>";


echo "<button>Créer un formulaire</button>";

