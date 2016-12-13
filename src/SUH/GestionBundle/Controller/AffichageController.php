<?php

namespace SUH\GestionBundle\Controller;

use SUH\GestionBundle\Entity\Annee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AffichageController extends ListeEtudiantController
{
    public function migrationAction()
    {
        $etudiantHandicapeRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHGestionBundle:EtudiantHandicape');

        $allEh = $etudiantHandicapeRepository->findAll();
        
        foreach ($allEh as $eHandicape) 
        {
            if($eHandicape->getAideExamen()->getSecretaireExamen() != null && $eHandicape->getAideExamen()->getSecretaireExamen()->getFonction() == "NON")
            {
                $s = $eHandicape->getAideExamen()->getSecretaireExamen();
                $eHandicape->getAideExamen()->setSecretaireExamen(null);
                $this->getDoctrine()->getManager()->remove($s);
            }
        }

        foreach ($allEh as $eHandicape) 
        {
            foreach ($eHandicape->getAideExamen()->getOrdinateur() as $ordinateur) 
            {
                if($ordinateur->getType() == "NON")
                {
                    $this->getDoctrine()->getManager()->remove($ordinateur);
                }
            }
        }

        foreach ($allEh as $eHandicape) 
        {
            if($eHandicape->getAideExamen()->getMateriel() != null && $eHandicape->getAideExamen()->getMateriel()->getNom() == "NON")
            {
                $s = $eHandicape->getAideExamen()->getMateriel();
                $eHandicape->getAideExamen()->setMateriel(null);
                $this->getDoctrine()->getManager()->remove($s);
            }
        }

        foreach ($allEh as $eHandicape) 
        {
            foreach ($eHandicape->getAmenagementEtude() as $aEtude) 
            {
                if($aEtude->getNom() == "NON")
                {
                    $this->getDoctrine()->getManager()->remove($aEtude);
                }
            }
        }

        foreach ($allEh as $eHandicape) 
        {
            foreach ($eHandicape->getAideExamen()->getAdaptationDocuments() as $aDocument) 
            {
                if($aDocument->getDetail() == "NON")
                {
                    $this->getDoctrine()->getManager()->remove($aDocument);
                }
            }
        }

        $this->getDoctrine()->getManager()->flush();
        return new Response();
    }

    /**
     * récupère les données d'un étudiant recherché avec son id et les envoie à la page d'affichage
     * @param type $id
     * @return type
     */
    public function AfficherAccueilEtudiantAction($id)
    {
        $etudiantHandicapeRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHGestionBundle:EtudiantHandicape');
        
        $etudiant = $etudiantHandicapeRepository->find($id);

        $etudiantetudiantHandicapeRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHGestionBundle:EtudiantEtudiantHandicape');

        $a = $this->get('session')->get('filter');

        $etetH = $etudiantetudiantHandicapeRepository->findOneBy(
        array('anneeScolaire' => $a['year'], 
              'etudiantHandicape' => $id));  

        $tabHandicap = array();
        $tabFormation = array();
        $tabAideExamen = array();

        foreach($etudiant->getHandicap() as $val)
        {
            $tabHandicaparray[] = $val;
        }
        foreach($etetH->getEtudiant()->getListEtudiantFormation() as $val)
        {
            $tabFormationarray[] = $val;        
        }

        
        $formation = false;
        $handicap = false;
        $aideExamen = false;
    
        

        $informationsEtudiant = $etudiantHandicapeRepository->getInformationsStudent($id,$a['year']);
        
        return $this->render('SUHGestionBundle:AffichageEtudiants:spoiler.html.twig',array(
            'informationsEtudiant'=>$informationsEtudiant,
            'annee' => $a['year'],
        ));
    }     
    
    /**
     * Affiche l'accueil avec une liste composée d'étudiants récupérés suite à une recherche rapide
     * @param Request $request
     * @return type
     */
    public function AfficherAccueilEtudiantRechercheNomOuPrenomAction(Request $request)
    {
        return $this->render('SUHGestionBundle:AffichageEtudiants:accueil.html.twig',array(
            'listeEtudiantsHandicapes'=>$this->getListeEtudiants($request->query->get('chaine'))
        ));
    }     
        
    
    /**
     * Affiche l'importation et exportation
     * @param Request $request
     * @return type
     */
    public function afficheImportExportPageAction()
    {
        return $this->render('SUHGestionBundle:AffichageEtudiants:accueil.html.twig',array(
            'afficheExcelVue' => true,
        ));
    }  
    
    /**
     * Affiche l'importation et exportation
     * @param Request $request
     * @return type
     */
    public function afficheGestionUtilisateurPageAction()
    {
        return $this->render('SUHGestionBundle:AffichageEtudiants:accueil.html.twig',array(
            'userVue' => true,
        ));
    }  
    
    /**
     * Affiche la page de recherche avancée
     * @return type
     */
    public function AfficherRechercheAvanceeAction()
    {
        return $this->render('SUHGestionBundle:AffichageEtudiants:accueil.html.twig',array(
            'listeEtudiantsHandicapes'=>$this->getListeEtudiants(null),
            'rechercheAvancee'=>true
            
        ));
    }

    public function afficherListeAnneeAction(){
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('SUHGestionBundle:Annee')
        ;


        $listYears = $repository->myFindAll();
        return $this->render('SUHGestionBundle:AffichageEtudiants:listeAnnee.html.twig',array(
            'listYears'=> $listYears,
        ));
    }

    public function deleteAnneeAction($id, Request $request){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SUHGestionBundle:Annee');

        $year = $repository->find($id);
        $em->remove($year);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Année supprimée');
        return $this->redirectToRoute('suh_gestion_annee');
    }

    public function editAnneesAction(Request $request){

        $annee = new Annee();

        $array = array();
        $form =$this->get('form.factory')->createBuilder('form', $array)
            ->add('annee', 'integer')
            ->add('Ajouter',   'submit')

            ->getForm();

        if($request->isMethod("POST")) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $array = $form->getData();

                $anneeUniversitaire =  $array['annee']."-".($array['annee']+1);
                $annee->setAnneeUniversitaire($anneeUniversitaire);
                $em = $this->getDoctrine()->getManager();
                $em->persist($annee);

                try{
                    $em->flush();
                    $request->getSession()->getFlashBag()->add('notice', 'Nouvelle année ajoutée ');

                }
                catch(\Doctrine\DBAL\DBALException $e ){

                    $request->getSession()->getFlashBag()->add('error', 'Cette année existe déjà...');

                }
                finally{
                    return $this->redirectToRoute('suh_gestion_annee');
                }


            }
        }

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('SUHGestionBundle:Annee')
        ;

        $listYears = $repository->myFindAll();

        return $this->render('SUHGestionBundle:AffichageEtudiants:accueil.html.twig',array(
            'listYears'=>$listYears,
            'editAnnees'=>true,
            'form' => $form->createView(),

        ));
    }

    /**
     * Le but est de retourner tous les étudiants correspondant aux critères choisis par l'utilisateur
     */
    public function AfficherResultatRechercheAction(){
        $array = array();
        for($i=0;$i<32;$i++){
            if(!empty($_POST['InfoEcrite'.$i]) && isset($_POST['InfoEcrite'.$i])){
                if(!empty($_POST['InfoSelect'.$i]) && isset($_POST['InfoSelect'.$i])){ 

                    $condition = null;
                    if(isset($_POST['radio'.$i]))
                    { 
                        $condition = $_POST['radio'.$i];
                    }
                    $infoSelect = $_POST['InfoSelect'.$i];
                    $infoEcrite = $_POST['InfoEcrite'.$i];
                    //$i = $array[$i];
                    $array[$i][$infoSelect][0] = $infoEcrite; 
                    $array[$i][$infoSelect][1] = $condition;                   
                }
            }
        }

        $etudiantRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHGestionBundle:Etudiant');

        $a = $this->get('session')->get('filter');

        $year = $a['year'];

        $students = $etudiantRepository->getAllStudentsByYear($year);

        $tabStudents = array();

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

                        if(!array_key_exists($student->getId(), $tabStudents))
                        {
                            $tabStudents[$student->getId()] = null;
                        }

                        if($oneCondition[1] == 'ET')
                        {
                            if($c == true)
                            {
                                if($tabStudents[$student->getId()] == false)
                                {
                                    $tabStudents[$student->getId()] = false;
                                }  

                                else if($tabStudents[$student->getId()] == null)
                                {
                                    $tabStudents[$student->getId()] = true;
                                }    
                            }
                            else
                            {
                                if($tabStudents[$student->getId()] == null)
                                {
                                    $tabStudents[$student->getId()] = false;
                                } 
                                if($tabStudents[$student->getId()] == true)
                                {
                                    $tabStudents[$student->getId()] = false;
                                }
                            }
                        }
                        else if($oneCondition[1] == 'OU')
                        {
                            if($c == true)
                            {
                                if($tabStudents[$student->getId()] == false)
                                {
                                    $tabStudents[$student->getId()] = true;
                                }
                                else if($tabStudents[$student->getId()] == null)
                                {
                                    $tabStudents[$student->getId()] = true;
                                }
                            }
                            else
                            {
                                if($tabStudents[$student->getId()] == null)
                                {
                                    $tabStudents[$student->getId()] = false;
                                }
                            }                            
                        }
                        else if($oneCondition[1] == null)
                        {
                            $tabStudents[$student->getId()] = $c;
                        }
                    }
                }
            }

        $etudiantHandicapeRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHGestionBundle:EtudiantHandicape');

        $etudiantRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SUHGestionBundle:Etudiant');

        $i = 0;
        foreach($tabStudents as $id => $result)
        {
            if($result)
            {
                $eHandicape = $etudiantRepository->getHandicapStudentThisYear($id, $year);
                $etudiant = $etudiantRepository->find($id);

                if($eHandicape[0] != null && $etudiant != null)
                {                    
                    $a = $this->get('session')->get('filter');
                    $etuInfos = $etudiantRepository->getStudentInformationsByYear($id,$a['year']);

                    if($etuInfos != null)
                    {
                        $listFiltreeEtudiant[$i]["id"] = $eHandicape[0]['id'];
                        $listFiltreeEtudiant[$i]["nom"] = $etuInfos[0]['nom'];
                        $listFiltreeEtudiant[$i]["prenom"] = $etuInfos[0]['prenom'];
                        $listFiltreeEtudiant[$i]["mailInstitutionnel"] = $etuInfos[0]['mailInstitutionnel'];
                        $listFiltreeEtudiant[$i]["mailPerso"] = $etuInfos[0]['mailPerso'];
                        $i=$i+1;
                    }                    
                }                
            }
        }

        if($i>=1){
            return $this->render('SUHGestionBundle:AffichageEtudiants:accueil.html.twig',array(
                'listeEtudiantsHandicapes'=>$listFiltreeEtudiant
            ));
        }
        else{
            $listFiltreeEtudiant[$i]["id"] = 0;
            $listFiltreeEtudiant[$i]["nom"] = "Pas de résultat";
            $listFiltreeEtudiant[$i]["prenom"] = " ";
            $listFiltreeEtudiant[$i]["mailInstitutionnel"] = " ";
            $listFiltreeEtudiant[$i]["mailPerso"] = " ";
               return $this->render('SUHGestionBundle:AffichageEtudiants:accueil.html.twig',array(
                'listeEtudiantsHandicapes'=>$listFiltreeEtudiant
            ));
        }
    }
    
    
}
