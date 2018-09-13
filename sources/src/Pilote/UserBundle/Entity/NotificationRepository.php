<?php

/*
 * Copyright (C) 2015 Hamza Ayoub, Valentin Chareyre, Sofian Hamou-Mamar,
 * Alain Krok, Wenlong Li, Rémi Patrizio, Yamine Zaidou
 *
 * ________________________________
 *
 * This file is part of Pilote.
 *
 * Pilote is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Pilote is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Pilote.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Pilote\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * NotificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NotificationRepository extends EntityRepository
{
    /**
     * Trouver les 5 dernières notifications envoyées
     * à l'utilisateur passé en paramètre.
     * @param  User $user : L'utilisateur concerné
     * @return array() : Tableau des 5 dernières notifications
     */
	public function findLastFives($user)
    {
        $query = $this->createQueryBuilder('n')
        	->select('n')
        	->where('n.receiver = :user')
	        ->orderBy('n.date', 'DESC')
	        ->setParameter('user', $user)
	        ->setMaxResults(5);
        return $query->getQuery()->getResult();
	}

    /**
     * Trouver les 5 dernières notifications envoyées
     * à l'utilisateur passé en paramètre, dont la date
     * d'émission précède celle de la notification passée
     * en paramètre.
     * @param  Notification $notif : La notification dont on
     * veut trouver les prédécesseures.
     * @param  User         $user  : L'utilisateur concerné
     * @return array() : Tableau des 5 dernières notifications
     * avant celle passée en paramètre
     */
	public function findNextFives($notif, $user)
    {
        $query = $this->createQueryBuilder('n')
        	->select('n')
        	->where('n.receiver = :user')
        	->andWhere('n.date < :date')
	        ->orderBy('n.date', 'DESC')
	        ->setParameter('date', $notif->getDate())
	        ->setParameter('user', $user)
	        ->setMaxResults(5);
        return $query->getQuery()->getResult();
	}
}
