<?php

defined('SYSPATH') OR exit('No direct script access allowed');

class ComposerAutoloaderInit5dd2bcd33707ad3654caeb1d6ecaae61
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require SYSPATH . '/autoload/composer/ClassLoader.php';
        }
    }

    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit5dd2bcd33707ad3654caeb1d6ecaae61', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader();
        spl_autoload_unregister(array('ComposerAutoloaderInit5dd2bcd33707ad3654caeb1d6ecaae61', 'loadClassLoader'));

        require_once SYSPATH . '/autoload/composer/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit5dd2bcd33707ad3654caeb1d6ecaae61::getInitializer($loader));

        $loader->register(true);
        return $loader;
    }
}
