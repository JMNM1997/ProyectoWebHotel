<?php

namespace App\Repository;

use App\Entity\Reserva;
use App\Repository\Doctrine_Query;
use App\Entity\Habitacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class HabitacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reserva::class);
    }

    public function getHabitacionesFiltroplanta($planta)
    {

        $em = $this->getEntityManager();
        $consulta = $em->createQuery(

            'select h from App\Entity\Habitacion h
            where(h.planta= :planta)'
        )->setParameter('planta', $planta);
        return $consulta->getResult();
    }

    public function getHabitacionesFiltroprecio($precio)
    {

        $em = $this->getEntityManager();
        $consulta = $em->createQuery(

            'select h from App\Entity\Habitacion h
            where(h.precio >= :precio)'
        )->setParameter('precio', $precio);
        return $consulta->getResult();
    }


    public function getHabitacionesFiltrosSinprecio(array $complemento = null, string $planta, bool $and = true)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('h')
            ->select('h')->from('App\Entity\Habitacion', 'h')
            ->innerJoin('h.complementoIdcomplemento', 'co');

        if ($planta) {
            //$qb->innerJoin('p.colorflorIdcolorflor', 'c');

            if ($and) {
                $qb->where('co.nombre IN (:nombre)')
                    ->andWhere('h.planta = :planta');
            } else {
                $qb->where('co.nombre IN (:nombre)')
                    ->orWhere('h.planta = :planta');
            }
        } else {
            $qb->where('co.nombre IN (:nombre)');
        }

        if (!$planta) {
            $qb->setParameter('nombre', $complemento);
        } else {
            $qb->setParameters(['nombre' => $complemento, 'planta' => $planta]);
        }

        $consulta = $qb->getQuery();

        return $consulta->execute();
    }

    public function getHabitacionesFiltros(string $planta, array $complemento = null, string $precio, bool $and = true)
    {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder('h')
            ->select('h')->from('App\Entity\Habitacion', 'h');

        if ($complemento) {
            $qb->innerJoin('h.complementoIdcomplemento', 'co');
            if ($and) {
                $qb->where('h.planta = :planta')
                    ->andWhere('co.nombre IN (:nombre)');
            } else {
                $qb->where('h.planta = :planta')
                    ->orWhere('co.nombre IN (:nombre)');
            }
        } else {
            $qb->where('h.planta = :planta');
        }

        if (!$complemento) {
            $qb->setParameter('planta', $planta);
        }
        if (!$complemento && $planta && $precio) {
            $qb->setParameters(['planta' => $planta, 'precio' => $precio]);
        }
        if ($complemento && $planta && !$precio) {
            $qb->setParameters(['planta' => $planta, 'nombre' => $complemento]);
        }

        if ($precio && !$complemento) {

            if ($and) {
                $qb
                    ->where('h.planta = :planta')
                    ->andWhere('h.precio <= :precio');
            }
        }

        if ($precio && $complemento) {

            if ($and) {
                $qb
                    ->where('h.planta = :planta')
                    ->andWhere('h.precio <= :precio')
                    ->andWhere('co.nombre IN (:nombre)');
            } else {
                $qb->innerJoin('h.complementoIdcomplemento', 'co')
                    ->where('h.planta = :planta')
                    ->orWhere('co.nombre IN (:nombre)')
                    ->orWhere('h.precio <= :precio');
            }
        }

        if (!$precio && !$complemento && $planta) {
            $qb->where('h.planta = :planta');
        }

        if (!$precio && $complemento && $planta) {
            $qb->where('h.planta = :planta')
                ->andWhere('co.nombre IN (:nombre)');
        }

        if ($precio && !$complemento && $planta) {
            $qb->setParameters(['planta' => $planta, 'precio' => $precio]);
        }

        if ($planta && $complemento && $precio) {
            $qb->setParameters(['planta' => $planta, 'nombre' => $complemento, 'precio' => $precio]);
        }

        $consulta = $qb->getQuery();

        return $consulta->execute();
    }
}
