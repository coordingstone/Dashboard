<?php

use DI\Container;
use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{

    /** @var Container $container */
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        $builder = new ContainerBuilder();
        $this->container = $builder->build();

        $dbMock = $this->getMockBuilder('Dashboard\Database\Db')
            ->disableOriginalConstructor()->getMock();
        $this->container->set('Dashboard\Database\Db', $dbMock);
    }

    protected function createStandardDaoMock(string $className)
    {
        return $this->getMockBuilder($className)
            ->setConstructorArgs(array($this->container->get('Dashboard\Database\Db')))
            ->getMock();
    }

    protected function createStandardServiceMock(string $className, array $daoClassNames)
    {
        $daoMocks = array();
        foreach ($daoClassNames as $daoClassName) {
            $daoMocks[] = $this->createStandardDaoMock($daoClassName);
        }
        return $this->getMockBuilder($className)
            ->setConstructorArgs($daoMocks)
            ->getMock();
    }
}