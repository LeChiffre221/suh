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

class ContratController extends Controller
{
    public function addContratAction(Request $request, $idEtudiant){

        $contrat = new Contrat();
        $form = $this->get('form.factory')->create(new ContratType, $contrat);


        if ($request->getMethod() == 'POST') {

            $tabResponse = $request->request->get('suh_contratbundle_contrat');
           $natureContrat = $tabResponse['natureContrat'];
            $nbHeureInitiales = $tabResponse['nbHeureInitiales'];

            $dateDebutContrat = date("Y-m-d", strtotime(strtr($tabResponse['dateDebutContrat'], '/', '-') ));

            $dateFinContrat = date("Y-m-d", strtotime(strtr($tabResponse['dateFinContrat'], '/', '-') ));

            $semestreConcerne = $tabResponse['semestreConcerne'];

            $contrat->setNatureContrat($natureContrat);
            $contrat->setNbHeureInitiales($nbHeureInitiales);
            $contrat->setDateDebutContrat($dateDebutContrat);
            $contrat->setDateFinContrat($dateFinContrat);
            $contrat->setSemestreConcerne($semestreConcerne);

            $em = $this->getDoctrine()->getManager();

            // On l'étudiant pour lui ajouter un contrat
            $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->find($idEtudiant);

            $contrat->setEtudiantAidant($etudiant);
            $em->persist($contrat);

            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', "Contrat créé !");
            return $this->redirect($this->generateUrl('suh_contrat_afficherContrat', array(
                'idEtudiant' => $idEtudiant,
            )));
        }


        return $this->render('SUHContratBundle:AffichageContrats:addContrat.html.twig', array(
            'form' => $form->createView(),
            'idEtudiant' => $idEtudiant,
        ));
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
        return $this->redirectToRoute('suh_contrat_afficherContrat', array('idEtudiant' => $etudiant->getId()));
    }

    public function editContratAction($idContrat){
        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->find($idContrat);
    }

    public function deleteContratAction($idContrat){

        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->find($idContrat);

        $etudiant = $contrat->getEtudiantAidant();
        $em->remove($contrat);
        $em->flush();

        return $this->redirectToRoute('suh_contrat_afficherContrat', array('idEtudiant' => $etudiant->getId()));
    }



}