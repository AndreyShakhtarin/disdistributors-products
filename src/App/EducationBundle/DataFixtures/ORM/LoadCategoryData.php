<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 27.09.16
 * Time: 20:57
 */

namespace App\EducationBundle\DataFixture\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\EducationBundle\Entity\Category;
use App\EducationBundle\Slug\Slugger;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $em)
    {
        $document = new Category();
        $document->setCategory('Document goods');
        $document->setSlug();
        $audio = new Category();
        $audio->setCategory('Audio goods');
        $audio->setSlug();
        $video = new Category();
        $video->setCategory('Material goods');
        $video->setSlug();
        $other = new Category();
        $other->setCategory('Other goods');
        $other->setSlug();
        $em->persist($document);
        $em->persist($audio);
        $em->persist($video);
        $em->persist($other);
        $em->flush();

        $this->addReference('category-document', $document);
        $this->addReference('category-audio', $audio);
        $this->addReference('category-material', $video);
        $this->addReference('category-other', $other);
    }

    public function getOrder()
    {
        return 1;
    }
}