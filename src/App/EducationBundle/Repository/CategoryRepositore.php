<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 15.10.16
 * Time: 13:57
 */

namespace App\EducationBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepositore extends EntityRepository
{
    public function findOneByCategory($category)
    {
        $qb = $this->createQueryBuilder("SELECT c FROM 'AppEducationBundle:Category' c")
            ->where('category =:category')
            ->setParameter('category', $category);
        $query = $qb->getQuery();
        return $query->getResult();
    }
}