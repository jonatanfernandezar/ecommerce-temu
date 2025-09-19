<?php

return [

    /*
    |--------------------------------------------------------------------------
    | JWT Secret
    |--------------------------------------------------------------------------
    |
    | Llave secreta para firmar los tokens. Debe estar definida en tu archivo
    | .env como JWT_SECRET y en .env.testing para los tests.
    |
    */

    'secret' => env('JWT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Algoritmo
    |--------------------------------------------------------------------------
    |
    | Algoritmo usado para firmar el token. HS256 es el más común.
    |
    */

    'algo' => env('JWT_ALGO', 'HS256'),

    /*
    |--------------------------------------------------------------------------
    | Expiration time
    |--------------------------------------------------------------------------
    |
    | Tiempo de expiración del token en minutos. Se puede configurar según
    | necesidades en el archivo .env.
    |
    */

    'ttl' => env('JWT_TTL', 60), // 1 hora por defecto

    /*
    |--------------------------------------------------------------------------
    | Refresh time
    |--------------------------------------------------------------------------
    |
    | Tiempo máximo (en minutos) durante el cual un token puede ser refrescado.
    |
    */

    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160), // 2 semanas por defecto

];
