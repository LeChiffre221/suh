<?php

namespace SUH\ConnexionBundle\Controller;


use SUH\ConnexionBundle\Entity\Parameters;
use SUH\ConnexionBundle\Form\ParametersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\Session;
class ParametersController extends Controller
{

    //Afficher page parametre
    public function AfficherParametersAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $parameters = $em->getRepository('SUHConnexionBundle:Parameters')->find(1);
        $form = $this->get('form.factory')->create(new ParametersType, $parameters);


        if ($form->handleRequest($request)->isValid()) {

            $em->persist($parameters);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Variables éditées !');

            return $this->redirect($this->generateUrl('suh_user_parameters'));
        }
        return $this->render('SUHConnexionBundle:Connexion:gestionParameters.html.twig', array(
            'form' => $form->createView()
        ));
    }
}