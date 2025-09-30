<?php

return [

    /*
     * The name of the disk on which the snapshots are stored.
     */
    'disk' => 'snapshots',

    /*
     * The connection to be used to create snapshots. Set this to null
     * to use the default configured in `config/databases.php`
     */
    'default_connection' => null,

    /*
     * The directory where temporary files will be stored.
     */
    'temporary_directory_path' => storage_path('app/laravel-db-snapshots/temp'),

    /*
     * Create dump files that are gzipped
     */
    'compress' => false,

    /*
     * Only these tables will be included in the snapshot. Set to `null` to include all tables.
     *
     * Default: `null`
     */
    'tables' => null,

    /*
     * All tables will be included in the snapshot except these tables. Set to `null` to include all tables.
     *
     * Default: `null`
     */
    'exclude' => null,

    /*
     * Custom paths for mysql & mysqldump executables (Windows fix)
     */
    'db_dumper_commands' => [
        'mysql' => '"C:\xampp\mysql\bin\mysql.exe"',
        'mysqldump' => '"C:\xampp\mysql\bin\mysqldump.exe"',
    ],

    // Gunakan yang ini jika di host server!
    // 'db_dumper_commands' => [
    //     'mysql' => '/usr/bin/mysql',
    //     'mysqldump' => '/usr/bin/mysqldump',
    // ],
];
