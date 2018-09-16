<?php

declare(strict_types=1);

namespace FriendsOfBehat\PageObjectExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use FriendsOfBehat\PageObjectExtension\Page\Page;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

final class PageObjectExtension implements Extension
{
    public function getConfigKey(): string
    {
        return 'fob_page_object';
    }

    public function load(ContainerBuilder $container, array $config): void
    {
        $definition = new Definition(Page::class, [
            new Reference('mink.default_session'),
            new Parameter('%__behat__.mink.parameters%')
        ]);
        $definition->setAbstract(true);
        $definition->setPublic(false);

        $container->setDefinition('sylius.behat.page', $definition);
    }

    public function initialize(ExtensionManager $extensionManager): void
    {
    }

    public function configure(ArrayNodeDefinition $builder): void
    {
    }

    public function process(ContainerBuilder $container): void
    {
    }
}
