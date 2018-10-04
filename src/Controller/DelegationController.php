<?php

namespace App\Controller;

use App\Collection\ImportCollection;
use App\Collection\ImportObject;
use App\Entity\Delegation;
use App\Form\DelegationType;
use App\Form\GenericImportResolveType;
use App\Form\GenericImportStep1Type;
use App\Form\GenericImportStep2Type;
use App\Repository\DelegationRepository;
use App\Tools\Pager;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class DelegationController
 * @package App\Controller
 */
class DelegationController extends Controller
{
    /**
     * @Route("/delegation", name="delegation")
     * 
     * @param Request $request
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        /** @var DelegationRepository $delegationRepo */
        $delegationRepo = $this->getDoctrine()->getRepository(Delegation::class);
        $pager = new Pager($delegationRepo->loadDelegations());
        $pager->setPage($request->get('page', 1));
        $pager->setRouteName('delegation');
        $pager->setRouteParams([]);

        return $this->render('delegation/list.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/delegation/import/1", name="delegation_import_step_1")
     * 
     * @param Request $request
     * @param SessionInterface $session
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function import_step_1(Request $request, SessionInterface $session)
    {
        $file_headers_required = ['code', 'name'];
        
        $form = $this->createForm(GenericImportStep1Type::class, null, ['file_headers_required' => $file_headers_required]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form['file']->getData();

            $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
            $data = $serializer->decode(file_get_contents($file->getPathname()), 'csv');
            
            // Mise en session de l'import
            $session->set('delegation_import', $data);
            
            // Redirection vers l'assistant d'import
            return $this->redirectToRoute('delegation_import_step_2');
        }

        return $this->render('delegation/import_step_1.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delegation/import/2", name="delegation_import_step_2")

     * @param Request $request
     * @param SessionInterface $session
     * @param LoggerInterface $logger
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function import_step_2(Request $request, SessionInterface $session, LoggerInterface $logger)
    {
        // Get session data
        // Parse data
        $collection = new ImportCollection();

        /** @var DelegationRepository $delegationRepo */
        $delegationRepo = $this->getDoctrine()->getRepository(Delegation::class);
        $delegations_by_code = $delegationRepo->getDelegationsByCode();

        $data = $session->get('delegation_import');
        
        foreach ($data as $row) {
            $import = new Delegation();
            $import->setCode($row['code']);
            $import->setName($row['name']);
            
            $match = $delegations_by_code[$import->getCode()] ?? null;
            
            if ($match === null || !$import->equal($match)) {
                $resolve = $match ? GenericImportResolveType::RESOLVE_OVERWRITE : GenericImportResolveType::RESOLVE_ADD;

                $collection->add($import, $match, $resolve);
            }
        }

        $form = $this->createForm(GenericImportStep2Type::class, $collection);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            /** @var Connection $conn */
            $conn = $this->getDoctrine()->getConnection();

            try {
                $conn->beginTransaction();

                /** @var ImportCollection $importCollection */
                $importCollection = $form->getData();
                /** @var ImportObject $object */
                foreach ($importCollection->getObjects() as $object) {
                    /** @var Delegation $import */
                    $import = $object->getImport();
                    /** @var Delegation $match */
                    $match = $object->getMatch();
                    $resolve = $object->getResolve();
                    if ($resolve === GenericImportResolveType::RESOLVE_ADD) {
                        $logger->debug('Add delegation : ' . $import);
                        $entityManager->persist($import);
                        $entityManager->flush();
                    } elseif ($resolve === GenericImportResolveType::RESOLVE_OVERWRITE) {
                        $logger->debug('Overwrite delegation : ' . $match . ' with ' . $import);
                        $match->copy($import);
                        $entityManager->persist($match);
                        $entityManager->flush();
                    } elseif ($resolve === GenericImportResolveType::RESOLVE_SKIP) {
                        $logger->debug('Skip delegation : ' . $import);
                    }
                }

                $conn->commit();
            } catch (\Exception $e) {
                $conn->rollBack();
                $logger->error($e->getMessage());
            }
            
            // TODO comptabiliser les ajouts/modifications/skip

            return $this->redirectToRoute('delegation');
        }

        return $this->render('delegation/import_step_2.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/delegation/new", name="delegation_new")
     * 
     * @param Request $request
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
     * 
     * @param Request $request
     * @param Delegation $delegation
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
     * 
     * @param Delegation $delegation
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(Delegation $delegation)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($delegation);
        $entityManager->flush();

        return $this->redirectToRoute('delegation');
    }

}