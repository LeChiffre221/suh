<?php

namespace SUH\ContratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use SUH\ContratBundle\Entity\Parameters;
use SUH\ContratBundle\Form\ParametersType;
use SUH\ContratBundle\Form\EtudiantAidantType;

class AffichageController extends Controller
{

    /**
     * récupère les données d'un contrat 
     * @return type
     */
    public function AfficherAccueilContratAction(Request $request)
    {
        $session = $this->getRequest()->getSession(); // Lance une session

        if(!$session instanceof Session){
            $session = new Session(); // pas de session ?
        }
        $session->set('chaine', null);

        $annee = $session->get('filter');
        

        return $this->render('SUHContratBundle:AffichageContrats:accueil.html.twig',array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine'), $annee),

            ));
    }


    //get la liste des etudiants
    public function getListeEtudiants($chaine, $year = null)
    {

        $em = $this->getDoctrine()->getManager();

        $annee = $em->getRepository('SUHGestionBundle:Annee')->findByAnneeUniversitaire($year['year']);
        $etudiantRepository = $em->getRepository('SUHContratBundle:EtudiantAidant');

        // pas d'annee transmise
        if(empty($year)){

            // pas de champ recherche
            if(empty($chaine))
            {
                $listEtudiant = $etudiantRepository->findAll();
                

                foreach ($listEtudiant as $etudiant){

                    $nbHeureNonValide = $em->getRepository('SUHContratBundle:HeureEffectuee')->selectNbHeureNonValidePourUnEtudiant($etudiant);
                    $etudiant->setHeureNonValide($nbHeureNonValide[1]);

                }
                return $listEtudiant;

            } else {

                return $etudiantRepository->getListeEtudiantsRecherche($chaine);
            }

        } else {

            // pas de champ recherche
            if(empty($chaine))
            {
                $listEtudiant = $etudiantRepository->findAll();

                foreach($listEtudiant as $etudiantAidant){
                    $valide = false;

                    foreach($etudiantAidant->getAnnees() as $annee){
                        if($annee->getAnneeUniversitaire() == $year['year']){
                            $valide = true;
                        }
                    }

                    if(!$valide){
                        $key = array_search($etudiantAidant, $listEtudiant);
                        unset($listEtudiant[$key]);
                    }
                }


                foreach ($listEtudiant as $etudiant){

                    $nbHeureNonValide = $em->getRepository('SUHContratBundle:HeureEffectuee')-> selectNbHeureNonValidePourUnEtudiant($etudiant);
                    $etudiant->setHeureNonValide($nbHeureNonValide[1]);

                }

                return $listEtudiant;

            // annee + champ recherche
            } else {
                $listeEtudiantAidant = $etudiantRepository->getListeEtudiantsRecherche($chaine);

                foreach($listeEtudiantAidant as $etudiantAidant){
                    $valide = false;

                    foreach($etudiantAidant->getAnnees() as $annee){
                        if($annee->getAnneeUniversitaire() == $year['year']){
                            $valide = true;
                        }
                    }

                    if(!$valide){
                        $key = array_search($etudiantAidant, $listeEtudiantAidant);
                        unset($listeEtudiantAidant[$key]);
                    }
                }
                return $listeEtudiantAidant;
            }

        }
    }


    public function getUser(){
        $security = $this->container->get('security.context');

        // On récupère le token
        $token = $security->getToken();

        // Sinon, on récupère l'utilisateur
        return $token->getUser();
    }



    public function importExportAction(){

        $session = $this->getRequest()->getSession(); // Lance une session
        if(!$session instanceof Session){
            $session = new Session(); // pas de session ?
        }

        $annee = $session->get('filter');

        return $this->render('SUHContratBundle:AffichageContrats:importExport.html.twig', array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine'),$annee)

        ));
    }

    //get nombre de contrats (pour ex-pagination et compteur)
    public function getNbContrats($id, $paiement)
    {     
        $em = $this->getDoctrine()->getManager();
        $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->find($id);

        $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->findBy(
                array(
                'etudiantAidant' => $etudiant,
                'active' => 1),
                array(
                'dateDebutContrat' => 'desc'
                )
           );

        //paiement pour savoir si on recupere les heures verifiees
        if($paiement){

            $listeHeures = array();
            $arrayMonth = array();

            $listeHeures = $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(
                array(
                    'contrat' => $listeContrats,
                    'verification' => true
                ),
                array(
                    'dateAndTime' => 'desc'
                )
            );

            //array qui nous renvoie si un mois possede une heure payee ou non 1 = mois non paye / 0 = mois totalement paye
            foreach($listeHeures as $heure){
                
                if(!$heure->getHeurePayee()){
                    $arrayMonth[intval(substr($heure->getDateAndTime(),3,2), 10)] = 1;
                } else {
                    $arrayMonth[intval(substr($heure->getDateAndTime(),3,2), 10)] = 0;
                }
            }
            
            $temp = array_count_values($arrayMonth);

            //on gere l exception d un mois 
            if(array_key_exists ( 1 , $temp )){
                $listeContrats = $temp[1];
            } else {
                $listeContrats = 0;
            }

            if($listeContrats)
            {
               return $listeContrats;

            } else {

                return 0;

            }
            

        } else {
            
           if($listeContrats)
            {
               return count($listeContrats);

            } else {

                return 0;

            }
        }

        
    }

    //Afficher liste contrats
    public function AfficherContratsPourUnEtudiantAction(Request $request, $id, $page, $nbPerPage = 4){

        $session = $this->getRequest()->getSession(); // Lance une session
        if(!$session instanceof Session){
            $session = new Session(); // pas de session ?
        }
        $session->set('suppressionContratFromArchive', false);

        $em = $this->getDoctrine()->getManager();

        $annee = $session->get('filter');


        // On recupere la liste des contrats pour un etudiant donne
        // Utilisation du paginator pour les longues listes de contrats
        $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->getPage($page, $nbPerPage, $id);

        //On recupere la liste des avenants pour chaque contrat
        foreach ($listeContrats as $contrat){
            $listeAvenants = $em->getRepository('SUHContratBundle:Avenant')->getAvenantsPourUnContrat($contrat);
            $contrat->setListeAvenant($listeAvenants);

        }

        //renvoi du nb de pages
        $nbPages = ceil(count($listeContrats)/$nbPerPage);

        if(count($listeContrats) <= 4){
            $page = -1;
        }


        return $this->render('SUHContratBundle:AffichageContrats:listeContratsEtudiant.html.twig',array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine'), $annee),
            'nbContrats'=>$this->getNbContrats($id, false),
            'nbContratsPaiement'=>$this->getNbContrats($id, true),
            'listeContrats' => $listeContrats,
            'nbPages' => $nbPages,
            'page' => $page,
            'id' => $id
        ));

    }

    //Afficher accueil compte etudiant
    public function AfficherAccueilEtudiantAction(){

        return $this->render('SUHContratBundle:ZoneEtudiante:accueilEtudiant.html.twig');
    }


    //Recherche d'un etudiant
    public function AfficherSearchEtudiantAidantAction(Request $request)
    {

        $session = $this->getRequest()->getSession(); // Lance une session

        if(!$session instanceof Session){
            $session = new Session(); // pas de session ?
        }

        //on stock la query du formulaire de recherche en session
        $session->set('chaine', $request->query->get('chaine'));
        $annee = $session->get('filter');

        return $this->render('SUHContratBundle:AffichageContrats:accueil.html.twig',array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine'), $annee),
        ));
        
    }

    //Afficher etudiant
    public function AfficherShowEtudiantAidantAction(Request $request, $id)
    {
        $session = $this->getRequest()->getSession(); // Lance une session

        if(!$session instanceof Session){
            $session = new Session(); // pas de session ?
        }

        $annee = $session->get('filter');
        $etudiantAidantRepo = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHContratBundle:EtudiantAidant');
        
        $etudiant = $etudiantAidantRepo->find($id);

        return $this->render('SUHContratBundle:AffichageContrats:showEtudiantAidant.html.twig',array(
            'informationsEtudiantAidant' => $etudiant,
            'id' => $id,
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine'), $annee),
            'nbContrats'=>$this->getNbContrats($id, false),
            'nbContratsPaiement'=>$this->getNbContrats($id, true),
            )); 
        
    }

    // Afficher archive
    public function AfficherArchiveContratAction(Request $request, $id, $page){

        $session = $this->getRequest()->getSession(); // Lance une session
        if(!$session instanceof Session){
            $session = new Session(); // pas de session ?
        }

        $session->set('suppressionContratFromArchive', true);
        $annee = $session->get('filter');


        $em = $this->getDoctrine()->getManager();

        $nbPerPage = 4;
        // On récupère la liste des contrats pour un étudiant donné
        $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->getPageArchive($page, $nbPerPage, $id);

        //On recupère la liste des avenants pour chaque contrat
        foreach ($listeContrats as $contrat){
            $listeAvenants = $em->getRepository('SUHContratBundle:Avenant')->getAvenantsPourUnContrat($contrat);
            $contrat->setListeAvenant($listeAvenants);

        }
        $nbPages = ceil(count($listeContrats)/$nbPerPage);

        if(count($listeContrats) <= 4){
            $page = -1;
        }

        return $this->render('SUHContratBundle:AffichageContrats:archivesContrat.html.twig',array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine'), $annee),
            'listeContrats' => $listeContrats,
            'nbPages' => $nbPages,
            'page' => $page,
            'nbContrats'=>$this->getNbContrats($id, false),
            'nbContratsPaiement'=>$this->getNbContrats($id, true),
            'id' => $id
        ));
    }

    public function AfficherPaiementContratAction(Request $request, $id){

        $session = $this->getRequest()->getSession(); // Lance une session
        if(!$session instanceof Session){
            $session = new Session(); // pas de session ?
        }
        $annee = $session->get('filter');

        $em = $this->getDoctrine()->getManager();

        // On récupère la liste des contrats pour un étudiant donné
        $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->getPageMiseEnPaiement($id);
        $listeHeures = array();

        //On recupère la liste des avenants pour chaque contrat
        foreach ($listeContrats as $contrat){
            $listeAvenants = $em->getRepository('SUHContratBundle:Avenant')->getAvenantsPourUnContrat($contrat);
            $contrat->setListeAvenant($listeAvenants);

        }

        //liste d heures verifiees suivant la liste des contrats
        $listeHeures = $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(
            array(
                'contrat' => $listeContrats,
                'verification' => true
            ),
            array(
                'dateAndTime' => 'desc'
            )
        );

        //array de mois en francais
        $tabMois = array(   '01' => "Janvier", '02' => "Février", '03' => "Mars", '04' => "Avril", '05' => "Mai", '06' => "Juin",
                    '07' => "Juillet", '08' => "Aout", '09'  => "Septembre", '10' => "Octobre", '11' => "Novembre", '12' => "Décembre");

        return $this->render('SUHContratBundle:AffichageContrats:paiementContratsEtudiant.html.twig',array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine'), $annee),
            'listeContrats' => $listeContrats,
            'nbContrats'=>$this->getNbContrats($id, false),
            'nbContratsPaiement'=>$this->getNbContrats($id, true),
            'id' => $id,
            'listeHeures' => $listeHeures,
            'tabMois' => $tabMois
        ));
    }

    public function AfficherHeureEspaceEtudiantAction (Request $request){
        $session = $this->getRequest()->getSession(); // Lance une session

        if(!$session instanceof Session){
            $session = new Session(); // pas de session ?
        }
        $annee = $session->get('filter');

        $em = $this->getDoctrine()->getManager();

        //utilisateur (=etudiant) courant
        $etudiantAidant = $em->getRepository('SUHContratBundle:EtudiantAidant')->findOneBy(array(
                                                                                'user' => $this->getUser()
                                                                        ));
        //contrats actifs de letudiant
        $listeContrat = $em->getRepository('SUHContratBundle:Contrat')->findBy(array(
                                                                                'etudiantAidant' => $etudiantAidant,
                                                                                'active' => true,
                                                                            ));
        //heures verifiees du contrat de letudiant
        $listeHeureValide=  $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(array(
                                                                            'contrat' => $listeContrat,
                                                                            'verification' => true
                                                                        ),
                                                                        array('dateAndTime' => 'desc'));



        return $this->render('SUHContratBundle:ZoneEtudiante:heureValidesEtudiant.html.twig', array(
            'etudiant' => $etudiantAidant,
            'listeHeureValide' => $listeHeureValide
        ));

    }

    public function AfficherHeureNonValidesEspaceEtudiantAction (Request $request){
        $session = $this->getRequest()->getSession(); // Lance une session

        if(!$session instanceof Session){
            $session = new Session(); // pas de session ?
        }
        $annee = $session->get('filter');
        $em = $this->getDoctrine()->getManager();
        //utilisateur (=etudiant) courant
        $etudiantAidant = $em->getRepository('SUHContratBundle:EtudiantAidant')->findOneBy(array(
                                                                                'user' => $this->getUser()
                                                                        ));
        //contrats actifs de letudiant
        $listeContrat = $em->getRepository('SUHContratBundle:Contrat')->findBy(array(
                                                                                'etudiantAidant' => $etudiantAidant,
                                                                                'active' => true,
                                                                        ));
        //heures non verifiees du contrat de letudiant
        $listeHeureNonValide =  $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(array(
                                                                                'contrat' => $listeContrat,
                                                                                'verification' => false
                                                                        ),
                                                                        array('dateAndTime' => 'desc'));



        return $this->render('SUHContratBundle:ZoneEtudiante:heureNonValidesEtudiant.html.twig', array(
            'etudiant' => $etudiantAidant,
            'listeHeureNonValide' => $listeHeureNonValide
        ));

    }

    public function AfficherGestionHeuresAction(Request $request, $id){

        $session = $this->getRequest()->getSession(); // Lance une session
        if(!$session instanceof Session){
            $session = new Session(); // pas de session ?
        }
        $annee = $session->get('filter');
        $em = $this->getDoctrine()->getManager();
        $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->find($id);

        //liste des contrats
        $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->findBy(
            array(
            'etudiantAidant' => $etudiant,
            'active' => 1),
            array(
            'dateDebutContrat' => 'desc'
            )
       );
        
        $listeHeures = array();

        //liste dheures suivant contrats
        $listeHeures = $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(
            array(
                'contrat' => $listeContrats,
            ),
            array(
                'dateAndTime' => 'desc'
            )
        );


        $tabMois = array(   '01' => "Janvier", '02' => "Février", '03' => "Mars", '04' => "Avril", '05' => "Mai", '06' => "Juin",
                            '07' => "Juillet", '08' => "Aout", '09'  => "Septembre", '10' => "Octobre", '11' => "Novembre", '12' => "Décembre");



        return $this->render('SUHContratBundle:AffichageContrats:gestionHeures.html.twig', array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine'), $annee),
            'nbContrats'=>$this->getNbContrats($id, false),
            'nbContratsPaiement'=>$this->getNbContrats($id, true),
            'listeContrats' => $listeContrats,
            'listeHeures' => $listeHeures,
            'id' => $id,
            'tabMois' => $tabMois
            ));

    }


    /**
     * récupère les données d'un contrat 
     * @return type
     */
    public function AfficherCompteEspaceEtudiantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $this->getRequest()->getSession(); // Lance une session
        if(!$session instanceof Session){
            $session = new Session(); // pas de session ?
        }
        $annee = $session->get('filter');

        $etudiantAidantRepo = $em->getRepository('SUHContratBundle:EtudiantAidant');
        $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->findOneBy(array('user' => $this->getUser()));

        //on genere un formulaire perso pour ledition du compte
        $form = $this->get('form.factory')->create(new EtudiantAidantType, $etudiant)
                ->remove('etudiant')
                ->remove('prenom')
                ->remove('etudiantFormation')
                ->remove('certificatMedical')
        ;
        $formInformations = $form->get('etudiantInformations');
        $formInformations
                        ->remove('prenom')
                        ->remove('nom')
                        ->remove('mailParents')
                        ->remove('adresseFamiliale')
                        ->remove('telephoneParents')
                        ->remove('mailInstitutionnel')
                        ->remove('parite')
        ;
        
        //si formulaire envoye, on enregistre les donnees
        if ($form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($etudiant);
            $em->flush();

            return $this->redirect($this->generateUrl('suh_etudiant_compteEtudiant'));
        }

        return $this->render('SUHContratBundle:ZoneEtudiante:compteEtudiant.html.twig',array(
            'form' => $form->createView()
            )); 

    }


    public function afficherListeAnneeAction(){
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('SUHGestionBundle:Annee')
        ;


        $listYears = $repository->myFindAll();

        return $this->render('SUHContratBundle:AffichageContrats:listeAnnee.html.twig',array(
            'listYears'=> $listYears,
        ));
    }

    
    public function AfficherReinscriptionEtudiantAidantAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $session = $this->getRequest()->getSession(); // Lance une session
        if(!$session instanceof Session){
            $session = new Session(); // pas de session ?
        }
        $annee = $session->get('filter');

        $etudiantRepository = $em->getRepository('SUHContratBundle:EtudiantAidant');
        $anneeRepository = $em->getRepository('SUHGestionBundle:Annee');

        $listYears = $anneeRepository->myFindAll();

        $listeEtudiantsReinscription = array();

        //On genere une liste suivant l'annee selectionnee
        if($session->get('filterEtu')){
           $listeEtudiantsReinscription = $this->getListeEtudiants($session->get('chaine'), $session->get('filterEtu'));
        }

        return $this->render('SUHContratBundle:AffichageContrats:reinscriptionEtudiant.html.twig',array(
            'listeEtudiantsAidants' => $this->getListeEtudiants($session->get('chaine'), $annee),
            'listeEtudiantsReinscription' => $listeEtudiantsReinscription,
            'listYears'=> $listYears,
        ));
    }


}
