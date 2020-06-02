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

    //consulta que devuelve habitaciones según sus plantas

    public function getHabitacionesFiltroplanta($planta)
    {

        $em = $this->getEntityManager();
        $consulta = $em->createQuery(

            'select h from App\Entity\Habitacion h
            where(h.planta= :planta)'
        )->setParameter('planta', $planta);
        return $consulta->getResult();
    }

    //consulta que devuelve habitaciones según sus precios

    public function getHabitacionesFiltroprecio($precio)
    {

        $em = $this->getEntityManager();
        $consulta = $em->createQuery(

            'select h from App\Entity\Habitacion h
            where(h.precio >= :precio)'
        )->setParameter('precio', $precio);
        return $consulta->getResult();
    }


    // cpnsulta que devuelve habitaciones según complementos y planta

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


    //consulta  modificada / devuelve habitaciones filtros completos
    public function getHabitacionesFiltros(string $planta, array $complemento = null, string $precio, bool $and = true)
    {
        //vamos a contar cuantos complementos ha traido el usuario y restarle 1 para luego hacer el having
        $cuenta = count($complemento) - 1;

        //tenemos que unir 2 tablas, la de habitación, y la n:m que recoge información de a que complementos pertenecen las habitaciones
        $em = $this->getEntityManager();
        $sql = "SELECT h FROM App\Entity\Habitacion h INNER JOIN h.complementoIdcomplemento co WHERE h.planta = :planta AND h.precio <= :precio";
        if ($complemento) {
            $sql .= " and (";
            //necesitamos recorrer el array de complementos 
            foreach ($complemento as $idcomplemento) {
                $sql .= "co.idcomplemento='" . $idcomplemento . "' or ";
            }
            $sql .= " false=false )";
            $sql .= " group by h.codhabitacion having COUNT(h.codhabitacion) > :cuenta ";
        }
        $qb = $em->createQuery($sql);
        $qb->setParameters(['planta' => $planta, 'precio' => $precio, 'cuenta' => $cuenta]);
        $datos = $qb->execute();
        return $datos;
    }

    //consulta que devuelve habitaciones teniendo en cuenta planta y precio

    public function getHabitacionesFiltrosSincomplemento(string $planta, string $precio, bool $and = true)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('h')
            ->select('h')->from('App\Entity\Habitacion', 'h');

        if ($planta) {
            //$qb->innerJoin('p.colorflorIdcolorflor', 'c');

            if ($and) {
                $qb->where('h.precio <= :precio')
                    ->andWhere('h.planta = :planta');
            } else {
                $qb->Where('h.precio <= :precio')
                    ->orWhere('h.planta = :planta');
            }
        } else {
            $qb->where('h.precio <= :precio');
        }

        if (!$planta) {
            $qb->setParameter('precio', $precio);
        } else {
            $qb->setParameters(['precio' => $precio, 'planta' => $planta]);
        }

        $consulta = $qb->getQuery();

        return $consulta->execute();
    }
}
