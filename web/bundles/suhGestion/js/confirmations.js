function confirmationImport()
{
    var check1 = confirm("###Voulez vous ajouter le contenu de ce fichier excel à votre base de données ?###");
    if (check1)
    {
        var check2 = confirm("###Etes-vous sûr ?###");
        if (check2)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

function confirmationExport()
{
    if (confirm("###Voulez vous exporter votre base de données ?###"))
    {
        return true;
    }
    return false;
}

function ConfirmationSuppression()
{
    var check1 = confirm("###Voulez vous vraiment SUPRIMER l'étudiant de votre base de données ?###");
    if (check1)
    {
        var check2 = confirm("###Etes-vous sûr ? L'étudiant sera SUPPRIME définitivement !###");
        if (check2)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

function ConfirmationModification()
{
    if (confirm("###Voulez vous vraiment MODIFIER l'étudiant ?###"))
    {
        return true;
    }
    return false;
}


function ConfirmationAjoutCompteUtilisateur()
{
    if (confirm("###Voulez vous vraiment ajouter un nouveau compte utilisateur ?###"))
    {
        return true;
    }
    return false;
}


function ConfirmationSuppressionCompteUtilisateur()
{
    var check1 = confirm("###Voulez vous vraiment SUPRIMER ce compte utilisateur de votre base de données ?###");
    if (check1)
    {
        var check2 = confirm("###Etes-vous sûr ? Le compte utilisateur sera SUPPRIME définitivement !###");
        if (check2)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

function confirmationSuppression()
{
    var check1 = confirm("###Voulez vous vraiment SUPRIMER ces données de votre base de données ?###");
    if (check1)
    {
        var check2 = confirm("###Etes-vous sûr ? La suppression sera définitive !###");
        if (check2)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

function confirmationSuuppressionFormation()
{
    var check1 = confirm("###Voulez vous vraiment SUPRIMER cette formation ?###");
    if (check1)
    {
        var check2 = confirm("###Etes-vous sûr ? La suppression sera définitive !###");
        if (check2)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }

}