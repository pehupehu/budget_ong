<?php

namespace App\Controller;

use App\Collection\DelegationCollection;
use App\Collection\ImportCollection;
use App\Collection\ImportObject;
use App\Entity\Delegation;
use App\Form\DelegationImportType;
use App\Form\DelegationType;
use App\Form\GenericImportResolveType;
use App\Form\GenericImportType;
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
     * @Route("/delegation/import/1", name="delegation_import_step_1")
     */
    public function import_step_1(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(GenericImportType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
            
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

        $form = $this->createForm(DelegationImportType::class, $collection);

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
                    } else if ($resolve === GenericImportResolveType::RESOLVE_OVERWRITE) {
                        $logger->debug('Overwrite delegation : ' . $match . ' with ' . $import);
                        $match->copy($import);
                        $entityManager->persist($match);
                        $entityManager->flush();
                    }
                }

                $conn->commit();
            } catch (\Exception $e) {
                $conn->rollBack();
                $logger->error($e->getMessage());
            }
        }

        return $this->render('delegation/import_step_2.html.twig', [
            'form' => $form->createView(),
        ]);

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