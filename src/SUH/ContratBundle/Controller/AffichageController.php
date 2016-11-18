<?php

namespace SUH\ContratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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

    public function AfficherContratsPourUnEtudiantAction($idEtudiant){
        $em = $this->getDoctrine()->getManager();

        $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->find($idEtudiant);

        // On récupère la liste des contrat pour un étudiant donné
        $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->findBy(array(
            'etudiantAidant' => $etudiant
        ));

        return $this->render('SUHContratBundle:AffichageContrats:listeContratsEtudiant.html.twig',array(
            'listeContrats' => $listeContrats
        ));

    }

    public function AfficherAccueilEtudiantAction(){

        return $this->render('SUHContratBundle:AffichageContrats:accueilEtudiant.html.twig');
    }

    //Recuperer etudiant
    public function AfficherGetEtudiantAidantAction($id)
    {

        return $this->render('SUHContratBundle:AffichageContrats:getEtudiantAidant.html.twig',array(
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
            'id' => $id
            )); 
        
    }

}
