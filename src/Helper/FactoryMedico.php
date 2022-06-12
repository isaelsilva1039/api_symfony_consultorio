<?php

namespace App\Helper;

use App\Entity\Especialidade;
use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;

class FactoryMedico{

    /**
     * @var EspecialidadeRepository
     */

     private $especialidadeRepository;
    public function __construct(EspecialidadeRepository $especialidadeRepository)
    {
        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function criarNovoMedico(string $corpoDaRequicicao): Medico
    {

        $dadosEmJson = json_decode($corpoDaRequicicao);

        $especialidadeId = $dadosEmJson->especialidadeId;
    
        $especialidade = $this->especialidadeRepository->find($especialidadeId);
    
        $medico = new Medico();
        $medico->setCrm($dadosEmJson->crm)->setNome($dadosEmJson->nome)->setEspecialidade($especialidade);
    
        return $medico;
    }
}