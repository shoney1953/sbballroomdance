<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticIniteb0d9c46f3b017b4b6cbb1f1c0842254
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticIniteb0d9c46f3b017b4b6cbb1f1c0842254::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticIniteb0d9c46f3b017b4b6cbb1f1c0842254::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticIniteb0d9c46f3b017b4b6cbb1f1c0842254::$classMap;

        }, null, ClassLoader::class);
    }
}
