<?php
/**
 * Created by PhpStorm.
 * User: lechi
 * Date: 22/11/2016
 * Time: 09:33
 */

namespace SUH\ContratBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImportExportController extends Controller
{
    public function exportContratPDFAction(Request $request, $idContrat){

        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->find($idContrat);

        $pdf = new \FPDI();
        //Methode pour importer toutes les pages sur un template
        $pdf = $this->importTemplate($pdf, $contrat);

        return new Response($pdf->Output('D','ContratEtudiant.pdf'));
    }


    public function importTemplate($pdf, $contrat){

        $pageCount =  $pdf->setSourceFile('../ContratEtudiant.pdf');

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            // import a page
            $templateId = $pdf->importPage($pageNo);
            // get the size of the imported page
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage('P', array($size['w'], $size['h']));


            // use the imported page
            $pdf->useTemplate($templateId);

            if($pageNo == 1){

                $nom = $contrat->getEtudiantAidant()->getEtudiantInformations()->getNom();
                $prenom = $contrat->getEtudiantAidant()->getEtudiantInformations()->getPrenom();

                if( $contrat->getEtudiantAidant()->getEtudiantInformations()->getParite() == "F"){
                    $parite = "Mme.";
                }
                else{
                    $parite = "M.";
                }

                $dateNaissance = $contrat->getEtudiantAidant()->getEtudiant()->getDateNaissance()->format('d-m-Y');

                //diplome composante etablissement

                $diplome = $contrat->getEtudiantAidant()->getFormation()->getDiplome();
                $composante = $contrat->getEtudiantAidant()->getFormation()->getComposante();
                $etablissement = $contrat->getEtudiantAidant()->getFormation()->getEtablissement();

                if((!empty($composante) || !empty($etablissement)) && !empty($diplome)){
                    $diplome.= ",";
                }
                if(!empty($composante) && !empty($etablissement)){
                    $composante .= ',';
                }

                $dateDebutContrat = $contrat->getDateDebutContrat();
                $dateFinContrat = $contrat->getDateFinContrat();
                $nbHeuresPrevisionnelles = $contrat->getNbHeureInitiales();

                $pdf->SetFont('Arial','B',11);

                $pdf->Text(60,116,(utf8_decode($parite).' '.utf8_decode($nom).' '.utf8_decode($prenom).utf8_decode(" né(e) le ").$dateNaissance));

                $pdf->SetFont('Arial','',11);



                $natureContrat = '(';
                foreach ($contrat->getNatureContrat() as $nature) {
                    if ($nature == "tutorat") {
                        $nature = "Tutorat";
                    } elseif ($nature == "assistancePédagogique") {
                        $nature = "Assistance Pédagogique";
                    } else {
                        $nature = "Prise de note";
                    }

                    $natureContrat  .= $nature . ", ";
                }
                $natureContrat  = substr($natureContrat , 0, -2) . ")";

                $pdf->Text(22, 165, (utf8_decode($natureContrat)));

                $pdf->Text(66 ,143,(utf8_decode($diplome. ' '. $composante. ' '. $etablissement)));


                $pdf->Text(21,227.1,(utf8_decode($dateDebutContrat)));
                $pdf->Text(48,227.1,(utf8_decode($dateFinContrat)));

                $pdf->Text(114.5,227.1,(utf8_decode($nbHeuresPrevisionnelles. ' heures.')));


            }




        }

        return $pdf;
    }
}