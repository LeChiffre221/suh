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


        foreach($contrats as $contrat){
            
            foreach($contrat->getNatureContrat() as $nature){
                array_push($listeNatureContrats, $nature);
            }
            $moisTemp = intval(substr($contrat->getDateDebutContrat(),3,2), 10);
            $dateMonth = \DateTime::createFromFormat('!m', $moisTemp);
            if(!in_array($dateMonth->format('F'), $mois, true)){
                $mois[$moisTemp] = $dateMonth->format('F');
            }

        }


        //Formulaire selection
        $hue =array();
        $form = $this->createFormBuilder($hue)
            ->add('Annees', 'choice', array(
                'choices' => $mois
                ))
            ->add('Envoyer', 'submit')
            ->getForm()
            ;

        if ($form->handleRequest($request)->isValid()) {

            var_dump($form->getData());

            $listeNatureContrats = array();

            $contratsMois = $em->getRepository('SUHContratBundle:Contrat')->findAll();

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
            var_dump($form->getData());
            $data = json_encode($table);


            return $this->render('SUHContratBundle:Statistiques:natureStats.html.twig', array(

            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
            'listeNatureContrats' => $data,
            'form' => $form->createView(),
            'contenu' => $form->getData()
            ));

        }

        //Datas
        

        var_dump($mois);
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

        $heures = $em->getRepository('SUHContratBundle:HeureEffectuee')->findBy([], ['natureMission' => 'ASC']);
        $parameters = $em->getRepository('SUHContratBundle:Parameters')->find(1);
        $coefTutorat = $parameters->getCoefTutorat();
        $coefPriseDeNote = $parameters->getCoefPriseDeNote();
        $coefAssistance = $parameters->getCoefAssistance();

        var_dump($coefAssistance);
        $listeCoutContrats = array();
        $heureTemp = 0;

        foreach($heures as $heure){
            
            if(!in_array($heure->getNatureMission(), $listeCoutContrats, true)){
                // array_push($listeCoutContrats, $heure->getNatureMission());
                
                $listeCoutContrats[$heureTemp] = $heure->getNatureMission();
                $heureTemp = 0;
             }
            if(in_array($heure->getNatureMission(), $listeCoutContrats, true)){
                $heureTemp += $heure->getNbHeure();
                
            }
             
            

             
        }
        var_dump($listeCoutContrats);

        $rawData = array_count_values ($listeCoutContrats);

        $table['cols'] = array(
            array('type' => 'string', 'label' => 'Nature'),
            array('type' => 'number', 'label' => 'Nombre')
            );
        foreach($rawData as $key => $raw){
            if($key == 'assistancePédagogique'){
                $coef = $raw * $coefAssistance;
                $table['rows'][] = array('c' => array( array('v'=>$key), array('v'=>$coef)) );
                var_dump($coef);
            } else {
                 $table['rows'][] = array('c' => array( array('v'=>$key), array('v'=>$raw)) );
            }
        }

        $data = json_encode($table);


        return $this->render('SUHContratBundle:Statistiques:coutStats.html.twig', array(

            'listeEtudiantsAidants'=>$this->getListeEtudiants($session->get('chaine')),
            'listeCoutContrats' => $data

        ));
    }

}
