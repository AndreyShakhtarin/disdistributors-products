<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 20.09.16
 * Time: 20:35
 */

namespace App\EducationBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findById($id = null)
    {
        $qb = $this->createQueryBuilder(
            "SELECT n FROM 'AppEducationBundle:Product' n"
        )
            ->where('p.id = :id')
            ->setParameter('id', $id);
        //$query = $qb->getQuery();
        return $qb->getResult();
    }

    public function findByToken($token)
    {
        $qb = $this->createQueryBuilder(
            "SELECT n FROM 'AppEducationBundle:Product' n "
        )
            ->where('n.name = :name')
            ->setParameter('name', $token);
        return $qb->getResult();
    }

    public function findByName($name)
    {
        $qb = $this->createQueryBuilder(
            "SELECT n FROM 'AppEducationBundle:Product' n"
        )
            ->where('n.name = :name')
            ->setParameter('name', $name);
        $query = $qb->getQuery();
        return $query->getResult();

    }
}