<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Payment;
use App\Entity\Participant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class PaymentController extends AbstractController
{
     /**
     * @Route("/payment/{id}", name="payment")
     */
   public function index(Request $request, Campaign $campaign): Response
   {
       $amount = $request->request->get('amount');

       return $this->render('campaign/payment.html.twig', [
           'controller_name' => 'PaymentController',
           'campaign' => $campaign,
           'amount' => $amount,
           ]);
           
   }   


   /**
     * @Route("/payment_send/{id}", name="payment_send")
     */
    public function newPayment(Request $request, Campaign $campaign): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $participant = new Participant();

        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $anonymat = $request->request->get('anonymat');

        if($anonymat === "on"){
            $participant->setCampaign($campaign);
            $entityManager->persist($participant);
            $entityManager->flush();
        }else{
            $participant->setName($name)
            ->setEmail($email)
            ->setCampaign($campaign);
            $entityManager->persist($participant);
            $entityManager->flush();
        }
         

        $payments = new Payment();
        $amount = $request->request->get('amount');
       
        if($amount > 0){
            $payments->setAmount($amount)
            ->setParticipant($participant)
            ->setCreatedAt(new \DateTime());

            $entityManager->persist($payments);
            $entityManager->flush();  
        }
        
       
            
      

        // Set your secret key. Remember to switch to your live secret key in production!
// See your keys here: https://dashboard.stripe.com/account/apikeys
try {
    \Stripe\Stripe::setApiKey('sk_test_51H2ZbWBOL1Ug5bIFqKwkRdtImBSxtHOIdClHA8RkNjdi0fJD7hKBvhbSLN3CYOOoG4NEaNg3UaJSD4sUQmFv8ArB00W293UMtm');

// Token is created using Stripe Checkout or Elements!
// Get the payment token ID submitted by the form:
$token = $request->request->get('stripeToken');
$charge = \Stripe\PaymentIntent::create([
'amount' => $payments,
'currency' => 'usd',
'description' => 'Example charge',
'source' => $token,
]);
} 
catch(\Exception $e){
    dd('erreur payment',$e,$e->getMessage());
}
return $this->redirectToRoute('campaign_index');
    }  
}

 




