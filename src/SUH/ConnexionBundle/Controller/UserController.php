<?php

namespace SUH\ConnexionBundle\Controller;


use SUH\ConnexionBundle\Entity\User;
use SUH\ConnexionBundle\Form\UtilisateurType;
use SUH\ConnexionBundle\SUHConnexionBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{
    public function afficherListeUtilisateurAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $listeUtilisateur = $em->getRepository('SUHConnexionBundle:User')->findByRole("ROLE_ADMIN");

        $user = new \SUH\ConnexionBundle\Entity\User();
        

        $form = $this->get('form.factory')->create(new UtilisateurType, $user);
        $form->remove('connexion');

        if ($form->handleRequest($request)->isValid()) {

            
            $pass = $user->getPassword();
            $username = $user->getUsername();

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


            //Encodage du mot de passe (sha512, converti en base64, 5000itérations)
            $user = new \SUH\ConnexionBundle\Entity\User();
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($pass, '');

            $user->setUsername($username);
            $user->setPassword($password);
            $user->setSalt('');
            $user->setRoles(array("ROLE_ADMIN"));
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $pass);

            return $this->redirect($this->generateUrl('suh_user_homepage'));

        }

        // foreach ($user as $listeUtilisateur){
        //     var_dump($user['roles']);
        // }

        return $this->render('SUHConnexionBundle:Connexion:gestionUser.html.twig', array(
            'form' => $form->createView(),
            'listeUsers' => $listeUtilisateur

        ));
    }

    /**
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteUserAction(Request $request, $idUser){
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("SUHConnexionBundle:User")->find($idUser);
        $em->remove($user);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Administrateur supprimé !');
        return $this->redirectToRoute("suh_user_homepage");

    }
}