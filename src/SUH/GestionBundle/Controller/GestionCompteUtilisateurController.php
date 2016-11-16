<?php

namespace SUH\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class GestionCompteUtilisateurController extends Controller {
    
    /**
     * Permet l'ajout d'un compte utilisateur
     * Les mots de passe sont hashés en sha512, convertis en base64, il y a 5000 itérations
     * @param Request $request
     */
    public function ajouterCompteUtilisateurAction(Request $request)
    {
        //Récupération données formulaire POST
        $username = $request->request->get('username');
        $pass = $request->request->get('pass');

        //Si il n'y a pas déjà un utilisateur avec le même identifiant
        $manager = $this->getDoctrine()->getManager();
        $userRepository = $manager->getRepository('SUHConnexionBundle:User');
		$a = $userRepository->findByUsername($username);
        if (empty($a)) {
            //Création d'un nouvel utilisateur
            $user = new \SUH\ConnexionBundle\Entity\User();

            //Encodage du mot de passe (sha512, converti en base64, 5000itérations)
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($pass, $user->getSalt());

            $user->setUsername($username);
            $user->setPassword($password);
            $user->setSalt('');
            $user->setRoles(array('ROLE_USER'));

            $manager->persist($user);
            $manager->flush();

            return $this->render('SUHGestionBundle:AffichageEtudiants:accueil.html.twig', array(
                        'userVue' => true,
            ));
        }
        return new Response('Identifiant déjà existant');
    }

    /**
     * Permet de supprimer d'un compte utilisateur
     * Les mots de passe sont hashés en sha512, convertis en base64, il y a 5000 itérations
     * @param Request $request
     */
    public function supprimerCompteUtilisateurAction(Request $request) {
        //Récupération données formulaire POST
        $username = $request->request->get('username');
        $pass = $request->request->get('pass');

        //Si il n'y a pas l'identifiant dans la base de données c'est que cet utilisateur n'existe pas
        $manager = $this->getDoctrine()->getManager();
        $userRepository = $manager->getRepository('SUHConnexionBundle:User');        
        $arrayUser = $userRepository->findByUsername($username);     
        
        if (empty($arrayUser)) {
            return new Response('Identifiant ou mot de passe incorrect');
        }
        
        //Si il y a moins d'un utilisateur on ne peut pas le supprimer
        if($userRepository->getNbUser() <= 1)
        {
            return new Response("Suppression impossible, il ne reste qu'un utilisateur");
        }
        
        $user=$arrayUser[0];
        //hash du mot de passe pour le comparer
        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', true, 5000);
        $password = $encoder->encodePassword($pass, $user->getSalt());
        
        if($user->getPassword()===$password)
        {
            $manager->remove($user);
            $manager->flush();
            return $this->render('SUHGestionBundle:AffichageEtudiants:accueil.html.twig', array(
                    'userVue' => true,
            ));
        }
        return new Response('Identifiant ou mot de passe incorrect');
        
    }

}
