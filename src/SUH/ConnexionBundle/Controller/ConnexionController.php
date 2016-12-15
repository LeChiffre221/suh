<?php

namespace SUH\ConnexionBundle\Controller;

use SUH\GestionBundle\Entity\Annee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class ConnexionController extends Controller
{
    /**
     * gère l'authentification
     * @param Request $request
     */
    public function loginAction(Request $request)
    {       
         // Si le visiteur est déjà identifié, on le redirige vers l'accueil      
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
          return $this->redirect($this->generateUrl('suh_choix_homepage'));
        }               
        
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('SUHConnexionBundle:Connexion:connexion.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }

    /**
     * gère l'authentification
     * @param Request $request
     */
    public function indexAction(Request $request)
    {        
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        if ($this->get('security.context')->isGranted('ROLE_ADMIN')){



            $em = $this->getDoctrine()->getManager();
            $annee = $em->getRepository('SUHGestionBundle:Annee')->findBy(array(), array('anneeUniversitaire' => 'desc'), 1);

            $date = date('Y-m-d');
            if($annee == null){
                $annee = new Annee();


                if(intval(substr($date, 5, 2)) > 8){
                    $annee->setAnneeUniversitaire((substr($date, 0, 4)).'-'.(intval(substr($date, 0, 4))+1));
                }
                else{
                    $annee->setAnneeUniversitaire((intval(substr($date, 0, 4))+1).'-'.(substr($date, 0, 4)));
                }

                $em->persist($annee);
                $em->flush();
            }

            $annee = $em->getRepository('SUHGestionBundle:Annee')->findBy(array(), array('anneeUniversitaire' => 'desc'), 1);

            $session->set('filter', array(
                'year' => $annee[0]->getAnneeUniversitaire()
            ));

            return $this->render('SUHConnexionBundle:Connexion:index.html.twig', array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            ));
        }
        else if($this->get('security.context')->isGranted('ROLE_USER')){

            return $this->redirectToRoute('suh_etudiant_homepageEtudiant');
        }

    }
}
