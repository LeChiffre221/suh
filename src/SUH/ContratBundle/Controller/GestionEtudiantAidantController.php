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
	//renvoi la liste des étudiants
    public function getListeEtudiants($chaine)
    {      
        $etudiantRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHContratBundle:EtudiantAidant');

        
        if(empty($chaine))
        {

            $listEtudiant = $etudiantRepository->findAll();
            $em = $this->getDoctrine()->getManager();

            foreach ($listEtudiant as $etudiant){

                $nbHeureNonValide = $em->getRepository('SUHContratBundle:HeureEffectuee')-> selectNbHeureNonValidePourUnEtudiant($etudiant);
                $etudiant->setHeureNonValide($nbHeureNonValide[1]);



            }
            return $etudiantRepository->findAll();

        } else {
            
            return $etudiantRepository->getListeEtudiantsRecherche($chaine);
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

    //Ajouter etudiant
    public function addEtudiantAidantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $etudiantassiste = array();
        $etudiantAidant = new EtudiantAidant();
        $controllerAffichage = $this->forward('controller_affichage:getListeEtudiants', array());

        $parameters = $em->getRepository('SUHContratBundle:Parameters');
        $emailAdmin = $parameters->find(1)->getAdminMail();
        $hostDb = $parameters->find(1)->getHostMail();
        $portDb = $parameters->find(1)->getPortMail();
        $userDb = $parameters->find(1)->getUsernameMail();
        $passwordDb = $parameters->find(1)->getPasswordMail();

        var_dump($emailAdmin);
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


            //ENVOI EMAIL

            $emailEtu = $request->request->get('mailPerso');

            // surcharge du parameters.yml
            $transport = \Swift_SmtpTransport::newInstance($hostDb,$portDb)
                ->setUsername($userDb)
                ->setPassword($passwordDb)
            ;

            
            $mailer = \Swift_Mailer::newInstance($transport);

            $message = \Swift_Message::newInstance()
            ->setSubject('SUH - Vos identifiants de connexion')
            ->setFrom($emailAdmin)
            ->setTo($emailEtu)
            ->setBody(
                $this->renderView(
                    'SUHContratBundle:Emails:registration.html.twig'
                ),
                'text/html'
            );

            $mailer->send($message);

            //Persist en base  
            $em->persist($user);
            $em->persist($etudiantAidant);
            $em->flush();

            

            // Render
            return $this->render('SUHContratBundle:AffichageContrats:addEtudiantAidant.html.twig', array(
            'info' => $etudiantassiste, 
            'form' => $form->createView(),
            'listeEtudiantsAidants'=>$this->getListeEtudiants(null),
        )); 

	    }


        return $this->render('SUHContratBundle:AffichageContrats:addEtudiantAidant.html.twig', array(
            'info' => $etudiantassiste, 
            'form' => $form->createView(),
            'listeEtudiantsAidants'=>$this->getListeEtudiants(null),
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

        //On recupere les entites correspondantes a $id
        $idEtudiantAidant = $etudiantAidant->find($id);
        $idEtudiantInformations = $etudiantInformations->find($idEtudiantAidant->getEtudiantInformations());
        $idFormation = $formation->find($idEtudiantAidant->getEtudiantFormation());
        $idContrat = $contrat->findByEtudiantAidant($idEtudiantAidant);
        $idHeures = $heure->findByContrat($idContrat);

        foreach($idContrat as $contrat)
        {
            $em->remove($contrat);
        }
        //On supprime les entites
        $em->remove($idEtudiantAidant);
        $em->remove($idEtudiantInformations);
        $em->remove($idFormation);

        
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Etudiant supprimé');
        return $this->redirectToRoute('suh_contrat_homepage');
        
    }

    //Edition etudiant
    public function editEtudiantAidantAction($id, Request $request)
    {

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
            'listeEtudiantsAidants'=>$this->getListeEtudiants(null),
            'nbContrats'=>$this->getNbContrats($id),
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

}
