<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 27.09.16
 * Time: 19:08
 */

namespace App\EducationBundle\Repository;

use Doctrine\ORM\EntityRepository;
class ImageRepository extends EntityRepository
{
    public function findByIdProduct($id)
    {
        $q = $this->createQueryBuilder("SELECT i FROM 'AppEductaionBundle:Image' i")
            ->where('id_category =:id_category')
            ->setParameter('id_category', $id);
        $query = $q->getQuery();
        return $query->getResult();
    }
}