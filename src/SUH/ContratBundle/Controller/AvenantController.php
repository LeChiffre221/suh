<?php
/**
 * Created by PhpStorm.
 * User: lechi
 * Date: 30/11/2016
 * Time: 10:38
 */

namespace SUH\ContratBundle\Controller;


use SUH\ContratBundle\Entity\Avenant;
use SUH\ContratBundle\Form\AvenantType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AvenantController extends Controller
{
    public function addAvenantAction(Request $request, $idContrat){
        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository("SUHContratBundle:Contrat")->find($idContrat);

        $avenant = new Avenant();
        $form = $this->get('form.factory')->create(new AvenantType, $avenant);
        $form->remove('dateEnvoiDRH');
        $form->remove('dateEnvoiEtudiant');

        $idEtudiant = $contrat->getEtudiantAidant()->getId();

        if ($form->handleRequest($request)->isValid()) {

            $avenant->setContrat($contrat);

            $em->persist($avenant);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Avenant ajouté !');

            return $this->redirect($this->generateUrl('suh_contrat_afficherContrat', array(
                'id' => $idEtudiant,
            )));
        }
        return $this->render('SUHContratBundle:AffichageContrats:addAvenant.html.twig', array(
            'form' => $form->createView(),
            'id' => $idEtudiant,
            'contrat' => $contrat,
            'listeEtudiantsAidants'=>$this->getListeEtudiants(null),
            'nbContrats'=>$this->getNbContrats($idEtudiant),
        ));
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
}