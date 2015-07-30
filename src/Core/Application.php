<?php namespace Intraxia\Jaxion\Core;

use Intraxia\Jaxion\Contract\Core\Application as ApplicationContract;

/**
 * Class Application
 * @package Intraxia\Jaxion
 */
class Application extends Container implements ApplicationContract
{
    /**
     * Singleton instance of the Application object
     *
     * @var Application
     */
    protected static $instance = null;

    /**
     * @inheritdoc
     */
    public function __construct($file)
    {
        if (static::$instance !== null) {
            throw new ApplicationAlreadyBootedException;
        }

        static::$instance = $this;

        $this['url'] = plugin_dir_url($file);
        $this['path'] = plugin_dir_path($file);
        $this['basename'] = plugin_basename($file);

        $this['Loader'] = function ($app) {
            return new Loader($app);
        };
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this['Loader']->register();
    }

    /**
     * @inheritDoc
     */
    public static function get()
    {
        if (static::$instance === null) {
            throw new ApplicationNotBootedException;
        }

        return static::$instance;
    }

    /**
     * @inheritDoc
     */
    public static function shutdown()
    {
        if (static::$instance !== null) {
            static::$instance = null;
        }
    }
}
