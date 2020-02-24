<?php

namespace App\Repository;

use App\Entity\Planta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class PlantaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planta::class);
    }

    public function getPlantasColorFlor($color)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery(
            'select p from App\Entity\Planta p
            inner join p.colorflorIdcolorflor c
            where c.color = :color'
        )->setParameter(':color', $color);
        return $consulta->getResult();
    }
}
