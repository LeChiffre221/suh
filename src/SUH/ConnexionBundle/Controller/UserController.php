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
use SUH\ConnexionBundle\SUHConnexionBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function afficherListeUtilisateurAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $listeUtilisateur = $em->getRepository('SUHConnexionBundle:User')->findByRole("ROLE_ADMIN");

        $user = new User();
        $form = $this->get('form.factory')->create(new UtilisateurType, $user);
        $form->remove('connexion');

        if ($form->handleRequest($request)->isValid()) {

            $user->setRoles(array("ROLE_ADMIN"));
            $user->setSalt("");

            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Administrateur ajouté !');

        }

        foreach ($user as $listeUtilisateur){
            var_dump($user['roles']);
        }

        return $this->render('SUHConnexionBundle:Connexion:gestionUser.html.twig', array(
            'form' => $form->createView(),
            'listeUsers' => $listeUtilisateur

        ));
    }

    /**
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteUser(Request $request, $idUser){
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("SUHConnexionBundle:User")->find($idUser);
        $em->remove($user);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute("suh_user_homepage");

    }
}