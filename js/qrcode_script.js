function updateValeur(idSlider, idValeur, slider) {
    var valeur = document.getElementById(idSlider).value;
    var indicator = document.getElementById(idValeur);

    document.getElementById(idValeur).innerText = valeur;
    indicator.innerText = valeur;
    var max = slider.max;
    var min = slider.min;
    var newVal = Number(((valeur - min) * 100) / (max - min));
    console.log(newVal)
    indicator.parentElement.style.left = `calc(${newVal}% + (${6 - newVal * 0.24}px))`;
}
function validerForm() {
    var nomFichier = document.getElementById('nomFichier').value;
    var url = document.getElementById('url').value;
    var regexNomFichier = /^[a-zA-Z0-9_.-]*$/; // Regex pour valider le nom de fichier
    var regexUrl = /^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([\/\w .-]*)*\/?$/; // Regex pour valider l'URL

    // Vérifier si les champs sont remplis
    if (nomFichier === '' || url === '') {
        alert('Veuillez remplir tous les champs.');
        return false;
    }

    // Vérifier le nom de fichier
    if (!regexNomFichier.test(nomFichier)) {
        alert('Le nom du fichier ne doit contenir que des lettres, des chiffres, des points, des tirets et des underscores.');
        return false;
    }

    // Vérifier l'URL
    if (!regexUrl.test(url)) {
        alert('L\'URL n\'est pas valide.');
        return false;
    }

    return true; // Le formulaire est valide
}
function supprimerImage(nomFichier) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', urlSuppression, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status == 200) {
                console.log(this.responseText);
                location.reload(); // Recharger la page pour afficher les changements
            }
        };
        xhr.send('nomFichier=' + nomFichier);
    }
}
document.addEventListener('DOMContentLoaded', function() {
    var slider = document.getElementById('taillePixel');
    var marge = document.getElementById('marge');
    updateValeur('taillePixel', 'valeurTaillePixel', slider);
    updateValeur('marge', 'valeurMarge', marge);
});
