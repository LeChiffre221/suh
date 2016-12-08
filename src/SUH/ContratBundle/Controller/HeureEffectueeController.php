<?php
/**
 * Created by PhpStorm.
 * User: lechi
 * Date: 24/11/2016
 * Time: 08:46
 */

namespace SUH\ContratBundle\Controller;

use DateTime;
use SUH\ContratBundle\Entity\Contrat;
use SUH\ContratBundle\Entity\ContratRepository;
use SUH\ContratBundle\Entity\EtudiantAidant;
use SUH\ContratBundle\Entity\HeureEffectuee;
use SUH\ContratBundle\Form\ContratType;
use SUH\ContratBundle\Form\HeureEffectueeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\DependencyInjection\ContainerInterface;



class HeureEffectueeController extends Controller
{
    public function addHeureEffectueeAction(Request $request, $id = null){


        $em = $this->getDoctrine()->getManager();
        $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->findOneBy(array('user' => $this->getUser()));

        $heureEffectuee = new HeureEffectuee();
        $form = $this->get('form.factory')->create(new HeureEffectueeType, $heureEffectuee);

        $form->add('contrat', 'entity', array(
            'class' => 'SUHContratBundle:Contrat',
            'query_builder' => function(ContratRepository $repo) use($etudiant) {
                return $repo->getContratsPourUnEtudiant($etudiant);
            },
            'property' => 'toStringContrat'

        ));

        if($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $contratChoisi = $heureEffectuee->getContrat();

            //Variables intermédiaires pour comparé par la suite les dates
            $dateEtudiant = date("Y-m-d", strtotime(strtr($heureEffectuee->getDateAndTime(), '/', '-')));
            $dateDebut = date("Y-m-d", strtotime(strtr($contratChoisi->getDateDebutContrat(), '/', '-')));
            $dateFin = date("Y-m-d", strtotime(strtr($contratChoisi->getDateFinContrat(), '/', '-')));

            $natureMissionValide = false;
            $validateDate = false;
            //Compare si les date de l'heure sont cohérente avec les dates du contrat
            if(($dateEtudiant >= $dateDebut) && ($dateEtudiant <= $dateFin)) {

                //Date Valide
                $validateDate = true;

                //Test si la nature de la mision correspond à la nature d'un contrat
                if (!in_array($heureEffectuee->getNatureMission(), $contratChoisi->getNatureContrat())) {

                    $lesMission = '[';
                    foreach ($contratChoisi->getNatureContrat() as $nature) {
                        if ($nature == "tutorat") {
                            $nature = "Tutorat";
                        } elseif ($nature == "assistancePédagogique") {
                            $nature = "Assistance Pédagogique";
                        } else {
                            $nature = "Prise de note";
                        }

                        $lesMission .= $nature . "/";
                    }
                    $lesMission = substr($lesMission, 0, -1) . "]";

                    $dateContratDebut = date("d-m-Y", strtotime($dateDebut));
                    $dateContratFin = date("d-m-Y", strtotime($dateFin));
                    $request->getSession()->getFlashBag()->add('warning', 'La nature de votre mission n\'est pas pris en charge par ce contrat.
                                                                           Les Mission disponible du ' . $dateContratDebut . ' au ' . $dateContratFin . ' sont  : ' . $lesMission . '.');

                }
                else {
                    $natureMissionValide = true;
                }
            }

            if(!$natureMissionValide || !$validateDate){
                $natureMissionValide = false;
                $validateDate = false;

                $listeAvenants = $em->getRepository('SUHContratBundle:Avenant')->getAvenantsPourUnContrat($contratChoisi);

                //On compare également si elel sont cohérente avec les date de l'avenant
                foreach ($listeAvenants as $avenant) {
                    $dateDebut = date("Y-m-d", strtotime(strtr($avenant->getDateDebutAvenant(), '/', '-')));
                    $dateFin = date("Y-m-d", strtotime(strtr($avenant->getDateFinAvenant(), '/', '-')));

                    if(($dateEtudiant >= $dateDebut) && ($dateEtudiant <= $dateFin)){
                        $validateDate = true;

                        //Test si la nature de la mision correspond à la nature d'un avenant
                        if(!in_array($heureEffectuee->getNatureMission(), $avenant->getNatureAvenant())){
                            $lesMission = '[';
                            foreach ($avenant->getNatureAvenant() as $nature) {
                                if($nature == "tutorat"){
                                    $nature = "Tutorat";
                                }
                                elseif($nature == "assistancePédagogique"){
                                    $nature = "Assistance Pédagogique";
                                }
                                else{
                                    $nature = "Prise de note";
                                }

                                $lesMission.=$nature."/";
                            }
                            $lesMission = substr($lesMission,0,-1)."]";

                            $dateAvenantDebut = date("d-m-Y", strtotime($dateDebut ));
                            $dateAvenantFin = date("d-m-Y", strtotime($dateFin ));
                            $request->getSession()->getFlashBag()->add('warning', 'La nature de votre mission n\'est pas pris en charge par ce contrat.
                                                                           Les Mission disponible du '.$dateAvenantDebut.' au '.$dateAvenantFin.' sont : '.$lesMission.'.');

                            $natureMissionValide = false;
                        }
                        else{
                            $natureMissionValide = true;
                        }
                    }

                }
                if(!$validateDate){
                    $request->getSession()->getFlashBag()->add('warning', 'La date de vos heures n\'est pas pris en charge par ce contrat !');
                }
            }




            if(!$validateDate || !$natureMissionValide) {


                return $this->render('SUHContratBundle:ZoneEtudiante:accueilEtudiant.html.twig', array(
                    'form' => $form->createView(),
                    'etudiant' => $etudiant,

                ));
            }
            else{
                $flashBag = $this->get('session')->getFlashBag();
                foreach ($flashBag->keys() as $type) {
                    $flashBag->set($type, array());
                }
            }




            $heureEffectuee->setVerification(false);

            $em->persist($heureEffectuee);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Heure ajouté !');

            return $this->redirect($this->generateUrl('suh_etudiant_homepageEtudiant'));



        }



        return $this->render('SUHContratBundle:ZoneEtudiante:accueilEtudiant.html.twig', array(
            'form' => $form->createView(),
            'etudiant' => $etudiant

        ));

    }

    public function editHeureEffectueeAction($idHeure, Request $request){
        $em = $this->getDoctrine()->getManager();
        $heureEffectuee = $em->getRepository('SUHContratBundle:HeureEffectuee')->find($idHeure);

        $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->findOneBy(array('user' => $this->getUser()));

        $form = $this->get('form.factory')->create(new HeureEffectueeType, $heureEffectuee);

        $form->add('contrat', 'entity', array(
            'class' => 'SUHContratBundle:Contrat',
            'query_builder' => function(ContratRepository $repo) use($etudiant) {
                return $repo->getContratsPourUnEtudiant($etudiant);
            },
            'property' => 'toStringContrat',
        ));


        if($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $contratChoisi = $heureEffectuee->getContrat();

            //Variables intermédiaires pour comparé par la suite les dates
            $dateEtudiant = date("Y-m-d", strtotime(strtr($heureEffectuee->getDateAndTime(), '/', '-')));
            $dateDebut = date("Y-m-d", strtotime(strtr($contratChoisi->getDateDebutContrat(), '/', '-')));
            $dateFin = date("Y-m-d", strtotime(strtr($contratChoisi->getDateFinContrat(), '/', '-')));

            $natureMissionValide = false;
            $validateDate = false;
            //Compare si les date de l'heure sont cohérente avec les dates du contrat
            if(($dateEtudiant >= $dateDebut) && ($dateEtudiant <= $dateFin)) {

                //Date Valide
                $validateDate = true;

                //Test si la nature de la mision correspond à la nature d'un contrat
                if (!in_array($heureEffectuee->getNatureMission(), $contratChoisi->getNatureContrat())) {

                    $lesMission = '[';
                    foreach ($contratChoisi->getNatureContrat() as $nature) {
                        if ($nature == "tutorat") {
                            $nature = "Tutorat";
                        } elseif ($nature == "assistancePédagogique") {
                            $nature = "Assistance Pédagogique";
                        } else {
                            $nature = "Prise de note";
                        }

                        $lesMission .= $nature . "/";
                    }
                    $lesMission = substr($lesMission, 0, -1) . "]";

                    $dateContratDebut = date("d-m-Y", strtotime($dateDebut));
                    $dateContratFin = date("d-m-Y", strtotime($dateFin));
                    $request->getSession()->getFlashBag()->add('warning', 'La nature de votre mission n\'est pas pris en charge par ce contrat.
                                                                           Les Mission disponible du ' . $dateContratDebut . ' au ' . $dateContratFin . ' sont  : ' . $lesMission . '.');

                }
                else {
                    $natureMissionValide = true;
                }
            }

            if(!$natureMissionValide || !$validateDate){
                $natureMissionValide = false;
                $validateDate = false;

                $listeAvenants = $em->getRepository('SUHContratBundle:Avenant')->getAvenantsPourUnContrat($contratChoisi);

                //On compare également si elel sont cohérente avec les date de l'avenant
                foreach ($listeAvenants as $avenant) {
                    $dateDebut = date("Y-m-d", strtotime(strtr($avenant->getDateDebutAvenant(), '/', '-')));
                    $dateFin = date("Y-m-d", strtotime(strtr($avenant->getDateFinAvenant(), '/', '-')));

                    if(($dateEtudiant >= $dateDebut) && ($dateEtudiant <= $dateFin)){
                        $validateDate = true;

                        //Test si la nature de la mision correspond à la nature d'un avenant
                        if(!in_array($heureEffectuee->getNatureMission(), $avenant->getNatureAvenant())){
                            $lesMission = '[';
                            foreach ($avenant->getNatureAvenant() as $nature) {
                                if($nature == "tutorat"){
                                    $nature = "Tutorat";
                                }
                                elseif($nature == "assistancePédagogique"){
                                    $nature = "Assistance Pédagogique";
                                }
                                else{
                                    $nature = "Prise de note";
                                }

                                $lesMission.=$nature."/";
                            }
                            $lesMission = substr($lesMission,0,-1)."]";

                            $dateAvenantDebut = date("d-m-Y", strtotime($dateDebut ));
                            $dateAvenantFin = date("d-m-Y", strtotime($dateFin ));
                            $request->getSession()->getFlashBag()->add('warning', 'La nature de votre mission n\'est pas pris en charge par ce contrat.
                                                                           Les Mission disponible du '.$dateAvenantDebut.' au '.$dateAvenantFin.' sont : '.$lesMission.'.');

                            $natureMissionValide = false;
                        }
                        else{
                            $natureMissionValide = true;
                        }
                    }

                }
                if(!$validateDate){
                    $request->getSession()->getFlashBag()->add('warning', 'La date de vos heures n\'est pas pris en charge par ce contrat !');
                }
            }

            if(!$validateDate || !$natureMissionValide) {


                return $this->render('SUHContratBundle:ZoneEtudiante:accueilEtudiant.html.twig', array(
                    'form' => $form->createView(),
                    'etudiant' => $etudiant,
                    'modeEdition' => true
                ));
            }
            else{
                $flashBag = $this->get('session')->getFlashBag();
                foreach ($flashBag->keys() as $type) {
                    $flashBag->set($type, array());
                }
            }


            $heureEffectuee->setVerification(false);

            $em->persist($heureEffectuee);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Heure edité!');

            return $this->redirect($this->generateUrl('suh_etudiant_homepageEtudiant'));



        }
        return $this->render('SUHContratBundle:ZoneEtudiante:accueilEtudiant.html.twig', array(
            'form' => $form->createView(),
            'etudiant' => $etudiant,
            'modeEdition' => true

        ));
    }

    public function validationHeuresAction(Request $request, $id){

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



        if ($request->isMethod('POST')){
            foreach($listeHeures as $heure){
                $validation = $request->request->get('heure'.$heure->getId());
                if($validation == "on"){
                    $heure->setVerification(true);
                }
                else{
                    $heure->setVerification(false);
                }

                $em->persist($heure);
            }

            $em->flush();
           // return new Response($request->request->get('heure3'));
        }

        return $this->redirectToRoute("suh_contrat_gestionHeures", array(
            "id" => $id)
        );
    }


    public function deleteHeureEffectueeAction($idHeure, Request $request){
        $em = $this->getDoctrine()->getManager();
        $heure = $em->getRepository('SUHContratBundle:HeureEffectuee')->find($idHeure);

        $em->remove($heure);
        $em->flush();

        return $this->redirectToRoute("suh_etudiant_heuresNonValidesEtudiant");
    }

    public function getUser(){
        $security = $this->container->get('security.context');

        // On récupère le token
        $token = $security->getToken();

        // Sinon, on récupère l'utilisateur
        return $token->getUser();
    }
}