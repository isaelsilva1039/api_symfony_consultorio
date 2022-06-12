<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadeController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    /**
     * @var EntityManagerInterface
     */
    private $repository;


    public function __construct(EntityManagerInterface $entityManager , EspecialidadeRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }


    /**
    * @Route("/especialidade", methods={"POST"})
    */
   
    public function nova(Request $request): Response
    {
        $corpoRequicisao = $request->getContent();
        $dadosEmJson = json_decode($corpoRequicisao);

        $especialidade = new Especialidade;
        $especialidade->setDescricao($dadosEmJson->descricao);
        
        $this->entityManager->persist($especialidade);
        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }


    /**
     * @Route("/especialidade", methods={"GET"})
     */
    public function bucarTodos(): Response
    {   
        $especialidade = $this->repository->findAll();
        return new JsonResponse($especialidade);
    }


       /**
     * @Route("/especialidade/{id}", methods={"GET"})
     */
    public function bucarUmaEspecialidade($id): Response
    {   
        $especialidade = $this->repository->find($id);
        return new JsonResponse($especialidade);
    }


       /**
     * @Route("/especialidade/{id}", methods={"PUT"})
     */
    public function atualizarEspecialidade($id, Request $request): Response
    {   
        $corpoRequicisao = $request->getContent();
        $dadosEmJson = json_decode($corpoRequicisao);

        $especialidade = $this->repository->find($id);
     
        $especialidade->setDescricao($dadosEmJson->descricao);

        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }


    /**
     * @Route("especialidade/{id}", methods={"DELETE"})
     */
    public function remover($id): Response
    {
        $especialidade = $this->repository->find($id);
        $this->entityManager->remove($especialidade);
        $this->entityManager->flush();
    
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
