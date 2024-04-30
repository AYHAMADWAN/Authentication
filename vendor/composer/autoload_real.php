<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit30ecbc89b385ec51f6a2321c0053fcec
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit30ecbc89b385ec51f6a2321c0053fcec', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit30ecbc89b385ec51f6a2321c0053fcec', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit30ecbc89b385ec51f6a2321c0053fcec::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}