<?php
namespace Consolidation\Bootstrap;

use Consolidation\TestUtils\TestBoot;
use Consolidation\TestUtils\TestBootstrapAwareObject;

class BootstrapTests extends \PHPUnit_Framework_TestCase
{
    function testBootstrapManager()
    {
        $manager = new BootstrapManager();
        $testBootstrapAwareObject = new TestBootstrapAwareObject();
        $testBootstrapAwareObject->setBootstrapCurrator($manager->getBootstrapCurrator());

        $testBoot1 = new TestBoot('/path/to/boot1', '1.2.3', ['code', 'database', 'configuration']);
        $testBoot2 = new TestBoot('/path/to/boot2', '3.4.5', ['code', 'database', 'configuration']);

        $manager->add($testBoot1);
        $manager->add($testBoot2);

        $bootstrapObject = $manager->selectBootstrap('/path/to/boot1');

        $this->assertNotNull($bootstrapObject, 'Could not select a bootstrap object');
        $this->assertEquals('1.2.3', $testBootstrapAwareObject->getBootstrap()->getFrameworkVersion());
    }
}
