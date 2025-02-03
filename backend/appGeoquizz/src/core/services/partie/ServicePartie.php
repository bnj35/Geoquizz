<?php

namespace geoquizz\core\services\partie;

//partie
use geoquizz\core\domain\entities\partie\Partie;
//dto
use geoquizz\core\dto\partie\InputPartieDTO;
use geoquizz\core\dto\partie\PartieDTO;
//interfaces
use geoquizz\core\repositoryInterfaces\PartieRepositoryInterface;
use geoquizz\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use geoquizz\core\repositoryInterfaces\RepositoryInternalServerError;

class ServicePartie implements ServicePartieInterface
{
    private PartieRepositoryInterface $partieRepository;

    public function __construct(PartieRepositoryInterface $partieRepository)
    {
        $this->partieRepos$partieRepository = $partieRepository;
    }

}