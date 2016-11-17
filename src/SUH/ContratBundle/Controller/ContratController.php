<?php
/**
 * Created by PhpStorm.
 * User: lechi
 * Date: 16/11/2016
 * Time: 08:47
 */

namespace SUH\ContratBundle\Controller;


use SUH\ContratBundle\Entity\Contrat;
use SUH\ContratBundle\Form\ContratType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContratController extends Controller
{
public function addContratAction(Request $request, $idEtudiant){

        $contrat = new Contrat();
        $form = $this->get('form.factory')->create(new ContratType, $contrat);



        if($form->handleRequest($request)->isValid()){


            $em = $this->getDoctrine()->getManager();

            // On l'étudiant pour lui ajouter un contrat
            $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->find($idEtudiant);

            $contrat->setEtudiantAidant($etudiant);
            $em->persist($contrat);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', "Contrat créé !");
            return $this->redirect($this->generateUrl('suh_contrat_homepage'));

        }

        return $this->render('SUHContratBundle:AffichageContrats:addContrat.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}