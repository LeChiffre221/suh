<?php

namespace SUH\ContratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class AffichageController extends Controller
{
    /**
     * récupère les données d'un contrat 
     * @return type
     */
    public function AfficherAccueilContratAction()
    {
        return $this->render('SUHContratBundle:AffichageContrats:accueil.html.twig',array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants(null),
            ));
    }

    public function getListeEtudiants()
    {      
        $etudiantRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHContratBundle:EtudiantAidant');

        $listeEtudiantsAidants = $etudiantRepository->findAll();
        if(!empty($listeEtudiantsAidants))
        {
           return $listeEtudiantsAidants;
        }   
    }

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


    public function AfficherContratsPourUnEtudiantAction($id, $page, $nbPerPage = 4){

        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session)
            $session = new Session(); // if there is no session, start it
        $session->set('suppressionContratFromArchive', false);

        $em = $this->getDoctrine()->getManager();

        // $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->find($idEtudiant);

        // On récupère la liste des contrats pour un étudiant donné
        $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->getPage($page, $nbPerPage, $id);
        $nbPages = ceil(count($listeContrats)/$nbPerPage);

        if(count($listeContrats) <= 4){
            $page = -1;
        }


        return $this->render('SUHContratBundle:AffichageContrats:listeContratsEtudiant.html.twig',array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants(null),
            'nbContrats'=>$this->getNbContrats($id),
            'listeContrats' => $listeContrats,
            'nbPages' => $nbPages,
            'page' => $page,
            'id' => $id
        ));

    }

    public function AfficherAccueilEtudiantAction(){

        return $this->render('SUHContratBundle:AffichageContrats:accueilEtudiant.html.twig');
    }

    //Recuperer etudiant
    public function AfficherGetEtudiantAidantAction($id)
    {

        return $this->render('SUHContratBundle:AffichageContrats:getEtudiantAidant.html.twig',array(
            'nbContrats'=>$this->getNbContrats($id),
            'id' => $id
            )); 
        
    }

    //Afficher etudiant
    public function AfficherShowEtudiantAidantAction($id)
    {
        $etudiantAidantRepo = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHContratBundle:EtudiantAidant');
        
        $etudiant = $etudiantAidantRepo->find($id);

        return $this->render('SUHContratBundle:AffichageContrats:showEtudiantAidant.html.twig',array(
            'informationsEtudiantAidant' => $etudiant,
            'id' => $id,
            'listeEtudiantsAidants'=>$this->getListeEtudiants(null),
            'nbContrats'=>$this->getNbContrats($id),
            )); 
        
    }

    // Afficher archive
    public function AfficherArchiveContratAction($id, $page){

        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session)
            $session = new Session(); // if there is no session, start it

        $session->set('suppressionContratFromArchive', true);

        $em = $this->getDoctrine()->getManager();

        $nbPerPage = 4;
        // On récupère la liste des contrats pour un étudiant donné
        $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->getPageArchive($page, $nbPerPage, $id);
        $nbPages = ceil(count($listeContrats)/$nbPerPage);

        if(count($listeContrats) <= 4){
            $page = -1;
        }

        return $this->render('SUHContratBundle:AffichageContrats:archivesContrat.html.twig',array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants(null),
            'listeContrats' => $listeContrats,
            'nbPages' => $nbPages,
            'page' => $page,
            'nbContrats'=>$this->getNbContrats($id),
            'id' => $id
        ));
    }

    public function AfficherGestionHeuresAction($id){

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

        return $this->render('SUHContratBundle:AffichageContrats:gestionHeures.html.twig', array(
            'listeEtudiantsAidants'=>$this->getListeEtudiants(null),
            'nbContrats'=>$this->getNbContrats($id),
            'listeContrats' => $listeContrats,
            'id' => $id
            ));

    }

}
