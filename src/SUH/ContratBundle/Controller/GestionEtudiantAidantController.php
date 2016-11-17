<?php

namespace SUH\ContratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SUH\ContratBundle\Entity\EtudiantAidant;
use SUH\ContratBundle\Form\EtudiantAidantType;

class GestionEtudiantAidantController extends Controller
{
	
    public function addEtudiantAidantAction(Request $request)
    {

        $etudiantassiste = array();
        $etudiantAidant = new EtudiantAidant();


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
	      $request->getSession()->getFlashBag()->add('notice', 'EtudiantAidant inscrit');

	    }

        

        



        return $this->render('SUHContratBundle:AffichageContrats:addEtudiantAidant.html.twig', array(
            'info' => $etudiantassiste, 
            'form' => $form->createView(),
        )); 
    }

}
