<?php

namespace Atolye15\SlackExceptionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
  /**
   * {@inheritdoc}
   */
  public function getConfigTreeBuilder()
  {
    $treeBuilder = new TreeBuilder();
    $rootNode = $treeBuilder->root('atolye15_slack_exception');

    $rootNode
      ->addDefaultsIfNotSet()
      ->children()
        ->enumNode('environment')
          ->values(['prod', 'all'])
          ->defaultValue('prod')
          ->cannotBeEmpty()
        ->end()
        ->scalarNode('token')
          ->cannotBeEmpty()
          ->isRequired()
        ->end()
        ->scalarNode('channel')
          ->cannotBeEmpty()
          ->isRequired()
        ->end()
        ->scalarNode('username')
          ->cannotBeEmpty()
          ->isRequired()
        ->end()
        ->scalarNode('project')
          ->cannotBeEmpty()
          ->isRequired()
        ->end()
        ->booleanNode('throw_exception')
          ->defaultFalse()
        ->end()
        ->integerNode('request_timeout')
          ->min(0)
          ->defaultValue(3000)
        ->end()
      ->end()
    ;

    return $treeBuilder;
  }
}
