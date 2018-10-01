<?php

namespace App\Controller;

use App\Twig\AppExtension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 * @package App\Controller
 */
class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="profile_index")
     */
    public function index()
    {
        return $this->render('profile.html.twig');
    }

    /**
     * @Route("/profile/{_locale}/locale", name="profile_switch_locale", requirements={"_locale"="[a-z]{2}"})
     */
    public function switchLocale(Request $request)
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

        return $this->redirect($this->generateUrl('index'));
    }
}