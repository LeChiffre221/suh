<?php

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Psr\Log\LoggerInterface;

/**
 * appProdUrlGenerator
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appProdUrlGenerator extends Symfony\Component\Routing\Generator\UrlGenerator
{
    private static $declaredRoutes = array(
        'suh_migration' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::migrationAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/migration',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_gestion_homepage' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::AfficherAccueilAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_gestion_annee' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::editAnneesAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/editAnnees',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_gestion_annee_delete' => array (  0 =>   array (    0 => 'id',  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::deleteAnneeAction',  ),  2 =>   array (    'id' => '\\d+',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '/',      2 => '\\d+',      3 => 'id',    ),    1 =>     array (      0 => 'text',      1 => '/deleteAnnee',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_get_etudiant' => array (  0 =>   array (    0 => 'id',  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::AfficherAccueilEtudiantAction',  ),  2 =>   array (    'id' => '[0-9]+',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '/',      2 => '[0-9]+',      3 => 'id',    ),    1 =>     array (      0 => 'text',      1 => '/get',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_get_etudiant_nomEtPrenom' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::AfficherAccueilEtudiantRechercheNomOuPrenomAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/by',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_gestion_rechercheAvancee' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::AfficherRechercheAvanceeAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/rechercheAvancee',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_gestion_resultatAvancee' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::AfficherResultatRechercheAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/resultatAvancee',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_import_export_page' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::afficheImportExportPageAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/importExportExcel',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_import_page' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\ExcelController::importExcelAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/importExcel',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_export_page' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\ExcelController::exportExcelAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/exportExcel',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_ajout_etudiant' => array (  0 =>   array (    0 => 'id',  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\GestionEtudiantController::addEtudiantAction',  ),  2 =>   array (    'id' => '[0-9]+',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '/',      2 => '[0-9]+',      3 => 'id',    ),    1 =>     array (      0 => 'text',      1 => '/ajouter',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_modification_etudiant' => array (  0 =>   array (    0 => 'id',  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\GestionEtudiantController::modificationEtudiantAction',  ),  2 =>   array (    'id' => '[0-9]+',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '/',      2 => '[0-9]+',      3 => 'id',    ),    1 =>     array (      0 => 'text',      1 => '/modifier',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_suppression_etudiant' => array (  0 =>   array (    0 => 'id',  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\GestionEtudiantController::suppressionEtudiantAction',  ),  2 =>   array (    'id' => '[0-9]+',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '/',      2 => '[0-9]+',      3 => 'id',    ),    1 =>     array (      0 => 'text',      1 => '/supprimer',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_gestion_utilisateur_page' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::afficheGestionUtilisateurPageAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/gestionUtilisateurs',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_ajouter_utilisateur' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\GestionCompteUtilisateurController::ajouterCompteUtilisateurAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/ajouterCompteUtilisateur',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_supprimer_utilisateur' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\GestionCompteUtilisateurController::supprimerCompteUtilisateurAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/supprimerCompteUtilisateur',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_gestion_beforeAddEtudiant' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\GestionEtudiantController::addEtudiantAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/beforeAdd',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_gestion_addEtudiant' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\GestionEtudiantController::addInfosEtudiantAction',  ),  2 =>   array (    'id' => '[0-9]+',  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/add',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_suppression_etudiantFormation' => array (  0 =>   array (    0 => 'idFormation',    1 => 'idEtudiant',  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\GestionEtudiantController::supprimerFormationAction',  ),  2 =>   array (    'idFormation' => '[0-9]+',    'idEtudiant' => '[0-9]+',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '/',      2 => '[0-9]+',      3 => 'idEtudiant',    ),    1 =>     array (      0 => 'variable',      1 => '/',      2 => '[0-9]+',      3 => 'idFormation',    ),    2 =>     array (      0 => 'text',      1 => '/supprimerFormation',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'get_student_last_years' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\GestionEtudiantController::getStudentLastYearsAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/getStudentLastYears',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'refresh_list' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\AjaxRequestController::refreshListAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/refresh_list',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'all_etudiants' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\AjaxRequestController::getAllEtudiantsAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/getAllEtudiants',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'add_et' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\AjaxRequestController::addEtudiantAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/completeAdd',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_stats' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\StatsController::indexAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/stats',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'suh_statsResults' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\GestionBundle\\Controller\\StatsController::resultsAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/statsResults',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'login' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SUH\\ConnexionBundle\\Controller\\ConnexionController::loginAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/login',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'login_check' => array (  0 =>   array (  ),  1 =>   array (  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/login_check',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'logout' => array (  0 =>   array (  ),  1 =>   array (  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/logout',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
    );

    /**
     * Constructor.
     */
    public function __construct(RequestContext $context, LoggerInterface $logger = null)
    {
        $this->context = $context;
        $this->logger = $logger;
    }

    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        if (!isset(self::$declaredRoutes[$name])) {
            throw new RouteNotFoundException(sprintf('Unable to generate a URL for the named route "%s" as such route does not exist.', $name));
        }

        list($variables, $defaults, $requirements, $tokens, $hostTokens, $requiredSchemes) = self::$declaredRoutes[$name];

        return $this->doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $referenceType, $hostTokens, $requiredSchemes);
    }
}
