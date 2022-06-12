<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Medico;
use App\Helper\FactoryMedico;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class MedicosController extends AbstractController
{
    
    /**
    * @var EntityManagerInterface
    */

    /**
    * @var FactoryMedico
    */

    private $factoryMedico;



    //EntityManager gerencia a nossas entidade 
    // isso é chamado de injeção de denpendencia
    public function __construct(EntityManagerInterface $entityManager , FactoryMedico $factoryMedico)
    {
        $this->entityManager = $entityManager;
        $this->factoryMedico = $factoryMedico;
    }

    
    /**
    * @Route("/medico", methods={"POST"})
    */
    //rota para criar novos medicos e mandar para o banco de dados
    public function novo(Request $request): Response
    {
        $corpoRequicisao = $request->getContent();
        $medico = $this->factoryMedico->criarNovoMedico($corpoRequicisao);
        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }


    /**
    * @Route("/medicos", methods={"GET"})
    */
    //rota para pegar todos os medicos
    public function buscartodosOsmedicosCadastrados():Response
    {
        $repositorioMedicos = $this->entityManager->getRepository(Medico::class);
            
        $listaDeMedicos = $repositorioMedicos->findAll();

        return new JsonResponse($listaDeMedicos);
    }


    /**
    * @Route("/medico/{id}", methods={"GET"})
    */

    public function buscarMedicoPorId($id):Response
    {
        
        $medico = $this->buscaMedico($id);

        $codigoRetorno = is_null($medico) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($medico , $codigoRetorno);
    }


    /**
    * @Route("/medico/{id}", methods={"PUT"})
    */

    public function atualizarMedicoPorId(Request $request,$id):Response
    {

        // pega o id enviando pela url
        

        // pega o corpo da requisição
        $corpoRequicisao = $request->getContent();
        // mapear por tipo de dao em json
        $dadosEmJson     = json_decode($corpoRequicisao);

        //mapea os parametos recebido para uma entidade para o tipo medico(garantido que os dados vão ser enviado para o banco)
        $medicoEnviado = $this->factoryMedico->criarNovoMedico($corpoRequicisao);

        // recuperar o medico existente
        $medicoExistente = $this->buscaMedico($id);

        if(is_null($medicoExistente) ) {
            return new JsonResponse('Medico não encontrado',Response::HTTP_NOT_FOUND);
        }
       
        // entidade do medico receber o novo medico , assim e substituido as informaçoẽs 
        $medicoExistente->setCrm($medicoEnviado->getCrm());
        $medicoExistente->setNome($medicoEnviado->getNome());
    
        // manda pro banco
        $this->entityManager->flush();
        
        return new JsonResponse($medicoExistente);
    }



    /**
    * @Route("/medico/{id}", methods={"DELETE"})
    */

    public function deletarMedicos(int $id ):Response
    {
        $dados = [];
        
        $medicoSelecionado = $this->buscaMedico($id);

        if(isset($medicoSelecionado->id)){ 
           
            $this->entityManager->remove($medicoSelecionado);
            $this->entityManager->flush();
            
            $dados = 
            [   
                "mensagem" => [ "status" =>"Removido" ,"crm" => $medicoSelecionado->crm, "medico" =>$medicoSelecionado->nome]
            ];
    
        }else{
            $dados = ["mensagem" => "medico não existe"];
        }
        
        return new JsonResponse($dados);
    }




    public function buscaMedico($id)
    {
        $repositorioMedicos = $this->entityManager->getRepository(Medico::class);       
        $medico = $repositorioMedicos->find($id);

        return $medico;
    }


}   