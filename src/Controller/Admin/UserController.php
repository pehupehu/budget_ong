<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Tools\Pager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller\Admin
 */
class UserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function index(RouterInterface $router, RequestStack $requestStack)
    {
        /** @var UserRepository $usersRepo */
        $usersRepo = $this->getDoctrine()->getRepository(User::class);
        $pager = new Pager($usersRepo->loadUsers());
        $pager->setPage($requestStack->getCurrentRequest()->get('page', 1));
        $pager->setRouteName('admin_user');
        $pager->setRouteParams([]);

        return $this->render('admin/user/list.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/admin/user/new", name="admin_user_new")
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
     */
    public function remove(Request $request, User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_user');
    }

    /**
     * @Route("/admin/user/{id}/disable", name="admin_user_disable")
     */
    public function disable(Request $request, User $user)
    {
        $user->setIsActive(false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_user');
    }

    /**
     * @Route("/admin/user/{id}/enable", name="admin_user_enable")
     */
    public function enable(Request $request, User $user)
    {
        $user->setIsActive(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_user');
    }
}