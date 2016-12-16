<?php
/**
 * Created by PhpStorm.
 * User: lechi
 * Date: 24/11/2016
 * Time: 08:46
 */

namespace SUH\ContratBundle\Controller;

use DateTime;
use SUH\ContratBundle\Entity\Avenant;
use SUH\ContratBundle\Entity\Contrat;
use SUH\ContratBundle\Entity\ContratRepository;
use SUH\ContratBundle\Entity\EtudiantAidant;
use SUH\ContratBundle\Entity\HeureEffectuee;

use SUH\ConnexionBundle\Entity\Parameters;

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
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->findOneBy(array(
            'etudiantAidant' => $etudiant,
            'active' => true
        ));

        $heureEffectuee = new HeureEffectuee();
        $heureEffectuee->setContrat($contrat);


        //Permet d'avoir dans le formulaire suelement le choix entre des nature proposé par le contrat
        $natureMission = array();

        if($contrat != null){
            foreach ($contrat->getNatureContrat() as $natureContrat){
                if($natureContrat == 'tutorat'){
                    $natureMission['tutorat'] = "Tutorat";
                }
                elseif ($natureContrat == 'priseNote'){
                    $natureMission['priseNote'] = "Prise de note";
                }
                else{
                    $natureMission['assistancePédagogique'] = "Assistance Pédagogique";
                }
            }
        }


        $form = $this->get('form.factory')->create(new HeureEffectueeType, $heureEffectuee);
        $form->add('natureMission', 'choice', array(
            'choices' => $natureMission,
            'multiple' => false,
            'expanded' => true,

        ));

        if($form->handleRequest($request)->isValid()){

            //Variables intermédiaires pour comparé par la suite les dates
            $dateEtudiant = date("Y-m-d", strtotime(strtr($heureEffectuee->getDateAndTime(), '/', '-')));
            $dateDebut = date("Y-m-d", strtotime(strtr($contrat->getDateDebutContrat(), '/', '-')));
            $dateFin = date("Y-m-d", strtotime(strtr($contrat->getDateFinContrat(), '/', '-')));

            $validateDate = false;
            //Compare si les date de l'heure sont cohérente avec les dates du contrat
            if(($dateEtudiant >= $dateDebut) && ($dateEtudiant <= $dateFin)) {

                //Nombre disponible pour le contrat
                $nbHeureDisponible = $contrat->getNbHeureInitiales();
                $nbHeureRealise = 0;

                $avenant = $em->getRepository('SUHContratBundle:Avenant')->findOneBy(array(
                    'contrat' => $contrat
                ));
                $listeHeureRealise =  $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(array(
                    'contrat' => $contrat
                ));

                //Si un avenant existe on ajoute les heures de l"avenant au nombre d'heure dispo
                if($avenant != null){
                    $nbHeureDisponible += $avenant->getNbHeure();
                }

                foreach ($listeHeureRealise as $heureRealise){
                    $nbHeureRealise += $heureRealise->getNbHeure();
                }


                if($nbHeureDisponible >= ( $nbHeureRealise + $heureEffectuee->getNbHeure() )){

                    $heureEffectuee->setContrat($contrat);
                    $heureEffectuee->setVerification(false);
                    $heureEffectuee->setHeurePayee(false);

                    $em->persist($heureEffectuee);

                    $em->flush();

                    $request->getSession()->getFlashBag()->add('notice', 'Heure ajouté !');
                }
                else{

                    $parameters = $em->getRepository('SUHConnexionBundle:Parameters')->find(1);
                    $request->getSession()->getFlashBag()->add('error', 'Vous avez dépassé le nombre d\'heures réalisables par votre contrat.
                                                                          Vous devez contacter le SUH ('.$parameters->getAdminMail().') pour réaliser d\'avantage d\'heures
                                                                          ');
                }

                //Test si la nature de la mision correspond à la nature d'un contrat


            }
            else{
                $request->getSession()->getFlashBag()->add('warning', 'La date de vos heures n\'est pas pris en charge par ce contrat !');
            }

        }


        return $this->render('SUHContratBundle:ZoneEtudiante:accueilEtudiant.html.twig', array(
            'form' => $form->createView(),
            'etudiant' => $etudiant,
            'contrat' => $contrat,
            'modeEdition' => false

        ));

    }

    public function editHeureEffectueeAction($idHeure, Request $request){
        $em = $this->getDoctrine()->getManager();
        $heureEffectuee = $em->getRepository('SUHContratBundle:HeureEffectuee')->find($idHeure);

        $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->findOneBy(array('user' => $this->getUser()));

        $contrat = $heureEffectuee->getContrat();

        if($contrat != null){
            foreach ($contrat->getNatureContrat() as $natureContrat){
                if($natureContrat == 'tutorat'){
                    $natureMission['tutorat'] = "Tutorat";
                }
                elseif ($natureContrat == 'priseNote'){
                    $natureMission['priseNote'] = "Prise de note";
                }
                else{
                    $natureMission['assistancePédagogique'] = "Assistance Pédagogique";
                }
            }
        }

        //formulaire pre-rempli
        $form = $this->get('form.factory')->create(new HeureEffectueeType, $heureEffectuee);
        $form->add('natureMission', 'choice', array(
            'choices' => $natureMission,
            'multiple' => false,
            'expanded' => true,

        ));


        if($form->handleRequest($request)->isValid()) {

            //Variables intermédiaires pour comparé par la suite les dates
            $dateEtudiant = date("Y-m-d", strtotime(strtr($heureEffectuee->getDateAndTime(), '/', '-')));
            $dateDebut = date("Y-m-d", strtotime(strtr($contrat->getDateDebutContrat(), '/', '-')));
            $dateFin = date("Y-m-d", strtotime(strtr($contrat->getDateFinContrat(), '/', '-')));

            $validateDate = false;
            //Compare si les date de l'heure sont cohérente avec les dates du contrat
            if (($dateEtudiant >= $dateDebut) && ($dateEtudiant <= $dateFin)) {

                //Nombre disponible pour le contrat
                $nbHeureDisponible = $contrat->getNbHeureInitiales();
                $nbHeureRealise = 0;

                $avenant = $em->getRepository('SUHContratBundle:Avenant')->findOneBy(array(
                    'contrat' => $contrat
                ));
                $listeHeureRealise = $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(array(
                    'contrat' => $contrat
                ));

                //Si un avenant existe on ajoute les heures de l"avenant au nombre d'heure dispo
                if ($avenant != null) {
                    $nbHeureDisponible += $avenant->getNbHeure();
                }

                foreach ($listeHeureRealise as $heureRealise) {
                    $nbHeureRealise += $heureRealise->getNbHeure();
                }


                if ($nbHeureDisponible >= ($nbHeureRealise + $heureEffectuee->getNbHeure())) {

                    $heureEffectuee->setContrat($contrat);
                    $heureEffectuee->setVerification(false);
                    $heureEffectuee->setHeurePayee(false);

                    $em->persist($heureEffectuee);

                    $em->flush();

                    $request->getSession()->getFlashBag()->add('notice', 'Heure edité !');
                } else {

                    $parameters = $em->getRepository('SUHConnexionBundle:Parameters')->find(1);
                    $request->getSession()->getFlashBag()->add('error', 'Vous avez dépassé le nombre d\'heures réalisables par votre contrat.
                                                                      Vous devez contacter le SUH (' . $parameters->getAdminMail() . ') pour réaliser d\'avantage d\'heures
                                                                      ');
                }


                //Test si la nature de la mision correspond à la nature d'un contrat


            } else {
                $request->getSession()->getFlashBag()->add('warning', 'La date de vos heures n\'est pas pris en charge par ce contrat !');
            }

        }

        return $this->render('SUHContratBundle:ZoneEtudiante:accueilEtudiant.html.twig', array(
            'form' => $form->createView(),
            'etudiant' => $etudiant,
            'modeEdition' => true,
            'contrat' => $contrat


        ));
    }

    public function validationHeuresAction(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->find($id);

        $contrat = $em->getRepository('SUHContratBundle:Contrat')->findOneBy(array(
            'etudiantAidant' => $etudiant,
            'active' => true
        ));

        $listeHeures = array();

        $listeHeures = $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(
            array(
                'contrat' => $contrat,
            ),
            array(
                'dateAndTime' => 'desc'
            )
        );

        if ($request->isMethod('POST')){

            $avenant = $em->getRepository('SUHContratBundle:Avenant')->findOneBy(array('contrat' => $contrat));

            $heureDejaValide= $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(
                array(
                    'contrat' => $contrat,
                    'verification' => true,
                )
            );


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

            foreach($listeHeures as $heure){
                $suppression = $request->request->get('heureToDelete'.$heure->getId());
                if($suppression == "on" && ($heure->getVerification() == false)){
                    $em->remove($heure);
                }
                
            }

            $em->flush();


           // return new Response($request->request->get('heure3'));
        }

        return $this->redirectToRoute("suh_contrat_gestionHeures", array(
            "id" => $id)
        );
    }

    public function paiementValidationHeuresAction(Request $request, $id){

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
                    $heure->setHeurePayee(true);

                }
                else{
                    $heure->setHeurePayee(false);


                }

                $em->persist($heure);
            }

            $em->flush();
           // return new Response($request->request->get('heure3'));
        }

        return $this->redirectToRoute("suh_contrat_showPaiementContrat", array(
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