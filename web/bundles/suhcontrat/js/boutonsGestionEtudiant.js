
//dernier etudiant selectionne dans la liste
var last;
//couleur de fond dans la liste de l'étudiant(permet de passer du rouge à la couleur d'avance)
var lastColor;
$(function() {

    // //pour tous les liens de la liste
    // $("#liste ul li a").on('click', function (event) {
    //     //l'url est celui du lien
    //     var url = $(this).prop('href');
    //     var id = url.substring(url.lastIndexOf("/") + 1);
    //     var urlAccueil = document.getElementById('accueil').getAttribute('href');

    //     //on change les liens des boutons d'ajout/modif/suppression
    //     document.getElementById('modification').setAttribute("href", urlAccueil + "editEtudiant/" + id);
    //     document.getElementById('suppression').setAttribute("href", urlAccueil + "supprimer/" + id);

    //     //on stocke la couleur de base avant de la passer au rouge(afin de la remettre en état
    //     //au prochain clic
    //     lastColor = $(this).css("background-color");
    //     lastCol = $(this).css("color");
    //     //le lien sélectionné devient rose
    //     $(this).css({'background-color': '#FF496B'});
    //     $(this).css({'color': 'white'});
    //     //si il y a déjà eu un étudiant selectionné sa couleur redevient celle d'origine
    //     if (!(typeof last === "undefined")) {
    //         $(last).css({'background-color': lastColor});
    //         $(last).css({'color': lastCol});
    //     }
    //     //le dernier étudiant sélectionné devient l'étudiant courant
    //     last = this;
    //     //on charge la div d'affichage d'un etudiant
    //     $("#affichage").load(url, function(){

    //     //Liens du menu des profils Etudiants
    //         $("#listeProfil ul li a").on('click', function (event) {
    //             //on évite la redirection
    //             event.preventDefault();
    //             //l'url est celui du lien
    //             var url2 = $(this).prop('href');
    //             var id2 = url2.substring(url2.lastIndexOf("/") + 1);
    //             $("#listeProfil ul li").each(function(){
    //                 $(this).removeClass('active');
    //             })
    //             $(this).parent().addClass('active');
    //             //on charge la div d'affichage d'un etudiant
    //             $("#content_right").load(url2);
                
    //         });
    //     });
    //     //on évite la redirection
    //     event.preventDefault();
    // });
    // $("#modification").on('click', function (event) {
    //     event.preventDefault();
    //     var url = $(this).prop('href');
    //     $("#affichage").load(url);
    // });

    $('.bloc-contrat:first-child').children('.panel-heading').addClass('contrat-open').nextAll().show();
    $('.bloc-contrat:first-child').children('.panel-heading').children('.arrow-panel-heading').removeClass('glyphicon-menu-down').addClass('glyphicon-menu-up');
    $('.bloc-contrat:nth-child(n+2)').children('.panel-heading').nextAll().hide().parent().css('padding', '0');

    $('.panel-heading').click(function(e){
        e.preventDefault();

            if($(this).hasClass('contrat-open')) {
                $(this).nextAll().stop().slideUp(function(){
                    $(this).prev('.panel-heading').removeClass('contrat-open').parent().css('padding', '0');
                    $(this).prev('.panel-heading').children('.arrow-panel-heading').removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
                });
            } else {
                 $(this).nextAll().stop().slideDown(function(){
                    $(this).prev('.panel-heading').addClass('contrat-open').parent().removeAttr('style');
                    $(this).prev('.panel-heading').children('.arrow-panel-heading').removeClass('glyphicon-menu-down').addClass('glyphicon-menu-up');
                });
            }
    })

    $('#help').click(function(e){
        
        if($('body').hasClass('help-open')) {
            $('body').removeClass('help-open');
            $('[data-toggle="tooltip"]').tooltip('destroy');
            window.location = this.href;
        } else {
            $('body').addClass('help-open');
            $('[data-toggle="tooltip"]').tooltip();

            
        }
    });

});  
