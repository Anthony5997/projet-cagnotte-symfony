<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\Campaign;
use App\Form\PaymentType;
use App\Repository\PaymentRepository;
use App\Repository\CampaignRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/payment")
 */
class PaymentController extends AbstractController
{
    /**
     * @Route("/", name="payment_index", methods={"GET"})
     */
    public function index(PaymentRepository $paymentRepository): Response
    {
        return $this->render('payment/index.html.twig', [
            'payments' => $paymentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="payment_new", methods={"GET","POST"})
     */
    public function new(Request $request, CampaignRepository $campaignRepository, Campaign $campaign ): Response
    {
        $amount = $request->get('amount');

        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
                
            $payment->getRelation()->setCampaignId($campaign);

                \Stripe\Stripe::setApiKey('sk_test_51ItX7eBHDY4ycr92YZjcJPRRUnKdJ9LCPv2nnks1TTHBCteznlcUASrx3TeTHmLOqlRFBrQATFR4jiAIJeSzcWKH00cbghmajS');
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => $payment->getAmount()*100,
                    'currency' => 'eur'
                ]);
                $output = [
                    'clientSecret' => $paymentIntent->client_secret,
                ];


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute('campaign_show',['id'=>$campaign->getId()]);
        }

        return $this->render('payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form->createView(),
            'amount' => $amount,
            'campaign' => $campaign
        ]);
    }
}
