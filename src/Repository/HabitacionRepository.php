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

    public function getHabitacionesFiltros4(string $planta, array $complemento = null, string $precio, bool $and = true)
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


    //consulta antonio
    public function getHabitacionesFiltros2(string $planta, array $complemento = null, string $precio, bool $and = true)
    {
        $em = $this->getEntityManager();
        $sql = "SELECT h FROM App\Entity\Habitacion h INNER JOIN h.complementoIdcomplemento co WHERE h.planta = :planta AND h.precio <= :precio";
        if ($complemento) {

            foreach ($complemento as $idcomplemento) {
                $sql .= " and co.idcomplemento='" . $idcomplemento . "'";
            }
        }
        $qb = $em->createQuery($sql);
        $qb->setParameters(['planta' => $planta, 'precio' => $precio,]);
        $datos = $qb->execute();
        return $datos;
    }

    //consulta traducida
    public function filter_rooms(string $R_floor, array $complement = null, string $price, bool $and = true)
    {
        $em = $this->getEntityManager();
        $sql = "SELECT h FROM App\Entity\Room h INNER JOIN h.complementoIdcomplemento co WHERE h.R_floor = :R_floor AND h.price <= :price";
        if ($complement) {
            $sql .= " and (";
            foreach ($complement as $namecomplement) {
                $sql .= "co.name='" . $namecomplement . "' or ";
            }
            $sql .= " false=false )";
        }
        $qb = $em->createQuery($sql);
        $qb->setParameters(['R_floor' => $R_floor, 'price' => $price,]);
        $datos = $qb->execute();
        return $datos;
    }

    //consulta  modificada c2 repetida
    public function getHabitacionesFiltros3(string $planta, array $complemento = null, string $precio, bool $and = true)
    {
        $em = $this->getEntityManager(); //App\Entity\Habitacion h
        $sql = "SELECT c, h FROM App\Entity\Complemento c INNER JOIN c.habitacionCodhabitacion co, App\Entity\Habitacion h INNER JOIN h.complementoIdcomplemento co
        WHERE h.planta = :planta AND h.precio <= :precio";
        if ($complemento) {
            $sql .= " and (";
            foreach ($complemento as $nombrecomplemeto) {
                $sql .= "c.nombre='" . $nombrecomplemeto . "' or ";
            }
            $sql .= " false=false )";
        }
        $qb = $em->createQuery($sql);
        $qb->setParameters(['planta' => $planta, 'precio' => $precio, 'nombre' => $complemento,]);
        $datos = $qb->execute();
        return $datos;
    }

    //consulta  modificada builder
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
