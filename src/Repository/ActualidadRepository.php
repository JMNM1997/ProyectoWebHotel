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

    //Devuelve las últimas 4 noticias

    public function getUltimasNoticias()
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery(

            'select n from App\Entity\Noticia n
            ORDER BY n.codnoticia DESC'
        )->setMaxResults(4);

        return $consulta->getResult();
    }

    // Pasamos una palabra y buscamos gracias al parámetro LIKE si esa palabra está contenida en un titular de noticia
    public function buscador($palabra)
    {

        $em = $this->getEntityManager();
        $consulta = $em->createQuery(

            'select n from App\Entity\Noticia n
            Where n.titular LIKE :palabra'
        )->setParameter('palabra', '%' . $palabra . '%');

        return $consulta->getResult();
    }

    // Filtro por categoria (noticias)
    public function filtrocategoria($idCategoria)
    {

        $em = $this->getEntityManager();
        $consulta = $em->createQuery(

            'select n from App\Entity\Noticia n
                Where n.categoriaIdcategoria = :idCategoria'
        )->setParameter('idCategoria', $idCategoria);

        return $consulta->getResult();
    }
}
