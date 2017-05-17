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
    public $arrayExample = [
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => [
            'key31' => 'value31',
            'key32' => 'value32'
        ]
    ];

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

    /**
     * Data provider for testGetter
     *
     * @return array
     */
    public function getterDataProvider()
    {
        $tests = [
            ['key2', 'value2'],
            ['key2.undefinedsubkey', null],
            ['key3.key32', 'value32'],
            ['undefinedkey', null],
            ['undefinedkey.secondlevel', null],
            ['undefinedkey.secondlevel.thirdlevel', null],
            ['key3.key33', null]
        ];

        return $tests;
    }

    /**
     * Testing all possible getter cases
     *
     * @dataProvider getterDataProvider
     */
    public function testGetter($key, $value)
    {
        $container = new DataContainer($this->arrayExample);
        $result = $container->get($key);
        $this->assertSame($value, $result);
    }
}
