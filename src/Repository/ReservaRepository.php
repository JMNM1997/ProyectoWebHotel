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

    //Devolvemos las habitaciones de base de datos que no tienen una reserva asignada

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

    //habitaciones que se encuentran entre una fecha y otra

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
