<?php

namespace App\Repository;

use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ProductoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Producto::class);
    }

    public function getProductosUso($uso)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery(
            'select p from App\Entity\Producto p
            inner join producto_has_usomedico pu ON pu.producto_idProducto = p.idProducto
            where pu.usomedico_idUsoMedico = :usomedico_idUsoMedico'
        )->setParameter(':usomedico_idUsoMedico', $uso);
        return $consulta->getResult();
    }
}
