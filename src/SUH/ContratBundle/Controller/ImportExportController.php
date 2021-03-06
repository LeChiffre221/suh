<?php
/**
 * Created by PhpStorm.
 * User: lechi
 * Date: 22/11/2016
 * Time: 09:33
 */

namespace SUH\ContratBundle\Controller;


use DateTime;
use SUH\ContratBundle\Entity\Avenant;
use SUH\ContratBundle\Entity\Contrat;
use SUH\ContratBundle\Entity\EtudiantAidant;
use SUH\GestionBundle\Entity\Etudiant;
use SUH\GestionBundle\Entity\EtudiantInformations;
use SUH\GestionBundle\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class ImportExportController extends Controller
{

    public function exportFichePaiePDFAction(Request $request, $idEtudiant, $month, $year){




        $em = $this->getDoctrine()->getManager();

        $etudiant = $em->getRepository('SUHContratBundle:EtudiantAidant')->find($idEtudiant);
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->findOneBy(array(
            'etudiantAidant' => $etudiant,
            'active' => true
        ));


        $tabMois = array(   '01' => "Janvier", '02' => "Février", '03' => "Mars", '04' => "Avril", '05' => "Mai", '06' => "Juin",
            '07' => "Juillet", '08' => "Aout", '09'  => "Septembre", '10' => "Octobre", '11' => "Novembre", '12' => "Décembre");


        $nom = $contrat->getEtudiantAidant()->getEtudiantInformations()->getNom();
        $prenom = $contrat->getEtudiantAidant()->getEtudiantInformations()->getPrenom();


        $heureEffectuee = $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(array(
            'contrat' => $contrat,
            'verification'  => true
        ));

        $nbHeure = 0;

        foreach ($heureEffectuee as $heure){

            $moisHeure = substr($heure->getDateAndTime(), 3, 2);
            $anneeHeure = substr($heure->getDateAndTime(), 6, 4);

            if($anneeHeure == $year && $month == $moisHeure){
                $nbHeure += $heure->getNbHeure();
            }
        }

        //return new Response($nbHeure);

        $pdf = new \FPDI();
        //Methode pour importer toutes les pages sur un template
        $pdf = $this->importFichePaieTemplate($pdf, $contrat, $nbHeure, $tabMois[$month]);

        return new Response($pdf->Output('D','Fiche de paie_'.$nom.'_'.$prenom.'.pdf'));
    }

    public function importFichePaieTemplate($pdf, $contrat, $nbHeure, $mois){

        $pageCount =  $pdf->setSourceFile('../FicheDePaie.pdf');

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            // import a page
            $templateId = $pdf->importPage($pageNo);
            // get the size of the imported page
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage('P', array($size['w'], $size['h']));

            // use the imported page
            $pdf->useTemplate($templateId);

            if ($pageNo == 1) {

                $nom = $contrat->getEtudiantAidant()->getEtudiantInformations()->getNom();
                $prenom = $contrat->getEtudiantAidant()->getEtudiantInformations()->getPrenom();
                $numINSEE = $contrat->getEtudiantAidant()->getEtudiant()->getNumeroEtudiant();


                $pdf->SetFont('Arial', 'B', 14);

                $pdf->Text(82, 17.2, (utf8_decode($mois)));

                $pdf->SetFont('Arial', '', 10);

                $pdf->Text(44, 39.3, (utf8_decode($nom)));
                $pdf->Text(118, 39.3, (utf8_decode($prenom)));
                $pdf->Text(42, 47.3, (utf8_decode($numINSEE)));

                $pdf->SetFont('Arial', '', 14);
                $pdf->Text(95, 152, (utf8_decode($nbHeure." heure(s)")));

                $pdf->SetFont('Arial', '', 10);
                $pdf->Text(72, 197.8, (utf8_decode($contrat->getDateDebutContrat())));
                $pdf->Text(146, 197.8, (utf8_decode($contrat->getDateFinContrat())));









            }
        }

        return $pdf;
    }



    public function exportAvenantPDFAction(Request $request, $idContrat){
        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->find($idContrat);

        $em = $this->getDoctrine()->getManager();
        $avenant = $em->getRepository('SUHContratBundle:Avenant')->findOneBy(array('contrat' => $contrat));



        $nom = $contrat->getEtudiantAidant()->getEtudiantInformations()->getNom();
        $prenom = $contrat->getEtudiantAidant()->getEtudiantInformations()->getPrenom();

        $natureContrat = '';
        foreach($contrat->getNatureContrat() as $nature){
            $natureContrat .= $nature."/";
        }
        $natureContrat = substr($natureContrat,0, -1);

        $pdf = new \FPDI();
        //Methode pour importer toutes les pages sur un template
        $pdf = $this->importAvenantTemplate($pdf, $avenant, $contrat);

        return new Response($pdf->Output('D','Avenant_'.$nom.'_'.$prenom.'_'.$natureContrat.'.pdf'));
    }

    public function importAvenantTemplate($pdf, $avenant, $contrat){

        $pageCount =  $pdf->setSourceFile('../AvenantEtudiant.pdf');

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

                $pdf->SetFont('Arial','B',10);

                $etudiantInfo = "Et ".$parite." ".$nom." ".$prenom."                             né(e) le ".$dateNaissance;
                $pdf->Text(15,136,(utf8_decode($etudiantInfo)));

                $pdf->SetFont('Arial','',11);

                $diplome = $contrat->getEtudiantAidant()->getFormation()->getDiplome();
                $composante = $contrat->getEtudiantAidant()->getFormation()->getComposante();
                $etablissement = $contrat->getEtudiantAidant()->getFormation()->getEtablissement();

                if((!empty($composante) || !empty($etablissement)) && !empty($diplome)){
                    $diplome.= ",";
                }
                if(!empty($composante) && !empty($etablissement)){
                    $composante .= ',';
                }
                $status = $diplome. ' '. $composante. ' '. $etablissement;

                $pieces = explode(" ", $status);

                $statusCourt = "";


                foreach ($pieces as $piece){
                    if(strlen($statusCourt) < 190){
                        $statusCourt .= " ".$piece;
                    }
                }
                if(strlen($statusCourt) > 190){
                    $statusCourt .= " ...";
                }

                $text = $parite." ".$nom." ".$prenom.", étudiant(e) inscrit(e) en ".$statusCourt.", est engagé(e) en qualité d'étudiant(e) pour assurer des travaux liés à l'assistance et à l'accompagnement des étudiant(e)s  en situation de handicap.";

                $diplomeCompoEtab1 = "";
                $diplomeCompoEtab2 = "";
                $diplomeCompoEtab3 = "";
                $diplomeCompoEtab4 = "";

                $pieces = explode(" ", $text);

                foreach ($pieces as $piece){

                    if(strlen($diplomeCompoEtab1) <= 100){
                        $diplomeCompoEtab1 .= " ".$piece;
                    }
                    else if(strlen($diplomeCompoEtab2) <= 100){
                        $diplomeCompoEtab2 .= " ".$piece;
                    }
                    else if(strlen($diplomeCompoEtab3) <= 100){
                        $diplomeCompoEtab3 .= " ".$piece;
                    }
                    else{
                        $diplomeCompoEtab4 .= " ".$piece;
                    }

                }

                $pdf->Text(15,175, utf8_decode($diplomeCompoEtab1));
                $pdf->Text(15,180, utf8_decode($diplomeCompoEtab2));
                $pdf->Text(15,185, utf8_decode($diplomeCompoEtab3));
                $pdf->Text(15,190, utf8_decode($diplomeCompoEtab4));



                 $dateDebutContrat = $contrat->getDateDebutContrat();
                $dateFinContrat = $contrat->getDateFinContrat();
                $nbHeures = $avenant->getNbHeure();

                $pdf->Text(23,210,(utf8_decode($dateDebutContrat)));
                $pdf->Text(55,210,(utf8_decode($dateFinContrat)));

                $pdf->SetFont('Arial','',11);
                $pdf->Text(140,210,(utf8_decode($nbHeures. ' heures.')));
            }
        }

        return $pdf;
    }

    public function exportContratPDFAction(Request $request, $idContrat){

        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SUHContratBundle:Contrat')->find($idContrat);

        $nom = $contrat->getEtudiantAidant()->getEtudiantInformations()->getNom();
        $prenom = $contrat->getEtudiantAidant()->getEtudiantInformations()->getPrenom();

        $natureContrat = '';
        foreach($contrat->getNatureContrat() as $nature){
            $natureContrat .= $nature."/";
        }
        $natureContrat = substr($natureContrat,0, -1);

        $pdf = new \FPDI();
        //Methode pour importer toutes les pages sur un template
        $pdf = $this->importContratTemplate($pdf, $contrat);

        return new Response($pdf->Output('D','Contrat_'.$nom.'_'.$prenom.'_'.$natureContrat.'.pdf'));
    }

    public function importContratTemplate($pdf, $contrat){

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

                $pdf->Text(22, 170.3, (utf8_decode($natureContrat)));

                $pieces = explode(" ", utf8_decode($diplome. ' '. $composante. ' '. $etablissement));

                $diplomeCompoEtab1 = "";
                $diplomeCompoEtab2 = "";

                foreach ($pieces as $piece){

                    if(strlen($diplomeCompoEtab1) <= 65){
                        $diplomeCompoEtab1 .= " ".$piece;
                    }
                    else{
                        if(strlen($diplomeCompoEtab2) <= 95){
                            $diplomeCompoEtab2 .= " ".$piece;

                            if(strlen($diplomeCompoEtab2) > 95){
                                $diplomeCompoEtab2 .= " ...";

                            }
                        }

                    }

                }

                $pdf->Text(66 ,143,($diplomeCompoEtab1));
                $pdf->Text(14 ,148.3,($diplomeCompoEtab2));


                $pdf->Text(21,232.4,(utf8_decode($dateDebutContrat)));
                $pdf->Text(48,232.4,(utf8_decode($dateFinContrat)));

                $pdf->Text(114.5,232.4,(utf8_decode($nbHeuresPrevisionnelles. ' heures.')));
            }
        }

        return $pdf;
    }



    public function importExcelAction(Request $request){

        $uploadedFile = $request->files->get('fichierExcel');
        $sizeMax = 1048576;
        $extension_upload = null;
        $extensions_valides = array('xls', 'xlsx');

        //Si l'utilisateur est bien connecté
        if (!empty($uploadedFile) && !empty($sizeMax)) {

            //Si la taille du fichier est inférieur à 1Mo
            if ($uploadedFile->getClientSize() < $sizeMax){
                //Si l'extension du fichier est bien valide
                $extension_upload = explode(".", $uploadedFile->getClientOriginalName());

                if (in_array($extension_upload[1], $extensions_valides)) {
                    //Génération d'un nom aléatoire et on déplace le fichier dans
                    //le répertoire local excel
                    $nom = md5(uniqid(rand(), true));
                    $dest = "./Excel/" . $nom . '.' . $extension_upload[1];
                    $resultat = move_uploaded_file($uploadedFile, $dest);

                    if ($resultat) {
                        $this->lireDonneesExcel($dest, $request);
                        unlink($dest);
                        $request->getSession()->getFlashBag()->add('notice', 'Importation effectuée');
                    } else {
                        $request->getSession()->getFlashBag()->add('error', 'Erreur lors de l\'importation');
                    }
                }
                else {
                    $request->getSession()->getFlashBag()->add('error', 'Le fichier doit avoir l\'extension .xls ou .xlsx');
                }
            }
            else {
                $request->getSession()->getFlashBag()->add('error', 'Le fichier ne peut dépasser 1Mo');
            }
        }
        else{
            $request->getSession()->getFlashBag()->add('error', 'Erreur lors de l\'importation');
        }

        return $this->redirectToRoute('suh_contrat_importExport');

    }

    private function lireDonneesExcel($fichier, $request){
        $objPHPExcel = \PHPExcel_IOFactory::load($fichier);

        //On récupère les dimensions du fichier excel
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        //nombre de colonnes réellement présentes
        $nbColonnesReelles= "Z";

        $tabExcel = array();

        //On vérifie si les données sont possibles (pas trop de lignes / colonnes)
        if ($nbColonnesReelles != $highestColumn) {
            //Lecture de la feuille excel
            var_dump("Hightest Row :".$highestRow);
            for ($row = 2; $row <= 2; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $nbColonnesReelles . $row, NULL, TRUE, FALSE);
                $this->ajouterLigne($rowData);
            }
        } else {
            $request->getSession()->getFlashBag()->add('error', 'Erreur lors de l\'importation');
        }

    }

    private function ajouterLigne($datas){
        $em = $this->getDoctrine()->getManager();


        $nom = $datas[0][0];
        $prenom = $datas[0][1];
        $dateNaissance = \PHPExcel_Style_NumberFormat::toFormattedString($datas[0][2], "DD/MM/YYYY");
        $dateNaissance = date("Y-m-d", strtotime(strtr($dateNaissance, '/', '-') ));
        $dateNaissance =  new DateTime($dateNaissance);

        $parite = $datas[0][3];
        $numEtudiant = $datas[0][4];
        $adresseEtudiante = $datas[0][5];
        $adresseFamiliale = $datas[0][6];
        $telephone = $datas[0][7];
        $mail = $datas[0][8];
        $diplome = $datas[0][9];
        $composante = $datas[0][10];
        $etablissement = $datas[0][11];
        $dateDebutContrat = \PHPExcel_Style_NumberFormat::toFormattedString($datas[0][12], "DD/MM/YYYY");
        $dateFinContrat = \PHPExcel_Style_NumberFormat::toFormattedString($datas[0][13], "DD/MM/YYYY");
        $nbHeure = $datas[0][14];
        $etudiantHandicape = $datas[0][15];
        $natureDuContrat = $datas[0][16];
        $semestre = $datas[0][17];
        $anneeExercice = $datas[0][18];
        $certiMedical = $datas[0][19];
        $dateEnvoiContratDRH = \PHPExcel_Style_NumberFormat::toFormattedString($datas[0][20], "DD/MM/YYYY");
        $dateEnvoiContratEtu = \PHPExcel_Style_NumberFormat::toFormattedString($datas[0][21], "DD/MM/YYYY");
        $etablissementAvenant = $datas[0][22];
        $nbHeureAvenant = $datas[0][23];
        $dateEnvoieAvenantDRH = \PHPExcel_Style_NumberFormat::toFormattedString($datas[0][24], "DD/MM/YYYY");
        $dateEnvoieAvenantEtu = \PHPExcel_Style_NumberFormat::toFormattedString($datas[0][25], "DD/MM/YYYY");


        $etudiant = $em->getRepository('SUHGestionBundle:Etudiant')->findOneBy(array("numeroEtudiant" => $numEtudiant));

        if($etudiant == null){
            var_dump("KO : ".$numEtudiant);
            $etudiantAidant = new EtudiantAidant();
            $etudiantAidant->setEtudiant(new Etudiant());
            $etudiantAidant->setEtudiantFormation(new Formation());
            $etudiantAidant->setEtudiantInformations(new EtudiantInformations());
        }else{
            var_dump("OK : ".$numEtudiant);
            $etudiantAidant = $em->getRepository('SUHContratBundle:EtudiantAidant')->findOneBy(array("etudiant" => $etudiant));
        }

        //ETUDIANT
        if($numEtudiant != null){
            $etudiantAidant->getEtudiant()->setNumeroEtudiant($numEtudiant);
        }

        if($dateNaissance != null){
            $etudiantAidant->getEtudiant()->setDateNaissance($dateNaissance);
        }

        //ETUDIANT AIDANT
        if($certiMedical != null){

            if(strtolower($certiMedical) == "ok"){
                $etudiantAidant->setCertificatMedical(1);
            }
            else{
                $etudiantAidant->setCertificatMedical(0);

            }
        }

        //INFORMATIONS
        if($nom != null){
            $etudiantAidant->getEtudiantInformations()->setNom($nom);
        }

        if($prenom != null){
            $etudiantAidant->getEtudiantInformations()->setPrenom($prenom);
        }

        if($parite != null){
            $etudiantAidant->getEtudiantInformations()->setParite($parite);
        }

        if($adresseEtudiante != null){
            $etudiantAidant->getEtudiantInformations()->setAdresseEtudiante($adresseEtudiante);
        }

        if($adresseFamiliale != null){
            $etudiantAidant->getEtudiantInformations()->setAdresseFamiliale($adresseFamiliale);
        }

        if($mail != null){
            $etudiantAidant->getEtudiantInformations()->setMailPerso($mail);
        }

        if($telephone != null){
            $etudiantAidant->getEtudiantInformations()->setTelephonePerso($telephone);
        }

        //FORMATION
        if($diplome != null){
            $etudiantAidant->getFormation()->setDiplome($diplome);
        }

        if($composante != null){
            $etudiantAidant->getFormation()->setComposante($composante);
        }

        if($etablissement != null){
            $etudiantAidant->getFormation()->setEtablissement($etablissement);
        }


        //Préparation de l'étudiant aidant
        $em->persist($etudiantAidant);

        //Si les informations d'un contrat sont présente alors on crée un contrat
        //CONTRAT
        if($dateDebutContrat != null && $dateFinContrat != null && $nbHeure != null && $semestre != null){
            $contrat = new Contrat();
            $contrat->setNatureContrat(explode("/", $natureDuContrat));
            $contrat->setDateDebutContrat($dateDebutContrat);
            $contrat->setDateFinContrat($dateFinContrat);
            $contrat->setNbHeureInitiales($nbHeure);
            $contrat->setSemestreConcerne($semestre);
            $contrat->setNomEtudiant($etudiantHandicape);
            $contrat->setActive(1);

            if(strtolower($etablissementAvenant) == "oui"){
                $contrat->setEtablissementAvenant(1);
            }
            else{
                $contrat->setEtablissementAvenant(0);
            }


            if($dateEnvoiContratDRH != null){
                $contrat->setDateEnvoiDRH($dateEnvoiContratDRH);
            }
            if($dateEnvoiContratEtu != null){
                $contrat->setDateEnvoiEtudiant($dateEnvoiContratEtu);
            }
        }



        if(isset($contrat)){
            $contrat->setEtudiantAidant($etudiantAidant);

            if($contrat->getEtablissementAvenant()){
                $avenant = new Avenant();
                $avenant->setNatureAvenant(explode("/", $natureDuContrat));


                $avenant->setNbHeure($nbHeureAvenant-$nbHeure);

                if($dateEnvoieAvenantDRH != null){
                    $avenant->setDateEnvoiDRH($dateEnvoieAvenantDRH);
                }

                if($dateEnvoieAvenantEtu != null){
                    $avenant->setDateEnvoiEtudiant($dateEnvoieAvenantEtu);
                }

                $avenant->setContrat($contrat);

                $em->persist($avenant);
            }

            $em->persist($contrat);



        }

        $em->flush();



    }

    //get la liste des éutudiants
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

}