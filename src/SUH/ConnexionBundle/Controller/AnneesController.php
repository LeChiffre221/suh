<?php

namespace SUH\ConnexionBundle\Controller;


use SUH\GestionBundle\Entity\Annee;

use SUH\ConnexionBundle\Form\AnneeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AnneesController extends Controller
{

    /**
     * récupère les données d'un contrat 
     * @return type
     */
    public function AfficherGestionAnneesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $annee = new Annee();

        $form = $this->get('form.factory')->create(new AnneeType, $annee);
        if($request->isMethod("POST")) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $array = $form->getData();

                $anneeUniversitaire =  $array['annee']."-".($array['annee']+1);

                $em->persist($annee);

                try{
                    $em->flush();
                    $request->getSession()->getFlashBag()->add('notice', 'Nouvelle année ajoutée ');

                }
                catch(\Doctrine\DBAL\DBALException $e ){

                    $request->getSession()->getFlashBag()->add('error', 'Cette année existe déjà...');

                }
                finally{
                    return $this->redirectToRoute('suh_user_annees');
                }


            }
        }

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('SUHGestionBundle:Annee')
        ;

        $listYears = $repository->myFindAll();

        return $this->render('SUHConnexionBundle:Connexion:gestionYear.html.twig', array(
            'form' => $form->createView(),
            'listYears' => $listYears
            ));

    }
}