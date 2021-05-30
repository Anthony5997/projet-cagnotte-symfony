<?php

namespace App\Controller;
use App\Repository\CampaignRepository;
use App\Repository\ParticipantRepository;
use App\Repository\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CampaignRepository $campaignRepository, ParticipantRepository $participantRepository, PaymentRepository $paymentRepository): Response
    {

        $campaign = $campaignRepository->findBy(
            array(),        // $where
            array(),    // $orderBy
            5,          // $limit
            0            // $offset
        );
        $participant = $participantRepository->findBy(['campaignId' => $campaign]);
        $payment = $paymentRepository->findBy(['relation' => $participant]);
        return $this->render('home/index.html.twig', [
            'campaigns' => $campaign,
            'participants' => $participant,
            'payments' => $payment,
        ]);

    }
}
