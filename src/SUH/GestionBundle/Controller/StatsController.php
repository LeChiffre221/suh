<?php

namespace SUH\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SUH\GestionBundle\Controller\ListeEtudiantController;

class StatsController extends Controller
{
	public function indexAction()
	{
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('SUHGestionBundle:Annee')
        ;

        $listYears = $repository->findAll();

		return $this->render('SUHGestionBundle:AffichageEtudiants:accueil.html.twig',array(
            //'listeEtudiantsHandicapes'=> false,
            'stats' =>true,
            'listYears' => $listYears
        ));
	}

	public function resultsAction()
	{

        $etudiantRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHGestionBundle:Etudiant');

        $years = array();
        foreach ($_POST as $key => $value) 
        {
            if($value == 'on')
            {
                $years[] = $key;
            }
        }

        $tabStudentsByYear = array(); $tabReturn = array();
        
		$array = array();
        for($i=0;$i<32;$i++){
            if(!empty($_POST['InfoEcrite'.$i]) && isset($_POST['InfoEcrite'.$i])){
                if(!empty($_POST['InfoSelect'.$i]) && isset($_POST['InfoSelect'.$i])){ 

                    $condition = null;
                    if(isset($_POST['radio'.$i]))
                    { 
                        $condition = $_POST['radio'.$i];
                    }

                    $array[$i][$_POST['InfoSelect'.$i]][0] = $_POST['InfoEcrite'.$i];
                    $array[$i][$_POST['InfoSelect'.$i]][1] = $condition;                    
                }
            }
        }

        // String qui récapitule la demande
        $recapView = '';
        $recapPDF = '';
        foreach ($array as  $value) 
        {
            foreach ($value as $key => $detail) 
            {
                if($detail[1] != null)
                {
                    $recapView .= ' ' . $detail[1] . ' ' . $key . ' = ' . $detail[0];
                    $recapPDF .= $detail[1] . ' ' . $key . ' = ' . $detail[0] . '\n';
                }
                else
                {
                    $recapView .= $key . ' = ' . $detail[0] ;
                    $recapPDF .= $key . ' = ' . $detail[0] . '\n';
                }
            }
        }

        foreach ($years as $year) 
        {
            $tabStudentsByYear[$year] = array();
            $students = $etudiantRepository->getAllStudentsByYear($year);

            foreach ($students as $k => $student) 
            {
                foreach ($array as $t) 
                {
                    foreach ($t as $parameter => $oneCondition) 
                    {
                        $c = false;

                        /* ETUDIANT */

                        if($parameter == 'numeroEtudiant')
                        {
                            if (strpos(strtolower($student->getNumeroEtudiant()),strtolower($oneCondition[0])) !== false) 
                            {
                                $c = true;
                            }
                        }  

                        if($parameter == 'age=')
                        {
                            $date = new \DateTime('now');
                            $annee= (int)$date->format('Y');
                            $anneeMax=new \DateTime($annee-$oneCondition[0]-1 ."-" .$date->format('m-d'));
                            $anneeMin=new \DateTime($annee-$oneCondition[0] ."-" .$date->format('m-d'));

                            if ($student->getDateNaissance() >= $anneeMax && $student->getDateNaissance() <= $anneeMin)
                            {
                                $c = true;
                            }
                        }  

                        if($parameter == 'age<')
                        {
                            $date = new \DateTime('now');
                            $annee= (int)$date->format('Y');
                            $anneeMin=new \DateTime($annee-$oneCondition[0] ."-" .$date->format('m-d'));

                            if ($student->getDateNaissance() > $anneeMin)
                            {
                                $c = true;
                            }
                        }   

                        if($parameter == 'age>')
                        {
                            $date = new \DateTime('now');
                            $annee= (int)$date->format('Y');
                            $anneeMin=new \DateTime($annee-$oneCondition[0] ."-" .$date->format('m-d'));

                            if ($student->getDateNaissance() < $anneeMin)
                            {
                                $c = true;
                            }
                        }

                        /* ETUDIANT INFORMATIONS */

                        if($parameter == 'nom')
                        {
                            foreach ($student->getListEtudiantInformations() as $eInfo) 
                            {
                                if($eInfo->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eInfo->getEtudiantInformations()->getNom()),strtolower($oneCondition[0])) !== false) 
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'prenom')
                        {
                            foreach ($student->getListEtudiantInformations() as $eInfo) 
                            {
                                if($eInfo->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eInfo->getEtudiantInformations()->getPrenom()),strtolower($oneCondition[0])) !== false) 
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'mailInstitutionnel')
                        {
                            foreach ($student->getListEtudiantInformations() as $eInfo) 
                            {
                                if($eInfo->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eInfo->getEtudiantInformations()->getMailInstitutionnel()),strtolower($oneCondition[0])) !== false) 
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'mailPerso')
                        {
                            foreach ($student->getListEtudiantInformations() as $eInfo) 
                            {
                                if($eInfo->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eInfo->getEtudiantInformations()->getmailPerso()),strtolower($oneCondition[0])) !== false) 
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'mailParents')
                        {
                            foreach ($student->getListEtudiantInformations() as $eInfo) 
                            {
                                if($eInfo->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eInfo->getEtudiantInformations()->getmailParents()),strtolower($oneCondition[0])) !== false) 
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'parite')
                        {
                            foreach ($student->getListEtudiantInformations() as $eInfo) 
                            {
                                if($eInfo->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eInfo->getEtudiantInformations()->getParite()),strtolower($oneCondition[0])) !== false) 
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }                       

                        if($parameter == 'adresseEtudiante')
                        {
                            foreach ($student->getListEtudiantInformations() as $eInfo) 
                            {
                                if($eInfo->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eInfo->getEtudiantInformations()->getAdresseEtudiante()),strtolower($oneCondition[0])) !== false) 
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        } 

                        if($parameter == 'adresseFamiliale')
                        {
                            foreach ($student->getListEtudiantInformations() as $eInfo) 
                            {
                                if($eInfo->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eInfo->getEtudiantInformations()->getAdresseFamiliale()),strtolower($oneCondition[0])) !== false) 
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        } 

                        if($parameter == 'telephonePerso')
                        {
                            foreach ($student->getListEtudiantInformations() as $eInfo) 
                            {
                                if($eInfo->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eInfo->getEtudiantInformations()->getTelephonePerso()),strtolower($oneCondition[0])) !== false) 
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'telephoneParents')
                        {
                            foreach ($student->getListEtudiantInformations() as $eInfo) 
                            {
                                if($eInfo->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eInfo->getEtudiantInformations()->getTelephoneParents()),strtolower($oneCondition[0])) !== false) 
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        /* ETUDIANT FORMATION */
                        if($parameter == 'diplome')
                        {
                            foreach ($student->getListEtudiantFormation() as $eFormation) 
                            {
                                if($eFormation->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eFormation->getFormation()->getDiplome()),strtolower($oneCondition[0])) !== false) 
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'composante')
                        {
                            foreach ($student->getListEtudiantFormation() as $eFormation) 
                            {
                                if($eFormation->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eFormation->getFormation()->getComposante()),strtolower($oneCondition[0])) !== false) 
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'etablissement')
                        {
                            foreach ($student->getListEtudiantFormation() as $eFormation) 
                            {
                                if($eFormation->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eFormation->getFormation()->getEtablissement()),strtolower($oneCondition[0])) !== false) 
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'filiere')
                        {
                            foreach ($student->getListEtudiantFormation() as $eFormation) 
                            {
                                if($eFormation->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eFormation->getFormation()->getFiliere()),strtolower($oneCondition[0])) !== false) 
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'cycle')
                        {
                            foreach ($student->getListEtudiantFormation() as $eFormation) 
                            {
                                if($eFormation->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eFormation->getFormation()->getCycle()),strtolower($oneCondition[0])) !== false)
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'anneeEtude')
                        {
                            foreach ($student->getListEtudiantFormation() as $eFormation) 
                            {
                                if($eFormation->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eFormation->getFormation()->getAnneeEtude()),strtolower($oneCondition[0])) !== false)
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        /* ETUDIANT HANDICAPE */

                        if($parameter == 'qhandi')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getQhandi()),strtolower($oneCondition[0])) !== false)  
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'nomHandicap')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    foreach ($eHandicape->getEtudiantHandicape()->getHandicap() as $handicap)
                                    {
                                        if (strpos(strtolower($handicap->getNomHandicap()),strtolower($oneCondition[0])) !== false)  
                                        {
                                            $c = true;
                                        }                                    
                                    }
                                }
                            }
                        }

                        /* AIDE EXAMEN */

                        if($parameter == 'tempsMajore')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                        if(strpos(strtolower($oneCondition[0]),'oui') !== false)
                                        {
                                            $c = $eHandicape->getEtudiantHandicape()->getAideExamen()->getTempsMajore() == true ? true : false;
                                        }
                                        else
                                        {
                                            $c = $eHandicape->getEtudiantHandicape()->getAideExamen()->getTempsMajore() == false ? true : false;
                                        }
                                    }
                                }
                            }
                        }

                        if($parameter == 'amenagementExamens')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                        if(strpos(strtolower($oneCondition[0]),'oui') !== false)
                                        {
                                            $c = $eHandicape->getEtudiantHandicape()->getAideExamen()->getAmenagementExamens() != null ? true : false;
                                        }
                                        else
                                        {
                                            $c = $eHandicape->getEtudiantHandicape()->getAideExamen()->getAmenagementExamens() == null ? true : false;
                                        }
                                    }
                                }
                            }
                        }

                        if($parameter == 'secretaireExamen')
                        {

                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                        if(strpos(strtolower($oneCondition[0]),'oui') !== false)
                                        {

                                            $c = $eHandicape->getEtudiantHandicape()->getAideExamen()->getSecretaireExamen() != null ? true : false;
                                        } 
                                        else
                                        {
                                            $c = $eHandicape->getEtudiantHandicape()->getAideExamen()->getSecretaireExamen() == null ? true : false;
                                        }
                                    }
                                }
                            }
                        }

                        if($parameter == 'secretaireExamenDetails')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                        if($eHandicape->getEtudiantHandicape()->getAideExamen()->getSecretaireExamen() != null)
                                        {
                                            if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getAideExamen()->getSecretaireExamen()->getFonction()),strtolower($oneCondition[0])) !== false)  
                                            {
                                                $c = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if($parameter == 'ordinateur')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                        if(strpos(strtolower($oneCondition[0]),'oui') !== false)
                                        {
                                            $c = count($eHandicape->getEtudiantHandicape()->getAideExamen()->getOrdinateur()) != 0 ? true : false;
                                        }
                                        else
                                        {
                                            $c = count($eHandicape->getEtudiantHandicape()->getAideExamen()->getOrdinateur()) == 0 ? true : false;
                                        }
                                    }
                                }
                            }
                        }

                        if($parameter == 'logiciel')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                        foreach($eHandicape->getEtudiantHandicape()->getAideExamen()->getOrdinateur() as $ordi)
                                        {                                    
                                            if (strpos(strtolower($ordi->getType()),strtolower($oneCondition[0])) !== false)  
                                            {
                                                $c = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if($parameter == 'materiel')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                            if(strpos(strtolower($oneCondition[0]),'oui') !== false)
                                            {
                                                $c = $eHandicape->getEtudiantHandicape()->getAideExamen()->getMateriel() != null ? true : false;
                                            } 
                                            else
                                            {
                                                $c = $eHandicape->getEtudiantHandicape()->getAideExamen()->getMateriel() == null ? true : false;
                                            }
                                    }
                                }
                            }
                        }

                        if($parameter == 'materielDetail')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                        if($eHandicape->getEtudiantHandicape()->getAideExamen()->getMateriel() != null)
                                        {
                                            if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getAideExamen()->getMateriel()->getNom()),strtolower($oneCondition[0])) !== false)  
                                            {
                                                $c = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if($parameter == 'adaptationDocuments')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                            if(strpos(strtolower($oneCondition[0]),'oui') !== false)
                                            {

                                                $c = count($eHandicape->getEtudiantHandicape()->getAideExamen()->getAdaptationDocuments()) != 0 ? true : false;
                                            } 
                                            else
                                            {
                                                $c = count($eHandicape->getEtudiantHandicape()->getAideExamen()->getAdaptationDocuments()) == 0 ? true : false;
                                            }
                                    }
                                }
                            }
                        }

                        if($parameter == 'adaptationDocumentsDetail')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                        foreach($eHandicape->getEtudiantHandicape()->getAideExamen()->getAdaptationDocuments() as $adapt)
                                        {
                                            if (strpos(strtolower($adapt->getDetail()),strtolower($oneCondition[0])) !== false)  
                                            {
                                                $c = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if($parameter == 'delocalisationExamens')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                            if(strpos(strtolower($oneCondition[0]),'oui') !== false)
                                            {
                                                $c = $eHandicape->getEtudiantHandicape()->getAideExamen()->getDelocalisationExamen() != null ? true : false;
                                            } 
                                            else
                                            {
                                                $c = $eHandicape->getEtudiantHandicape()->getAideExamen()->getDelocalisationExamen() == null ? true : false;
                                            }
                                    }
                                }
                            }
                        }

                        if($parameter == 'LieuDelocalisationExamens')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                        if($eHandicape->getEtudiantHandicape()->getAideExamen()->getDelocalisationExamen() != null)
                                        {
                                            if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getAideExamen()->getDelocalisationExamen()->getLieu()),strtolower($oneCondition[0])) !== false)  
                                            {
                                                $c = true;
                                            }
                                        }
                                    }
                                }
                            }
                        } 

                        if($parameter == 'DetailsDelocalisationExamens')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                        if($eHandicape->getEtudiantHandicape()->getAideExamen()->getDelocalisationExamen() != null)
                                        {
                                            if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getAideExamen()->getDelocalisationExamen()->getDetail()),strtolower($oneCondition[0])) !== false)  
                                            {
                                                $c = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }    

                        if($parameter == 'avisMedical')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                        if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getAideExamen()->getAvisMedical()),strtolower($oneCondition[0])) !== false)  
                                        {
                                            $c = true;
                                        }
                                    }
                                }
                            }
                        }    

                        /*if($parameter == 'dateValidite')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                        if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getAideExamen()->getDateValidite()),strtolower($oneCondition[0])) !== false)  
                                        {
                                            $c = true;
                                        }
                                    }
                                }
                            }
                        } */     

                        if($parameter == 'dureeValidite')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getAideExamen() != null)
                                    {
                                        if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getAideExamen()->getDureeAvisMedical()),strtolower($oneCondition[0])) !== false)  
                                        {
                                            $c = true;
                                        }
                                    }
                                }
                            }
                        }  

                        /* Aménagement études */

                        if($parameter == 'amenagementEtude')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if(strpos(strtolower($oneCondition[0]), 'oui') !== false)
                                    {
                                        $c = count($eHandicape->getEtudiantHandicape()->getAmenagementEtude()) != 0 ? true : false;
                                    }
                                    else
                                    {
                                        $c = count($eHandicape->getEtudiantHandicape()->getAmenagementEtude()) == 0 ? true : false;
                                    }
                                }
                            }
                        }

                        if($parameter == 'amenagementEtudeType')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    foreach ($eHandicape->getEtudiantHandicape()->getAmenagementEtude() as $amenagement)
                                    {
                                        if(strpos(strtolower($amenagement->getNom()),strtolower($oneCondition[0])) !== false)
                                        {
                                            $c = true;
                                        }
                                    }
                                }
                            }
                        }

                        if($parameter == 'amenagementEtudeInfoComplementaires')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    foreach ($eHandicape->getEtudiantHandicape()->getAmenagementEtude() as $amenagement)
                                    {
                                        if(strpos(strtolower($amenagement->getInformationComplementaire()),strtolower($oneCondition[0])) !== false)
                                        {
                                            $c = true;
                                        }
                                    }
                                }
                            }
                        }

                        if($parameter == 'amenagementEtudeDetail')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    foreach ($eHandicape->getEtudiantHandicape()->getAmenagementEtude() as $amenagement)
                                    {
                                        if(strpos(strtolower($amenagement->getDetail()),strtolower($oneCondition[0])) !== false)
                                        {
                                            $c = true;
                                        }
                                    }
                                }
                            }
                        }

                        if($parameter == 'suivi')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getSuivi()),strtolower($oneCondition[0])) !== false)  
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'descriptifComplementaire')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getDescriptifComplementaire()),strtolower($oneCondition[0])) !== false)  
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        /* MDPH */

                        if($parameter == 'reconnaissanceMdph')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if(strpos(strtolower($oneCondition[0]), 'en cours') !== false)
                                    {
                                        if($eHandicape->getEtudiantHandicape()->getMdph() !== null && $eHandicape->getEtudiantHandicape()->getDemandeMdphEnCours() == true)
                                        {
                                            $c = true;
                                        }
                                    }
                                    else if(strpos(strtolower($oneCondition[0]), 'oui') !== false)
                                    {
                                        $c = $eHandicape->getEtudiantHandicape()->getMdph() !== null ? true : false;
                                    }
                                    else
                                    {
                                        $c = $eHandicape->getEtudiantHandicape()->getMdph() !== null ? false : true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'departementMdph')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getMdph() !== null)
                                    {
                                        if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getMdph()->getDepartementMdph()),strtolower($oneCondition[0])) !== false)  
                                        {
                                            $c = true;
                                        }
                                    }
                                }
                            }
                        }

                        if($parameter == 'tauxInvalidite')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getTauxInvalidite()),strtolower($oneCondition[0])) !== false)  
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'typeAllocations')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getTypeAllocations()),strtolower($oneCondition[0])) !== false)  
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'rqth')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getRqth()),strtolower($oneCondition[0])) !== false)  
                                    {
                                        $c = true;
                                    }
                                }
                            }
                        }

                        /* NOTIFICATION SAVS */

                        if($parameter == 'notificationSavs')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if(strpos(strtolower($oneCondition[0]), 'oui') !== false)
                                    {
                                        $c = $eHandicape->getEtudiantHandicape()->getNotificationSavs() !== null ? true : false;
                                    }
                                    else
                                    {
                                        $c = $eHandicape->getEtudiantHandicape()->getNotificationSavs() !== null ? false : true;
                                    }
                                }
                            }
                        }

                        if($parameter == 'notificationSavsDetails')
                        {
                            foreach ($student->getListEtudiantHandicape() as $eHandicape) 
                            {
                                if($eHandicape->getAnneeScolaire() == $year)
                                {
                                    if($eHandicape->getEtudiantHandicape()->getNotificationSavs() !== null)
                                    {
                                        if (strpos(strtolower($eHandicape->getEtudiantHandicape()->getNotificationSavs()->getNotificationText()),strtolower($oneCondition[0])) !== false)  
                                        {
                                            $c = true;
                                        }
                                    }
                                }
                            }
                        }

                        if(!array_key_exists($student->getId(), $tabStudentsByYear[$year]))
                        {
                            $tabStudentsByYear[$year][$student->getId()] = null;
                        }

                        if($oneCondition[1] == 'ET')
                        {
                            if($c == true)
                            {
                                if($tabStudentsByYear[$year][$student->getId()] == false)
                                {
                                    $tabStudentsByYear[$year][$student->getId()] = false;
                                }  

                                else if($tabStudentsByYear[$year][$student->getId()] == null)
                                {
                                    $tabStudentsByYear[$year][$student->getId()] = true;
                                }    
                            }
                            else
                            {
                                if($tabStudentsByYear[$year][$student->getId()] == null)
                                {
                                    $tabStudentsByYear[$year][$student->getId()] = false;
                                } 
                                if($tabStudentsByYear[$year][$student->getId()] == true)
                                {
                                    $tabStudentsByYear[$year][$student->getId()] = false;
                                }
                            }
                        }
                        else if($oneCondition[1] == 'OU')
                        {
                            if($c == true)
                            {
                                if($tabStudentsByYear[$year][$student->getId()] == false)
                                {
                                    $tabStudentsByYear[$year][$student->getId()] = true;
                                }
                                else if($tabStudentsByYear[$year][$student->getId()] == null)
                                {
                                    $tabStudentsByYear[$year][$student->getId()] = true;
                                }
                            }
                            else
                            {
                                if($tabStudentsByYear[$year][$student->getId()] == null)
                                {
                                    $tabStudentsByYear[$year][$student->getId()] = false;
                                }
                            }                            
                        }
                        else if($oneCondition[1] == null)
                        {
                            $tabStudentsByYear[$year][$student->getId()] = $c;
                        }
                    }
                }               
            }            
        }

        foreach ($tabStudentsByYear as $year => $s) 
        {
            $tabReturn[$year] = 0;
            foreach ($s as $bool) 
            {
                if($bool == true)
                {
                    $tabReturn[$year]++;
                }
            }
        }

        return $this->render('SUHGestionBundle:AffichageEtudiants:accueil.html.twig',array(
            'statsResults' => true,
            'tab' => $tabReturn,
            'conditions' => $array,
            'recapPDF' => $recapPDF,
            'recapView' => $recapView,
        ));
        
    }
}
?>