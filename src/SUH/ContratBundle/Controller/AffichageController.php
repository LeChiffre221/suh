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

    public function AfficherAccueilEtudiantAction(){

        return $this->render('SUHContratBundle:AffichageContrats:accueilEtudiant.html.twig');
    }

}
