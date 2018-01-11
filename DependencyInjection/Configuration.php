<?php

namespace YWH\DoctrineEncryptionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ywh_doctrine_encryption');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('orm')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('mongodb')
                    ->scalarPrototype()->end()
                ->end()
                ->scalarNode('listener_class')
                    ->cannotBeEmpty()
                    ->defaultValue('YWH\Encryptable\EncryptableListener')
                ->end()
                ->scalarNode('encryptor_class')
                    ->cannotBeEmpty()
                    ->defaultValue('YWH\Encryptable\Encryptor\DefuseEncryptor')
                ->end()
                ->scalarNode('password')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
