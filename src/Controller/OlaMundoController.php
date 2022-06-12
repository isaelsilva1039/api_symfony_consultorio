<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OlaMundoController extends AbstractController
{
    /**
     * @Route("/ola")
     */
   
    public function index(): Response
    {
        return new JsonResponse( ['mensagem' => 'ola mundo']);
    }
}