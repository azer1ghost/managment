<?php

namespace App\Interfaces;

interface CreditorRepositoryInterface
{
    public function allFilteredCreditors(array $filters = []);
}