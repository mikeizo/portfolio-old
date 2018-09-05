<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $project = new Project();
            $project->setName('project ' . $i);
            $project->setDescription('lorem ipsum');
            $project->setShortDescription('et dolor');
            $project->setResources('test 1, test 2, test 3');
            $project->setSlug('project_' . $i);
            $project->setPosition($i);
            $manager->persist($project);
        }

        $manager->flush();
    }
}