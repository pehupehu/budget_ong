<?php

namespace App\Controller;

use App\Entity\Delegation;
use App\Form\DelegationType;
use App\Repository\DelegationRepository;
use App\Tools\Pager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DelegationController
 * @package App\Controller
 */
class DelegationController extends Controller
{
    /**
     * @Route("/delegation", name="delegation")
     */
    public function index(RequestStack $requestStack)
    {
        /** @var DelegationRepository $delegationRepo */
        $delegationRepo = $this->getDoctrine()->getRepository(Delegation::class);
        $pager = new Pager($delegationRepo->loadDelegations());
        $pager->setPage($requestStack->getCurrentRequest()->get('page', 1));
        $pager->setRouteName('delegation');
        $pager->setRouteParams([]);

        return $this->render('delegation/list.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/delegation/import", name="delegation_import")
     */
    public function import(Request $request)
    {

    }

    /**
     * @Route("/delegation/new", name="delegation_new")
     */
    public function new(Request $request)
    {
        $delegation = new Delegation();
        $delegation->setId(0);
        $delegation->setCode('');
        $delegation->setName('');

        $form = $this->createForm(DelegationType::class, $delegation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $delegation = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($delegation);
            $entityManager->flush();

            return $this->redirectToRoute('delegation');
        }

        return $this->render('delegation/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delegation/{id}/edit", name="delegation_edit")
     */
    public function edit(Request $request, Delegation $delegation)
    {
        $form = $this->createForm(DelegationType::class, $delegation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Delegation $delegation */
            $delegation = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($delegation);
            $entityManager->flush();

            return $this->redirectToRoute('delegation');
        }

        return $this->render('delegation/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delegation/{id}/remove", name="delegation_remove")
     */
    public function remove(Request $request, Delegation $delegation)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($delegation);
        $entityManager->flush();

        return $this->redirectToRoute('delegation');
    }

}