<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class ScoreService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function hasScore($team): bool {
        if(count($team->getScores()) === 0) {
            return false;
        }

        return true;
    }
}