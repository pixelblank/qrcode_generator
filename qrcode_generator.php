<?php

/*
Plugin Name: QrCode generator
Description: Un génératuer de QrCode pour parrainer d'autres annonceur.
Version: 1.0
Author: pixelb
*/
require_once(plugin_dir_path(__FILE__) . 'phpqrcode/lib/qrlib.php');
function enqueue_qrcode_scripts() {
    wp_enqueue_script('qrcode_script', plugin_dir_url(__FILE__) . 'js/qrcode_script.js', array('jquery'));
}
function qrcode_enqueue_styles() {
    wp_enqueue_style('mon-plugin-style', plugin_dir_url(__FILE__) . 'css/adminstyle.css');
}
add_action('admin_enqueue_scripts', 'qrcode_enqueue_styles');
add_action('admin_enqueue_scripts', 'enqueue_qrcode_scripts');

function mon_plugin_menu() {
    add_menu_page(
        'Réglages QR Code',       // Titre de la page
        'QR Code',                // Titre du menu
        'manage_options',         // Capacité requise
        'qrcode_page_html',     // Slug du menu
        'qrcode_page_html'    // Fonction de rappel
    );
}

add_action('admin_menu', 'mon_plugin_menu');

function qrcode_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    //var_dump($_SERVER['DOCUMENT_ROOT']);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nomFichier = $_POST['nomFichier'];
        $url = $_POST['url'];
        $cheminFichier = plugin_dir_path(__FILE__) . 'images\\' . $nomFichier . '.png';
        //var_dump($cheminFichier);
        $taillePixel = $_POST['taillePixel'];
        $marge = $_POST['marge'];

        QRcode::png($url, $cheminFichier, 'L', $taillePixel, $marge);
    }

    echo '<div class="form_container">';
    echo '<form action="" method="post" onsubmit="return validerForm()">'; // Modifiez 'action' pour pointer vers le script de traitement
    echo '<label for="nomFichier">Nom du fichier:</label>';
    echo '<input type="text" id="nomFichier" name="nomFichier"><br><br>';

    echo '<label for="url">URL:</label>';
    echo '<input type="text" id="url" name="url"><br><br>';

    echo '<div class="range_wrp">';
    echo '<label for="taillePixel">Taille des pixels </label>';
    echo '<div class="range_indicator"><span id="valeurTaillePixel">10</span></div>';
    echo '<input type="range" id="taillePixel" name="taillePixel" min="1" max="20" value="10" oninput="updateValeur(\'taillePixel\', \'valeurTaillePixel\', this)"><br><br>';
    echo '</div>';

    echo '<div class="range_wrp">';
    echo '<label for="marge">Marge</label>';
    echo '<div class="range_indicator"><span id="valeurMarge">4</span></div>';
    echo '<input type="range" id="marge" name="marge" min="0" max="10" value="4" oninput="updateValeur(\'marge\', \'valeurMarge\', this)"><br><br>';
    echo '</div>';

    echo '<input type="submit" value="Générer QR Code">';
    echo '</form>';
    echo '</div>';


    // Affichage des images QR Code
    $dossier = plugin_dir_path(__FILE__) . 'images/';
    $fichiers = scandir($dossier);
    $urlBase = plugin_dir_url(__FILE__) . 'images/';
    //var_dump($fichiers);
    echo '<div class="qrcode_container">';
    foreach ($fichiers as $fichier) {
        if ($fichier !== '.' && $fichier !== '..') {
            echo '<div class="qrcode_image">';
            echo '<img class="qrcode_pict" src="' . $urlBase . $fichier . '" alt="' . $fichier . '">';
            echo '<button onclick="supprimerImage(\'' . $fichier . '\')">Supprimer</button>';
            echo '</div>';
        }
    }
    echo '</div>';

    echo '<script>';
    echo 'var urlSuppression = "' . plugin_dir_url(__FILE__) . 'suppression_qrcode.php";';
    echo '</script>';
}
