<?php

namespace Sieg\SeparatorAccess\Tests;

use Sieg\SeparatorAccess\DataContainer;

/**
 * Class ContainerTest
 * @package SeparatorAccess
 *
 * @covers \Sieg\SeparatorAccess\Container
 */
class DataContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyArgumentConstructor()
    {
        $container = new DataContainer();
        $this->assertEquals([], $container->getData());
    }

    public function testConstructorWithArrayArgument()
    {
        $arrayExample = ['exampleKey' => 'exampleData'];
        $container = new DataContainer($arrayExample);
        $this->assertEquals($arrayExample, $container->getData());
    }

    public function testConstructorWithNotArrayArgument()
    {
        $notArrayExample = 'example';
        $container = new DataContainer($notArrayExample);
        $this->assertEquals([], $container->getData());
    }
}
