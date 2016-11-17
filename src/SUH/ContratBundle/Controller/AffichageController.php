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

    public function getListeEtudiants($chaine)
    {      
        $etudiantRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHContratBundle:EtudiantAidant');

        //$listeEtudiantsAidants = $etudiantRepository->findAll();
        if(empty($chaine))
        {
           return $etudiantRepository;
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

}
