<?php
/**
 * Created by PhpStorm.
 * User: lechi
 * Date: 16/11/2016
 * Time: 08:47
 */

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
        $contrat = new Contrat();
        $form = $this->get('form.factory')->create(new ContratType, $contrat);
        $form->remove('etablissementAvenant');
        $form->remove('dateEnvoiDRH');
        $form->remove('dateEnvoiEtudiant');
        $form->remove('dateEnvoiAvenantDRH');
        $form->remove('dateEnvoiAvenantEtudiant');


        if ($form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->find($id);

            $contrat->setActive(true);
            $contrat->setEtudiantAidant($etudiant);

            $em->persist($contrat);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Contrat ajouté !');

            return $this->redirect($this->generateUrl('suh_contrat_afficherContrat', array(
                'id' => $id,
            )));
        }
        return $this->render('SUHContratBundle:AffichageContrats:addContrat.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'listeEtudiantsAidants'=>$this->getListeEtudiants(null),
            'nbContrats'=>$this->getNbContrats($id),
        ));
    }


  public function editContratAction($idContrat, Request $request){

        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->find($idContrat);

        $form = $this->get('form.factory')->create(new ContratType, $contrat);

        if($contrat->getDateEnvoiDRH() == null){
           $form->remove('dateEnvoiDRH');
        }
        if($contrat->getDateEnvoiEtudiant() == null){
            $form->remove('dateEnvoiEtudiant');
        }
        if($contrat->getDateEnvoiAvenantDRH() == null){
            $form->remove('dateEnvoiAvenantDRH');
        }
        if($contrat->getDateEnvoiAvenantEtudiant() == null){
            $form->remove('dateEnvoiAvenantEtudiant');
        }

        if ($form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($contrat);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Contrat édité !');

            return $this->redirect($this->generateUrl('suh_contrat_afficherContrat', array(
                'id' => $contrat->getEtudiantAidant()->getId(),
            )));
        }

        return $this->render('SUHContratBundle:AffichageContrats:editContrat.html.twig', array(
            'form' => $form->createView(),
            'contrat' => $contrat,
            'id' => $contrat->getEtudiantAidant()->getId(),
            'listeEtudiantsAidants'=>$this->getListeEtudiants(null),
            'nbContrats'=>$this->getNbContrats($contrat->getEtudiantAidant()->getId()),
        ));

    }

    public function deleteContratAction($idContrat, Request $request){

        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session)
            $session = new Session(); // if there is no session, start it

        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->find($idContrat);

        $etudiant = $contrat->getEtudiantAidant();
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

    public function getListeEtudiants()
    {
        $etudiantRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository('SUHContratBundle:EtudiantAidant');

        $listeEtudiantsAidants = $etudiantRepository->findAll();
        if(!empty($listeEtudiantsAidants))
        {
            return $listeEtudiantsAidants;
        }
    }
    public function getNbContrats($id)
    {     
        $em = $this->getDoctrine()->getManager();
        $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->find($id);
        $listeContrats = $em->getRepository('SUHContratBundle:Contrat')->findBy(
            array(
            'etudiantAidant' => $etudiant,
            'active' => 1),
            array(
            'dateDebutContrat' => 'desc'
            )
       );
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

        $contrat->setActive(false);
        $em->persist($contrat);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Contrat archivé !');

        return $this->redirectToRoute('suh_contrat_afficherContrat', array('id' => $id));
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

            if(!empty($request->request->get("dateEnvoiAvenantDRH"))){
                $dateEnvoiAvenantDRH = $request->request->get("dateEnvoiAvenantDRH");
                $dateEnvoiAvenantDRH  = date("Y-m-d", strtotime(strtr($dateEnvoiAvenantDRH, '/', '-') ));
                $contrat->setDateEnvoiAvenantDRH($dateEnvoiAvenantDRH );
            }

            if(!empty($request->request->get("dateEnvoiAvenantEtudiant"))){
                $dateEnvoiAvenantEtudiant = $request->request->get("dateEnvoiAvenantEtudiant");
                $dateEnvoiAvenantEtudiant= date("Y-m-d", strtotime(strtr($dateEnvoiAvenantEtudiant, '/', '-')));
                $contrat->setDateEnvoiAvenantEtudiant($dateEnvoiAvenantEtudiant);
            }

        }

        $em->persist($contrat);
        $em->flush();

        $etudiant = $contrat->getEtudiantAidant();
        return $this->redirectToRoute('suh_contrat_afficherContrat', array('id' => $etudiant->getId()));
    }



}