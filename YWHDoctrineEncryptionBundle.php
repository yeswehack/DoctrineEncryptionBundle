<?php

namespace YWH\DoctrineEncryptionBundle;

use YWH\DoctrineEncryptionBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class YWHDoctrineEncryptionBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ValidateExtensionConfigurationPass());
    }
}
