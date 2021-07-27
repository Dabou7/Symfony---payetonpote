<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Form\CampaignType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Participant;
use App\Entity\Payment;




/**
 * @Route("/campaign")
 */
class CampaignController extends AbstractController
{
    /**
     * @Route("/", name="campaign_index", methods={"GET"})
     */
    public function index(): Response
    {
        $campaigns = $this->getDoctrine()
            ->getRepository(Campaign::class)
            ->findAll();

        return $this->render('campaign/index.html.twig', [
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * @Route("/new", name="campaign_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $campaign = new Campaign();
        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $campaign->setId();
            $entityManager->persist($campaign);
            $entityManager->flush();

            return $this->redirectToRoute('campaign_index');
        }

        return $this->render('campaign/new.html.twig', [
            'campaign' => $campaign,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="campaign_show", methods={"GET"})
     */
    public function show(Campaign $campaign): Response
    {
        $participants = $this->getDoctrine()
        ->getRepository(Participant::class)
        ->findBy(["campaign"=>$campaign]);
        $participantCount=count($participants);

        

        $payments = $this->getDoctrine()
                         ->getRepository(Payment::class)
                         ->findBy(["participant"=>$participants]);

        $paymentCount=count($payments);
        $amountTotal = 0;
                foreach($payments as $payment)
                {
                    $amountTotal += $payment->getAmount();
                }

        $pourcentage = round(($amountTotal/$campaign->getGoal())*100);
            
        return $this->render('campaign/show.html.twig', [
            'campaign' => $campaign,
            'participantCount' => $participantCount,
            'payments' => $payments,
            'paymentCount' => $paymentCount,
            'amountTotal' => $amountTotal,
            'pourcentage' => $pourcentage
        ]);
    }

    /**
     * @Route("/{id}/edit", name="campaign_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Campaign $campaign): Response
    {
        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('campaign_index');
        }

        return $this->render('campaign/edit.html.twig', [
            'campaign' => $campaign,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/{id}/content", name="campaign_content", methods={"GET","POST"})
     */
    public function NewEdit(Request $request, Campaign $campaign): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $content = $request->request->get('content');

        $campaign->setContent($content)
                 ->setUpdatedAt(new \DateTime());   

        $entityManager->persist($campaign);
        $entityManager->flush();      
            
        return $this->redirectToRoute('campaign_index');
    
    }

    /**
     * @Route("/{id}", name="campaign_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Campaign $campaign): Response
    {
        if ($this->isCsrfTokenValid('delete'.$campaign->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($campaign);
            $entityManager->flush();
        }

        return $this->redirectToRoute('campaign_index');
    }
}
