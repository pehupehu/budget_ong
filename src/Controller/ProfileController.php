<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Tools\FlashBagTranslator;
use App\Twig\AppExtension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ProfileController
 * @package App\Controller
 */
class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="profile")
     *
     * @param Request $request
     * @param FlashBagTranslator $flashBagTranslator
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return Response
     */
    public function index(Request $request, FlashBagTranslator $flashBagTranslator, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'profile.message.success');

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/{_locale}/locale", name="profile_switch_locale", requirements={"_locale"="[a-z]{2}"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function switchLocale(Request $request): Response
    {
        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }
        
        $current_local = $request->getLocale();
        if (!in_array($current_local, array_keys(AppExtension::getSupportedLocales()))) {
            $request->setLocale($request->getDefaultLocale());
            $request->getSession()->set('_locale', $request->getDefaultLocale());
        }

        return $this->redirectToRoute('index');
    }
}