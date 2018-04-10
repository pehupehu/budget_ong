<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ImportController
 * @package App\Controller
 */
class ImportController extends Controller
{
    /**
     * @Route("/import", name="import_index")
     */
    public function index()
    {
        return $this->render('import.html.twig');
    }
}