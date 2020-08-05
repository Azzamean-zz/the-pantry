<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9080db885871024141ecf9b0e9e651b8
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Tribe\\Tickets\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Tribe\\Tickets\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Tribe',
        ),
    );

    public static $classMap = array (
        'Tribe\\Tickets\\Events\\Attendees_List' => __DIR__ . '/../..' . '/src/Tribe/Events/Attendees_List.php',
        'Tribe\\Tickets\\Events\\Service_Provider' => __DIR__ . '/../..' . '/src/Tribe/Events/Service_Provider.php',
        'Tribe\\Tickets\\Events\\Views\\V2\\Hooks' => __DIR__ . '/../..' . '/src/Tribe/Events/Views/V2/Hooks.php',
        'Tribe\\Tickets\\Events\\Views\\V2\\Models\\Tickets' => __DIR__ . '/../..' . '/src/Tribe/Events/Views/V2/Models/Tickets.php',
        'Tribe\\Tickets\\Events\\Views\\V2\\Service_Provider' => __DIR__ . '/../..' . '/src/Tribe/Events/Views/V2/Service_Provider.php',
        'Tribe\\Tickets\\Migration\\Queue' => __DIR__ . '/../..' . '/src/Tribe/Migration/Queue.php',
        'Tribe\\Tickets\\Migration\\Queue_4_12' => __DIR__ . '/../..' . '/src/Tribe/Migration/Queue_4_12.php',
        'Tribe\\Tickets\\Repositories\\Post_Repository' => __DIR__ . '/../..' . '/src/Tribe/Repositories/Post_Repository.php',
        'Tribe\\Tickets\\Repositories\\Traits\\Event' => __DIR__ . '/../..' . '/src/Tribe/Repositories/Traits/Event.php',
        'Tribe\\Tickets\\Repositories\\Traits\\Post_Attendees' => __DIR__ . '/../..' . '/src/Tribe/Repositories/Traits/Post_Attendees.php',
        'Tribe\\Tickets\\Repositories\\Traits\\Post_Tickets' => __DIR__ . '/../..' . '/src/Tribe/Repositories/Traits/Post_Tickets.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9080db885871024141ecf9b0e9e651b8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9080db885871024141ecf9b0e9e651b8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9080db885871024141ecf9b0e9e651b8::$classMap;

        }, null, ClassLoader::class);
    }
}