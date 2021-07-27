<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Campaign;
use App\Entity\Participant;
use App\Entity\Payment;


class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        $campaigns = $this->getDoctrine()
        ->getRepository(Campaign::class)
        ->findAll();
        
        $participants = $this->getDoctrine()
        ->getRepository(Participant::class)
        ->findAll();
        

        $payments = $this->getDoctrine()
        ->getRepository(Payment::class)
        ->findAll();

            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'campaigns' => $campaigns,
                'participants' => $participants,
                'payments' => $payments,
                
                
        ]);

    
    }
}
