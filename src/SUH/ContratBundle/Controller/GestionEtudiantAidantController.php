<?php

namespace SUH\ContratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SUH\ContratBundle\Entity\EtudiantAidant;
use SUH\ContratBundle\Form\EtudiantAidantType;
use SUH\ContratBundle\Controller\AffichageController;

class GestionEtudiantAidantController extends Controller
{
	//renvoi la liste des étudiants
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

    //Ajouter etudiant
    public function addEtudiantAidantAction(Request $request)
    {

        $etudiantassiste = array();
        $etudiantAidant = new EtudiantAidant();
        $controllerAffichage = $this->forward('controller_affichage:getListeEtudiants', array());

        /* formulaire */

        $form = $this->get('form.factory')->create(new EtudiantAidantType(), $etudiantAidant);

        if ($form->handleRequest($request)->isValid()) {

	       $em = $this->getDoctrine()->getManager();


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
            /*$factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($pass, $user->getSalt());*/

            $user->setUsername($username);
            $user->setPassword($pass);
            $user->setSalt('');
            $user->setRoles(array('ROLE_USER'));

            $etudiantAidant->setUser($user);


            //ENVOI EMAIL
            /*
            $email = $request->request->get('mailPerso');

            $message = \Swift_Message::newInstance()
            ->setSubject('SUH - Vos identifiants de connexion')
            ->setFrom('couyperraispierre@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    // emmanuelle.feschet@udamail.fr
                    'SUHContratBundle:Emails:registration.html.twig'
                ),
                'text/html'
            );
            $this->get('mailer')->send($message);

            */
            $em->persist($user);
            $em->persist($etudiantAidant);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Etudiant aidant inscrit');
            return $this->redirectToRoute('suh_contrat_homepage');

	    }


        return $this->render('SUHContratBundle:AffichageContrats:addEtudiantAidant.html.twig', array(
            'info' => $etudiantassiste, 
            'form' => $form->createView(),
            'listeEtudiantsAidants'=>$this->getListeEtudiants(null)
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

        //On recupere les entites correspondantes a $id
        $idEtudiantAidant = $etudiantAidant->find($id);
        $idEtudiantInformations = $etudiantInformations->find($idEtudiantAidant->getEtudiantInformations());
        $idFormation = $formation->find($idEtudiantAidant->getEtudiantFormation());
        $idContrat = $contrat->findByEtudiantAidant($idEtudiantAidant);

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
            'id' => $id
            )); 

    }

}
