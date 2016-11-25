<?php
/**
 * Created by PhpStorm.
 * User: lechi
 * Date: 24/11/2016
 * Time: 08:46
 */

namespace SUH\ContratBundle\Controller;

use DateTime;
use SUH\ContratBundle\Entity\Contrat;
use SUH\ContratBundle\Entity\ContratRepository;
use SUH\ContratBundle\Entity\EtudiantAidant;
use SUH\ContratBundle\Entity\HeureEffectuee;
use SUH\ContratBundle\Form\ContratType;
use SUH\ContratBundle\Form\HeureEffectueeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\DependencyInjection\ContainerInterface;



class HeureEffectueeController extends Controller
{
    public function addHeureEffectueeAction(Request $request){


        $em = $this->getDoctrine()->getManager();
        $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->findOneBy(array('user' => $this->getUser()));

        $heureEffectuee = new HeureEffectuee();
        $form = $this->get('form.factory')->create(new HeureEffectueeType, $heureEffectuee);

        $form->add('contrat', 'entity', array(
            'class' => 'SUHContratBundle:Contrat',
            'query_builder' => function(ContratRepository $repo) use($etudiant) {
                return $repo->getContratsPourUnEtudiant($etudiant);
            },
            'property' => 'natureContrat',
        ));

        if($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();

            $heureEffectuee->setVerification(false);

            $em->persist($heureEffectuee);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Heure ajouté !');

            return $this->redirect($this->generateUrl('suh_etudiant_homepageEtudiant'));
        }



        return $this->render('SUHContratBundle:AffichageContrats:accueilEtudiant.html.twig', array(
            'form' => $form->createView(),
            'etudiant' => $etudiant

        ));

    }

    public function editHeureEffectueeAction($idHeure, Request $request){
        $em = $this->getDoctrine()->getManager();
        $heureEffectuee = $em->getRepository('SUHContratBundle:HeureEffectuee')->find($idHeure);

        $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->findOneBy(array('user' => $this->getUser()));

        $form = $this->get('form.factory')->create(new HeureEffectueeType, $heureEffectuee);

        $form->add('contrat', 'entity', array(
            'class' => 'SUHContratBundle:Contrat',
            'query_builder' => function(ContratRepository $repo) use($etudiant) {
                return $repo->getContratsPourUnEtudiant($etudiant);
            },
            'property' => 'natureContrat',
        ));

        if($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($heureEffectuee);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Heure ajouté !');

            return $this->redirectToRoute("suh_etudiant_heuresEtudiant");
        }
        return $this->render('SUHContratBundle:AffichageContrats:accueilEtudiant.html.twig', array(
            'form' => $form->createView(),
            'etudiant' => $etudiant,
            'modeEdition' => true

        ));
    }


    public function deleteHeureEffectueeAction($idHeure, Request $request){
        $em = $this->getDoctrine()->getManager();
        $heure = $em->getRepository('SUHContratBundle:HeureEffectuee')->find($idHeure);

        $em->remove($heure);
        $em->flush();

        return $this->redirectToRoute("suh_etudiant_heuresEtudiant");
    }

    public function getUser(){
        $security = $this->container->get('security.context');

        // On récupère le token
        $token = $security->getToken();

        // Sinon, on récupère l'utilisateur
        return $token->getUser();
    }
}