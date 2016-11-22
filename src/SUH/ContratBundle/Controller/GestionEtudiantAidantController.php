<?php

namespace SUH\ContratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SUH\ContratBundle\Entity\EtudiantAidant;
use SUH\ContratBundle\Form\EtudiantAidantType;
use SUH\ContratBundle\Controller\Affichage;

class GestionEtudiantAidantController extends Controller
{
	
    //Ajouter etudiant
    public function addEtudiantAidantAction(Request $request)
    {

        $etudiantassiste = array();
        $etudiantAidant = new EtudiantAidant();
        $controllerAffichage = $this->forward('app.hello_controller:indexAction', array('name' => $name));

        /* formulaire */

        $form = $this->get('form.factory')->create(new EtudiantAidantType(), $etudiantAidant);

        if ($form->handleRequest($request)->isValid()) {

	       $em = $this->getDoctrine()->getManager();


            /*génération pass/user*/

            //Récupération données formulaire POST
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            // $username = $nom.charAt(0) + $prenom.charAt(0);
            // $pass = substr( md5(rand()), 0, 5);

            // //Si il n'y a pas déjà un utilisateur avec le même identifiant
            // $userRepository = $em->getRepository('SUHConnexionBundle:User');
            // $a = $userRepository->findByUsername($username);
            // if (empty($a)) {
            //     //Création d'un nouvel utilisateur
            //     $user = new \SUH\ConnexionBundle\Entity\User();

            //     //Encodage du mot de passe (sha512, converti en base64, 5000itérations)
            //     $factory = $this->get('security.encoder_factory');
            //     $encoder = $factory->getEncoder($user);
            //     $password = $encoder->encodePassword($pass, $user->getSalt());

            //     $user->setUsername($username);
            //     $user->setPassword($password);
            //     $user->setSalt('');
            //     $user->setRoles(array('ROLE_USER'));

            //     $em->persist($user);
            // }

            //ENVOI EMAIL
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


	      $em->persist($etudiantAidant);
	      $em->flush();
	      $request->getSession()->getFlashBag()->add('notice', 'Etudiant aidant inscrit');
          return $this->redirectToRoute('suh_contrat_homepage');

	    }


        return $this->render('SUHContratBundle:AffichageContrats:addEtudiantAidant.html.twig', array(
            'info' => $etudiantassiste, 
            'form' => $form->createView(),
            'listeEtudiantsAidants' => $controllerAffichage->getListeEtudiants(null)
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

        //On recupere les entites correspondantes a $id
        $idEtudiantAidant = $etudiantAidant->find($id);
        $idEtudiantInformations = $etudiantInformations->find($idEtudiantAidant->getEtudiantInformations());
        $idFormation = $formation->find($idEtudiantAidant->getEtudiantFormation());

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
            $em->persist($contrat);
            $em->flush();

            return $this->redirect($this->generateUrl('suh_contrat_showEtudiantAidant', array(
                'id' => $id,
            )));
        }

        return $this->render('SUHContratBundle:AffichageContrats:editEtudiantAidant.html.twig',array(
            'informationsEtudiantAidant' => $etudiant,
            'form' => $form->createView(),
            )); 

    }

}
