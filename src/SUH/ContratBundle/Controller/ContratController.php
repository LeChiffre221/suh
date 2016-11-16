<?php
/**
 * Created by PhpStorm.
 * User: lechi
 * Date: 16/11/2016
 * Time: 08:47
 */

namespace SUH\ContratBundle\Controller;


use SUH\ContratBundle\Entity\Contrat;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContratController extends Controller
{
public function addContratAction(Request $request){

        $contrat = new Contrat();

        $em = $this->getDoctrine()->getManager();
        $em->persist($contrat);
        $em->flush();
        return $this->render('SUHContratBundle:AffichageContrats:addContrat.html.twig');
    }

}