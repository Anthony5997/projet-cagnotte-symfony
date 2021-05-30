<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\User;
use App\Form\Campaign1Type;
use App\Repository\CampaignRepository;
use App\Repository\ParticipantRepository;
use App\Repository\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/campaign")
 */
class CampaignController extends AbstractController
{
    /**
     * @Route("/", name="campaign_index", methods={"GET"})
     */
    public function index(CampaignRepository $campaignRepository, ParticipantRepository $participantRepository, PaymentRepository $paymentRepository): Response
    {
        $campagnes =  $campaignRepository->findAll();
        $participants = $participantRepository->findBy(['campaignId' =>$campagnes]);

        $payments = $paymentRepository->findBy(['relation'=> $participants]);
        return $this->render('campaign/index.html.twig', [
            'campaigns' => $campagnes,
            'payments' => $payments, 

        ]);
    }
    /**
     * @Route("/search", name="campaign_search", methods={"GET"})
     */
    public function search(CampaignRepository $campaignRepository, ParticipantRepository $participantRepository, PaymentRepository $paymentRepository)
    {
        $allCampaign = [];
        $campaigns =  $campaignRepository->findAll();
        foreach($campaigns as $campaign){
          array_push($allCampaign, $campaign->toArray());
        }
        echo json_encode($allCampaign);
        die;
    }

    /**
     * @Route("/new", name="campaign_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $campaignName = $request->get('campaignName');
        $campaign = new Campaign();
        $form = $this->createForm(Campaign1Type::class, $campaign);
        $form->handleRequest($request);

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $campaign->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($campaign);
            $entityManager->flush();

            return $this->redirectToRoute('campaign_index');
        }
        return $this->render('campaign/new.html.twig', [
            'campaign' => $campaign,
            'formCreated' => $form->createView(),
            'campaignName' => $campaignName,
            'user' => $this->getUser(),
        ]);
    }

  /**
     * @Route("/{id}", name="campaign_show", methods={"GET"})
     */
    public function show(Campaign $campaign, CampaignRepository $campainRepository): Response
    {


        $payments = $campainRepository->findAllByCampaignId($campaign);
        $sumPayment = $campainRepository->sumPayment($campaign);
        return $this->render('campaign/show.html.twig', [
            'campaign' => $campaign,
            'payments'=>$payments,
            'user' => $this->getUser(),
            ]);
           

    }

    /**
     * @Route("/{id}/edit", name="campaign_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Campaign $campaign): Response
    {
        $form = $this->createForm(Campaign1Type::class, $campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('campaign_index');
        }

        return $this->render('campaign/edit.html.twig', [
            'campaign' => $campaign,
            'campaignName' => $campaign->getName(),
            'formEdit' => $form->createView(),
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/{id}", name="campaign_delete", methods={"POST"})
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
