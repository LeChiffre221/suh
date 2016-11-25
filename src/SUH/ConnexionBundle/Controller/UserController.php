<?php
/**
 * Created by PhpStorm.
 * User: lechi
 * Date: 25/11/2016
 * Time: 14:15
 */

namespace SUH\ConnexionBundle\Controller;


use SUH\ConnexionBundle\Entity\User;
use SUH\ConnexionBundle\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function afficherListeUtilisateurAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $listeUtilisateur = 0;// $em->getRepository('SUHConnexionBundle:User')->find();

        $user = new User();
        $form = $this->get('form.factory')->create(new UtilisateurType, $user);
        $form->remove('connexion');

        if ($form->handleRequest($request)->isValid()) {

            $user->setRoles();

            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Administrateur ajouté !');

            
        }

        return $this->render('SUHConnexionBundle:Connexion:gestionUser.html.twig', array(
         'listeUtilisateur' => $listeUtilisateur,
        ));
    }
}