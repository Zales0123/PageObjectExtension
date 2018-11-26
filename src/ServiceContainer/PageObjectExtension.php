<?php

declare(strict_types=1);

namespace FriendsOfBehat\PageObjectExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use FriendsOfBehat\PageObjectExtension\Element\Element;
use FriendsOfBehat\PageObjectExtension\Page\Page;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
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
        try {
            /** @var ContainerInterface $scenarioContainer */
            $scenarioContainer = $container->get('fob_context_service.service_container.scenario');
        } catch (ServiceNotFoundException $exception) {
            return;
        }

        $pageDefinition = $this->registerPageDefinition($scenarioContainer);
        $this->registerSymfonyPageDefinition($scenarioContainer, $pageDefinition);
        $this->registerElementDefinition($scenarioContainer);
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

    private function registerPageDefinition(ContainerInterface $scenarioContainer): Definition
    {
        $pageDefinition = new Definition(Page::class, [
            new Reference('mink.default_session'),
            new Parameter('__behat__.mink.parameters')
        ]);
        $pageDefinition->setAbstract(true);
        $pageDefinition->setPublic(false);

        $scenarioContainer->setDefinition('fob_page_object.behat.page', $pageDefinition);

        return $pageDefinition;
    }

    private function registerSymfonyPageDefinition(ContainerInterface $scenarioContainer, Definition $pageDefinition): void
    {
        $symfonyPageDefinition = new ChildDefinition('fob_page_object.behat.page');
        $symfonyPageDefinition->setClass(SymfonyPage::class);
        $symfonyPageDefinition->addArgument(new Reference('__symfony_shared__.router'));
        $symfonyPageDefinition->setAbstract(true);
        $symfonyPageDefinition->setPublic(false);

        $scenarioContainer->setDefinition('fob_page_object.behat.symfony_page', $pageDefinition);
    }

    private function registerElementDefinition(ContainerInterface $scenarioContainer): void
    {
        $elementDefinition = new Definition(Element::class, [
            new Reference('mink.default_session'),
            new Parameter('__behat__.mink.parameters')
        ]);
        $elementDefinition->setAbstract(true);
        $elementDefinition->setPublic(false);

        $scenarioContainer->setDefinition('fob_page_object.behat.element', $elementDefinition);
    }
}
