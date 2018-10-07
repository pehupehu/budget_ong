<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Filters\UserFiltersType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Tools\Filters;
use App\Tools\FlashBagTranslator;
use App\Tools\Pager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Intl\Locale;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class UserController
 * @package App\Controller\Admin
 */
class UserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function index(Request $request): Response
    {
        $filters = $routeParams = [];

        $formFilters = $this->createForm(UserFiltersType::class);
        $formFilters->handleRequest($request);
        if ($formFilters->isSubmitted() && $formFilters->isValid()) {
            $filters = $formFilters->getData() ?? [];
            $routeParams[$formFilters->getName()] = $filters;
        }

        /** @var UserRepository $userRepo */
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $pager = new Pager($userRepo->loadUsers($filters));
        $pager->setPage($request->get('page', 1));
        $pager->setRouteName('admin_user');
        $pager->setRouteParams($routeParams);
        
        return $this->render('admin/user/list.html.twig', [
            'loggedUser' => $this->getUser(),
            'pager' => $pager,
            'errorFilters' => $formFilters->isSubmitted() && !$formFilters->isValid(),
            'formFilters' => $formFilters->createView(),
            'nbActiveFilters' => Filters::getNbActiveFilters($filters),
        ]);
    }

    /**
     * @Route("/admin/user/{action}", requirements={"action" = "enable|disable|remove"}, name="admin_user_action")
     *
     * @param Request $request
     * @param FlashBagTranslator $flashBagTranslator
     *
     * @return Response
     */
    public function action(Request $request, FlashBagTranslator $flashBagTranslator): Response
    {
        $params = ['ids' => json_decode($request->get('ids'), true)];
        if (empty($params['ids'])) {
            return $this->redirectToRoute('admin_user');
        }
        $action = $request->get('action');

        /** @var UserRepository $userRepo */
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $query = $userRepo->loadUsers($params);

        $entityManager = $this->getDoctrine()->getManager();

        $nb_enable = $nb_disable = $nb_remove = 0;

        /** @var User $user */
        foreach ($query->execute() as $user) {
            if ($user->canBeEditBy($this->getUser())) {
                if ($action === 'enable' && $user->enable()) {
                    $nb_enable++;
                    $entityManager->persist($user);
                } elseif ($action === 'disable' && $user->disable()) {
                    $nb_disable++;
                    $entityManager->persist($user);
                } elseif ($action === 'remove' && $user->remove()) {
                    $nb_remove++;
                    $entityManager->remove($user);
                }
            }
        }

        $entityManager->flush();

        if ($nb_enable) {
            $flashBagTranslator->addGroupMessage('info', 'admin_user.message.count_enable', true, $nb_enable);
        } elseif ($nb_disable) {
            $flashBagTranslator->addGroupMessage('info', 'admin_user.message.count_disable', true, $nb_disable);
        } elseif ($nb_remove) {
            $flashBagTranslator->addGroupMessage('info', 'admin_user.message.count_remove', true, $nb_remove);
        }
        if ($nb_enable + $nb_disable + $nb_remove) {
            $flashBagTranslator->execute();
        }

        return $this->redirectToRoute('admin_user');
    }

    /**
     * @Route("/admin/user/new", name="admin_user_new")
     * 
     * @param Request $request
     * @param FlashBagTranslator $flashBagTranslator
     * @param UserPasswordEncoderInterface $encoder
     * 
     * @return Response
     */
    public function new(Request $request, FlashBagTranslator $flashBagTranslator, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $user->setId(0);
        $user->setUsername('');
        $user->setPassword('');
        $user->setFirstname('');
        $user->setLastname('');
        $user->setCreatedAt(new \DateTime());
        $user->setRole(User::ROLE_USER);
        $user->setLocale(Locale::getDefaultFallback());

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'admin_user.message.success.new');

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     * 
     * @param Request $request
     * @param FlashBagTranslator $flashBagTranslator
     * @param User $user
     * 
     * @return Response
     */
    public function edit(Request $request, FlashBagTranslator $flashBagTranslator, User $user): Response
    {
        if (!$user->canBeEditBy($this->getUser())) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'admin_user.message.success.edit');

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/{id}/remove", name="admin_user_remove")
     *
     * @param FlashBagTranslator $flashBagTranslator
     * @param User $user
     * 
     * @return Response
     */
    public function remove(FlashBagTranslator $flashBagTranslator, User $user): Response
    {
        if (!$user->canBeEditBy($this->getUser())) {
            throw new AccessDeniedException();
        }

        if ($user->remove()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'admin_user.message.success.remove');
        } else {
            $flashBagTranslator->add('warning', 'admin_user.message.warning.remove');
        }
        
        return $this->redirectToRoute('admin_user');
    }

    /**
     * @Route("/admin/user/{id}/disable", name="admin_user_disable")
     *
     * @param FlashBagTranslator $flashBagTranslator
     * @param User $user
     * 
     * @return Response
     */
    public function disable(FlashBagTranslator $flashBagTranslator, User $user): Response
    {
        if (!$user->canBeEditBy($this->getUser())) {
            throw new AccessDeniedException();
        }

        if ($user->disable()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'admin_user.message.success.disable');
        } else {
            $flashBagTranslator->add('warning', 'admin_user.message.warning.disable');
        }

        return $this->redirectToRoute('admin_user');
    }

    /**
     * @Route("/admin/user/{id}/enable", name="admin_user_enable")
     *
     * @param FlashBagTranslator $flashBagTranslator
     * @param User $user
     * 
     * @return Response
     */
    public function enable(FlashBagTranslator $flashBagTranslator, User $user): Response
    {
        if (!$user->canBeEditBy($this->getUser())) {
            throw new AccessDeniedException();
        }

        if ($user->enable()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'admin_user.message.success.enable');
        } else {
            $flashBagTranslator->add('warning', 'admin_user.message.warning.enable');
        }

        return $this->redirectToRoute('admin_user');
    }
}