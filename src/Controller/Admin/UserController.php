<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Filters\UserFiltersType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Tools\Filters;
use App\Tools\Pager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
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
            'pager' => $pager,
            'errorFilters' => $formFilters->isSubmitted() && !$formFilters->isValid(),
            'formFilters' => $formFilters->createView(),
            'nbActiveFilters' => Filters::getNbActiveFilters($filters),
        ]);
    }

    /**
     * @Route("/admin/user/new", name="admin_user_new")
     * 
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $user->setId(0);
        $user->setUsername('');
        $user->setPassword('');
        $user->setFirstname('');
        $user->setLastname('');
        $user->setRole('');

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
     * @param User $user
     * @param UserPasswordEncoderInterface $encoder
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/{id}/remove", name="admin_user_remove")
     * 
     * @param User $user
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_user');
    }

    /**
     * @Route("/admin/user/{id}/disable", name="admin_user_disable")
     * 
     * @param User $user
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disable(User $user)
    {
        $user->setIsActive(false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_user');
    }

    /**
     * @Route("/admin/user/{id}/enable", name="admin_user_enable")
     * 
     * @param User $user
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enable(User $user)
    {
        $user->setIsActive(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_user');
    }
}