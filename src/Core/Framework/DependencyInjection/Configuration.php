<?php declare(strict_types=1);

namespace Shopware\Core\Framework\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('shopware');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->append($this->createFilesystemSection())
                ->append($this->createCdnSection())
                ->append($this->createApiSection())
                ->append($this->createStoreSection())
                ->append($this->createAdminWorkerSection())
                ->append($this->createAutoUpdateSection())
                ->append($this->createSitemapSection())
                ->append($this->createDeploymentSection())
                ->append($this->createMediaSection())
                ->append($this->createDalSection())
                ->append($this->createFeatureSection())
                ->append($this->createLoggerSection())
                ->append($this->createCacheSection())
            ->end();

        return $treeBuilder;
    }

    private function createFilesystemSection(): ArrayNodeDefinition
    {
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = (new TreeBuilder('filesystem'))->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('private')
                    ->performNoDeepMerging()
                    ->children()
                        ->scalarNode('type')->end()
                        ->scalarNode('visibility')->end()
                        ->variableNode('config')->end()
                    ->end()
                ->end()
                ->arrayNode('public')
                    ->performNoDeepMerging()
                    ->children()
                        ->scalarNode('type')->end()
                        ->scalarNode('url')->end()
                        ->variableNode('config')->end()
                    ->end()
                ->end()
                ->arrayNode('temp')
                    ->performNoDeepMerging()
                    ->children()
                        ->scalarNode('type')->end()
                        ->variableNode('config')->end()
                    ->end()
                ->end()
                ->arrayNode('theme')
                    ->performNoDeepMerging()
                    ->children()
                        ->scalarNode('type')->end()
                        ->scalarNode('url')->end()
                        ->variableNode('config')->end()
                    ->end()
                ->end()
                ->arrayNode('asset')
                    ->performNoDeepMerging()
                    ->children()
                        ->scalarNode('type')->end()
                        ->scalarNode('url')->end()
                        ->variableNode('config')->end()
                    ->end()
                ->end()
                ->arrayNode('sitemap')
                    ->performNoDeepMerging()
                    ->children()
                        ->scalarNode('type')->end()
                        ->scalarNode('url')->end()
                        ->variableNode('config')->end()
                    ->end()
                ->end()
                ->arrayNode('allowed_extensions')
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $rootNode;
    }

    private function createCdnSection(): ArrayNodeDefinition
    {
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = (new TreeBuilder('cdn'))->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('url')->end()
                ->scalarNode('strategy')->end()
            ->end();

        return $rootNode;
    }

    private function createApiSection(): ArrayNodeDefinition
    {
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = (new TreeBuilder('api'))->getRootNode();
        $rootNode
            ->children()
            ->arrayNode('store')
                ->children()
                ->scalarNode('context_lifetime')->defaultValue('P1D')->end()
                ->end()
            ->end()
            ->integerNode('max_limit')->end()
            ->arrayNode('api_browser')
                ->children()
                ->booleanNode('auth_required')
                    ->defaultTrue()
                ->end()
            ->end();

        return $rootNode;
    }

    private function createStoreSection(): ArrayNodeDefinition
    {
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = (new TreeBuilder('store'))->getRootNode();
        $rootNode
            ->children()
                ->booleanNode('frw')->end()
            ->end();

        return $rootNode;
    }

    private function createAdminWorkerSection(): ArrayNodeDefinition
    {
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = (new TreeBuilder('admin_worker'))->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('transports')
                    ->prototype('scalar')->end()
                ->end()
                ->integerNode('poll_interval')
                    ->defaultValue(20)
                ->end()
                ->booleanNode('enable_admin_worker')
                    ->defaultValue(true)
                ->end()
            ->end();

        return $rootNode;
    }

    private function createAutoUpdateSection(): ArrayNodeDefinition
    {
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = (new TreeBuilder('auto_update'))->getRootNode();
        $rootNode
            ->children()
                ->booleanNode('enabled')->end()
            ->end();

        return $rootNode;
    }

    private function createSitemapSection(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('sitemap');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('custom_urls')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('url')->end()
                            ->scalarNode('lastMod')->end()
                            ->enumNode('changeFreq')
                                ->values([
                                    'always',
                                    'hourly',
                                    'daily',
                                    'weekly',
                                    'monthly',
                                    'yearly',
                                ])
                            ->end()
                            ->floatNode('priority')->end()
                            ->scalarNode('salesChannelId')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('excluded_urls')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('resource')->end()
                            ->scalarNode('identifier')->end()
                            ->scalarNode('salesChannelId')->end()
                        ->end()
                    ->end()
                ->end()
                ->integerNode('batchsize')
                    ->min(1)
                    ->defaultValue(100)
                ->end()
            ->end();

        return $rootNode;
    }

    private function createDeploymentSection(): ArrayNodeDefinition
    {
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = (new TreeBuilder('deployment'))->getRootNode();
        $rootNode
            ->children()
                ->booleanNode('blue_green')->end()
            ->end();

        return $rootNode;
    }

    private function createMediaSection(): ArrayNodeDefinition
    {
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = (new TreeBuilder('media'))->getRootNode();
        $rootNode
            ->children()
                ->booleanNode('enable_url_upload_feature')->end()
                ->booleanNode('enable_url_validation')->end()
            ->end();

        return $rootNode;
    }

    private function createFeatureSection(): ArrayNodeDefinition
    {
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = (new TreeBuilder('feature'))->getRootNode();
        $rootNode
            ->children()
            ->arrayNode('flags')
                ->useAttributeAsKey('name')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('name')->end()
                        ->booleanNode('default')->defaultFalse()->end()
                        ->booleanNode('major')->defaultFalse()->end()
                        ->scalarNode('description')->end()
                    ->end()
                ->end()
                ->beforeNormalization()
                    ->always()->then(function ($flags) {
                        foreach ($flags as $key => $flag) {
                            // support old syntax
                            if (\is_int($key) && \is_string($flag)) {
                                unset($flags[$key]);

                                $flags[] = [
                                    'name' => $flag,
                                ];
                            }
                        }

                        return $flags;
                    })
                    ->end()
            ->end();

        return $rootNode;
    }

    private function createLoggerSection(): ArrayNodeDefinition
    {
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = (new TreeBuilder('logger'))->getRootNode();
        $rootNode
            ->children()
                ->integerNode('file_rotation_count')
                    ->defaultValue(14)
                ->end()
            ->end();

        return $rootNode;
    }

    private function createCacheSection(): ArrayNodeDefinition
    {
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = (new TreeBuilder('cache'))->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('invalidation')
                    ->children()
                        ->integerNode('delay')
                            ->defaultValue(0)
                        ->end()
                        ->integerNode('count')
                            ->defaultValue(150)
                        ->end()
                        ->arrayNode('http_cache')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('product_listing_route')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('product_detail_route')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('product_search_route')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('product_suggest_route')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('product_cross_selling_route')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('payment_method_route')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('shipping_method_route')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('navigation_route')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('category_route')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('landing_page_route')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('language_route')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('currency_route')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $rootNode;
    }

    private function createDalSection(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('dal');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->integerNode('batch_size')
                    ->min(1)
                    ->defaultValue(125)
                ->end()
            ->end();

        return $rootNode;
    }
}
