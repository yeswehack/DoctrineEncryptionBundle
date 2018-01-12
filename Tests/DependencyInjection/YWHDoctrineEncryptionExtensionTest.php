<?php

namespace YWH\DoctrineEncryptionBundle\Tests\DependencyInjection;

use YWH\DoctrineEncryptionBundle\DependencyInjection\YWHDoctrineEncryptionExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class YWHDoctrineEncryptionExtensionTest extends TestCase
{
    public function testLoadORMConfig()
    {
        $extension = new YWHDoctrineEncryptionExtension();
        $container = new ContainerBuilder();

        $config = array(
            'orm' => array(
                'default',
                'other',
            ),
            'password' => 'password',
            'key' => 'key',
        );

        $extension->load(array($config), $container);

        $this->assertTrue($container->hasDefinition('ywh_doctrine_encryption.listener.encryptable'));

        $def = $container->getDefinition('ywh_doctrine_encryption.listener.encryptable');

        $this->assertTrue($def->hasTag('doctrine.event_subscriber'));

        $tags = $def->getTag('doctrine.event_subscriber');

        $this->assertCount(2, $tags);
    }

    public function testLoadMongodbConfig()
    {
        $extension = new YWHDoctrineEncryptionExtension();
        $container = new ContainerBuilder();

        $config = array(
            'mongodb' => array(
                'default',
                'other',
            ),
            'password' => 'password',
            'key' => 'key',
        );

        $extension->load(array($config), $container);

        $this->assertTrue($container->hasDefinition('ywh_doctrine_encryption.listener.encryptable'));

        $def = $container->getDefinition('ywh_doctrine_encryption.listener.encryptable');

        $this->assertTrue($def->hasTag('doctrine_mongodb.odm.event_subscriber'));

        $tags = $def->getTag('doctrine_mongodb.odm.event_subscriber');

        $this->assertCount(2, $tags);
    }

    public function testLoadBothConfig()
    {
        $extension = new YWHDoctrineEncryptionExtension();
        $container = new ContainerBuilder();

        $config = array(
            'orm' => array('default'),
            'mongodb' => array('default'),
            'password' => 'password',
            'key' => 'key',
        );

        $extension->load(array($config), $container);

        $this->assertTrue($container->hasDefinition('ywh_doctrine_encryption.listener.encryptable'));

        $def = $container->getDefinition('ywh_doctrine_encryption.listener.encryptable');

        $this->assertTrue($def->hasTag('doctrine.event_subscriber'));
        $this->assertTrue($def->hasTag('doctrine_mongodb.odm.event_subscriber'));

        $this->assertCount(1, $def->getTag('doctrine.event_subscriber'));
        $this->assertCount(1, $def->getTag('doctrine_mongodb.odm.event_subscriber'));
    }
}
