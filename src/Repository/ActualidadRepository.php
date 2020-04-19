<?php

namespace App\Repository;

use App\Entity\Noticia;
use App\Repository\Doctrine_Query;
use App\Entity\Categoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class ActualidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Noticia::class);
    }

    //Vamos a sacar las últimas 4 noticias

    public function getUltimasNoticias()
    {

        $em = $this->getEntityManager();
        $consulta = $em->createQuery(

            'select n from App\Entity\Noticia n
            ORDER BY n.codnoticia DESC'
        )->setMaxResults(4);



        return $consulta->getResult();
    }
}
