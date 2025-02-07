<?php
namespace geoquizz\core\repositoryInterfaces;

interface RepositoryAuthInterface
{
    public function getEmailByUserId(string $id):string;
}