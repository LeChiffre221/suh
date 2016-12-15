<?php

namespace SUH\ContratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use SUH\ContratBundle\Entity\EtudiantAidant;
use SUH\ContratBundle\Form\EtudiantAidantType;
use SUH\ContratBundle\Controller\AffichageController;

class GestionEtudiantAidantController extends Controller
{
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
                'miseEnPaiement' => 1),
                array(
                'dateDebutContrat' => 'desc'
                )
           );
        } else {
            $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->findBy(
                array(
                'etudiantAidant' => $etudiant,
                'active' => 1),
                array(
                'dateDebutContrat' => 'desc'
                )
           );
        }
        if(!empty($listeContrats))
        {
           return count($listeContrats);

        } else {

            return 0;

        }
        
    }

    //Ajouter etudiant
    public function addEtudiantAidantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }

        $etudiantassiste = array();
        $etudiantAidant = new EtudiantAidant();
        $controllerAffichage = $this->forward('controller_affichage:getListeEtudiants', array());

        //ENVOI EMAIL

        /*
        $message = \Swift_Message::newInstance()
            ->setSubject("SUH - Identifiant de connexion")
            ->setFrom(array("mthgaume@gmail.com" => "Webmaster"))
            ->setTo("mthgaume@gmail.com")
            ->setCharset("utf-8")
            ->setContentType("text/html")
            ->setBody($this->renderView('SUHContratBundle:Emails:registration.html.twig'));

        $this->get('mailer')->send($message);


       */ $parameters = $em->getRepository('SUHConnexionBundle:Parameters');

        $annee = $this->get('session')->get('filter');    

        $parameters = $em->getRepository('SUHConnexionBundle:Parameters');
        $listannees = $em->getRepository('SUHGestionBundle:Annee')->findByAnneeUniversitaire($annee['year']);
        $emailAdmin = $parameters->find(1)->getAdminMail();
        $hostDb = $parameters->find(1)->getHostMail();
        $portDb = $parameters->find(1)->getPortMail();
        $userDb = $parameters->find(1)->getUsernameMail();
        $passwordDb = $parameters->find(1)->getPasswordMail();

        /* formulaire */

        $form = $this->get('form.factory')->create(new EtudiantAidantType(), $etudiantAidant);

        if ($form->handleRequest($request)->isValid()) {

            /*génération pass/user*/

            //Récupération données formulaire POST
            $nom = $etudiantAidant->getEtudiantInformations()->getNom();
            $prenom = $etudiantAidant->getEtudiantInformations()->getPrenom();
            $username = substr($prenom,0,2).substr($nom,0,5);
            $username = strtolower($username);


            $pass = substr(md5(uniqid(mt_rand(), true)), 0, 6);

            //Si il n'y a pas déjà un utilisateur avec le même identifiant
            $i= 0;
            $usernameTmp = $username;
            do{
                if($i != 0){
                    $username = $usernameTmp.$i;
                }

                $userRepository = $em->getRepository('SUHConnexionBundle:User');
                $user = $userRepository->findByUsername($username);

                $i++;

            }while(!empty($user));


            //Création d'un nouvel utilisateur
            $user = new \SUH\ConnexionBundle\Entity\User();

            //Encodage du mot de passe (sha512, converti en base64, 5000itérations)
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($pass, '');
            // Message d'event
            $request->getSession()->getFlashBag()->add('notice',  $pass);

            $user->setUsername($username);
            $user->setPassword($password);
            $user->setSalt('');
            $user->setRoles(array('ROLE_USER'));

            $etudiantAidant->setUser($user);

            foreach($listannees as $anneeListe){
                $etudiantAidant->addAnnee($anneeListe);
            }
            


            /*
            $emailEtu = $request->request->get('mailInstitutionnel');

            // // surcharge du parameters.yml
            // $transport = \Swift_SmtpTransport::newInstance($hostDb,$portDb)
            //     ->setUsername($userDb)
            //     ->setPassword($passwordDb)
            // ;

            
            // $mailer = \Swift_Mailer::newInstance($transport);

            // $message = \Swift_Message::newInstance()
            // ->setSubject('SUH - Vos identifiants de connexion')
            // ->setFrom($emailAdmin)
            // ->setTo($emailEtu)
            // ->setBody(
            //     $this->renderView(
            //         'SUHContratBundle:Emails:registration.html.twig'
            //     ),
            //     'text/html'

            $mailer->send($message);*/

            // $mailer->send($message);

            //Persist en base  
            $em->persist($user);
            $em->persist($etudiantAidant);
            $em->flush();

            

            // Render
            return $this->render('SUHContratBundle:AffichageContrats:addEtudiantAidant.html.twig', array(
            'info' => $etudiantassiste, 
            'form' => $form->createView(),
            'listeEtudiantsAidants' => $this->getListeEtudiants($session->get('chaine'), $annee),
        )); 

	    }


        return $this->render('SUHContratBundle:AffichageContrats:addEtudiantAidant.html.twig', array(
            'info' => $etudiantassiste, 
            'form' => $form->createView(),
            'listeEtudiantsAidants' => $this->getListeEtudiants($session->get('chaine'), $annee),
        )); 
    }



    //Suppression d'etudiant
    public function delEtudiantAidantAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //On recupere les entites
        $etudiantAidant = $em->getRepository('SUHContratBundle:EtudiantAidant');
        $etudiantInformations = $em->getRepository('SUHGestionBundle:EtudiantInformations'); 
        $formation = $em->getRepository('SUHGestionBundle:Formation'); 
        $contrat = $em->getRepository('SUHContratBundle:Contrat');
        $heure = $em->getRepository('SUHContratBundle:HeureEffectuee');




        $idEtudiantAidant = $etudiantAidant->find($id);

        $arrayAnneeEtudiant = array();
        foreach ($idEtudiantAidant->getAnnees() as $uneAnnee){
            array_push($arrayAnneeEtudiant, $uneAnnee);
        }


        if(sizeof($arrayAnneeEtudiant) == 1){
            //On recupere les entites correspondantes a $id

            $etudiant = $em->getRepository('SUHGestionBundle:Etudiant')->find($idEtudiantAidant->getEtudiant()->getId());

            $idEtudiantInformations = $etudiantInformations->find($idEtudiantAidant->getEtudiantInformations());
            $idFormation = $formation->find($idEtudiantAidant->getEtudiantFormation());
            $idContrat = $contrat->findByEtudiantAidant($idEtudiantAidant);
            $idHeures = $heure->findByContrat($idContrat);

            foreach($idContrat as $contrat)
            {
                $listeAvenants = $em->getRepository('SUHContratBundle:Avenant')->findBy(array("contrat" => $contrat));
                foreach ($listeAvenants as $avenant){
                    $em->remove($avenant);
                }
                $em->remove($contrat);
            }
            //On supprime les entites
            $em->remove($etudiant);
            $em->remove($idEtudiantAidant);
            $em->remove($idEtudiantInformations);
            $em->remove($idFormation);
            foreach($idHeures as $heure){
                $em->remove($heure);
            }
        }else{

            $session = $this->getRequest()->getSession(); // Get started session
            foreach ( $arrayAnneeEtudiant as $uneAnnee){
                $year = $session->get('filter');

                if($uneAnnee->getAnneeUniversitaire() == $year['year']){
                    $idEtudiantAidant->removeAnnee($uneAnnee);
                }
            }

            $em->persist($idEtudiantAidant);
        }
        
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Etudiant supprimé');
        return $this->redirectToRoute('suh_contrat_homepage');
        
    }

    //Edition etudiant
    public function editEtudiantAidantAction($id, Request $request)
    {

        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }
        $annee = $session->get('filter');

        $etudiantAidantRepo = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHContratBundle:EtudiantAidant');
        
        $etudiant = $etudiantAidantRepo->find($id);

        $form = $this->get('form.factory')->create(new EtudiantAidantType, $etudiant);

        

        if ($form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($etudiant);
            $em->flush();

            return $this->redirect($this->generateUrl('suh_contrat_showEtudiantAidant', array(
                'id' => $id,
            )));
        }

        return $this->render('SUHContratBundle:AffichageContrats:editEtudiantAidant.html.twig',array(
            'informationsEtudiantAidant' => $etudiant,
            'form' => $form->createView(),
            'listeEtudiantsAidants' => $this->getListeEtudiants($session->get('chaine'), $annee),
            'nbContrats'=>$this->getNbContrats($id, false),
            'nbContratsPaiement'=>$this->getNbContrats($id, true),
            'id' => $id
            )); 

    }


    //ResetPassword
    public function resetPasswordEtudiantAidantAction(Request $request, $id)
    {

        $session = $this->getRequest()->getSession(); // Get started session

        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }

        $em = $this->getDoctrine()->getManager();

        $etudiantAidantRepo = $em->getRepository('SUHContratBundle:EtudiantAidant');
        $userRepo = $em->getRepository('SUHConnexionBundle:User');

        $etudiant = $etudiantAidantRepo->find($id);
        $user = $userRepo->find($etudiant->getUser());

        $pass = substr(md5(uniqid(mt_rand(), true)), 0, 6);

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($pass, '');


        $user->setPassword($password);

        $em->persist($user);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $pass);
        return $this->redirectToRoute('suh_contrat_showEtudiantAidant', array(
                'id' => $id,
            ));
        
    }
    

    //Reinscrire des etudiants
    public function reinscriptionEtudiantAidantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $this->getRequest()->getSession(); // Get started session

        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }

        $listeEtudiant = $this->getListeEtudiants($session->get('chaine'), $session->get('filterEtu'));

        if ($request->isMethod('post')){

            $selectEtuYear = $request->request->get('selectEtu');


            foreach($listeEtudiant as $etudiant){

                $etu = $request->request->get('etudiant-'.$etudiant->getId());
                var_dump($etu);

                if($etu == 'on'){
                    $anneeSelectionne =$em->getRepository('SUHGestionBundle:Annee')->findOneBy(array(
                        'anneeUniversitaire' => $selectEtuYear)
                    );

                    $arrayAnneeEtudiant = array();
                    foreach ($etudiant->getAnnees() as $uneAnnee){
                        array_push($arrayAnneeEtudiant, $uneAnnee);
                    }
                    if(!in_array($anneeSelectionne, $arrayAnneeEtudiant)){

                        $etudiant->addAnnee($anneeSelectionne);
                        $em->persist($etudiant);
                    }


                }

            }

            $em->flush();

        }
       
        return $this->redirectToRoute('suh_contrat_reinscription');
        
    }

}
