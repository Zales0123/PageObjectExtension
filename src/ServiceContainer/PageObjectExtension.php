<?php

declare(strict_types=1);

namespace FriendsOfBehat\PageObjectExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use FriendsOfBehat\PageObjectExtension\Page\Page;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
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
        $pageDefinition = new Definition(Page::class, [
            new Reference('mink.default_session'),
            new Parameter('%__behat__.mink.parameters%')
        ]);
        $pageDefinition->setAbstract(true);
        $pageDefinition->setPublic(false);

        $container->setDefinition('fob.behat.page', $pageDefinition);

        $symfonyPageDefinition = new ChildDefinition('fob.behat.page');
        $symfonyPageDefinition->setClass(SymfonyPage::class);
        $symfonyPageDefinition->addArgument(new Reference('__symfony_shared__.router'));
        $symfonyPageDefinition->setAbstract(true);
        $symfonyPageDefinition->setPublic(false);

        $container->setDefinition('fob.behat.symfony_page', $pageDefinition);
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
