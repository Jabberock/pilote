<?php

namespace Pilote\MessageBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends EntityRepository
{
	public function count()
	{
		return $this->createQueryBuilder('m')
					->select('count(m)')
					->getQuery()
					->getSingleScalarResult();
	}
}