<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 13.11.16
 * Time: 17:07
 */

namespace App\UserBundle\Reposytory;

use Doctrine\ORM\EntityRepository;

class UsersRepository extends EntityRepository
{
    public function getUser($criteria, array $orderBy = null)
    {
        return parent::findOneBy($criteria, $orderBy);
    }
}