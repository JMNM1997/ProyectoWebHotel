<?php

namespace App\Repository;

use App\Entity\Reserva;
use App\Repository\Doctrine_Query;
use App\Entity\Habitacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ReservaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reserva::class);
    }

    public function getHabitacionesSinReserva()
    {

        $em = $this->getEntityManager();
        $consulta = $em->createQuery(

            'select h from App\Entity\Habitacion h
            where(
                NOT EXISTS (SELECT r FROM App\Entity\Reserva r WHERE r.habitacionCodhabitacion = h.codhabitacion))'
        );


        return $consulta->getResult();
    }

    public function getHabitacionesDisponibles($fechaE, $fechaS)
    {
        $em = $this->getEntityManager();
        $dateTime = new \DateTime();
        $qb = $em->createQueryBuilder('p')
            ->select('h')->from('App\Entity\Habitacion', 'h')
            ->Join('App\Entity\Reserva', 'r', 'WITH', 'h.codhabitacion = r.habitacionCodhabitacion')
            ->where(('r.fechaSalida < :fechaE'))
            ->andWhere('r.fechaEntrada > :fechaS')
            //->setParameter('fecha', $fecha);
            ->setParameters(
                [
                    'fechaE' => $dateTime->format('Y-m-d'),
                    'fechaS' => $dateTime->format('Y-m-d')

                ]
            );


        $consulta = $qb->getQuery();

        return $consulta->execute();
    }
    public function getHabitacionesDisponibles2($fechaE, $fechaS)
    {
        $em = $this->getEntityManager();
        $dateTime = new \DateTime();
        $qb = $em->createQueryBuilder('p');
        $qb->select('h')->from('App\Entity\Habitacion', 'h')
            ->Join('App\Entity\Reserva', 'r', 'WITH', 'h.codhabitacion = r.habitacionCodhabitacion')
            ->where($qb->expr()->not(
                (':fechaE BETWEEN r.fechaEntrada AND r.fechaSalida')
            ))
            ->andWhere($qb->expr()->not(
                (':fechaS BETWEEN  r.fechaSalida AND r.fechaEntrada')
            ))
            //->setParameter('fecha', $fecha);
            ->setParameters(
                [
                    'fechaE' => $dateTime->format('Y-m-d'),
                    'fechaS' => $dateTime->format('Y-m-d')

                ]
            );

        $consulta = $qb->getQuery();

        return $consulta->execute();
    }
}


/*
SELECT codHabitacion 
FROM habitacion h
WHERE
    NOT EXISTS (
        SELECT
            codReserva
        FROM reserva r
        WHERE
            Habitacion_codHabitacion = codHabitacion
    )
;
*/

/*

        $em = $this->getEntityManager();
        $consulta = $em->createQuery(
            'select h from App\Entity\Habitacion h
            where 
            not exists (
                select r from App\Entity\Reserva r
                where r.habitacionCodhabitacion = :h.codhabitacion
            )'
        );
    public function getHabitacionesDisponibles($fecha)
    {

        $em = $this->getEntityManager();
        $consulta = $em->createQuery(

            'select h from App\Entity\Habitacion h
            join App\Entity\Reserva r on h.codHabitacion = r.Habitacion_codHabitacion where r.fecha_salida < :fecha'
        )->setParameter(':fecha', $fecha);


        return $consulta->getResult();
    }

*/
