<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class JeuService {
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    
}