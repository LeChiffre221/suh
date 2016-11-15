<?php

namespace SUH\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ListeEtudiantController extends Controller
{
    /**
     * récupère liste d'étudiants
     * @param String $chaine
     */
    public function getListeEtudiants($chaine)
    {      
        $etudiantRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHGestionBundle:Etudiant');
        //Si $chaine est null tous les étudiants sont récupérés

        $a = $this->get('session')->get('filter');
        
        if($a['year'] == null)
        {
            $this->get('session')->set('filter', array(
                'year' => '2015-2016'
            ));
        }

        if(empty($chaine))
        {
            return $etudiantRepository->getAllIdNameSurname($a['year']);
        }
        //Si la $chaine n'est pas vide la recherche est faite avec cette chaine (WHERE)
        else
        {
            return $etudiantRepository->getListeEtudiantsParNomOuPrenom($chaine,$a['year']);
        }      
    }

    /**
     * Affiche l'accueil avec la liste des étudiants
     */
    public function AfficherAccueilAction()
    {        
        return $this->render('SUHGestionBundle:AffichageEtudiants:accueil.html.twig',array(
            'listeEtudiantsHandicapes'=>$this->getListeEtudiants(null),
        ));
    }  

}