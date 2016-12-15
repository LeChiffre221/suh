<?php
/**
 * Created by PhpStorm.
 * User: lechi
 * Date: 30/11/2016
 * Time: 10:38
 */

namespace SUH\ContratBundle\Controller;


use SUH\ContratBundle\Entity\Avenant;
use SUH\ContratBundle\Form\AvenantType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AvenantController extends Controller
{
    public function addAvenantAction(Request $request, $idContrat){
        $em = $this->getDoctrine()->getManager();

        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }
        $annee = $session->get('filter');


        $contrat = $em->getRepository("SUHContratBundle:Contrat")->find($idContrat);

        $avenant = new Avenant();
        $form = $this->get('form.factory')->create(new AvenantType, $avenant);
        $form->remove('dateEnvoiDRH');
        $form->remove('dateEnvoiEtudiant');

        $idEtudiant = $contrat->getEtudiantAidant()->getId();

        if ($form->handleRequest($request)->isValid()) {


                $avenant->setContrat($contrat);
                $em->persist($avenant);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Avenant ajouté !');

                return $this->redirect($this->generateUrl('suh_contrat_afficherContrat', array(
                    'id' => $idEtudiant,
                )));

        }
        return $this->render('SUHContratBundle:AffichageContrats:addAvenant.html.twig', array(
            'form' => $form->createView(),
            'id' => $idEtudiant,
            'contrat' => $contrat,
            'listeEtudiantsAidants' => $this->getListeEtudiants($session->get('chaine'), $annee),
            'nbContrats'=>$this->getNbContrats($idEtudiant, false),
            'nbContratsPaiement'=>$this->getNbContrats($idEtudiant, true),
        ));
    }

    public function addDateAvenantAction(Request $request, $idAvenant){
        $em = $this->getDoctrine()->getManager();
        $avenant = $em->getRepository('SUHContratBundle:Avenant')->find($idAvenant);


        if($request->isMethod('post')){

            if(!empty($request->request->get("dateEnvoiAvenantDRH"))){
                $dateEnvoiAvenantDRH = $request->request->get("dateEnvoiAvenantDRH");

                //$dateEnvoiAvenantDRH  = date("Y-m-d", strtotime(strtr($dateEnvoiAvenantDRH, '/', '-') ));
                $avenant-> setDateEnvoiDRH($dateEnvoiAvenantDRH );
            }

            if(!empty($request->request->get("dateEnvoiAvenantEtudiant"))){
                $dateEnvoiAvenantEtudiant = $request->request->get("dateEnvoiAvenantEtudiant");

                //$dateEnvoiAvenantEtudiant= date("Y-m-d", strtotime(strtr($dateEnvoiAvenantEtudiant, '/', '-')));
                $avenant-> setDateEnvoiEtudiant($dateEnvoiAvenantEtudiant);
            }

        }

        $em->persist($avenant);
        $em->flush();

        $etudiant = $avenant->getContrat()->getEtudiantAidant();
        return $this->redirectToRoute('suh_contrat_afficherContrat', array('id' => $etudiant->getId()));


    }
    /*
    public function deleteAvenantAction(Request $request, $idAvenant){
        $em = $this->getDoctrine()->getManager();

        $avenant = $em->getRepository("SUHContratBundle:Avenant")->find($idAvenant);
        $idEtudiant = $avenant->getContrat()->getEtudiantAidant()->getId();

        $em->remove($avenant);
        $em->flush();

        return $this->redirect($this->generateUrl('suh_contrat_afficherContrat', array(
            'id' => $idEtudiant,
        )));

    }
*/
    public function editAvenantAction(Request $request, $idAvenant){
        $em = $this->getDoctrine()->getManager();

        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }
        $annee = $session->get('filter');

        $avenant = $em->getRepository("SUHContratBundle:Avenant")->find($idAvenant);

        $form = $this->get('form.factory')->create(new AvenantType, $avenant);
        $contrat = $avenant->getContrat();
        $idEtudiant = $contrat->getEtudiantAidant()->getId();


        if($avenant->getDateEnvoiDRH() == null){
            $form->remove('dateEnvoiDRH');
        }
        if($avenant->getDateEnvoiEtudiant() == null){
            $form->remove('dateEnvoiEtudiant');
        }

        if ($form->handleRequest($request)->isValid()) {

            $avenant->setContrat($contrat);




                $em->persist($avenant);
                $em->flush();


                $request->getSession()->getFlashBag()->add('notice', 'Avenant edité !');


                return $this->redirect($this->generateUrl('suh_contrat_afficherContrat', array(
                    'id' => $idEtudiant,
                )));

        }

        return $this->render('SUHContratBundle:AffichageContrats:editAvenant.html.twig', array(
            'form' => $form->createView(),
            'id' => $idEtudiant,
            'contrat' => $contrat,
            'listeEtudiantsAidants' => $this->getListeEtudiants($session->get('chaine'), $annee),
            'nbContrats'=>$this->getNbContrats($idEtudiant, false),
            'nbContratsPaiement'=>$this->getNbContrats($idEtudiant, true),
        ));

    }



    public function getListeEtudiants($chaine, $year = null)
    {

        $em = $this->getDoctrine()->getManager();
        $annee = $em->getRepository('SUHGestionBundle:Annee')->findByAnneeUniversitaire($year['year']);
        $etudiantRepository = $em->getRepository('SUHContratBundle:EtudiantAidant');


        if(empty($year)){

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

    //get nombre de contrats (pour pagination et compteur)
    public function getNbContrats($id, $paiement)
    {     
        $em = $this->getDoctrine()->getManager();
        $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->find($id);
        if($paiement){
            $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->findBy(
                array(
                'etudiantAidant' => $etudiant,
                'active' => 1),
                array(
                'dateDebutContrat' => 'desc'
                )
            );

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

            foreach($listeHeures as $heure){
                
                if(!$heure->getHeurePayee()){
                    $arrayMonth[intval(substr($heure->getDateAndTime(),3,2), 10)] = 1;
                } else {
                    $arrayMonth[intval(substr($heure->getDateAndTime(),3,2), 10)] = 0;
                }
            }
            
            $temp = array_count_values($arrayMonth);

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
            $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->findBy(
                array(
                'etudiantAidant' => $etudiant,
                'active' => 1),
                array(
                'dateDebutContrat' => 'desc'
                )
           );
           if($listeContrats)
            {
               return count($listeContrats);

            } else {

                return 0;

            }
        }

        
    }
}