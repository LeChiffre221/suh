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

class AffichageController extends Controller
{

    /**
     * récupère les données d'un contrat 
     * @return type
     */
    public function AfficherAccueilContratAction(Request $request)
    {
        $session = $this->getRequest()->getSession(); // Get started session

        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }
        $session->set('chaine', null);

        return $this->render('SUHContratBundle:AffichageContrats:accueil.html.twig',array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
            ));
    }

    //get la liste des éutudiants
    public function getListeEtudiants($chaine)
    {      
        $etudiantRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHContratBundle:EtudiantAidant');

        
        if(empty($chaine))
        {

            $listEtudiant = $etudiantRepository->findAll();
            $em = $this->getDoctrine()->getManager();

            foreach ($listEtudiant as $etudiant){

                $nbHeureNonValide = $em->getRepository('SUHContratBundle:HeureEffectuee')-> selectNbHeureNonValidePourUnEtudiant($etudiant);
                $etudiant->setHeureNonValide($nbHeureNonValide[1]);



            }
            return $etudiantRepository->findAll();

        } else {
            
            return $etudiantRepository->getListeEtudiantsRecherche($chaine);
        }
    }

    //get nombre de contrats (pour pagination)
    public function getNbContrats($id)
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
        if(!empty($listeContrats))
        {
           return count($listeContrats);

        } else {

            return 0;

        }
        
    }

    //Afficher liste contrats
    public function AfficherContratsPourUnEtudiantAction(Request $request, $id, $page, $nbPerPage = 4){

        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }
        $session->set('suppressionContratFromArchive', false);

        $em = $this->getDoctrine()->getManager();

        // $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->find($idEtudiant);

        // On récupère la liste des contrats pour un étudiant donné
        $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->getPage($page, $nbPerPage, $id);

        //On recupère la liste des avenants pour chaque contrat
        foreach ($listeContrats as $contrat){
            $listeAvenants = $em->getRepository('SUHContratBundle:Avenant')->getAvenantsPourUnContrat($contrat);
            $contrat->setListeAvenant($listeAvenants);

        }
        $nbPages = ceil(count($listeContrats)/$nbPerPage);

        if(count($listeContrats) <= 4){
            $page = -1;
        }


        return $this->render('SUHContratBundle:AffichageContrats:listeContratsEtudiant.html.twig',array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
            'nbContrats'=>$this->getNbContrats($id),
            'listeContrats' => $listeContrats,
            'nbPages' => $nbPages,
            'page' => $page,
            'id' => $id
        ));

    }

    //Afficher accueil compte etudiant
    public function AfficherAccueilEtudiantAction(){

        return $this->render('SUHContratBundle:AffichageContrats:accueilEtudiant.html.twig');
    }

    //Afficher page parametre
    public function AfficherParametersAction(Request $request){

        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }
        $em = $this->getDoctrine()->getManager();
        $parameters = $em->getRepository('SUHContratBundle:Parameters')->find(1);
        $form = $this->get('form.factory')->create(new ParametersType, $parameters);


        if ($form->handleRequest($request)->isValid()) {

            $em->persist($parameters);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Variables éditées !');

            return $this->redirect($this->generateUrl('suh_contrat_parameters'));
        }
        return $this->render('SUHContratBundle:AffichageContrats:parameters.html.twig', array(
            'form' => $form->createView(),
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
        ));
    }

    //Recherche d'un etudiant
    public function AfficherSearchEtudiantAidantAction(Request $request)
    {

        $session = $this->getRequest()->getSession(); // Get started session

        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }
        $session->set('chaine', $request->query->get('chaine'));


        return $this->render('SUHContratBundle:AffichageContrats:accueil.html.twig',array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
        ));
        
    }

    //Afficher etudiant
    public function AfficherShowEtudiantAidantAction(Request $request, $id)
    {
        $session = $this->getRequest()->getSession(); // Get started session

        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }

        $etudiantAidantRepo = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHContratBundle:EtudiantAidant');
        
        $etudiant = $etudiantAidantRepo->find($id);

        return $this->render('SUHContratBundle:AffichageContrats:showEtudiantAidant.html.twig',array(
            'informationsEtudiantAidant' => $etudiant,
            'id' => $id,
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
            'nbContrats'=>$this->getNbContrats($id),
            )); 
        
    }

    // Afficher archive
    public function AfficherArchiveContratAction(Request $request, $id, $page){

        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }

        $session->set('suppressionContratFromArchive', true);

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
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
            'listeContrats' => $listeContrats,
            'nbPages' => $nbPages,
            'page' => $page,
            'nbContrats'=>$this->getNbContrats($id),
            'id' => $id
        ));
    }

    public function AfficherHeureEspaceEtudiantAction (Request $request){
        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session)
            $session = new Session(); // if there is no session, start it

        $em = $this->getDoctrine()->getManager();
        $etudiantAidant = $em->getRepository('SUHContratBundle:EtudiantAidant')->findOneBy(array(
                                                                                'user' => $this->getUser()
                                                                        ));

        $listeContrat = $em->getRepository('SUHContratBundle:Contrat')->findBy(array(
                                                                                'etudiantAidant' => $etudiantAidant
                                                                        ));

        $listeHeureNonValide =  $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(array(
                                                                                'contrat' => $listeContrat,
                                                                                'verification' => false
                                                                        ),
                                                                        array('dateAndTime' => 'desc'));

        $listeHeureValide=  $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(array(
                                                                            'contrat' => $listeContrat,
                                                                            'verification' => true
                                                                        ),
                                                                        array('dateAndTime' => 'desc'));



        return $this->render('SUHContratBundle:AffichageContrats:listeHeureEtudiant.html.twig', array(
            'etudiant' => $etudiantAidant,
            'listeHeureNonValide' => $listeHeureNonValide,
            'listeHeureValide' => $listeHeureValide
        ));

    }

    public function AfficherGestionHeuresAction(Request $request, $id){

        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session)
            $session = new Session(); // if there is no session, start it

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
        
        $listeHeures = array();

        $listeHeures = $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(
            array(
                'contrat' => $listeContrats,
            ),
            array(
                'dateAndTime' => 'desc'
            )
        );

        //$nbHeureTotale = $em->getRepository('SUHContratBundle:HeureEffectuee')->selectNbHeurePourUnEtudiant($etudiant);
        //$nbHeureValide = $em->getRepository('SUHContratBundle:HeureEffectuee')->selectNbHeureValidePourUnEtudiant($etudiant);

        //var_dump($nbHeureTotale);

        $tabMois = array(   '01' => "Janvier", '02' => "Fevrier", '03' => "Mars", '04' => "Avril", '05' => "Mai", '06' => "Juin",
                            '07' => "Juillet", '08' => "Aout", '09'  => "Septembre", '10' => "Octobre", '11' => "Novembre", '12' => "Decembre");



        return $this->render('SUHContratBundle:AffichageContrats:gestionHeures.html.twig', array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
            'nbContrats'=>$this->getNbContrats($id),
            'listeContrats' => $listeContrats,
            'listeHeures' => $listeHeures,
            'id' => $id,
            'tabMois' => $tabMois
            ));

    }

    public function getUser(){
        $security = $this->container->get('security.context');

        // On récupère le token
        $token = $security->getToken();

        // Sinon, on récupère l'utilisateur
        return $token->getUser();
    }

    /**
     * récupère les données d'un contrat 
     * @return type
     */
    public function AfficherCompteEtudiantAction(Request $request)
    {

        return $this->render('SUHContratBundle:AffichageContrats:compteEtudiant.html.twig');
    }

}
