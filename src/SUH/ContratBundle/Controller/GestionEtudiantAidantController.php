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

        $form = $this->get('form.factory')->create(new EtudiantAidantType(), $etudiantAidant);

        if ($form->handleRequest($request)->isValid()) {

	      $em = $this->getDoctrine()->getManager();
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
