<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appProdUrlMatcher.
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appProdUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $context = $this->context;
        $request = $this->request;

        // suh_choix_homepage
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'suh_choix_homepage');
            }

            return array (  '_controller' => 'SUH\\ConnexionBundle\\Controller\\ConnexionController::indexAction',  '_route' => 'suh_choix_homepage',);
        }

        if (0 === strpos($pathinfo, '/user')) {
            // suh_user_homepage
            if ($pathinfo === '/user') {
                return array (  '_controller' => 'SUH\\ConnexionBundle\\Controller\\UserController::afficherListeUtilisateurAction',  '_route' => 'suh_user_homepage',);
            }

            // suh_user_delete
            if (0 === strpos($pathinfo, '/user/delete') && preg_match('#^/user/delete/(?P<idUser>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_user_delete')), array (  '_controller' => 'SUH\\ConnexionBundle\\Controller\\UserController::deleteUserAction',));
            }

        }

        if (0 === strpos($pathinfo, '/gestion')) {
            // suh_migration
            if ($pathinfo === '/gestion/migration') {
                return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::migrationAction',  '_route' => 'suh_migration',);
            }

            // suh_gestion_homepage
            if (rtrim($pathinfo, '/') === '/gestion') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'suh_gestion_homepage');
                }

                return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::AfficherAccueilAction',  '_route' => 'suh_gestion_homepage',);
            }

            // suh_gestion_annee
            if ($pathinfo === '/gestion/editAnnees') {
                return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::editAnneesAction',  '_route' => 'suh_gestion_annee',);
            }

            // suh_gestion_annee_delete
            if (0 === strpos($pathinfo, '/gestion/deleteAnnee') && preg_match('#^/gestion/deleteAnnee/(?P<id>\\d+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_gestion_annee_delete')), array (  '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::deleteAnneeAction',));
            }

            // suh_get_etudiant
            if (0 === strpos($pathinfo, '/gestion/get') && preg_match('#^/gestion/get/(?P<id>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_get_etudiant')), array (  '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::AfficherAccueilEtudiantAction',));
            }

            // suh_get_etudiant_nomEtPrenom
            if ($pathinfo === '/gestion/by') {
                return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::AfficherAccueilEtudiantRechercheNomOuPrenomAction',  '_route' => 'suh_get_etudiant_nomEtPrenom',);
            }

            if (0 === strpos($pathinfo, '/gestion/re')) {
                // suh_gestion_rechercheAvancee
                if ($pathinfo === '/gestion/rechercheAvancee') {
                    return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::AfficherRechercheAvanceeAction',  '_route' => 'suh_gestion_rechercheAvancee',);
                }

                // suh_gestion_resultatAvancee
                if ($pathinfo === '/gestion/resultatAvancee') {
                    return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::AfficherResultatRechercheAction',  '_route' => 'suh_gestion_resultatAvancee',);
                }

            }

            if (0 === strpos($pathinfo, '/gestion/importEx')) {
                // suh_import_export_page
                if ($pathinfo === '/gestion/importExportExcel') {
                    return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::afficheImportExportPageAction',  '_route' => 'suh_import_export_page',);
                }

                // suh_import_page
                if ($pathinfo === '/gestion/importExcel') {
                    return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\ExcelController::importExcelAction',  '_route' => 'suh_import_page',);
                }

            }

            // suh_export_page
            if ($pathinfo === '/gestion/exportExcel') {
                return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\ExcelController::exportExcelAction',  '_route' => 'suh_export_page',);
            }

            // suh_ajout_etudiant
            if (0 === strpos($pathinfo, '/gestion/ajouter') && preg_match('#^/gestion/ajouter/(?P<id>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_ajout_etudiant')), array (  '_controller' => 'SUH\\GestionBundle\\Controller\\GestionEtudiantController::addEtudiantAction',));
            }

            // suh_modification_etudiant
            if (0 === strpos($pathinfo, '/gestion/modifier') && preg_match('#^/gestion/modifier/(?P<id>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_modification_etudiant')), array (  '_controller' => 'SUH\\GestionBundle\\Controller\\GestionEtudiantController::modificationEtudiantAction',));
            }

            // suh_suppression_etudiant
            if (0 === strpos($pathinfo, '/gestion/supprimer') && preg_match('#^/gestion/supprimer/(?P<id>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_suppression_etudiant')), array (  '_controller' => 'SUH\\GestionBundle\\Controller\\GestionEtudiantController::suppressionEtudiantAction',));
            }

            // suh_gestion_utilisateur_page
            if ($pathinfo === '/gestion/gestionUtilisateurs') {
                return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\AffichageController::afficheGestionUtilisateurPageAction',  '_route' => 'suh_gestion_utilisateur_page',);
            }

            // suh_ajouter_utilisateur
            if ($pathinfo === '/gestion/ajouterCompteUtilisateur') {
                return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\GestionCompteUtilisateurController::ajouterCompteUtilisateurAction',  '_route' => 'suh_ajouter_utilisateur',);
            }

            // suh_supprimer_utilisateur
            if ($pathinfo === '/gestion/supprimerCompteUtilisateur') {
                return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\GestionCompteUtilisateurController::supprimerCompteUtilisateurAction',  '_route' => 'suh_supprimer_utilisateur',);
            }

            // suh_gestion_beforeAddEtudiant
            if ($pathinfo === '/gestion/beforeAdd') {
                return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\GestionEtudiantController::addEtudiantAction',  '_route' => 'suh_gestion_beforeAddEtudiant',);
            }

            // suh_gestion_addEtudiant
            if ($pathinfo === '/gestion/add') {
                return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\GestionEtudiantController::addInfosEtudiantAction',  '_route' => 'suh_gestion_addEtudiant',);
            }

            // suh_suppression_etudiantFormation
            if (0 === strpos($pathinfo, '/gestion/supprimerFormation') && preg_match('#^/gestion/supprimerFormation/(?P<idFormation>[0-9]+)/(?P<idEtudiant>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_suppression_etudiantFormation')), array (  '_controller' => 'SUH\\GestionBundle\\Controller\\GestionEtudiantController::supprimerFormationAction',));
            }

            // get_student_last_years
            if ($pathinfo === '/gestion/getStudentLastYears') {
                return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\GestionEtudiantController::getStudentLastYearsAction',  '_route' => 'get_student_last_years',);
            }

            // refresh_list
            if ($pathinfo === '/gestion/refresh_list') {
                return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\AjaxRequestController::refreshListAction',  '_route' => 'refresh_list',);
            }

            // all_etudiants
            if ($pathinfo === '/gestion/getAllEtudiants') {
                return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\AjaxRequestController::getAllEtudiantsAction',  '_route' => 'all_etudiants',);
            }

            // add_et
            if ($pathinfo === '/gestion/completeAdd') {
                return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\AjaxRequestController::addEtudiantAction',  '_route' => 'add_et',);
            }

            if (0 === strpos($pathinfo, '/gestion/stats')) {
                // suh_stats
                if ($pathinfo === '/gestion/stats') {
                    return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\StatsController::indexAction',  '_route' => 'suh_stats',);
                }

                // suh_statsResults
                if ($pathinfo === '/gestion/statsResults') {
                    return array (  '_controller' => 'SUH\\GestionBundle\\Controller\\StatsController::resultsAction',  '_route' => 'suh_statsResults',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/contrat')) {
            // suh_contrat_homepage
            if (rtrim($pathinfo, '/') === '/contrat') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'suh_contrat_homepage');
                }

                return array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AffichageController::AfficherAccueilContratAction',  '_route' => 'suh_contrat_homepage',);
            }

            // suh_contrat_showArchive
            if (preg_match('#^/contrat/(?P<id>[0-9]+)/contrats/archives(?:/(?P<page>[0-9]+))?$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_showArchive')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AffichageController::AfficherArchiveContratAction',  'page' => 1,));
            }

            // suh_contrat_afficherContrat
            if (preg_match('#^/contrat/(?P<id>[0-9]+)/contrats(?:/(?P<page>[0-9]+))?$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_afficherContrat')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AffichageController::AfficherContratsPourUnEtudiantAction',  'page' => 1,));
            }

            // suh_contrat_addContrat
            if (preg_match('#^/contrat/(?P<id>[0-9]+)/addContrat$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_addContrat')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\ContratController::addContratAction',));
            }

            // suh_contrat_archiverContrat
            if (0 === strpos($pathinfo, '/contrat/archiver') && preg_match('#^/contrat/archiver/(?P<idContrat>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_archiverContrat')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\ContratController::archiverContratAction',));
            }

            // suh_contrat_desarchiverContrat
            if (0 === strpos($pathinfo, '/contrat/desarchiver') && preg_match('#^/contrat/desarchiver/(?P<idContrat>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_desarchiverContrat')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\ContratController::desarchiverContratAction',));
            }

            // suh_contrat_editerContrat
            if (0 === strpos($pathinfo, '/contrat/editContrat') && preg_match('#^/contrat/editContrat/(?P<idContrat>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_editerContrat')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\ContratController::editContratAction',));
            }

            // suh_contrat_deleteContrat
            if (0 === strpos($pathinfo, '/contrat/deleteContrat') && preg_match('#^/contrat/deleteContrat/(?P<idContrat>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_deleteContrat')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\ContratController::deleteContratAction',));
            }

            // suh_contrat_addDateContrat
            if (0 === strpos($pathinfo, '/contrat/addDateContrat') && preg_match('#^/contrat/addDateContrat/(?P<idContrat>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_addDateContrat')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\ContratController::addDateContratAction',));
            }

            // suh_contrat_showPaiementContrat
            if (preg_match('#^/contrat/(?P<id>[0-9]+)/contrats/paiement$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_showPaiementContrat')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AffichageController::AfficherPaiementContratAction',));
            }

            if (0 === strpos($pathinfo, '/contrat/miseEnPaiement')) {
                // suh_contrat_miseEnPaiementContrat
                if (preg_match('#^/contrat/miseEnPaiement/(?P<idContrat>[0-9]+)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_miseEnPaiementContrat')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\ContratController::mettreEnPaiementAction',));
                }

                // suh_contrat_miseEnPaiementValidation
                if (0 === strpos($pathinfo, '/contrat/miseEnPaiementValidationHeures') && preg_match('#^/contrat/miseEnPaiementValidationHeures/(?P<id>[0-9]+)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_miseEnPaiementValidation')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\HeureEffectueeController::paiementValidationHeuresAction',));
                }

            }

            if (0 === strpos($pathinfo, '/contrat/add')) {
                // suh_contrat_addAvenant
                if (0 === strpos($pathinfo, '/contrat/addAvenant') && preg_match('#^/contrat/addAvenant/(?P<idContrat>[0-9]+)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_addAvenant')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AvenantController::addAvenantAction',));
                }

                // suh_contrat_addDateAvenant
                if (0 === strpos($pathinfo, '/contrat/addDateAvenant') && preg_match('#^/contrat/addDateAvenant/(?P<idAvenant>[0-9]+)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_addDateAvenant')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AvenantController::addDateAvenantAction',));
                }

            }

            // suh_contrat_deleteAvenant
            if (0 === strpos($pathinfo, '/contrat/deleteAvenant') && preg_match('#^/contrat/deleteAvenant/(?P<idAvenant>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_deleteAvenant')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AvenantController::deleteAvenantAction',));
            }

            // suh_contrat_editerAvenant
            if (0 === strpos($pathinfo, '/contrat/editAvenant') && preg_match('#^/contrat/editAvenant/(?P<idAvenant>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_editerAvenant')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AvenantController::editAvenantAction',));
            }

            // suh_contrat_addEtudiantAidant
            if ($pathinfo === '/contrat/addEtudiant') {
                return array (  '_controller' => 'SUH\\ContratBundle\\Controller\\GestionEtudiantAidantController::addEtudiantAidantAction',  '_route' => 'suh_contrat_addEtudiantAidant',);
            }

            // suh_contrat_getEtudiantAidant
            if (0 === strpos($pathinfo, '/contrat/getEtudiant') && preg_match('#^/contrat/getEtudiant/(?P<id>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_getEtudiantAidant')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AffichageController::AfficherGetEtudiantAidantAction',));
            }

            // suh_contrat_showEtudiantAidant
            if (0 === strpos($pathinfo, '/contrat/showEtudiant') && preg_match('#^/contrat/showEtudiant/(?P<id>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_showEtudiantAidant')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AffichageController::AfficherShowEtudiantAidantAction',));
            }

            // suh_contrat_editEtudiantAidant
            if (0 === strpos($pathinfo, '/contrat/editEtudiant') && preg_match('#^/contrat/editEtudiant/(?P<id>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_editEtudiantAidant')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\GestionEtudiantAidantController::editEtudiantAidantAction',));
            }

            // suh_contrat_delEtudiantAidant
            if (0 === strpos($pathinfo, '/contrat/supprimer') && preg_match('#^/contrat/supprimer/(?P<id>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_delEtudiantAidant')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\GestionEtudiantAidantController::delEtudiantAidantAction',));
            }

            // suh_contrat_searchEtudiantAidant
            if ($pathinfo === '/contrat/by') {
                return array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AffichageController::AfficherSearchEtudiantAidantAction',  '_route' => 'suh_contrat_searchEtudiantAidant',);
            }

            // suh_contrat_resetPassword
            if (0 === strpos($pathinfo, '/contrat/resetPassword') && preg_match('#^/contrat/resetPassword/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_resetPassword')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\GestionEtudiantAidantController::resetPasswordEtudiantAidantAction',));
            }

            if (0 === strpos($pathinfo, '/contrat/importEx')) {
                // suh_contrat_importExport
                if ($pathinfo === '/contrat/importExport') {
                    return array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AffichageController::importExportAction',  '_route' => 'suh_contrat_importExport',);
                }

                // suh_contrat_importationExcel
                if ($pathinfo === '/contrat/importExcel') {
                    return array (  '_controller' => 'SUH\\ContratBundle\\Controller\\ImportExportController::importExcelAction',  '_route' => 'suh_contrat_importationExcel',);
                }

            }

            if (0 === strpos($pathinfo, '/contrat/exporter')) {
                // suh_contrat_exportationContrat
                if (0 === strpos($pathinfo, '/contrat/exporterContratPDF') && preg_match('#^/contrat/exporterContratPDF/(?P<idContrat>[0-9]+)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_exportationContrat')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\ImportExportController::exportContratPDFAction',));
                }

                // suh_contrat_exportationFichePaie
                if (0 === strpos($pathinfo, '/contrat/exporterFichePaiePDF') && preg_match('#^/contrat/exporterFichePaiePDF/(?P<month>[0-9]+)/(?P<year>[0-9]+)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_exportationFichePaie')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\ImportExportController::exportFichePaiePDFAction',));
                }

            }

            // suh_contrat_gestionHeures
            if (0 === strpos($pathinfo, '/contrat/heures') && preg_match('#^/contrat/heures/(?P<id>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_gestionHeures')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AffichageController::AfficherGestionHeuresAction',));
            }

            // suh_contrat_validationHeures
            if (0 === strpos($pathinfo, '/contrat/validationHeures') && preg_match('#^/contrat/validationHeures/(?P<id>[0-9]+)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_contrat_validationHeures')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\HeureEffectueeController::validationHeuresAction',));
            }

            // suh_contrat_parameters
            if ($pathinfo === '/contrat/parameters') {
                return array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AffichageController::AfficherParametersAction',  '_route' => 'suh_contrat_parameters',);
            }

            if (0 === strpos($pathinfo, '/contrat/statistiques')) {
                // suh_contrat_statistiques_avancees
                if ($pathinfo === '/contrat/statistiques/avancees') {
                    return array (  '_controller' => 'SUH\\ContratBundle\\Controller\\StatsController::TraitementStatsAvanceesAction',  '_route' => 'suh_contrat_statistiques_avancees',);
                }

                // suh_contrat_statistiques_heures
                if ($pathinfo === '/contrat/statistiques/heures') {
                    return array (  '_controller' => 'SUH\\ContratBundle\\Controller\\StatsController::TraitementStatsHeuresAction',  '_route' => 'suh_contrat_statistiques_heures',);
                }

                // suh_contrat_statistiques_nature
                if ($pathinfo === '/contrat/statistiques/nature') {
                    return array (  '_controller' => 'SUH\\ContratBundle\\Controller\\StatsController::TraitementStatsNatureAction',  '_route' => 'suh_contrat_statistiques_nature',);
                }

                // suh_contrat_statistiques_cout
                if ($pathinfo === '/contrat/statistiques/cout') {
                    return array (  '_controller' => 'SUH\\ContratBundle\\Controller\\StatsController::TraitementStatsCoutAction',  '_route' => 'suh_contrat_statistiques_cout',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/log')) {
            if (0 === strpos($pathinfo, '/login')) {
                // login
                if ($pathinfo === '/login') {
                    return array (  '_controller' => 'SUH\\ConnexionBundle\\Controller\\ConnexionController::loginAction',  '_route' => 'login',);
                }

                // login_check
                if ($pathinfo === '/login_check') {
                    return array('_route' => 'login_check');
                }

            }

            // logout
            if ($pathinfo === '/logout') {
                return array('_route' => 'logout');
            }

        }

        if (0 === strpos($pathinfo, '/etudiant')) {
            // suh_etudiant_homepageEtudiant
            if ($pathinfo === '/etudiant') {
                return array (  '_controller' => 'SUH\\ContratBundle\\Controller\\HeureEffectueeController::addHeureEffectueeAction',  '_route' => 'suh_etudiant_homepageEtudiant',);
            }

            if (0 === strpos($pathinfo, '/etudiant/heures')) {
                // suh_etudiant_heuresValidesEtudiant
                if ($pathinfo === '/etudiant/heuresValides') {
                    return array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AffichageController::AfficherHeureEspaceEtudiantAction',  '_route' => 'suh_etudiant_heuresValidesEtudiant',);
                }

                // suh_etudiant_heuresNonValidesEtudiant
                if ($pathinfo === '/etudiant/heuresNonValides') {
                    return array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AffichageController::AfficherHeureNonValidesEspaceEtudiantAction',  '_route' => 'suh_etudiant_heuresNonValidesEtudiant',);
                }

            }

            // suh_etudiant_compteEtudiant
            if ($pathinfo === '/etudiant/monCompte') {
                return array (  '_controller' => 'SUH\\ContratBundle\\Controller\\AffichageController::AfficherCompteEspaceEtudiantAction',  '_route' => 'suh_etudiant_compteEtudiant',);
            }

            if (0 === strpos($pathinfo, '/etudiant/heuresValides')) {
                // suh_etudiant_heuresNonValidesEtudiant_editer
                if (0 === strpos($pathinfo, '/etudiant/heuresValides/edit') && preg_match('#^/etudiant/heuresValides/edit/(?P<idHeure>[0-9]+)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_etudiant_heuresNonValidesEtudiant_editer')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\HeureEffectueeController::editHeureEffectueeAction',));
                }

                // suh_etudiant_heuresNonValidesEtudiant_delete
                if (0 === strpos($pathinfo, '/etudiant/heuresValides/delete') && preg_match('#^/etudiant/heuresValides/delete/(?P<idHeure>[0-9]+)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suh_etudiant_heuresNonValidesEtudiant_delete')), array (  '_controller' => 'SUH\\ContratBundle\\Controller\\HeureEffectueeController::deleteHeureEffectueeAction',));
                }

            }

        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
