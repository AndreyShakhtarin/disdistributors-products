<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 22.09.16
 * Time: 2:45
 */

namespace App\EducationBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /*public function findByUser($username =  null)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.username =:username')
            ->setParameter('username', $username);
        $query = $qb->getQuery();
        return $query->getResult();
    }*/
}