<?php

namespace MennoVanHout\JMS;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Support\ServiceProvider;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use MennoVanHout\JMS\Console\Commands\ClearCacheCommand;

class JMSServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
        $this->registerAnnotations();
        $this->registerSerializer();
        $this->registerCommands();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/jms.php' => config_path('jms.php'),
        ], 'config');
    }

    /**
     * Merge config
     */
    protected function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/jms.php',
            'jms'
        );
    }

    /**
     * Register serializer annotations
     */
    protected function registerAnnotations()
    {
        // RegisterLoader is deprecated because this will be removed in Doctrine/Annotations 2.0.
        // They recommend the following code until the release of this version.
        AnnotationRegistry::registerLoader('class_exists');
    }

    /**
     * Register the serializer
     */
    protected function registerSerializer()
    {
        $this->app->singleton(Serializer::class, function ($app) {
            $serializer = SerializerBuilder::create()
                ->setDebug(config('app.debug'))
                ->setCacheDir(config('jms.cache'))
                ->setPropertyNamingStrategy(new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy()))
                ->addDefaultHandlers();

            $this->registerCustomHandlers($serializer, config('jms.handlers'));

            return $serializer->build();
        });

        $this->app->bind(SerializerInterface::class, Serializer::class);
    }

    /**
     * Register Commands
     */
    protected function registerCommands()
    {
        $this->commands([
            ClearCacheCommand::class
        ]);
    }

    protected function registerCustomHandlers(SerializerBuilder $serializer, array $handlers)
    {
        foreach ($handlers as $handler)
        {
            $serializer->configureHandlers(function(HandlerRegistry $registry) use ($handler){
                $registry->registerSubscribingHandler(new $handler());
            });
        }
    }
}