<?php

namespace SUH\GestionBundle\Controller;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

use SUH\GestionBundle\Entity\AmenagementEtude;
use SUH\GestionBundle\Entity\Etudiant;
use SUH\GestionBundle\Entity\EtudiantInformations;
use SUH\GestionBundle\Entity\Formation;
use SUH\GestionBundle\Entity\Handicap;
use SUH\GestionBundle\Entity\Materiel;
use SUH\GestionBundle\Entity\Ordinateur;
use SUH\GestionBundle\Entity\SecretaireExamen;
use SUH\GestionBundle\Entity\DelocalisationExamen;
use SUH\GestionBundle\Entity\AdaptationDocuments;
use SUH\GestionBundle\Entity\AideExamen;
use SUH\GestionBundle\Entity\EtudiantHandicape;
use SUH\GestionBundle\Entity\Mdph;
use SUH\GestionBundle\Entity\EtudiantEtudiantHandicape;
use SUH\GestionBundle\Entity\EtudiantEtudiantInformations;
use SUH\GestionBundle\Entity\EtudiantFormation;
use SUH\GestionBundle\Entity\NotificationSavs;
use \DateTime;


class AjaxRequestController extends ListeEtudiantController
{    

    /* On utilise cette fonction pour l'ajout et la modification */

    public function addEtudiantAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager();

        $a = $this->get('session')->get('filter');
        
        if($request->request->get('action') == 'modification')
        {
            $etudiant = $em->getRepository('SUHGestionBundle:Etudiant')->find($request->request->get('id'));

            $numeroEtudiant = $request->request->get('numeroEtudiant');

            $premiereInscription = $request->request->get('premiereInscription');

            $dateNaissanceString = $request->request->get('dateNaissance');

            if(!empty($dateNaissanceString))
            {
                $dateNaissance = DateTime::createFromFormat('d/m/Y', $dateNaissanceString);
            }
            else
            {
                $dateNaissance = null;
            }

            $etudiant->setDateNaissance($dateNaissance);

            $etudiant->setPremiereInscription($premiereInscription);

            $etudiant->setNumeroEtudiant($numeroEtudiant);

            $em->persist($etudiant);


            //******************************************************

            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $mailInstitutionnel = $request->request->get('emailInstitutionnel');
            $parite = $request->request->get('parite');
            $mailPerso = $request->request->get('emailPersonnel');
            $mailParents = $request->request->get('emailParents');
            $adresseEtudiante = $request->request->get('adresseEtudiante');
            $adresseFamiliale = $request->request->get('adresseFamiliale');
            $telephonePerso = $request->request->get('telephonePerso');
            $telephoneParents = $request->request->get('telephoneParents');

            /*if(count($etudiant->getListEtudiantInformations()) == 0)
            {
                $etudiantInformations = new EtudiantInformations ($nom, $prenom, $mailInstitutionnel, $parite, $mailPerso, $mailParents, $adresseEtudiante, $adresseFamiliale
                    , $telephonePerso, $telephoneParents);

                $em->persist($etudiantInformations);

                $etudiantEtudiantInformations = new EtudiantEtudiantInformations ($etudiant,$a['year'],$etudiantInformations); 

                $em->persist($etudiantEtudiantInformations);
            }
            else
            {*/
            foreach ($etudiant->getListEtudiantInformations() as $key => $eeInfos) 
            {
                if($eeInfos->getAnneeScolaire() == $a['year'])
                {
                    $etudiantInformations = $eeInfos->getEtudiantInformations();
                    
                    $etudiantInformations->setNom($nom);
                    $etudiantInformations->setPrenom($prenom);
                    $etudiantInformations->setMailInstitutionnel($mailInstitutionnel);
                    $etudiantInformations->setParite($parite);
                    $etudiantInformations->setMailPerso($mailPerso);
                    $etudiantInformations->setMailParents($mailParents);
                    $etudiantInformations->setAdresseEtudiante($adresseEtudiante);
                    $etudiantInformations->setAdresseFamiliale($adresseFamiliale);
                    $etudiantInformations->setTelephonePerso($telephonePerso);
                    $etudiantInformations->setTelephoneParents($telephoneParents);

                    $em->persist($etudiantInformations);
                }            
            }  
            //}

            //******************************************************

            $i = 0;

            foreach ($etudiant->getListEtudiantFormation() as $key => $eeInfos) 
            {
                if($eeInfos->getAnneeScolaire() == $a['year'])
                {
                    $etudiantFormation = $eeInfos->getFormation();                   
                        
                    if($i==0)
                    {
                        $diplome = $request->request->get('diplome_1');
                        $composante = $request->request->get('composante_A');
                        $filiere = $request->request->get('filiere_A');
                        $cycle = $request->request->get('cycle_A');
                        $etablissement = $request->request->get('etablissement1');
                        $anneeEtude = $request->request->get('anneeEtude_A');   

                        if($diplome!='' || $composante!='' || $filiere!='' || $etablissement!='')
                        {
                            $etudiantFormation->setDiplome($diplome);
                            $etudiantFormation->setComposante($composante);
                            $etudiantFormation->setFiliere($filiere);
                            $etudiantFormation->setCycle($cycle);
                            $etudiantFormation->setEtablissement($etablissement);
                            $etudiantFormation->setAnneeEtude($anneeEtude);       
                            $em->persist($etudiantFormation);
                        }
                        else
                        {
                            
                            $em->remove($etudiantFormation);                            
                        }                    
                    }
                    if($i==1)
                    {
                        $diplome = $request->request->get('diplome_2');
                        $composante = $request->request->get('composante_B');
                        $filiere = $request->request->get('filiere_B');
                        $cycle = $request->request->get('cycle_B');
                        $etablissement = $request->request->get('etablissement2');
                        $anneeEtude = $request->request->get('anneeEtude_B');

                        if($diplome!='' || $composante!='' || $filiere!='' ||$etablissement!='')
                        {
                            $etudiantFormation->setDiplome($diplome);
                            $etudiantFormation->setComposante($composante);
                            $etudiantFormation->setFiliere($filiere);
                            $etudiantFormation->setCycle($cycle);
                            $etudiantFormation->setEtablissement($etablissement);
                            $etudiantFormation->setAnneeEtude($anneeEtude);
                            $em->persist($etudiantFormation);

                        }
                        else
                        {
                            $em->remove($etudiantFormation);
                            
                        } 
                    }
                    $i++;
                }   
            }
            $d = $request->request->get('diplome_1');
            $d2 = $request->request->get('diplome_2');
            if($i == 0 && !empty($d))
            {
                $f = new Formation ($request->request->get('diplome_1'),$request->request->get('composante_A'),$request->request->get('filiere_A'),$request->request->get('cycle_A'),$request->request->get('etablissement1'),$request->request->get('anneeEtude_A'));
                $em->persist($f);
                $ef = new EtudiantFormation($etudiant,$a['year'],$f);
                $em->persist($ef);
            }
            if(($i == 0 || $i == 1) && ($d2 != ''))
            {
                $f2 = new Formation ($request->request->get('diplome_2'),$request->request->get('composante_B'),$request->request->get('filiere_B'),$request->request->get('cycle_B'),$request->request->get('etablissement2'),$request->request->get('anneeEtude_B'));
                $em->persist($f2);
                $ef2 = new EtudiantFormation($etudiant,$a['year'],$f2);
                $em->persist($ef2);
            }
            
            //******************************************************

            $nom = $request->request->get('materielSUHText');

            foreach ($etudiant->getListEtudiantHandicape() as $key => $eeInfos) 
            {
                if($eeInfos->getAnneeScolaire() == $a['year'])
                {
                    $etudiantHandicape = $eeInfos->getEtudiantHandicape();
                    $materiel = $etudiantHandicape->getAideExamen()->getMateriel();
                    

                    if(!empty($materiel))
                    {   
                        if($request->request->get('materielSUH') == 'Oui')
                        {
                            $materiel->setNom($nom);
                            $em->persist($materiel);
                            $etudiantHandicape->getAideExamen()->setMateriel($materiel); 

                        }
                        else
                        {
                            $em->remove($materiel);
                            $etudiantHandicape->getAideExamen()->setMateriel(null);
                        }
                    }

                    else
                    {
                        if($request->request->get('materielSUH') == 'Oui')
                        {
                            $materiel = new Materiel ($nom);
                            $em->persist($materiel);
                            $etudiantHandicape->getAideExamen()->setMateriel($materiel);
                        }

                    }
                    $em->persist($etudiantHandicape); 
                    
                }
            }

            //******************************************************

            $fonction = $request->request->get('secretaireExamenOptions');

            foreach ($etudiant->getListEtudiantHandicape() as $key => $eeInfos) 
            {
                if($eeInfos->getAnneeScolaire() == $a['year'])
                {
                    $etudiantHandicape = $eeInfos->getEtudiantHandicape();
                    $secretaireExamen = $etudiantHandicape->getAideExamen()->getSecretaireExamen();

                    if($secretaireExamen)
                    {
                        if($request->request->get('secretaireExamen') == 'Oui')
                        {
                            $secretaireExamen->setFonction($fonction);
                            $em->persist($secretaireExamen);
                        }

                        else
                        {
                            $em->remove($secretaireExamen);
                            $etudiantHandicape->getAideExamen()->setSecretaireExamen(null);

                        }
                        

                    }

                    else
                    {
                        if($request->request->get('secretaireExamen') == 'Oui')
                        {
                            $secretaireExamen = new SecretaireExamen ($fonction);
                            $etudiantHandicape->getAideExamen()->setSecretaireExamen($secretaireExamen);
                            $em->persist($secretaireExamen);
                        }
                    }
                }
            }

            //******************************************************

            $lieu = $request->request->get('delocalisationExamOption');
            $detail = $request->request->get('delocalisationOptionText');

            foreach ($etudiant->getListEtudiantHandicape() as $key => $eeInfos) 
            {
                if($eeInfos->getAnneeScolaire() == $a['year'])
                {
                    $etudiantHandicape = $eeInfos->getEtudiantHandicape();
                    $delocalisationExamen = $etudiantHandicape->getAideExamen()->getDelocalisationExamen();

                    
                    if($delocalisationExamen!=null)
                    {
                        if($request->request->get('delocalisationExam') == 'Oui')
                        {

                            $delocalisationExamen->setLieu($lieu);
                            $delocalisationExamen->setDetail($detail);

                            $em->persist($delocalisationExamen);

                        }

                        else
                        {
                            $em->remove($delocalisationExamen);
                            $etudiantHandicape->getAideExamen()->setDelocalisationExamen(null);
                        }
                        

                    }

                    else
                    {
                        if($request->request->get('delocalisationExam') == 'Oui')
                        {
                            $delocalisationExamen = new DelocalisationExamen ($lieu, $detail);
                            $etudiantHandicape->getAideExamen()->setDelocalisationExamen($delocalisationExamen);
                            $em->persist($delocalisationExamen);
                        }
                    }
                }
            }
            
            //******************************************************
            $amenagementExamens = $request->request->get('amenagementExam');

            $tempsMajore = $request->request->get('tempsMajore') == 'true' ? true : false;
            
            $avisMedical = $request->request->get('avisMedical');

            $dateValiditeString = $request->request->get('dateValidite');
            if(!empty($dateValiditeString))
            {
                $dateValidite = DateTime::createFromFormat('d/m/Y', $dateValiditeString);
            }
            else
            {
                $dateValidite=null;
            }

            $dureeAvisMedical = $request->request->get('dureeAvisMedical');

            foreach ($etudiant->getListEtudiantHandicape() as $key => $eeInfos) 
            {
                if($eeInfos->getAnneeScolaire() == $a['year'])
                {
                    $etudiantHandicape = $eeInfos->getEtudiantHandicape();
                    $aideExamen = $etudiantHandicape->getAideExamen();

                    if($aideExamen!=null)
                    {
                            $aideExamen->setAmenagementExamens($amenagementExamens);
                            $aideExamen->setTempsMajore($tempsMajore);
                            $aideExamen->setAvisMedical($avisMedical);
                            $aideExamen->setDateValidite($dateValidite);
                            $aideExamen->setDureeAvisMedical($dureeAvisMedical);

                            $em->persist($aideExamen);
                    }

                    else
                    {
                        $aideExam = new AideExamen ($amenagementExamens,$tempsMajore,$secretaireExamen,
                        $delocalisationExamen,$avisMedical,$dateValidite,$dureeAvisMedical, $materiel);
                        
                        $em->persist($aideExam);
                    }
                }
            }

            //******************************************************

            $tabAdaptationDocuments = $request->request->get('adaptationDocumentsOptions');

            foreach ($etudiant->getListEtudiantHandicape() as $key => $eeInfos) 
            {
                if($eeInfos->getAnneeScolaire() == $a['year'])
                {
                    $etudiantHandicape = $eeInfos->getEtudiantHandicape();
                    $adaptationDocuments = $etudiantHandicape->getAideExamen()->getAdaptationDocuments();

                    if($adaptationDocuments)
                    {
                        foreach ($adaptationDocuments as $uneAdaptation) 
                        {   
                            $em->remove($uneAdaptation);
                            $adaptationDocuments = $etudiantHandicape->getAideExamen()->removeAdaptationDocuments($uneAdaptation);
                        }
                        

                        if(!empty($tabAdaptationDocuments))
                        {
                            foreach ($tabAdaptationDocuments as $uneAdaptation) 
                            {
                                $adaptation = new AdaptationDocuments($uneAdaptation);
                                $em->persist($adaptation);
                                $aideExamen->addAdaptationDocuments($adaptation);

                                $em->persist($aideExamen);
                            }    
                        }
                    }

                    else
                    {
                        if(!empty($tabAdaptationDocuments))
                        {
                            foreach ($tabAdaptationDocuments as $uneAdaptation) 
                            {
                                $adaptation = new AdaptationDocuments($uneAdaptation);
                                $em->persist($adaptation);
                                $aideExam->addAdaptationDocuments($adaptation);

                                $em->persist($aideExam);
                            }
                        }
                    }
                }
            }

            //******************************************************

            $tabOrdinateur = $request->request->get('ordinateurOption');

            foreach ($etudiant->getListEtudiantHandicape() as $key => $eeInfos) 
            {
                if($eeInfos->getAnneeScolaire() == $a['year'])
                {
                    $etudiantHandicape = $eeInfos->getEtudiantHandicape();
                    $ordinateur = $etudiantHandicape->getAideExamen()->getOrdinateur();

                    if($ordinateur!=null)
                    {
                        foreach ($ordinateur as $unOrdinateur) 
                        {
                            
                            $em->remove($unOrdinateur);
                            $etudiantHandicape->getAideExamen()->removeOrdinateur($unOrdinateur);

                        }
                        

                        if(!empty($tabOrdinateur))
                        {
                            foreach ($tabOrdinateur as $unOrdinateur) 
                            {   
                                $ordinateur = new Ordinateur($unOrdinateur);
                                $em->persist($ordinateur);
                                $aideExamen->addOrdinateur($ordinateur);

                                $em->persist($aideExamen);
                            }
                        }
                    }

                    else
                    {
                        if(!empty($tabOrdinateur))
                        {
                            foreach ($tabOrdinateur as $unOrdinateur) 
                            {   
                                $ordinateur = new Ordinateur($unOrdinateur);
                                $em->persist($ordinateur);
                                $aideExam->addOrdinateur($ordinateur);

                                $em->persist($aideExam);
                            }
                        }
                    }
                }
            }

            //******************************************************

            $qhandi = $request->request->get('qhandi');
            $rqth = $request->request->get('rqth');
            $notificationText = $request->request->get('notificationSAVSText');
            $demandeRqthEnCours = $request->request->get('demandeRqthEnCours') == 'true' ? true : false;
            $demandeNotificationSavsEnCours = $request->request->get('demandeNotificationSavsEnCours') == 'true' ? true : false;
            $tauxInvalidite = $request->request->get('tauxInvalidite');
            $suivi = $request->request->get('suivi');
            $typeAllocations = $request->request->get('typeAllocation');
            $descriptifComplementaire = $request->request->get('descriptifComplementaire');


            foreach ($etudiant->getListEtudiantHandicape() as $key => $eeInfos) 
            {
                if($eeInfos->getAnneeScolaire() == $a['year'])
                {
                    $etudiantHandicape = $eeInfos->getEtudiantHandicape();
                    $notificationSavs=$etudiantHandicape->getNotificationSavs();

                    if($etudiantHandicape!=null)
                    {
                            $etudiantHandicape->setQhandi($qhandi);
                            $etudiantHandicape->setRqth($rqth);
                            if($notificationSavs!=null)
                            {
                                if($request->request->get('notificationSAVS') == 'Oui' || $request->request->get('notificationSAVS') == 'En cours')
                                {
                                   $etudiantHandicape->getNotificationSavs()->setNotificationText($notificationText);
                                   $em->persist($notificationSavs);
                                   $em->persist($etudiantHandicape);
                                }
                                else
                                {   
                                    $no = $etudiantHandicape->getNotificationSavs()->getNotificationText();
                                    if(!empty($no))
                                    {
                                        $etudiantHandicape->setNotificationSavs(null);
                                        $em->remove($notificationSavs);
                                    }
                                }
                            }
                            else
                            {
                                if($request->request->get('notificationSAVS') == 'Oui' || $request->request->get('notificationSAVS') == 'En cours')
                                    {
                                        $notificationSavs = new NotificationSavs ($notificationText);

                                        $etudiantHandicape->setNotificationSavs($notificationSavs);
                                        $em->persist($notificationSavs);
                                        $em->persist($etudiantHandicape); 
                                    }
                            }                 
                            $etudiantHandicape->setDemandeRqthEnCours($demandeRqthEnCours);
                            $etudiantHandicape->setDemandeNotificationSavsEnCours($demandeNotificationSavsEnCours);
                            $etudiantHandicape->setTauxInvalidite($tauxInvalidite);
                            $etudiantHandicape->setSuivi($suivi);
                            $etudiantHandicape->setTypeAllocations($typeAllocations);
                            $etudiantHandicape->setDescriptifComplementaire($descriptifComplementaire);
                    }
                    else
                    {
                        if($request->request->get('notificationSAVS') == 'Oui')
                        {
                            $notificationSavs = new NotificationSavs ($notificationText);
                            $em->persist($notificationSavs);
                        }
                        elseif($request->request->get('notificationSAVS') == 'En cours')
                        {
                            $notificationSavs = new NotificationSavs ($notificationText);
                            $em->persist($notificationSavs);
                        }
                        else
                        {
                            $notificationSavs= null;
                        }
                        $etudiantHandicape = new EtudiantHandicape ($qhandi,$rqth,$notificationSavs,null,
                        $tauxInvalidite,$suivi,$typeAllocations,$descriptifComplementaire,null,null,false, $demandeRqthEnCours, $demandeNotificationSavsEnCours,$aideExam); 
                        $em->persist($etudiantHandicape); 

                        $etudiantEtudiantHandicape = new EtudiantEtudiantHandicape ($etudiant,$a['year'],$etudiantHandicape); 
                        $em->persist($etudiantEtudiantHandicape); 
                    }
                }
            }
            
            //******************************************************

            $amenagementEtudeOption=$request->request->get('amenagementEtudeOption');

            foreach ($etudiant->getListEtudiantHandicape() as $key => $eeInfos) 
            {
                if($eeInfos->getAnneeScolaire() == $a['year'])
                {
                    $etudiantHandicape = $eeInfos->getEtudiantHandicape();
                    $amenagementEtude = $etudiantHandicape->getAmenagementEtude();
                    
                    if($amenagementEtude!=null)
                    {
                        foreach ($amenagementEtude as $key => $unAmenagement) 
                        {
                            $em->remove($unAmenagement);
                            $etudiantHandicape->removeAmenagementEtude($unAmenagement);
                        }
                        

                        if(!empty($amenagementEtudeOption))
                        {
                            foreach (($amenagementEtudeOption) as $key => $unAmenagement) 
                            {

                                $amenagementEtude = new AmenagementEtude($unAmenagement['type'], $unAmenagement['infoComplementaires'], $unAmenagement['detail']);

                                $em->persist($amenagementEtude);
                                $etudiantHandicape->addAmenagementEtude($amenagementEtude);

                                $em->persist($etudiantHandicape);
                            }  
                        }
                    }

                    else
                    {
                        if(!empty($amenagementEtudeOption))
                        {
                            foreach (($amenagementEtudeOption) as $key => $unAmenagement) 
                            {
                                $amenagementEtude = new AmenagementEtude($unAmenagement['type'], $unAmenagement['infoComplementaires'], $unAmenagement['detail']);

                                $em->persist($amenagementEtude);
                                $etudiantHandicape->addAmenagementEtude($amenagementEtude);

                                $em->persist($etudiantHandicape);
                            }
                        }
                    }
                }
            }

            //******************************************************

            foreach ($etudiant->getListEtudiantHandicape() as $key => $eeInfos) 
            {
                if($eeInfos->getAnneeScolaire() == $a['year'])
                {
                    $etudiantHandicape = $eeInfos->getEtudiantHandicape();
                    $mdph=$etudiantHandicape->getMdph();

                    if($mdph!=null)
                    {
                        if($request->request->get('mdph') == 'Non')
                        {
                            $em->remove($mdph);
                            $etudiantHandicape->setMdph(null);

                        }
                        else
                        {
                            $etudiantHandicape->getMdph()->setDepartementMdph($request->request->get('numeroMdph'));
                            $etudiantHandicape->setMdph($mdph);
                        }
                    }

                    else
                    {
                       if($request->request->get('mdph') == 'Oui')
                        {
                            $mdph = new Mdph($request->request->get('numeroMdph'));
                            $etudiantHandicape->setMdph($mdph);
                        }
                        elseif($request->request->get('mdph') == 'En cours')
                        {
                            $etudiantHandicape->setDemandeMdphEnCours(true);
                            $mdph = new Mdph($request->request->get('numeroMdph'));
                            $etudiantHandicape->setMdph($mdph);
                        } 
                    }
                    $em->persist($etudiantHandicape);
                }
            }

            //******************************************************

            $tabHandicap = $request->request->get('typeHandicap');

            foreach ($etudiant->getListEtudiantHandicape() as $key => $eeInfos) 
            {
                if($eeInfos->getAnneeScolaire() == $a['year'])
                {
                    $etudiantHandicape = $eeInfos->getEtudiantHandicape();
                    $handicap=$etudiantHandicape->getHandicap();

                    if($handicap != null)
                    {

                        foreach ($handicap as $hand) 
                        {
                            $em->remove($hand);
                            $etudiantHandicape->removeHandicap($hand);
                        }

                        if(!empty($tabHandicap))
                        {
                            foreach ($tabHandicap as $unHandicap) 
                            {
                                if($unHandicap != '')
                                {
                                    $handicap = new Handicap($unHandicap);
                                    $em->persist($handicap);
                                    $etudiantHandicape->addHandicap($handicap);
                                }
                            }
                        }

                        $em->persist($etudiantHandicape);
                    }
                    else
                    {
                        if($handicap)
                        {
                            foreach ($tabHandicap as $unHandicap) 
                            {
                                $handicap = new Handicap($unHandicap);
                                $em->persist($handicap);
                                $etudiantHandicape->addHandicap($handicap);
                            }
                            $em->persist($etudiantHandicape);
                        }

                    }
                }
            }
            $em->flush();
        }
        else
        {
            $numeroEtudiant = $request->request->get('numeroEtudiant');
            $dateNaissanceString = $request->request->get('dateNaissance');

            $premiereInscription = $request->request->get('premiereInscription');

            if(!empty($dateNaissanceString))
            {
                $dateNaissance = DateTime::createFromFormat('d/m/Y', $dateNaissanceString);
            }
            else
            {
                $dateNaissance = null;
            }

            if($request->request->get('action') == 'add')
            {

                $etudiant = new Etudiant ($numeroEtudiant, $dateNaissance, $premiereInscription);


            }
            else
            {
                //var_dump($request->request); die;
                $etudiant = $em->getRepository('SUHGestionBundle:Etudiant')->find($request->request->get('id'));
                $etudiant->setDateNaissance($dateNaissance);
                $etudiant->setNumeroEtudiant($numeroEtudiant);
            }
            $em->persist($etudiant);

            //******************************************************
            
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $mailInstitutionnel = $request->request->get('emailInstitutionnel');
            $parite = $request->request->get('parite');
            

            $mailPerso = $request->request->get('emailPersonnel');
            $mailParents = $request->request->get('emailParents');
            $adresseEtudiante = $request->request->get('adresseEtudiante');
            $adresseFamiliale = $request->request->get('adresseFamiliale');
            $telephonePerso = $request->request->get('telephonePerso');
            $telephoneParents = $request->request->get('telephoneParents');

            $etudiantInformations = new EtudiantInformations ($nom, $prenom, $mailInstitutionnel, $parite, $mailPerso, $mailParents, $adresseEtudiante, $adresseFamiliale
            , $telephonePerso, $telephoneParents);

            $em->persist($etudiantInformations);

            //******************************************************

            $diplome = $request->request->get('diplome_1');
            $composante = $request->request->get('composante_A');
            $filiere = $request->request->get('filiere_A');
            $cycle = $request->request->get('cycle_A');
            $etablissement = $request->request->get('etablissement1');
            $anneeEtude = $request->request->get('anneeEtude_A');
            $a = $this->get('session')->get('filter');
            $anneeScolaire = $a['year'];
            
            if($diplome != '' || $composante != '' || $filiere != '' || $etablissement != '')
            {
                $formation_1 = new Formation ($diplome,$composante,$filiere,$cycle,$etablissement,$anneeEtude);
                $em->persist($formation_1);

                $etudiantFormation = new EtudiantFormation ($etudiant,$anneeScolaire,$formation_1);
                $em->persist($etudiantFormation);
            }
            
            $d2 = $request->request->get('diplome_2');
            if(!empty($d2))
            {
                $diplome = $request->request->get('diplome_2');
                $composante = $request->request->get('composante_B');
                $filiere = $request->request->get('filiere_B');
                $cycle = $request->request->get('cycle_B');
                $etablissement = $request->request->get('etablissement2');
                $anneeEtude = $request->request->get('anneeEtude_B');
            
                $formation_2 = new Formation ($diplome,$composante,$filiere,$cycle,$etablissement,$anneeEtude);
                $em->persist($formation_2);
            }           

            //******************************************************

            $nom = $request->request->get('materielSUHText');

            if($request->request->get('materielSUH') == 'Oui')
            {
                $materiel = new Materiel ($nom);
                $em->persist($materiel);
            }

            else
            {
                $materiel= null;
            }


    //******************************************************

            $fonction = $request->request->get('secretaireExamenOptions');

            if($request->request->get('secretaireExamen') == 'Oui')
            {
                $secretaireExamen = new SecretaireExamen ($fonction);
                $em->persist($secretaireExamen);
            }
            else
            {
                $secretaireExamen = null;
            }            

            //******************************************************

            $lieu = $request->request->get('delocalisationExamOption');
            $detail = $request->request->get('delocalisationOptionText');

            if($request->request->get('delocalisationExam') == 'Oui')
            {
                $delocalisationExamen = new DelocalisationExamen ($lieu,$detail);

                $em->persist($delocalisationExamen);
            }

            else
            {
                $delocalisationExamen = null;
            }

            //******************************************************

            $amenagementExamen = $request->request->get('amenagementExam');

            $tempsMajore = $request->request->get('tempsMajore') == 'true' ? true : false;
            
            $avisMedical = $request->request->get('avisMedical');

            $dateValiditeString = $request->request->get('dateValidite');
            if(!empty($dateValiditeString))
            {
                $dateValidite = DateTime::createFromFormat('d/m/Y', $dateValiditeString);
            }
            else
            {
                $dateValidite=null;
            }

            $dureeAvisMedical = $request->request->get('dureeAvisMedical');

            $aideExam = new AideExamen ($amenagementExamen,$tempsMajore,$secretaireExamen,
                $delocalisationExamen,$avisMedical,$dateValidite,$dureeAvisMedical, $materiel);
            
            $em->persist($aideExam);

            //******************************************************

            $tabAdaptationDocuments = $request->request->get('adaptationDocumentsOptions');
            if(!empty($tabAdaptationDocuments))
            {
                foreach ($tabAdaptationDocuments as $uneAdaptation) {
                    $adaptation = new AdaptationDocuments($uneAdaptation);
                    $em->persist($adaptation);
                    $aideExam->addAdaptationDocuments($adaptation);
                }

                $em->persist($aideExam);
            }
           
            //******************************************************
     
            $tabOrdinateur = $request->request->get('ordinateurOption');
            if(!empty($tabOrdinateur))
            {   
                foreach ($tabOrdinateur as $unOrdinateur) {
                    $ordinateur = new Ordinateur($unOrdinateur);
                    $em->persist($ordinateur);
                    $aideExam->addOrdinateur($ordinateur);
                }

                $em->persist($aideExam);
            }

            //******************************************************

            $qhandi = $request->request->get('qhandi');
            $rqth = $request->request->get('rqth');

            $notificationText = $request->request->get('notificationSAVSText');

            if($request->request->get('notificationSAVS') == 'Oui')
            {
            $notificationSavs = new NotificationSavs ($notificationText);

            $em->persist($notificationSavs);
            }

            elseif($request->request->get('notificationSAVS') == 'En cours')
            {
                $notificationSavs = new NotificationSavs ($notificationText);
                $em->persist($notificationSavs);
            }

            else
            {
                $notificationSavs= null;
            }

            $demandeRqthEnCours = $request->request->get('demandeRqthEnCours') == 'true' ? true : false;
            $demandeNotificationSavsEnCours = $request->request->get('demandeNotificationSavsEnCours') == 'true' ? true : false;
            $tauxInvalidite = $request->request->get('tauxInvalidite');
            $suivi = $request->request->get('suivi');
            $typeAllocations = $request->request->get('typeAllocation');
            $descriptifComplementaire = $request->request->get('descriptifComplementaire');

            $etudiantHandicape = new EtudiantHandicape ($qhandi,$rqth,$notificationSavs,null,
                $tauxInvalidite,$suivi,$typeAllocations,$descriptifComplementaire,null,null,false, $demandeRqthEnCours, $demandeNotificationSavsEnCours,$aideExam); 


            //******************************************************
            $amenagementEtudeOption=$request->request->get('amenagementEtudeOption');

            if(!empty($amenagementEtudeOption))
            {
            foreach (($amenagementEtudeOption) as $key => $unAmenagement) 
                {

                    $amenagementEtude = new AmenagementEtude($unAmenagement['type'], $unAmenagement['infoComplementaires'], $unAmenagement['detail']);

                    $em->persist($amenagementEtude);
                    $etudiantHandicape->addAmenagementEtude($amenagementEtude);
                }

            $em->persist($etudiantHandicape);
            }            

            //******************************************************

            if($request->request->get('mdph') == 'Oui')
            {
                $mdph = new Mdph($request->request->get('numeroMdph'));
                $etudiantHandicape->setMdph($mdph);
            }
            elseif($request->request->get('mdph') == 'En cours')
            {
                $etudiantHandicape->setDemandeMdphEnCours(true);
                $mdph = new Mdph($request->request->get('numeroMdph'));
                $etudiantHandicape->setMdph($mdph);
            }

            //******************************************************

            $tabHandicap = $request->request->get('typeHandicap');

            if(!empty($tabHandicap))
            {
                foreach ($tabHandicap as $unHandicap) 
                {
                    $handicap = new Handicap($unHandicap);
                    $em->persist($handicap);
                    $etudiantHandicape->addHandicap($handicap);
                }
            

            $em->persist($etudiantHandicape);
            }

            //******************************************************            

            $etudiantEtudiantHandicape = new EtudiantEtudiantHandicape ($etudiant,$anneeScolaire,$etudiantHandicape); 
            $em->persist($etudiantEtudiantHandicape);

        //******************************************************

            $etudiantEtudiantInformations = new EtudiantEtudiantInformations ($etudiant,$anneeScolaire,$etudiantInformations); 
            $em->persist($etudiantEtudiantInformations);

        //******************************************************

            if(isset($formation_2) && $formation_2 !== null)
            {
                $etudiantFormation2 = new EtudiantFormation ($etudiant,$anneeScolaire,$formation_2);
                $em->persist($etudiantFormation2);
            }           

            $em->flush();
        }
        return $this->redirectToRoute('suh_gestion_homepage');

    }

    public function refreshListAction(Request $request)
    {
        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }
        
        $year = $request->request->get('year');     


        $session->set('filter', array(
            'year' => $year,
        ));

        return $this->redirectToRoute('suh_gestion_homepage');
    }
}