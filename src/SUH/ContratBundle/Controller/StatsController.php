<?php

namespace SUH\ContratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use SUH\ContratBundle\Entity\Parameters;
use SUH\ContratBundle\Form\ParametersType;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\DateTime;

class StatsController extends Controller
{

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

    /**
     * Traite les données des statistiques avancees
     * @return type
     */
    public function TraitementStatsAvanceesAction(Request $request)
    {
        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }

        return $this->render('SUHContratBundle:Statistiques:avanceesStats.html.twig', array(

            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
        ));
    }

    /**
     * Traite les données des statistiques par heures
     * @return type
     */
    public function TraitementStatsHeuresAction(Request $request)
    {
        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }

        return $this->render('SUHContratBundle:Statistiques:avanceesStats.html.twig', array(

            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
        ));
    }

    /**
     * Traite les données des statistiques par nature
     * @return type
     */
    public function TraitementStatsNatureAction(Request $request)
    {
        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }
        $em = $this->getDoctrine()->getManager();

        //JSON

        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $contrats = $em->getRepository('SUHContratBundle:Contrat')->findAll();

        $listeNatureContrats = array();
        $mois = array();
        $annees = array();


        foreach($contrats as $contrat){
            
            foreach($contrat->getNatureContrat() as $nature){
                array_push($listeNatureContrats, $nature);
            }

            $moisTemp = intval(substr($contrat->getDateDebutContrat(),3,2), 10);
            $dateMonth = \DateTime::createFromFormat('!m', $moisTemp);

            $anneeTemp = intval(substr($contrat->getDateDebutContrat(),6,4), 10);
            $dateYear = \DateTime::createFromFormat('!Y', $anneeTemp);

            if(!in_array($dateMonth->format('F'), $mois, true)){
                $mois[$moisTemp] = $dateMonth->format('F');
            }
            if(!in_array($dateYear->format('Y'), $annees, true)){
                $annees[$anneeTemp] = $dateYear->format('Y');
            }


   

        }


        //Formulaire selection
        $hue =array();
        $form = $this->createFormBuilder($hue)
            ->add('Annees', 'choice', array(
                'choices' => $annees
                ))
            ->add('Mois', 'choice', array(
                'choices' => $mois
                ))
            ->add('Envoyer', 'submit')
            ->getForm()
            ;

        if ($form->handleRequest($request)->isValid()) {

            
            $temp = $form->getData();


            $mois = \DateTime::createFromFormat('m', $temp['Annees']) ;
            $moisPlusUn = \DateTime::createFromFormat('!m', $temp['Annees'] + 1) ;


            $listeNatureContrats = array();

            $contratsMois = $em->getRepository('SUHContratBundle:Contrat')->findByDateDebutContrat($mois);

            foreach($contratsMois as $contrat){
            
                foreach($contrat->getNatureContrat() as $nature){
                    array_push($listeNatureContrats, $nature);
                }
            }

            $rawData = array_count_values ($listeNatureContrats);

            $table['cols'] = array(
                array('type' => 'string', 'label' => 'Nature'),
                array('type' => 'number', 'label' => 'Nombre')
                );
            foreach($rawData as $key => $raw){
                $table['rows'][] = array('c' => array( array('v'=>$key), array('v'=>$raw)) );
            }
            $data = json_encode($table);


            return $this->render('SUHContratBundle:Statistiques:natureStats.html.twig', array(

            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
            'listeNatureContrats' => $data,
            'form' => $form->createView(),
            'contenu' => $form->getData()
            ));

        }

        //Datas
        

        $rawData = array_count_values ($listeNatureContrats);

        $table['cols'] = array(
            array('type' => 'string', 'label' => 'Nature'),
            array('type' => 'number', 'label' => 'Nombre')
            );
        foreach($rawData as $key => $raw){
            $table['rows'][] = array('c' => array( array('v'=>$key), array('v'=>$raw)) );
        }

        $data = json_encode($table);


        return $this->render('SUHContratBundle:Statistiques:natureStats.html.twig', array(

            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
            'listeNatureContrats' => $data,
            'form' => $form->createView()
        ));
    }


    /**
     * Traite les données des statistiques par cout
     * @return type
     */
    public function TraitementStatsCoutAction(Request $request)
    {
        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }

        $em = $this->getDoctrine()->getManager();

        //JSON

        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $heures = $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy(array('heurePayee' => 1), ['dateAndTime' => 'ASC']);
        $parameters = $em->getRepository('SUHConnexionBundle:Parameters')->find(1);
        $coefTutorat = $parameters->getCoefTutorat();
        $coefPriseDeNote = $parameters->getCoefPriseDeNote();
        $coefAssistance = $parameters->getCoefAssistance();
        $coutHoraire = $parameters->getPrixHoraire();
        
        //jalons limites
        $jourLimite = intval(substr($parameters->getDateMoisLimite(),0,2), 10);
        $moisLimite = intval(substr($parameters->getDateMoisLimite(),3,2), 10);
        //Init var
        $listeCoutContrats = array();
        $listeCoutContratsss = array();
        $tempHours = 0;
        $heureTemp = 0;
        $tempNature = null;
        $tempYear = 0;
        $arrayTemp = array('assistancePédagogique' => 0, 'tutorat' => 0, 'priseNote' => 0);
        
        //Header du chart
        $table['cols'] = array(
            array('type' => 'string', 'label' => 'Année'),
            array('type' => 'number', 'label' => 'Assistance'),
            array('type' => 'number', 'label' => 'Tutorat'),
            array('type' => 'number', 'label' => 'Prise de note')
        );

        //Extrait les donnees des contrats
        foreach($heures as $heure){
            $day = intval(substr($heure->getDateAndTime(),0,2), 10);
            $month = intval(substr($heure->getDateAndTime(),3,2), 10);

            //Si le jour du contrat est superieur a la date limite, annee = annee + 1
            if($month > $moisLimite || ($day > $jourLimite && $month == $moisLimite)){
                $annee = intval(substr($heure->getDateAndTime(),6,4), 10) + 1;
            } else {
                $annee = intval(substr($heure->getDateAndTime(),6,4), 10);
            }

            //Nouvelle annee ?
            if($tempYear != $annee){
                $arrayTemp = array('assistancePédagogique' => 0, 'tutorat' => 0, 'priseNote' => 0);
                $tempHours = 0;
            }

            //Nouvelle nature ?
            if($tempNature != $heure->getNatureMission()){
                $tempHours = 0;
                $tempNature = null;
            }
            //Coef Horaire + Coef Nature
            if($heure->getNatureMission() == "tutorat"){
                $arrayTemp[$heure->getNatureMission()] = (($heure->getNbHeure() * $coefTutorat) * $coutHoraire) + (($tempHours * $coefTutorat) * $coutHoraire);
            } elseif($heure->getNatureMission() == "assistancePédagogique") {
                $arrayTemp[$heure->getNatureMission()] = (($heure->getNbHeure() * $coefAssistance) * $coutHoraire) + (($tempHours * $coefAssistance) * $coutHoraire);
            } else {
                $arrayTemp[$heure->getNatureMission()] = (($heure->getNbHeure() * $coefPriseDeNote) * $coutHoraire) + (($tempHours * $coefPriseDeNote) * $coutHoraire);
            }
            

            $listeCoutContrats[$annee] = $arrayTemp;

            $tempYear = $annee;
            $tempHours += $heure->getNbHeure();
            $tempNature = $heure->getNatureMission();

        }

        //Fabrique un array au format json pour le google chart
        foreach($listeCoutContrats as $key => $cout){

            $arrayTempTotal = array();
            $arrayTempKey = array();

            array_push($arrayTempKey, array('v'=>$key) );

            foreach($cout as $keyCout => $total){
                array_push($arrayTempTotal, array('v'=>$total) );
            }
            $result = array_merge($arrayTempKey, $arrayTempTotal);

            $table['rows'][] = array('c' => $result );

        }

        //Encodage
        $data = json_encode($table);

        $this->calculeBudget($heures, $jourLimite, $moisLimite);


        return $this->render('SUHContratBundle:Statistiques:coutStats.html.twig', array(

            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
            'listeCoutContrats' => $data

        ));
    }

    public function calculeBudget($heurePaye, $jourLimite, $moisLimite, $coefTutorat, $coefPriseDeNote, $coefAssistance, $coutHoraire){

        $arrayCout = null;

        foreach ($heurePaye as $heure){
            $annee = substr($heure->getDate(), 0, 4);
            $mois = substr($heure->getDate(), 5, 7);
            $jour = substr($heure->getDate(), 8, 10);

            //Si la date jalon est dépasé
            if( ( $mois > $moisLimite ) || ( ( $mois == $moisLimite ) && ( $jour >= $jourLimite ) ) ){
                $annee++;
            }

            if( key(end($arrayCout)) != $annee){
                $arrayCout[$annee]['nbHeure'] = 0;
                $arrayCout[$annee]['cout'] = 0;
            }

            $arrayCout[$annee]['nbHeure'] += $heure->getNbHeure();

            switch ($heure->getNatureMission()){
                case 'tutorat':
                    $arrayCout[$annee]['cout'] += $heure->getNbHeure()*$coutHoraire*$coefTutorat;
                    break;

                case 'priseNote':
                    $arrayCout[$annee]['cout'] += $heure->getNbHeure()*$coutHoraire*$coefPriseDeNote;
                    break;

                case 'assistancePédagogique':
                    $arrayCout[$annee]['cout'] += $heure->getNbHeure()*$coutHoraire*$coefAssistance;
                    break;
            }

            return $arrayCout;

        }
    }

}
