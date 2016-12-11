<?php

namespace SUH\ContratBundle\Controller;


use DateTime;
use SUH\ContratBundle\Entity\Contrat;
use SUH\ContratBundle\Form\ContratType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class ContratController extends Controller
{
    public function addContratAction(Request $request, $id){

        $session = $this->getRequest()->getSession(); // Get started session

        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }

        $contrat = new Contrat();
        $form = $this->get('form.factory')->create(new ContratType, $contrat);
        $form->remove('listeAvenant');
        $form->remove('etablissementAvenant');
        $form->remove('dateEnvoiDRH');
        $form->remove('dateEnvoiEtudiant');
        $form->remove('dateEnvoiAvenantDRH');
        $form->remove('dateEnvoiAvenantEtudiant');


        if ($form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->find($id);

            $listeContrat = $em->getRepository('SUHContratBundle:Contrat')->findBy(array("etudiantAidant" => $etudiant));

            $nbContratEnCours = 0;
            foreach($listeContrat as $contrat){
                if($contrat->getActive()){
                    $nbContratEnCours++;
                }
            }

            if($nbContratEnCours == 0){
                $contrat->setActive(true);
                $contrat->setMiseEnPaiement(false);
                $contrat->setEtudiantAidant($etudiant);

                $dateDebut = date("Y-m-d", strtotime(strtr($contrat->getDateDebutContrat(), '/', '-')));
                $dateFin = date("Y-m-d", strtotime(strtr($contrat->getDateFinContrat(), '/', '-')));
                if($dateDebut > $dateFin){
                    $contrat->setDateDebutContrat(null);
                    $contrat->setDateFinContrat(null);

                    $request->getSession()->getFlashBag()->add('warning', 'La date de début ne peut être supèrieure à la date de fin');


                }
                else{
                    $em->persist($contrat);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('notice', 'Contrat ajouté !');

                }
                return $this->redirect($this->generateUrl('suh_contrat_afficherContrat', array(
                    'id' => $id,
                )));
            }
            else{
                $request->getSession()->getFlashBag()->add('error', 'Un contrat actif existe déjà pour cet étudiant !');
            }

        }
        return $this->render('SUHContratBundle:AffichageContrats:addContrat.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
            'nbContrats'=>$this->getNbContrats($id, false),
            'nbContratsPaiement'=>$this->getNbContrats($id, true),
        ));
    }


  public function editContratAction($idContrat, Request $request){

        $session = $this->getRequest()->getSession(); // Get started session

        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }

        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->find($idContrat);

        $avenant = $em->getRepository('SUHContratBundle:Avenant')->findBy(array(
            'contrat' => $contrat
        ));
        $contrat->setListeAvenant($avenant);

        $form = $this->get('form.factory')->create(new ContratType, $contrat);

        if($contrat->getDateEnvoiDRH() == null){
           $form->remove('dateEnvoiDRH');
        }
        if($contrat->getDateEnvoiEtudiant() == null){
            $form->remove('dateEnvoiEtudiant');
        }


        if ($form->handleRequest($request)->isValid()) {


            $dateDebut = date("Y-m-d", strtotime(strtr($contrat->getDateDebutContrat(), '/', '-')));
            $dateFin = date("Y-m-d", strtotime(strtr($contrat->getDateFinContrat(), '/', '-')));
            if($dateDebut > $dateFin){
                $contrat->setDateDebutContrat(null);
                $contrat->setDateFinContrat(null);

                $request->getSession()->getFlashBag()->add('warning', 'La date de début ne peut être supèrieure à la date de fin');


            }
            else {
                $em = $this->getDoctrine()->getManager();
                $em->persist($contrat);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Contrat édité !');

                return $this->redirect($this->generateUrl('suh_contrat_afficherContrat', array(
                    'id' => $contrat->getEtudiantAidant()->getId(),
                )));
            }
        }

        return $this->render('SUHContratBundle:AffichageContrats:editContrat.html.twig', array(
            'form' => $form->createView(),
            'id' => $contrat->getEtudiantAidant()->getId(),
            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
            'nbContrats'=>$this->getNbContrats($contrat->getEtudiantAidant()->getId(), false),
            'nbContratsPaiement'=>$this->getNbContrats($contrat->getEtudiantAidant()->getId(), true),
        ));

    }

    public function deleteContratAction($idContrat, Request $request){

        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session)
            $session = new Session(); // if there is no session, start it

        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->find($idContrat);

        $avenants = $em->getRepository('SUHContratBundle:Avenant')->findBy(array(
            'contrat' => $contrat
        ));

        $heureEffectuee = $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(array(
            'contrat' => $contrat)
        );


        $etudiant = $contrat->getEtudiantAidant();
        foreach ($heureEffectuee as $heure){
            $em->remove($heure);

        }
        foreach ($avenants as $avenant){
            $em->remove($avenant);

        }
        $em->remove($contrat);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Contrat supprimé !');


        if($session->get('suppressionContratFromArchive')){
            return $this->redirectToRoute('suh_contrat_showArchive', array('id' => $etudiant->getId()));
        }
        else{
            return $this->redirectToRoute('suh_contrat_afficherContrat', array('id' => $etudiant->getId()));
        }


    }

    public function getListeEtudiants($chaine)
    {      
        $etudiantRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHContratBundle:EtudiantAidant');

        
        if(empty($chaine))
        {

            $listEtudiant = $etudiantRepository->findAll();
            $em = $this->getDoctrine()->getManager();

            foreach ($listEtudiant as $etudiant){

                $nbHeureNonValide = $em->getRepository('SUHContratBundle:HeureEffectuee')-> selectNbHeureNonValidePourUnEtudiant($etudiant);
                $etudiant->setHeureNonValide($nbHeureNonValide[1]);



            }
            return $etudiantRepository->findAll();

        } else {
            
            return $etudiantRepository->getListeEtudiantsRecherche($chaine);
        }
    }

    //get nombre de contrats (pour pagination et compteur)
    public function getNbContrats($id, $paiement)
    {     
        $em = $this->getDoctrine()->getManager();
        $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->find($id);
        if($paiement){
            $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->findBy(
                array(
                'etudiantAidant' => $etudiant,
                'miseEnPaiement' => 1),
                array(
                'dateDebutContrat' => 'desc'
                )
           );
        } else {
            $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->findBy(
                array(
                'etudiantAidant' => $etudiant,
                'active' => 1),
                array(
                'dateDebutContrat' => 'desc'
                )
           );
        }
        if(!empty($listeContrats))
        {
           return count($listeContrats);

        } else {

            return 0;

        }
        
    }

    public function desarchiverContratAction($idContrat, Request $request){
        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->find($idContrat);

        $id = $contrat->getEtudiantAidant()->getId();

        $contrat->setActive(true);
        $em->persist($contrat);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Contrat desarchivé !');


        return $this->redirectToRoute('suh_contrat_showArchive', array('id' => $id));
    }

    public function archiverContratAction($idContrat, Request $request){
        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->find($idContrat);

        $id = $contrat->getEtudiantAidant()->getId();

        $listeHeures = array();
        $nonConforme = false;
        $listeHeures = $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(
            array(
                'contrat' => $contrat,
            )
        );

        foreach ($listeHeures as $heure){
            if(!$heure->getHeurePayee()){
                $nonConforme = true;
            }
        }

        if($nonConforme){

            $request->getSession()->getFlashBag()->add('error', 'Le contrat n\'est pas totalement payé !');
        } else {

        $contrat->setActive(false)
                ->setMiseEnPaiement(false)
        ;
        $em->persist($contrat);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Contrat archivé !');
        }

        return $this->redirectToRoute('suh_contrat_showPaiementContrat', array('id' => $id));
    }

    public function mettreEnPaiementAction($idContrat, Request $request){
        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->find($idContrat);

        $id = $contrat->getEtudiantAidant()->getId();

        $listeHeures = array();
        $nonConforme = false;
        $listeHeures = $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(
            array(
                'contrat' => $contrat,
            )
        );

        foreach ($listeHeures as $heure){
            if(!$heure->getVerification()){
                $nonConforme = true;
            }
        }

        if($nonConforme){

            $request->getSession()->getFlashBag()->add('error', 'Une heure du contrat n\'est pas validée !');
            return $this->redirectToRoute('suh_contrat_afficherContrat', array('id' => $id));

        } else {

            $contrat->setMiseEnPaiement(true);
            $contrat->setActive(false);
            $em->persist($contrat);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Contrat mis en paiement !');

            return $this->redirectToRoute('suh_contrat_showPaiementContrat', array('id' => $id));
        }

        

        
    }


    /**
     * @param Request $request
     * @param $idContrat
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addDateContratAction(Request $request, $idContrat){

        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->find($idContrat);


        if($request->isMethod('post')){

            if(!empty($request->request->get("dateEnvoiDRH"))){
                $dateEnvoiDRH = $request->request->get("dateEnvoiDRH");
                $dateEnvoiDRH = date("Y-m-d", strtotime(strtr($dateEnvoiDRH, '/', '-')));
                $contrat->setDateEnvoiDRH($dateEnvoiDRH);
            }

            if(!empty($request->request->get("dateEnvoiEtudiant"))){
                $dateEnvoiEtudiant = $request->request->get("dateEnvoiEtudiant");
                $dateEnvoiEtudiant= date("Y-m-d", strtotime(strtr($dateEnvoiEtudiant, '/', '-')));
                $contrat->setDateEnvoiEtudiant($dateEnvoiEtudiant);
            }



        }

        $em->persist($contrat);
        $em->flush();

        $etudiant = $contrat->getEtudiantAidant();
        return $this->redirectToRoute('suh_contrat_afficherContrat', array('id' => $etudiant->getId()));
    }





}