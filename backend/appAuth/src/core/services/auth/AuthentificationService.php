<?php

namespace geoquizz\core\services\auth;

use geoquizz\core\domain\entities\user\User;
use geoquizz\core\dto\auth\AuthDTO;
use geoquizz\core\dto\auth\CredentialsDTO;
use geoquizz\core\dto\stats\InputStatsDTO;
use geoquizz\core\repositoryInterfaces\AuthRepositoryInterface;
use geoquizz\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use geoquizz\core\repositoryInterfaces\RepositoryInternalServerError;
use geoquizz\core\services\stats\StatsServiceInterface;

class AuthentificationService implements AuthentificationServiceInterface
{
    private AuthRepositoryInterface $authRepository;
    private StatsServiceInterface $statsService;

    public function __construct(AuthRepositoryInterface $authRepository, StatsServiceInterface $statsService)
    {
        $this->authRepository = $authRepository;
        $this->statsService = $statsService;
    }

    public function register(CredentialsDTO $credentials, int $role): string
    {
        try{
            $user = $this->authRepository->getUserByEmail($credentials->email);
            if ($user !== null) {
                throw new AuthentificationServiceBadDataException("User already exists");
            }
        }catch (RepositoryEntityNotFoundException $e) {
            // User not found, we can continue
        }
        try{
            $pass = password_hash($credentials->password, PASSWORD_DEFAULT);
            $user = new User($credentials->email, $pass, $role);
            $user_id = $this->authRepository->save($user);
            $user->setID($user_id);
            $input = new InputStatsDTO($user->getID(), 0, 0, 0, 0, 0);
            $this->statsService->createStats($input);
            return $user_id;
        }catch (RepositoryInternalServerError $e){
            throw new AuthentificationServiceInternalServerErrorException($e->getMessage());
        }catch (RepositoryEntityNotFoundException $e){
            throw new AuthentificationServiceNotFoundException($e->getMessage());
        }
    }

    public function byCredentials(CredentialsDTO $credentials): AuthDTO
    {
        try{
            $user = $this->authRepository->getUserByEmail($credentials->email);
            if ($user === null) {
                throw new AuthentificationServiceBadDataException("User not found");
            }
            if (!password_verify($credentials->password, $user->getPassword())) {
                throw new AuthentificationServiceBadDataException("Invalid password");
            }
            return new AuthDTO($user->getID(), $user->getEmail(), $user->getRole());
        }catch (RepositoryEntityNotFoundException $e){
            throw new AuthentificationServiceBadDataException("User not found");
        }catch (RepositoryInternalServerError $e){
            throw new AuthentificationServiceInternalServerErrorException("Error while fetching user");
        }
    }

    public function getUsers(): array
    {
        try{
            $users = $this->authRepository->getUsers();
            $usersDTO = [];
            foreach ($users as $user) {
                $usersDTO[] = new AuthDTO($user->getID(), $user->getEmail(), $user->getRole());
            }
            return $usersDTO;
        }catch (RepositoryInternalServerError $e){
            throw new AuthentificationServiceInternalServerErrorException("Error while fetching users");
        }
    }

    public function getUserById(string $id): AuthDTO
    {
        try{
            $user = $this->authRepository->getUserById($id);
            if ($user === null) {
                throw new AuthentificationServiceBadDataException("User not found");
            }
            return new AuthDTO($user->getID(), $user->getEmail(), $user->getRole());
        }catch (RepositoryEntityNotFoundException $e){
            throw new AuthentificationServiceBadDataException("User not found");
        }catch (RepositoryInternalServerError $e){
            throw new AuthentificationServiceInternalServerErrorException("Error while fetching user");
        }
    }

    public function getUserByEmail(string $email): AuthDTO {
        try{
            $user = $this->authRepository->getUserByEmail($email);
            if ($user === null) {
                throw new AuthentificationServiceBadDataException("User not found");
            }
            return new AuthDTO($user->getID(), $user->getEmail(), $user->getRole());
        }catch (RepositoryEntityNotFoundException $e){
            throw new AuthentificationServiceBadDataException("User not found");
        }catch (RepositoryInternalServerError $e){
            throw new AuthentificationServiceInternalServerErrorException("Error while fetching user");
        }
    }

}
