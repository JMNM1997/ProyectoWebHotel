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

    public function getHabitacionesDisponibles(\Datetime $fechaentrada, \Datetime $fechasalida)
    {

        $em = $this->getEntityManager();

        $consulta = $em->createQuery(

            'select h from App\Entity\Habitacion h
            where NOT EXISTS (
                SELECT r FROM App\Entity\Reserva r where h.codhabitacion = r.habitacionCodhabitacion and(
                     :fechaentrada between r.fechaEntrada and r.fechaSalida
                    or :fechasalida between r.fechaEntrada and r.fechaSalida))'
        )->setParameter('fechaentrada', $fechaentrada)->setParameter('fechasalida', $fechasalida);


        return $consulta->getResult();
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
