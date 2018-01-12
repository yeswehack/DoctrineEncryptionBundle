<?php

namespace YWH\DoctrineEncryptionBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class YWHDoctrineEncryptionExtension extends Extension
{
    private $entityManagers   = array();
    private $documentManagers = array();

    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('encryptable.xml');

        $this->entityManagers = $this->processObjectManagerConfigurations($config['orm'], $container, 'doctrine.event_subscriber');
        $this->documentManagers = $this->processObjectManagerConfigurations($config['mongodb'], $container, 'doctrine_mongodb.odm.event_subscriber');

        $container->setParameter('ywh_doctrine_encryption.listener.encryptable.class', $config['listener_class']);
        $container->setParameter('ywh_doctrine_encryption.encryptor.class', $config['encryptor_class']);
        $container->setParameter('ywh_doctrine_encryption.encryptor.password', $config['password']);
        $container->setParameter('ywh_doctrine_encryption.encryptor.key', $config['key']);
    }

    /**
     * @internal
     */
    public function configValidate(ContainerBuilder $container)
    {
        foreach ($this->entityManagers as $name) {
            if (!$container->hasDefinition(sprintf('doctrine.dbal.%s_connection', $name))) {
                throw new \InvalidArgumentException(sprintf('Invalid %s config: DBAL connection "%s" not found', $this->getAlias(), $name));
            }
        }

        foreach ($this->documentManagers as $name) {
            if (!$container->hasDefinition(sprintf('doctrine_mongodb.odm.%s_document_manager', $name))) {
                throw new \InvalidArgumentException(sprintf('Invalid %s config: document manager "%s" not found', $this->getAlias(), $name));
            }
        }
    }

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     * @param string           $doctrineSubscriberTag
     *
     * @return array
     */
    private function processObjectManagerConfigurations(array $configs, ContainerBuilder $container, $doctrineSubscriberTag)
    {
        $usedManagers = array();

        foreach ($configs as $name) {
            $attributes = array('connection' => $name);

            $definition = $container->getDefinition('ywh_doctrine_encryption.listener.encryptable');
            $definition->addTag($doctrineSubscriberTag, $attributes);

            $usedManagers[$name] = true;
        }

        return array_keys($usedManagers);
    }
}
