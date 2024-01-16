<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress_advert/wp-load.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['nomFichier'])) {
    //var_dump($_POST);
    $nomFichier = $_POST['nomFichier'];
    //var_dump($nomFichier);
    $cheminFichier = plugin_dir_path(__FILE__) . 'images/' . $nomFichier;
    if (file_exists($cheminFichier)) {
        unlink($cheminFichier); // Supprimer le fichier
    }
}