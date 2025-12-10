<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Salt String
    |--------------------------------------------------------------------------
    |
    | This salt string is used for generating HashIDs and should be set
    | to a random string, otherwise these generated HashIDs will not be
    | safe. Please do this definitely before deploying your application!
    |
    */

    'salt' => env('HASHID_SALT', 'your-secret-salt-string'),

    /*
    |--------------------------------------------------------------------------
    | Raw HashID Length
    |--------------------------------------------------------------------------
    |
    | This is the length of the raw HashID. The model prefix, separator
    | and the raw HashID are combined all together. So the Model HashID
    | length is the sum of raw HashID, separator, and model prefix lengths.
    |
    | Default: 13
    |
    */

    'length' => 13,

    /*
    |--------------------------------------------------------------------------
    | HashID Alphabet
    |--------------------------------------------------------------------------
    |
    | This alphabet will generate raw HashIDs. Please keep in mind that it
    | must contain at least 16 unique characters and can't contain spaces.
    |
    | Default: 'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890'
    |
    */

    'alphabet' => 'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890',

    /*
    |--------------------------------------------------------------------------
    | Model Prefix Length
    |--------------------------------------------------------------------------
    |
    | Here you can specify the length of the model prefix. By default, they
    | will generate it from the first letters of short class name.
    | Set it -1 to use full short class name as prefix.
    | Set it 0 to not use any prefix at all.
    |
    | Default: 3
    |
    */

    'prefix_length' => 3,

    /*
    |--------------------------------------------------------------------------
    | Model Prefix Case
    |--------------------------------------------------------------------------
    |
    | Here you can set the case of the prefix. Please keep in mind that for
    | some prefix cases, underscore ('_') characters will be added to the
    | prefix if your model name is multi word.
    |
    | Default: 'lower'
    |
    | Supported: "lower", "upper", "camel", "snake", "kebab",
    |            "title", "studly", "plural_studly"
    |
    */

    'prefix_case' => 'lower',

    /*
    |--------------------------------------------------------------------------
    | HashID Model Prefix Separator
    |--------------------------------------------------------------------------
    |
    | Here you can set the separator for your HashIDs. The separator
    | will be added between model prefix and the raw HashID.
    |
    | Default: '_'
    |
    | Options: '_', '-', '.', etc.
    |
    */

    'separator' => '_',

    /*
    |--------------------------------------------------------------------------
    | HashID Database Column
    |--------------------------------------------------------------------------
    |
    | By using `SavesHashIDs` trait, you can save model HashIDs to database.
    | Here you can set the database column name for HashIDs to save.
    |
    | Default: 'hash_id'
    |
    */

    'database_column' => 'hash_id',

    /*
    |--------------------------------------------------------------------------
    | Model Specific Generators
    |--------------------------------------------------------------------------
    |
    | Here you can set specific HashID generators for individual Models.
    | Each one of the settings above can be defined per model. You can
    | see an example below as a comment.
    |
    */

    'model_generators' => [
        // Configuration personnalisée pour Brand
        App\Models\Brand::class => [
            'salt' => env('HASHID_SALT_BRAND', 'brand-secret-salt-' . env('APP_KEY', 'default-key')),
            'prefix' => 'brd', // Préfixe personnalisé pour Brand
            'separator' => '_',
            'length' => 16,
        ],

        // Configuration personnalisée pour VehicleModel
        App\Models\VehicleModel::class => [
            'salt' => env('HASHID_SALT_VEHICLE_MODEL', 'vehicle-model-secret-salt-' . env('APP_KEY', 'default-key')),
            'prefix' => 'vmd', // Préfixe personnalisé pour VehicleModel
            'separator' => '_',
            'length' => 16,
        ],

        // Configuration personnalisée pour Genre
        App\Models\Genre::class => [
            'salt' => env('HASHID_SALT_GENRE', 'genre-secret-salt-' . env('APP_KEY', 'default-key')),
            'prefix' => 'gen', // Préfixe personnalisé pour Genre
            'separator' => '_',
            'length' => 16,
        ],

        // Configuration personnalisée pour Usage
        App\Models\Usage::class => [
            'salt' => env('HASHID_SALT_USAGE', 'usage-secret-salt-' . env('APP_KEY', 'default-key')),
            'prefix' => 'usg', // Préfixe personnalisé pour Usage
            'separator' => '_',
            'length' => 16,
        ],
    ],
];
