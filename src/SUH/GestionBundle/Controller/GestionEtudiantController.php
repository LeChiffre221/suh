<?php

namespace SUH\GestionBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SUH\GestionBundle\Entity\Etudiant;
use SUH\GestionBundle\Entity\Formation;
use SUH\GestionBundle\Entity\EtudiantFormation;
use SUH\GestionBundle\Entity\DatesAideExamen;
use SUH\GestionBundle\Entity\AideExamen;
use SUH\GestionBundle\Entity\Mdph;
use SUH\GestionBundle\Entity\EtudiantHandicape;
use SUH\GestionBundle\Entity\Handicap;

class GestionEtudiantController extends Controller
{
    /* On supprime toutes les informations liées à un étudiant pour l'année sélectionnée */

    public function suppressionEtudiantAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();        

        $etudiantHandicapeRepository = $entityManager->getRepository('SUHGestionBundle:EtudiantHandicape');  
        $a = $this->get('session')->get('filter');      
        $etudiant = $etudiantHandicapeRepository->getStudentConcerned($id, $a['year']);

        $etudiantRepository = $entityManager->getRepository('SUHGestionBundle:Etudiant');  
        $allInformations = $etudiantRepository->find($etudiant->getId());

        foreach ($allInformations->getListEtudiantHandicape() as $h) 
        {            
            if($h->getAnneeScolaire() == $a['year'])
            {
                if($h->getEtudiantHandicape()->getAideExamen() != null)
                {
                    $entityManager->remove($h);
                    /* suppression des adaptations documents */
                    foreach($h->getEtudiantHandicape()->getAideExamen()->getAdaptationDocuments() as $adaptationDoc)
                    {
                        $entityManager->remove($adaptationDoc);
                    }

                    /* suppression des ordinateurs */
                    foreach($h->getEtudiantHandicape()->getAideExamen()->getOrdinateur() as $ordi)
                    {
                        $entityManager->remove($ordi);
                    }

                    /* suppression du matériel */
                    if($h->getEtudiantHandicape()->getAideExamen()->getMateriel() != null)
                    {
                        $entityManager->remove($h->getEtudiantHandicape()->getAideExamen()->getMateriel());
                    }

                    /* suppression du secrétaire d'examen */
                    if($h->getEtudiantHandicape()->getAideExamen()->getSecretaireExamen() != null)
                    {
                        $entityManager->remove($h->getEtudiantHandicape()->getAideExamen()->getSecretaireExamen());
                    }

                    /* suppression de la délocalisation d'examen */
                    if($h->getEtudiantHandicape()->getAideExamen()->getDelocalisationExamen() != null)
                    {
                        $entityManager->remove($h->getEtudiantHandicape()->getAideExamen()->getDelocalisationExamen());
                    }

                    $entityManager->remove($h->getEtudiantHandicape()->getAideExamen());
                }

                foreach ($h->getEtudiantHandicape()->getHandicap() as $handicap) 
                {
                    $entityManager->remove($handicap);
                }

                if($h->getEtudiantHandicape()->getMdph() != null)
                {
                    $entityManager->remove($h->getEtudiantHandicape()->getMdph());
                }

                $entityManager->remove($h->getEtudiantHandicape());
            }
        }

        foreach ($allInformations->getListEtudiantFormation() as $f) 
        { 
            if($f->getAnneeScolaire() == $a['year'])
            {
                $entityManager->remove($f);
                $entityManager->remove($f->getFormation());
            }
        }

        foreach ($allInformations->getListEtudiantInformations() as $i) 
        { 
            if($i->getAnneeScolaire() == $a['year'])
            {
                $entityManager->remove($i);
                $entityManager->remove($i->getEtudiantInformations());
            }
        }

        $entityManager->flush();

        if(count($allInformations->getListEtudiantInformations()) == 0 && count($allInformations->getListEtudiantFormation()) == 0 &&  count($allInformations->getListEtudiantHandicape()) == 0)
        {
            $entityManager->remove($allInformations);
        }

        $entityManager->flush();

        return $this->redirectToRoute('suh_gestion_homepage');
    }

    /* Fonction permettant de retourner tous les étudiants pouvant être réinscrit l'année sélectionnée */

    public function getStudentLastYearsAction(Request $request)
    {
        $handicapRepository = $this->getDoctrine()->getManager()->getRepository('SUHGestionBundle:Etudiant');
        $etudiantReinscription = $handicapRepository->getStudentsReinscription($request->request->get('year'));

        $response = json_encode(array($etudiantReinscription));

        return new Response($response, 200, array(
            'Content-Type' => 'application/json'
        ));
    }
        
    /**
     * Permet la suppression d'une formation de l'étudiant (suppression avec la croix rouge à gauche de la formation)
     * @param type $id
     * @return type
     */
    public function supprimerFormationAction($idFormation,$idEtudiant)
    {
        $manager = $this->getDoctrine()->getManager();
        $formationRepository = $manager->getRepository('SUHGestionBundle:Formation');
        $etudiantRepository = $manager->getRepository('SUHGestionBundle:Etudiant');
        $etudiantFormationRepository = $manager->getRepository('SUHGestionBundle:EtudiantFormation');
        $etudiantHandicapeRepository = $manager->getRepository('SUHGestionBundle:EtudiantHandicape');

        $formation = $formationRepository->find($idFormation);
        $etudiant = $etudiantRepository->find($idEtudiant);
        $etudiantFormation = $etudiantFormationRepository->findOneBy(array('formation' => $idFormation, 'etudiant' => $idEtudiant));

        if($formation != null && $etudiant != null && $etudiantFormation != null)
        {
            $etudiant->removeListEtudiantFormation($formation);
            $manager->remove($etudiantFormation);   
            $manager->flush();

            $manager->remove($formation);       
            $manager->flush();

            $manager->persist($etudiant);
            $manager->flush();
        }
        else
        {
            return new Response('Erreur');
        }

        $a = $this->get('session')->get('filter');
        
        $idEtudiant = $etudiant->getId();
        $etudiantHandicape = $etudiantRepository->getHandicapStudentThisYear($idEtudiant,$a['year']);
        
        if(isset($etudiantHandicape[0]))
        {
            $informationsEtudiant = $etudiantHandicapeRepository->getInformationsStudent($etudiantHandicape[0]['id'],$a['year']);
        }
 
        return $this->render('SUHGestionBundle:AffichageEtudiants:accueil.html.twig',array(
              'listeEtudiantsHandicapes'=>$etudiantRepository->getAllIdNameSurname($a['year'])
            ));
    }

    //======================================================================================

    // addxxx concerve les fonctions d'ajout d'informations lors de la création d'un étudiant
    // modifxxx concerne les fonctions de modifications des informations des étudiants
    //======================================================================================
    
    /* Retourne la vue du choix entre la réinscription et l'ajout d'un étudiant */
    public function addEtudiantAction(Request $request)
    {        
        return $this->render('SUHGestionBundle:AffichageEtudiants:beforeAddEtudiant.html.twig', array(
        ));                
    }

    /* Permet de différencier l'ajout d'une réinscription */ 
    public function addInfosEtudiantAction(Request $request){


        $informationsEtudiant = array();
        $action = 'add';
        $id = null;

        if($request->query->get('id'))
        {
            $id = $request->query->get('id');

            $informationsEtudiant = $this->getDoctrine()->getManager()->getRepository('SUHGestionBundle:EtudiantHandicape')
                                         ->getLastsInformationsStudent($id);

            $action = 'reinscription';
        }
        
        return $this->render('SUHGestionBundle:AffichageEtudiants:addEtudiant.html.twig', array(
            'info' => $informationsEtudiant,
            'action' => $action,
            'id' => $id,
        )); 
               
    }   
    
    /* Modification d'un étudiant */
    public function modificationEtudiantAction($id)
    {

        $etudiant = $this->getDoctrine()->getManager()->getRepository('SUHGestionBundle:EtudiantHandicape')->getStudentConcerned($id);
        $idEtudiant = $etudiant->getId();

        $annee = $this->get('session')->get('filter');

        $formation = false;

        foreach ($etudiant->getListEtudiantFormation() as $key => $value) 
        {
            if($value->getAnneeScolaire() == $annee['year'])
            {
                $formation = true;
            }
        }

        $informationEtudiant = $this->getDoctrine()->getManager()->getRepository('SUHGestionBundle:EtudiantHandicape')->getModifInformationsStudent($idEtudiant, $annee['year'], $formation);

        return $this->render('SUHGestionBundle:AffichageEtudiants:addEtudiant.html.twig', array(
            'info' => $informationEtudiant,
            'action' => 'modification',
            'id' => $idEtudiant,
            ));
    } 
}