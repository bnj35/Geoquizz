<?php

namespace geoquizz\core\services\auth;

use geoquizz\core\domain\entities\user\User;
use geoquizz\core\dto\auth\AuthDTO;
use geoquizz\core\dto\auth\CredentialsDTO;
use geoquizz\core\repositoryInterfaces\AuthRepositoryInterface;
use geoquizz\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use geoquizz\core\repositoryInterfaces\RepositoryInternalServerError;

class AuthentificationService implements AuthentificationServiceInterface
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(CredentialsDTO $credentials, int $role): string
    {
        try{
            $user = $this->authRepository->getUserByEmail($credentials->email);
            if ($user !== null) {
                throw new AuthentificationServiceBadDataException("User already exists");
            }
        }catch (RepositoryEntityNotFoundException $e) {
            // User not found, we can register
        }
        try{
            $pass = password_hash($credentials->password, PASSWORD_DEFAULT);
            $user = new User($credentials->email, $pass, $role);
            return $this->authRepository->save($user);
        }catch (RepositoryInternalServerError $e){
            throw new AuthentificationServiceInternalServerErrorException("Error while registering user");
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

}
