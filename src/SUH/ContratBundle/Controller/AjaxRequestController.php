<?php

namespace SUH\ContratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class AjaxRequestController extends Controller
{    


    //ajax pour la selection des annees globale    
    public function refreshListAction(Request $request)
    {


        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }

        $year = $request->request->get('year');     

        $session = $this->get('session');
        $session->set('filter', array(
            'year' => $year,
        ));

        return $this->redirectToRoute('suh_contrat_homepage');

    }

    //ajax pour la gestion des annees de la reinscription
    public function refreshListEtuAction(Request $request)
    {
        $session = $this->getRequest()->getSession(); // Get started session
        if(!$session instanceof Session){
            $session = new Session(); // if there is no session, start it
        }

        $yearEtu = $request->request->get('yearEtu');  

        $session->set('filterEtu', array('year' => $yearEtu));

        return $this->redirectToRoute('suh_contrat_reinscription');
    }
}