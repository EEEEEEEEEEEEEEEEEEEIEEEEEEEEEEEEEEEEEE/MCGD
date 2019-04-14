<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit504a05de58570504ba9a99db0c76af10
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'Grafika\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Grafika\\' => 
        array (
            0 => __DIR__ . '/..' . '/kosinix/grafika/src/Grafika',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit504a05de58570504ba9a99db0c76af10::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit504a05de58570504ba9a99db0c76af10::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}