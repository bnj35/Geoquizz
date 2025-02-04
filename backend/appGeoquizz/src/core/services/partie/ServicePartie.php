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
use geoquizz\core\services\partie\ServicePartieInterface;

class ServicePartie implements ServicePartieInterface
{
    private PartieRepositoryInterface $partieRepository;

    public function __construct(PartieRepositoryInterface $partieRepository)
    {
        $this->partieRepository = $partieRepository;
    }

    public function createPartie(InputPartieDTO $dto): PartieDTO
    {
        $partie = new Partie($dto->nom, $dto->token, $dto->nb_photos, $dto->score, $dto->theme);
        $id = $this->partieRepository->save($partie);
        $partie->setID($id);
        return $partie->toDTO();
    }

    public function getAllParties(): array
    {
        try {
            $parties = $this->partieRepository->getAllParties();
            $partiesDTO = [];
            foreach ($parties as $partie) {
                $partiesDTO[] = $partie->toDTO();
            }
            return $partiesDTO;
        } catch (RepositoryInternalServerError $e) {
            throw new ServicePartieInternalServerError($e->getMessage());
        }
    }

    public function getPartieById(string $id): PartieDTO
    {
        try {
            $partie = $this->partieRepository->getPartieById($id);
            return new PartieDTO($partie);
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServicePartieInvalidDataException('invalid Partie ID');
        } catch(RepositoryInternalServerError $e) {
            throw new ServicePartieInternalServerError($e->getMessage());
        }
    }

    public function getPartiesByUser(string $user): array
    {
        $parties = $this->partieRepository->getPartiesByEmail($user);
        return array_map(function($partie) {
            return $partie->toDTO();
        }, $parties);
    }
}