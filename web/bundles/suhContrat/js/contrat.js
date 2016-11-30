
//dernier etudiant selectionne dans la liste
var last;
//couleur de fond dans la liste de l'étudiant(permet de passer du rouge à la couleur d'avance)
var lastColor;
$(function() {


    $('.bloc-contrat:first-child').children('.panel-heading').addClass('contrat-open').nextAll().show();
    // $('.bloc-contrat:first-child').children('.panel-heading').children('.arrow-panel-heading').removeClass('glyphicon-menu-down').addClass('glyphicon-menu-up');
    $('.bloc-contrat:nth-child(n+2)').children('.panel-heading').nextAll().hide().parent().css('padding', '0');

    $('.panel-heading').click(function(e){
        e.preventDefault();

            if($(this).hasClass('contrat-open')) {
                $(this).nextAll().stop().slideUp(function(){
                    $(this).prev('.panel-heading').removeClass('contrat-open').parent().css('padding', '0');
                    // $(this).prev('.panel-heading').children('.arrow-panel-heading').removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
                });
            } else {
                 $(this).nextAll().stop().slideDown(function(){
                    $(this).prev('.panel-heading').addClass('contrat-open').parent().removeAttr('style');
                    // $(this).prev('.panel-heading').children('.arrow-panel-heading').removeClass('glyphicon-menu-down').addClass('glyphicon-menu-up');
                });
            }
    })

    $('#help').click(function(e){
        
        if($('body').hasClass('help-open')) {
            $('body').removeClass('help-open');
            $('[data-toggle="tooltip"]').tooltip('destroy');
        } else {
            $('body').addClass('help-open');
            $('[data-toggle="tooltip"]').tooltip();
            e.preventDefault();
            
        }
    });

    $('.message-fixed').click(function(e){

        $(this).slideUp();

    });

});  
