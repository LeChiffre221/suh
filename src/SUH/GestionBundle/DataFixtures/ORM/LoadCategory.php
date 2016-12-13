<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadCategory.php

namespace SUH\GestionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SUH\GestionBundle\Entity;

class LoadCategory extends \Symfony\Bundle\FrameworkBundle\Controller\Controller implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
      // Les noms d'utilisateurs à créer
    $listNames = array('admin');
    
    foreach ($listNames as $name) {
      // On crée l'utilisateur
      $user = new \SUH\ConnexionBundle\Entity\User();

      $factory = $this->get('security.encoder_factory'); 
      $encoder = $factory->getEncoder($user);
      
      // Le nom d'utilisateur et le mot de passe sont identiques
      $user->setUsername($name);
      //$user->setPassword($name);
      
      $password = $encoder->encodePassword('pass', $user->getSalt());
      $user->setPassword($password);
      
      
    
      // On ne se sert pas du sel pour l'instant
      $user->setSalt('');
      // On définit uniquement le role ROLE_USER qui est le role de base
      $user->setRoles(array('ROLE_USER'));

      // On le persiste
      $manager->persist($user);
    }

    // On déclenche l'enregistrement
    $manager->flush();
    /* ====================================================================== */
    /* Tables sans jointure */
    
    /*$arrayEtudiant = array();
    $arrayFormation = array();
    $arrayMdph = array();
    $arrayHandicap = array();
    $arrayAideExamen = array();
    
    for($i=0;$i<400;$i++)
    {
        $etudiant=new Entity\Etudiant('nom'.$i,'prenom'.$i,'01/01/2015','mail'.$i,
                'adresseFamiliale'.$i,'adresseEtudiante'.$i,'0606060606');
        $arrayEtudiant[]=$etudiant;
        $manager->persist($etudiant);
    }
    for($i=0;$i<400;$i++)
    {
        $formation=new Entity\Formation('diplome'.$i,'composante'.$i,'filiere'.$i,
                1,'etablissement'.$i,1);
        $arrayFormation[]=$formation;
        $manager->persist($formation);
    }
    for($i=0;$i<400;$i++)
    {
        $mdph=new Entity\Mdph($i%2,$i+2);
        $arrayMdph[]=$mdph;
        $manager->persist($mdph);
    }
    for($i=0;$i<400;$i++)
    {
        $handicap=new Entity\Handicap('handicap '.$i);
        $arrayHandicap[]=$handicap;
        $manager->persist($handicap);
    }
    for($i=0;$i<400;$i++)
    {
        $aideExamen=new Entity\AideExamen('$amenagement examen '.$i, $i%2, ($i.$i)%2,
                'delocalisaiton examen '.$i,'01/01/2012','duree avis medical '.$i);
        $arrayAideExamen[]=$aideExamen;
        $manager->persist($aideExamen);
    }
    $manager->flush();*/
    
    /* ====================================================================== */
    /* Tables avec jointures */
   /* $arrayEtudiantHandicape = array();
    $arrayDatesAideExamen = array();
    $arrayEtudiantFormation = array();
   
    for($i=0;$i<400;$i++)
    {
        $etudiantHandicape=new Entity\EtudiantHandicape($arrayEtudiant[$i],'qhandi '.$i,($i*3)%2,$i%2,
                'amenagement etude '.$i,'taux invalidite '.$i,'suivi '.$i,'01/01/2016',
                'descriptif complementaire '.$i,$arrayMdph[$i],$arrayHandicap[$i]);
        $arrayEtudiantHandicape[]=$etudiantHandicape;
        $manager->persist($etudiantHandicape);
    }
    
    $manager->flush();
    
    for($i=0;$i<400;$i++)
    {
        $datesAideExamen=new Entity\DatesAideExamen(
        $arrayEtudiantHandicape[$i],$arrayAideExamen[$i],null,null);
        $arrayDatesAideExamen[]=$datesAideExamen;
        $manager->persist($datesAideExamen);
    }
    
    for($i=0;$i<400;$i++)
    {
        $etudiantFormation=new Entity\EtudiantFormation(
                $arrayEtudiant[$i],'2015',$arrayFormation[$i]);
        $arrayEtudiantFormation[]=$etudiantFormation;
        $manager->persist($etudiantFormation);
    }
    
    $manager->flush();*/
  
    }
}