<?php

namespace geoquizz\core\services\partie;

//partie
use geoquizz\core\domain\entities\partie\Partie;

//dto
use geoquizz\core\dto\partie\InputPartieDTO;
use geoquizz\core\dto\partie\PartieDTO;

//interfaces
use geoquizz\core\repositoryInterfaces\RepositoryAuthInterface;
use geoquizz\core\repositoryInterfaces\PartieRepositoryInterface;
use geoquizz\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use geoquizz\core\repositoryInterfaces\RepositoryInternalServerError;
use geoquizz\core\services\partie\ServicePartieInterface;

class ServicePartie implements ServicePartieInterface
{
    private PartieRepositoryInterface $partieRepository;
    private RepositoryAuthInterface $authRepository;

    public function __construct(PartieRepositoryInterface $partieRepository, RepositoryAuthInterface $authRepository)
    {
        $this->partieRepository = $partieRepository;
        $this->authRepository = $authRepository;
    }

    public function createPartie(InputPartieDTO $dto): PartieDTO
    {
        try {
            $partie = new Partie($dto->nom, $dto->token, $dto->nb_photos, $dto->score, $dto->theme, $dto->temps, $dto->user_id);
            $id = $this->partieRepository->save($partie);
            $partie->setID($id);
            $this->partieRepository->setUserId($id, $dto->user_id);
            return $partie->toDTO();
        } catch (RepositoryInternalServerError $e) {
            throw new ServicePartieInternalServerError($e->getMessage());
        }
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
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServicePartieInvalidDataException('invalid Partie ID');
        } catch (RepositoryInternalServerError $e) {
            throw new ServicePartieInternalServerError($e->getMessage());
        }
    }

    public function getPartiesByUser(string $user_id): array
    {
        try {

            $partie = $this->partieRepository->getPartieByUserId($user_id);
            $partiesDTO = [];
            foreach ($partie as $p) {
                $partiesDTO[] = $p->toDTO();
            }
            return $partiesDTO;
        } catch (RepositoryInternalServerError $e) {
            throw new ServicePartieInternalServerError($e->getMessage());
        }
    }

    public function getPartieByUserId(string $userId): array
    {
        try {
            $parties = $this->partieRepository->getPartieByUserId($userId);
            $partiesDTO = [];
            foreach ($parties as $partie) {
                $partiesDTO[] = $partie->toDTO();
            }
            return $partiesDTO;
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServicePartieInvalidDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new ServicePartieInternalServerError($e->getMessage());
        }
    }

    public function updateScore(string $id, int $score): void
    {
        try {
            $this->partieRepository->updateScore($id, $score);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServicePartieInvalidDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new ServicePartieInternalServerError($e->getMessage());
        }
    }

    public function closePartie(string $id):void
    {
        try {
            $this->partieRepository->closePartie($id);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServicePartieInvalidDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new ServicePartieInternalServerError($e->getMessage());
        }
    }

    public function getEmailByPartieId(string $userId): string
    {
        try{
            $email = $this->authRepository->getEmailByUserId($userId);
            return $email;
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServicePartieInvalidDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new ServicePartieInternalServerError($e->getMessage());
        }
    }

    public function setPartieImage(array $images, string $partie_id):array
    {
        try{
            $partieImages = $this->partieRepository->setPartieImage($images, $partie_id);
            return $partieImages;
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServicePartieInvalidDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new ServicePartieInternalServerError($e->getMessage());
        }
    }

    public function setUserId(string $id, string $user_id):void
    {
        try{
            $this->partieRepository->setUserId($id, $user_id);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServicePartieInvalidDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new ServicePartieInternalServerError($e->getMessage());
        }
    }

    public function getUserIdByPartieId(string $id): string
    {
        try{
            $userId = $this->partieRepository->getUserIdByPartieId($id);
            return $userId;
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServicePartieInvalidDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new ServicePartieInternalServerError($e->getMessage());
        }
    }

    public function getToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
