
//dernier etudiant selectionne dans la liste
var last;
//couleur de fond dans la liste de l'étudiant(permet de passer du rouge à la couleur d'avance)
var lastColor;
$(function() {

    //pour tous les liens de la liste
    $("#liste ul li a").on('click', function (event) {
        //l'url est celui du lien
        var url = $(this).prop('href');
        var id = url.substring(url.lastIndexOf("/") + 1);
        var urlAccueil = document.getElementById('accueil').getAttribute('href');

        //on change les liens des boutons d'ajout/modif/suppression
        document.getElementById('modification').setAttribute("href", urlAccueil + "editEtudiant/" + id);
        document.getElementById('suppression').setAttribute("href", urlAccueil + "supprimer/" + id);

        //on stocke la couleur de base avant de la passer au rouge(afin de la remettre en état
        //au prochain clic
        lastColor = $(this).css("background-color");
        lastCol = $(this).css("color");
        //le lien sélectionné devient rose
        $(this).css({'background-color': '#FF496B'});
        $(this).css({'color': 'white'});
        //si il y a déjà eu un étudiant selectionné sa couleur redevient celle d'origine
        if (!(typeof last === "undefined")) {
            $(last).css({'background-color': lastColor});
            $(last).css({'color': lastCol});
        }
        //le dernier étudiant sélectionné devient l'étudiant courant
        last = this;
        //on charge la div d'affichage d'un etudiant
        $("#affichage").load(url);
        //on évite la redirection
        event.preventDefault();
    })
    $('body').on('change', '#affichage', function(){
        alert('changed');
    });
});  
$(function() {    
    //Liens du menu des profils Etudiants
    $("#listeProfil ul li a").on('click', function (event) {
        //on évite la redirection
        event.preventDefault();
        //l'url est celui du lien
        console.log("zboub");
        var url = $(this).prop('href');
        console.log(url);
        var id = url.substring(url.lastIndexOf("/") + 1);
        
        //on charge la div d'affichage d'un etudiant
        $("#content_right").load(url);
        
    });
});

