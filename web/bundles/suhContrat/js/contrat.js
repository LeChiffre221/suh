$(function() {

    $('#autre-diplome').hide();
    $('#autre-formation').hide();
    
    $('#suh_contratbundle_etudiantaidant_etudiantFormation_diplome').children('option:last-child').on('click', function(e){
        e.preventDefault();
        $('#autre-diplome').slideDown();
    });
    $('#autre-diplome').on('change', function(e){
        var value = $(this).val();
        $('#suh_contratbundle_etudiantaidant_etudiantFormation_diplome').children('option:last-child').attr('value',value);
        console.log(value);
    });

    $('#suh_contratbundle_etudiantaidant_etudiantFormation_etablissement').children('option:last-child').on('click', function(e){
        e.preventDefault();
        $('#autre-formation').slideDown();
    });
    $('#autre-formation').on('change', function(e){
        var value = $(this).val();
        $('#suh_contratbundle_etudiantaidant_etudiantFormation_etablissement').children('option:last-child').attr('value',value);
        console.log(value);
    });



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
