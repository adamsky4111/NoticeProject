<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * klasa została stworzona aby usprawnić dodawnia fixture do konkretnych testów
 */
abstract class AbstractFixtureWebTestCase extends WebTestCase
{
    /**
     * @var ORMExecutor
     */
    private $fixtureExecutor;

    /**
     * @var ContainerAwareLoader
     */
    private $fixtureLoader;

    protected function setUp()
    {
        self::bootKernel();
    }

    protected function addFixture(FixtureInterface $fixture)
    {
        $this->getFixtureLoader()->AddFixture($fixture);
    }

    private function getFixtureLoader()
    {
        if ($this->fixtureLoader === null) {
            $this->fixtureLoader = new ContainerAwareLoader(self::$kernel->getContainer());
        }

        return $this->fixtureLoader;
    }

    protected function executeFixtures()
    {
        $this->getFixtureExecutor()->execute($this->getFixtureLoader()->getFixtures());

    }

    private function getFixtureExecutor()
    {
        if ($this->fixtureExecutor === null) {
            $entityManager = self::$kernel->getContainer()
                ->get('doctrine')
                ->getManager();

            $this->fixtureExecutor = new ORMExecutor($entityManager, new ORMPurger($entityManager));
        }

        return $this->fixtureExecutor;
    }
}