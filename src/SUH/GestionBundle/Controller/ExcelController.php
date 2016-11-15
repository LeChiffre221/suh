<?php

namespace SUH\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ExcelController extends Controller
{   
    /**
     * Permet l'exportation de fichiers excel
     * @return type
     */
    public function exportExcelAction()
    { 
		ini_set('max_execution_time', 300);
        //compteur démarrant à 2 car la première ligne concerne les noms des colonnes
        $i=2;
        //vérifie si l'étudiant a plus d'une formation/handicap/aideExamen (si la boucle foreach de chaque a déjà eu lieu
        //à la fin de la première itération le boolean passe à true
        //si une nouvelle boucle a lieu, une condition vérifiant le booleen à true incrémente j
        $bool=false;   
        
        //Récupération de tous les étudiants et de leurs informations



        $entityManager = $this->getDoctrine()->getManager();        

        //Création objet excel
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("SUH")
                ->setLastModifiedBy("SUH membre")
                ->setTitle("Office 2005 XLSX Test Document")
                ->setSubject("Office 2005 XLSX Test Document")
                ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
                ->setKeywords("office 2005 openxml php")
                ->setCategory("Test result file");


        $phpExcelObject
                ->setActiveSheetIndex(0)
                ->setCellValue('A1', '1ere Inscription')
                ->setCellValue('B1', 'NOM')
                ->setCellValue('C1', 'PRENOM')
                ->setCellValue('D1', 'PARITE')
                ->setCellValue('E1', 'DATE DE NAISSANCE')
                ->setCellValue('F1', 'N° ETUDIANT')
                ->setCellValue('G1', 'QHANDI')
                ->setCellValue('H1', 'HANDICAP')
                ->setCellValue('I1', 'MAIL INSTITUTIONNEL')
                ->setCellValue('J1', 'MAIL PERSONNEL')
                ->setCellValue('K1', 'MAIL PARENTS')
                ->setCellValue('L1', 'ADRESSE ETUDIANTE')
                ->setCellValue('M1', 'ADRESSE FAMILIALE')
                ->setCellValue('N1', 'TELEPHONE PERSONNEL')
                ->setCellValue('O1', 'TELEPHONE PARENTS')
                ->setCellValue('P1', 'ETABLISSEMENT')
                ->setCellValue('Q1', 'UFR/ECOLE')
                ->setCellValue('R1', 'DIPLÔME')
                ->setCellValue('S1', 'FILIERE')
                ->setCellValue('T1', 'CYCLE')
                ->setCellValue('U1', 'ANNEE')
                ->setCellValue('V1', 'AMENAGEMENT D\'EXAMENS')
                ->setCellValue('W1', 'TEMPS MAJORE')
                ->setCellValue('X1', 'SECRETAIRE EXAMENS')
                ->setCellValue('Y1', 'ORDINATEUR')
                ->setCellValue('Z1', 'MATERIEL SUH')
                ->setCellValue('AA1', 'ADAPTATION DOCUMENTS')
                ->setCellValue('AB1', 'DELOCALISATION DES EXAMENS')
                ->setCellValue('AC1', 'AVIS MEDICAL')
                ->setCellValue('AD1', 'DATE DE VALIDITE')
                ->setCellValue('AE1', 'DUREE AVIS MEDICAL')
                ->setCellValue('AF1', 'AMENAGEMENT D\'ETUDES')
                ->setCellValue('AG1', 'SUIVI')
                ->setCellValue('AH1', 'DESCRIPTIF COMPLEMENTAIRE')
                ->setCellValue('AI1', 'MDPH')
                ->setCellValue('AJ1', 'DEPARTEMENT MDPH')
                ->setCellValue('AK1', 'TAUX INVALIDITE')
                ->setCellValue('AL1', 'TYPE D\'ALLOCATION')
                ->setCellValue('AM1', 'RQTH')
                ->setCellValue('AN1', 'SAVS');

        $a = $this->get('session')->get('filter');

        $etudiantRepository = $entityManager->getRepository('SUHGestionBundle:Etudiant');

        //Récupération de tous les étudiants pour une année
        $allInformations = $etudiantRepository->getAllStudentsByYear($a['year']);

        $j = 2;        

        $booleanToFR[true] = 'Oui';
        $booleanToFR[false] = 'Non';
        foreach ($allInformations as $ai) 
        {
            foreach ($ai->getListEtudiantHandicape() as $h) 
            {            
                if($h->getAnneeScolaire() == $a['year'])
                {
                    if($h->getEtudiantHandicape()->getAideExamen() != null)
                    { 
                        $aDoc = '';
                        /* suppression des adaptations documents */
                        foreach($h->getEtudiantHandicape()->getAideExamen()->getAdaptationDocuments() as $adaptationDoc)
                        {
                            $aDoc .= $adaptationDoc->getDetail() . ' - ';
                        }

                        $ordinateurText = '';
                        /* suppression des ordinateurs */
                        foreach($h->getEtudiantHandicape()->getAideExamen()->getOrdinateur() as $ordi)
                        {
                            $ordinateurText .= $ordi->getType() . ' - ' ;
                        }
                        $phpExcelObject
                            ->setActiveSheetIndex(0)
                            ->setCellValue('Y'.$j, $ordinateurText);

                        /* suppression du matériel */
                        $materielText = '';
                        if($h->getEtudiantHandicape()->getAideExamen()->getMateriel() != null)
                        {
                            $phpExcelObject
                            ->setActiveSheetIndex(0)
                            ->setCellValue('Z'.$j, $h->getEtudiantHandicape()->getAideExamen()->getMateriel()->getNom());
                        }
                        

                        /* suppression du secrétaire d'examen */
                        if($h->getEtudiantHandicape()->getAideExamen()->getSecretaireExamen() != null)
                        {
                            $phpExcelObject
                            ->setActiveSheetIndex(0)
                            ->setCellValue('X'.$j, $h->getEtudiantHandicape()->getAideExamen()->getSecretaireExamen()->getFonction());
                        }


                        /* suppression de la délocalisation d'examen */
                        if($h->getEtudiantHandicape()->getAideExamen()->getDelocalisationExamen() != null)
                        {
                            $phpExcelObject
                            ->setActiveSheetIndex(0)
                            ->setCellValue('AB'.$j, $h->getEtudiantHandicape()->getAideExamen()->getDelocalisationExamen()->getLieu() . ' - ' . $h->getEtudiantHandicape()->getAideExamen()->getDelocalisationExamen()->getDetail());
                        }

                        $phpExcelObject
                            ->setActiveSheetIndex(0)
                            ->setCellValue('V'.$j, $booleanToFR[$h->getEtudiantHandicape()->getAideExamen()->getAmenagementExamens()])
                            ->setCellValue('AC'.$j, $h->getEtudiantHandicape()->getAideExamen()->getAvisMedical())
                            ->setCellValue('AE'.$j, $h->getEtudiantHandicape()->getAideExamen()->getDureeAvisMedical())
                            ->setCellValue('AA'.$j, $aDoc)
                            ->setCellValue('W'.$j, $booleanToFR[$h->getEtudiantHandicape()->getAideExamen()->getTempsMajore()]);

                            if($h->getEtudiantHandicape()->getAideExamen()->getDateValidite() != null)
                            {
                                $phpExcelObject
                            ->setActiveSheetIndex(0)
                            ->setCellValue('AD'.$j, $h->getEtudiantHandicape()->getAideExamen()->getDateValidite()->format('d/m/Y'));

                            }
                    }

                    $listeHandicap = '';
                    foreach ($h->getEtudiantHandicape()->getHandicap() as $handicap) 
                    {
                        $listeHandicap .= $handicap->getNomHandicap() . ' - '; 
                    }
                    $phpExcelObject
                            ->setActiveSheetIndex(0)
                            ->setCellValue('H'.$j, $listeHandicap);

                    $am = '';
                    foreach ($h->getEtudiantHandicape()->getAmenagementEtude() as $key => $amenagementEtude) 
                    {
                        $am = $amenagementEtude->getNom();
                        if($amenagementEtude->getInformationComplementaire() != '')
                        {
                            $am .= ' - ' . $amenagementEtude->getInformationComplementaire();
                        }
                        if($amenagementEtude->getDetail() != '')
                        {
                            $am .= ' - ' . $amenagementEtude->getDetail();
                        }
                        if($key != 0)
                        {
                            $am .= ' | ';
                        }                        
                    }
                    if($h->getEtudiantHandicape()->getNotificationSavs() != null)
                    {
                        if($h->getEtudiantHandicape()->getDemandeNotificationSavsEnCours())
                        {
                            $phpExcelObject
                                ->setActiveSheetIndex(0)
                                ->setCellValue('AN'.$j, 'En cours');
                        }

                    }
                    else
                    {
                        $phpExcelObject
                            ->setActiveSheetIndex(0)
                            ->setCellValue('AN'.$j, 'Non');
                    }

                    if($h->getEtudiantHandicape()->getMdph() != null)
                    {
                        $phpExcelObject
                            ->setActiveSheetIndex(0)
                            ->setCellValue('AJ'.$j, $h->getEtudiantHandicape()->getMdph()->getDepartementMdph())
                            ->setCellValue('AI'.$j, 'Oui');
                    }
                    else
                    {
                        $phpExcelObject
                            ->setActiveSheetIndex(0)
                            ->setCellValue('AI'.$j, 'Non');
                    }

                    $phpExcelObject
                            ->setActiveSheetIndex(0)
                            ->setCellValue('G'.$j, $h->getEtudiantHandicape()->getQhandi())
                            ->setCellValue('AC'.$j, $am)//PAS VERIFIE
                            ->setCellValue('AK'.$j, $h->getEtudiantHandicape()->getTauxInvalidite())
                            ->setCellValue('AM'.$j, $h->getEtudiantHandicape()->getRqth())
                            ->setCellValue('AL'.$j, $h->getEtudiantHandicape()->getTypeAllocations())
                            ->setCellValue('AH'.$j, $h->getEtudiantHandicape()->getDescriptifComplementaire())
                            ->setCellValue('AG'.$j, $h->getEtudiantHandicape()->getSuivi());
                }
            }

            $etablissement = ''; $composante = ''; $diplome = ''; $anneeEtude = ''; $filiere = ''; $cycle = '';

            $premiereInscription = null;

            $p=0;
            foreach ($ai->getListEtudiantFormation() as $f) 
            { 
                if($f->getAnneeScolaire() < $premiereInscription || $premiereInscription == null)
                {
                    $premiereInscription = $f->getAnneeScolaire();
                }
                if($f->getAnneeScolaire() == $a['year'])
                {
                    if($p == 0)
                    {
                        $etablissement .= $f->getFormation()->getEtablissement();
                        $composante .= $f->getFormation()->getComposante();
                        $diplome .= $f->getFormation()->getDiplome();
                        $anneeEtude .= $f->getFormation()->getAnneeEtude();
                        $filiere .= $f->getFormation()->getFiliere();
                        $cycle .= $f->getFormation()->getCycle();
                    }
                    else
                    {
                        $etablissement .= ' - ' . $f->getFormation()->getEtablissement();
                        $composante .= ' - ' . $f->getFormation()->getComposante();
                        $diplome .= ' - ' . $f->getFormation()->getDiplome();
                        $anneeEtude .= ' - ' . $f->getFormation()->getAnneeEtude();
                        $filiere .= ' - ' . $f->getFormation()->getFiliere();
                        $cycle .= ' - ' . $f->getFormation()->getCycle();
                    }
                    $p++;

                }

            }

            $phpExcelObject
                            ->setActiveSheetIndex(0)
                            //VERIFIE !!
                            ->setCellValue('T'.$j, $cycle)
                            ->setCellValue('S'.$j, $filiere)
                            ->setCellValue('U'.$j, $anneeEtude)
                            ->setCellValue('R'.$j, $diplome)
                            ->setCellValue('Q'.$j, $composante)
                            ->setCellValue('P'.$j, $etablissement);

            foreach ($ai->getListEtudiantInformations() as $i) 
            { 
                if($i->getAnneeScolaire() == $a['year'])
                {
                    $phpExcelObject
                            //VERIFIE !!!!
                            ->setActiveSheetIndex(0)
                            ->setCellValue('A'.$j, $i->getEtudiant()->getPremiereInscription())
                            ->setCellValue('B'.$j, $i->getEtudiantInformations()->getNom())
                            ->setCellValue('C'.$j, $i->getEtudiantInformations()->getPrenom())
                            ->setCellValue('F'.$j, $i->getEtudiant()->getNumeroEtudiant())
                            ->setCellValue('I'.$j, $i->getEtudiantInformations()->getMailInstitutionnel())
                            ->setCellValue('J'.$j, $i->getEtudiantInformations()->getMailPerso())
                            ->setCellValue('K'.$j, $i->getEtudiantInformations()->getMailParents())
                            ->setCellValue('N'.$j, $i->getEtudiantInformations()->getTelephonePerso())
                            ->setCellValue('O'.$j, $i->getEtudiantInformations()->getTelephoneParents())
                            ->setCellValue('M'.$j, $i->getEtudiantInformations()->getAdresseFamiliale())
                            ->setCellValue('L'.$j, $i->getEtudiantInformations()->getAdresseEtudiante())
                            ->setCellValue('D'.$j, $i->getEtudiantInformations()->getParite());

                    if($i->getEtudiant()->getDateNaissance())
                    {
                        $phpExcelObject
                            ->setActiveSheetIndex(0)
                            ->setCellValue('E'.$j, $i->getEtudiant()->getDateNaissance()->format('d/m/Y'));
                    }
                }
            }
            $j++;
        }

        //VUE
        $sheet = $phpExcelObject->getActiveSheet();
        $columnTab = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN");


        $sheet->getStyle('A1:AN'.$j)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:AN'.$j)->getAlignment()->setHorizontal("center");
        $sheet->getStyle('A1:AN'.$j)->getAlignment()->setVertical("center");

        $styleTitle = $sheet->getStyle('A1:AN1');
        $styleTitle->applyFromArray(array(

            'font' => array(
                'bold'=>true,
                'size'=>12,
                'name'=>'Arial',
                'color'=>array(
                    'rgb'=>'16365C',
                )
            ),
            'fill' => array(
                'color' => array('rgb' => '16365C')
            )
        ));


        foreach($columnTab as $column){
            $sheet->getColumnDimension($column)->setWidth(18);

            for ($i = 1; $i <= $j; $i++) {

                if($column == "A" && $i == 1){
                    $sheet->getRowDimension($i)->setRowHeight(60);
                }else if($column = "A" && $i > 1){
                    $sheet->getRowDimension($i)->setRowHeight(-1);
                }




            }
        }
        
        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=bdd.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        
        return $response;        
    }
    
    /* =======================================================================*/
    /* =======================================================================*/
    /* =======================================================================*/
    /* =======================================================================*/
    /* =======================================================================*/
    /* =======================================================================*/
    
    /**
     * Permet l'importation de fichiers excel
     * @return Response
     */
    public function importExcelAction() {
        $request = Request::createFromGlobals();

		ini_set('max_execution_time', 360);
        $uploadedFile = $request->files->get('fichierExcel');
        $sizeMax = $request->get('MAX_FILE_SIZE');
        $nbLignes = $request->get('nbLignes');
        $extension_upload = null;
        $extensions_valides = array('xls', 'xlsx');
        //Si l'utilisateur est bien connecté
        if (!empty($uploadedFile) && !empty($sizeMax) && !empty($nbLignes)) {
            //Si le fichier existe
            //Si la taille du fichier est inférieur à 1Mo
            if ($uploadedFile->getClientSize() < $sizeMax) {
                //Si l'extension du fichier est bien valide                
                $extension_upload = explode(".", $uploadedFile->getClientOriginalName());
                //$uploadedFile->guessExtension();     
                if (in_array($extension_upload[1], $extensions_valides)) {
                    //Génération d'un nom aléatoire et on déplace le fichier dans
                    //le répertoire local excel
                    $nom = md5(uniqid(rand(), true));
                    $dest = "./Excel/" . $nom . '.' . $extension_upload[1];
                    $resultat = move_uploaded_file($uploadedFile, $dest);
                    
                    if ($resultat) {                        
                        $content = $this->lireDonneesExcel($dest,$nbLignes);                        
                        unlink($dest);
                        return $content;
                    } else {
                        return new Response('Erreur chargement fichier');
                    }
                }
                else {
                    return new Response('Fichier d\'extension .xls ou .xlsx nécessaire');
                }
            }
            else {
                return new Response('Taille maximale du fichier à 1Mo');
            }            
        }
        return new Response('Le fichier ou le nombre de ligne n\'a pas été renseigné');
    }

    
    /**
     * Lit les donnees d'un fichier excel passé en argument
     * @param type $fichier
     * @param type $nbLignesReel
     * @return Response
     */
    private function lireDonneesExcel($fichier, $nbLignesReel) {
        $objPHPExcel = \PHPExcel_IOFactory::load($fichier);

        //On récupère les dimensions du fichier excel
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        //nombre de colonnes réellement présentes
        $nbColonnesReelles= "AN";
        
        $tabExcel = array();
        
        //On vérifie si les données sont possibles (pas trop de lignes / colonnes)
        if ($this->verifierLignes($nbLignesReel, $highestRow) && $this->verifierColonnes($nbColonnesReelles, $highestColumn)) {
            //Lecture de la feuille excel
            for ($row = 2; $row <= $nbLignesReel; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $nbColonnesReelles . $row, NULL, TRUE, FALSE);
                $this->ajouterLigne($rowData);
            }            
        } else {
            return new Response("fichier excel non conforme : nombre de lignes ou colonnes incorrect<br/>"
                    . "Nb lignes max = " . $highestRow . " // nb lignes indiquées = " . $nbLignesReel . "<br/>"
                    . "Nb colonnes max = " . $highestColumn . " // nb colonnes indiquées = " . $nbColonnesReelles . "<br/>");
        }
        return $this->render('SUHGestionBundle:AffichageEtudiants:accueil.html.twig',array(
            'afficheExcelVue' => true,
        ));
    }

    /**
     * Se charge d'ajouter un tableau représentant une ligne du fichier Excel, dans la BDD
     * @param type $ligne
     */
    private function ajouterLigne($rowData)
    {
        $a = $this->get('session')->get('filter');
        
        $manager = $this->getDoctrine()->getManager();
        $dateCourante = new \DateTime('now'); 
        
        $dateNaissance = null;        
        if(!empty($rowData[0][4]))
        {
            $dateNaissance = $rowData[0][4];
            $dateNaissanceString = \PHPExcel_Style_NumberFormat::toFormattedString($dateNaissance, "DD-MM-YYYY");
            $dateNaissance = \DateTime::createFromFormat('d-m-Y',$dateNaissanceString);
            if($dateNaissance==false)
            {
                $dateNaissance = \DateTime::createFromFormat('d/m/Y',$dateNaissanceString);
                if($dateNaissance==false)
                {
                    $dateNaissance=null;
                }                
            }
        }
        
        $dateValidite = null;     
        if(!empty($rowData[0][29]))
        {
            $dateValidite = $rowData[0][29];
            $dateValiditeString = \PHPExcel_Style_NumberFormat::toFormattedString($dateValidite, "DD-MM-YYYY");
            $dateValidite = \DateTime::createFromFormat('d-m-Y',$dateValiditeString);
            if($dateValidite==false)
            {
                $dateValidite = \DateTime::createFromFormat('d/m/Y',$dateNaissanceString);
                if($dateValidite==false)
                {
                    $dateValidite=null;
                }  
            }
        }
        
        /*$dateMiseAJour = null;
        if(!empty($rowData[0][31]))
        {
            $dateMiseAJour = $rowData[0][31];
            $dateMiseAJourString = \PHPExcel_Style_NumberFormat::toFormattedString($dateMiseAJour, "DD-MM-YYYY");
            $dateMiseAJour = \DateTime::createFromFormat('d-m-Y',$dateMiseAJourString);
            if($dateMiseAJour==false)
            {
                $dateMiseAJour = \DateTime::createFromFormat('d/m/Y',$dateNaissanceString);
                if($dateMiseAJour==false)
                {
                    $dateMiseAJour=null;
                }
            }
        }*/

        if($rowData[0][5] == null)
    {
        $numeroEtudiant = '';
    }
    else
    {
        $numeroEtudiant = $rowData[0][5];
    }


    $premiereInscription = $rowData[0][0];





        $entityManager = $this->getDoctrine()->getManager();
        $etudiantInformationsRepository = $entityManager->getRepository('SUHGestionBundle:EtudiantInformations');
        $etudiantRepository = $entityManager->getRepository('SUHGestionBundle:Etudiant');

        // On va regarder si l'étudiant existe déjà en base de données :

        $issetStudent = false;
        $pastStudent = $etudiantInformationsRepository->findOneBy(array('nom' => $rowData[0][1], 'prenom' => $rowData[0][2]));

        $student = null;

        /* On teste si l'étudiant est déjà inscrit dans l'appli */
        /* Si oui => on l'inscrit dans l'année selectionnée et on le rattache à l'entité étudiante déjà existante. */
        /* Si non => on créé l'étudiant. */

        if($pastStudent !== null)
        {
            $student = $etudiantRepository->getStudentByStudentInformationsId($pastStudent->getId());
            if(isset($student[0]))
            {
                $student = $student[0];
                if($etudiantRepository->getStudentInformationsByYear($student->getId(),$a['year']) != null)
                {
                    $student->setNumeroEtudiant($numeroEtudiant);
                    $student->setDateNaissance($dateNaissance);
                    $student->setPremiereInscription($premiereInscription);
                    $entityManager->persist($student);

                    foreach ($student->getListEtudiantHandicape() as $h) 
                    {            
                        if($h->getAnneeScolaire() == $a['year'])
                        {
                            if($h->getEtudiantHandicape()->getAideExamen() != null)
                            {
                                $entityManager->remove($h);
                                /* suppression des adaptations documents */
                                foreach($h->getEtudiantHandicape()->getAideExamen()->getAdaptationDocuments() as $adaptationDoc)
                                {
                                    $entityManager->remove($adaptationDoc);
                                }

                                /* suppression des ordinateurs */
                                foreach($h->getEtudiantHandicape()->getAideExamen()->getOrdinateur() as $ordi)
                                {
                                    $entityManager->remove($ordi);
                                }

                                /* suppression du matériel */
                                if($h->getEtudiantHandicape()->getAideExamen()->getMateriel() != null || $h->getEtudiantHandicape()->getAideExamen()->getMateriel() != "NON")
                                {
                                    $entityManager->remove($h->getEtudiantHandicape()->getAideExamen()->getMateriel());
                                }

                                /* suppression du secrétaire d'examen */
                                if($h->getEtudiantHandicape()->getAideExamen()->getSecretaireExamen() != null || $h->getEtudiantHandicape()->getAideExamen()->getSecretaireExamen() != "NON")
                                {
                                    $entityManager->remove($h->getEtudiantHandicape()->getAideExamen()->getSecretaireExamen());
                                }

                                /* suppression de la délocalisation d'examen */
                                if($h->getEtudiantHandicape()->getAideExamen()->getDelocalisationExamen() != null)
                                {
                                    $entityManager->remove($h->getEtudiantHandicape()->getAideExamen()->getDelocalisationExamen());
                                }

                                $entityManager->remove($h->getEtudiantHandicape()->getAideExamen());
                            }

                            foreach ($h->getEtudiantHandicape()->getHandicap() as $handicap) 
                            {
                                $entityManager->remove($handicap);
                            }

                            if($h->getEtudiantHandicape()->getMdph() != null)
                            {
                                $entityManager->remove($h->getEtudiantHandicape()->getMdph());
                            }

                            $entityManager->remove($h->getEtudiantHandicape());

                            
                            /*$entityManager->remove($h->getEtudiantHandicape())
                            $entityManager->remove($h);*/
                        }
                    }

                    foreach ($student->getListEtudiantFormation() as $f) 
                    { 
                        if($f->getAnneeScolaire() == $a['year'])
                        {
                            $entityManager->remove($f);
                            $entityManager->remove($f->getFormation());
                        }
                    }

                    foreach ($student->getListEtudiantInformations() as $i) 
                    { 
                        if($i->getAnneeScolaire() == $a['year'])
                        {
                            $entityManager->remove($i);
                            $entityManager->remove($i->getEtudiantInformations());
                        }
                    }
                }

                $entityManager->flush();

                $issetStudent = true;
                }
        }

        

        if(!$issetStudent)
        {
            $student = new \SUH\GestionBundle\Entity\Etudiant(                
                $numeroEtudiant, //numéro étudiant
                $dateNaissance ,//dateNaissance
                $premiereInscription
            );

            $manager->persist($student);
        }

        // ===== ETUDIANT INFORMATIONS
        $etudiantInformations=new \SUH\GestionBundle\Entity\EtudiantInformations(
            $rowData[0][1], //nom
            $rowData[0][2], //prenom
            $rowData[0][8], //mail institutionnel
            $rowData[0][3], //parite
            $rowData[0][9], //mail perso
            $rowData[0][10], //mail parents
            $rowData[0][11], //adresse étudiante
            $rowData[0][12], //adresse familiale
            $rowData[0][13], //téléphone perso
            $rowData[0][14] //téléphone parents
        );          
         // ===== FORMATION
        $formation=new \SUH\GestionBundle\Entity\Formation(
                $rowData[0][17], //diplome
                $rowData[0][16], //composante
                $rowData[0][18], //filiere
                $rowData[0][19], //cycle
                $rowData[0][15], //etablissement
                $rowData[0][20] //annee d'étude
            );
        $manager->persist($formation);
        
         // ===== HANDICAP
        $handicap=new \SUH\GestionBundle\Entity\Handicap(
                $rowData[0][7] //nom handicap
            );
        $manager->persist($handicap);
        
         // ===== AIDE EXAMEN

        $amenagementExamen = $this->verifierBool($rowData[0][21]);
        $tempsMajore = $this->verifierBool($rowData[0][22]);
        $secretaireExamen = null;
        $delocalisationExamen = null;
        $materiel = null;

        if($rowData[0][25] != '')
        {
            $materiel = new \SUH\GestionBundle\Entity\Materiel($rowData[0][25]);
            $manager->persist($materiel);
        }

        if($rowData[0][23] != '')
        {
            $secretaireExamen = new \SUH\GestionBundle\Entity\SecretaireExamen($rowData[0][23]);
            $manager->persist($secretaireExamen);
        }

        if($rowData[0][27] != '')
        {
            $delocalisationExamen = new \SUH\GestionBundle\Entity\DelocalisationExamen('', $rowData[0][27]);
            $manager->persist($delocalisationExamen);
        }       

        $aideExamen=new \SUH\GestionBundle\Entity\AideExamen(
                $amenagementExamen, //amenagement examen (aménagement épreuve dans le fichier, ce n'est pas le booléen)
                $tempsMajore, //temps majoré
                $secretaireExamen, //secretaire d'examen
                $delocalisationExamen, //delocalisation examen
                $rowData[0][28], //avis médical
                $dateValidite, //date validité
                $rowData[0][30], //durée avis médical
                $materiel
            );

        /* ADAPTATION DOCUMENTS */

        if($rowData[0][26] != '')
        {
            $adaptationDocuments = new \SUH\GestionBundle\Entity\AdaptationDocuments($rowData[0][26]);
            $manager->persist($adaptationDocuments);
            $aideExamen->addAdaptationDocuments($adaptationDocuments);
        }

        /* ORDINATEUR */

        if($rowData[0][24] != '')
        {
            $ordi = new \SUH\GestionBundle\Entity\Ordinateur($rowData[0][24]);
            $manager->persist($ordi);
            $aideExamen->addOrdinateur($ordi);
        }

        $manager->persist($aideExamen);
        
    $manager->flush();
    
    /* ====================================================================== */
    /* Tables avec jointures */


    // ======= Etudiant/EtudiantInformations

    $eeInfos = new \SUH\GestionBundle\Entity\EtudiantEtudiantInformations(
        $student,
        $a['year'],
        $etudiantInformations
    );

    $manager->persist($eeInfos);

    /* RQTH */
    if(strtolower($rowData[0][38]) == 'en cours')
    {
        $demandeRqthEnCours = true;
        $rqth = true;
    }
    else
    {
        $demandeRqthEnCours = false;
        if(strtolower($rowData[0][38]) == 'oui')
        {
            $rqth = true;
        }
        else
        {
            $rqth = false;
        }
    }

    /* SAVS */

    if(strtolower($rowData[0][39]) == 'en cours')
    {
        $savsEnCours = true;
        $savs = new \SUH\GestionBundle\Entity\NotificationSavs($rowData[0][39]);

        $manager->persist($savs);
    }
    else
    {
        $savsEnCours = false;
        if(strtolower($rowData[0][39]) == 'oui')
        {
            $savs = new \SUH\GestionBundle\Entity\NotificationSavs($rowData[0][39]);
            $manager->persist($savs);
        }
        else
        {
            $savs = null;
        }
    }

    /* MDPH */

    if(strtolower($rowData[0][34]) == 'en cours')
    {
        $mdphEnCours = true;
        $mdph = new \SUH\GestionBundle\Entity\Mdph($rowData[0][35]);
        $manager->persist($mdph);
    }
    else
    {
        $mdphEnCours = false;
        if(strtolower($rowData[0][34]) == 'oui')
        {
            $mdph = new \SUH\GestionBundle\Entity\Mdph($rowData[0][35]);
            $manager->persist($mdph);
        }
        else
        {
            $mdph = null;
        }
    }

    $amenagementEtude = null;
    if($rowData[0][31] != '')
    {
        $amenagementEtude = new \SUH\GestionBundle\Entity\AmenagementEtude($rowData[0][31], '', '');
        $manager->persist($amenagementEtude);
    }

    /*$qhandi,$rqth,$notificationSavs,$amenagementEtude,
            $tauxInvalidite,$suivi,$typeAllocations,$descriptifComplementaire,$mdph,$handicap,$demandeMdphEnCours, $demandeRqthEnCours, $demandeNotificationSavsEnCours, $aideExamen
*/

     // ===== ETUDIANT HANDICAPE
    $rqth = $this->verifierBool($rowData[0][38]);
        $etudiantHandicape=new \SUH\GestionBundle\Entity\EtudiantHandicape(
                $rowData[0][6], //qhandi
                $rqth, //rqth
                $savs, //notificationSavs
                $amenagementEtude, //amenagementEtude
                $rowData[0][36], //tauxInvalidite
                $rowData[0][32], //suivi
                $rowData[0][37], //type allocations
                $rowData[0][33], //descr complémentaire
                $mdph, //Mdph
                $handicap, // handicap
                $mdphEnCours, //Demande mdph en cours
                $demandeRqthEnCours, // Demande rqth en cours
                $savsEnCours, // Demande SAVS en cours
                $aideExamen // Aide examen
            );    
            $manager->persist($etudiantHandicape);
    
    $manager->flush();


    
     // ===== ETUDIANT ETUDIANT HANDICAPE

        $eeHandicape = new \SUH\GestionBundle\Entity\EtudiantEtudiantHandicape(
            $student,
            $a['year'],
            $etudiantHandicape
        );

        $manager->persist($eeHandicape);

        // ===== ETUDIANT FORMATION
        $etudiantFormation=new \SUH\GestionBundle\Entity\EtudiantFormation(
                $student,
                $a['year'], //annee scolaire
                $formation
                );
        $manager->persist($etudiantFormation);
        
        $manager->flush();
    }
    
    /**
     * Vérifie si le nombre de lignes indiquées n'est pas plus important que la ligne la plus grande
     * @param type $nbLignes
     * @param type $highestRow
     * @return boolean
     */
    private function verifierLignes($nbLignes, $highestRow)
    {
        if($nbLignes > $highestRow )
        {
            return false;
        }        
        return true;
    }
    
    /**
     * Vérifie si le nombre de colonnes indiquées n'est pas plus important que la colonne la plus grande
     * @param type $nbColonnes
     * @param type $highestColumn
     * @return boolean
     */
    private function verifierColonnes($nbColonnes, $highestColumn)
    {
        if(strlen($nbColonnes) > strlen($highestColumn))
        {
            return false;
        }
        elseif(strlen($nbColonnes) == strlen($highestColumn))
        {
            if(strcmp($nbColonnes,$highestColumn) > 0)
            {
                return false;
            }   
        }
        return true;
    }
    
    /**
     * Cette méthode renvoie true si une cellule dans Excel contient "OUI", false sinon
     * @param type $case
     * @return boolean
     */
    private function verifierBool($cellule)
    {
        if(!empty($cellule) && strtolower($cellule) == "oui")
        {
            return true;
        }
        return false;
    }
    
}
