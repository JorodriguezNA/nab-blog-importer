<?php

use Anomaly\Streams\Platform\Database\Migration\Migration;

class NabmarketingteamModuleBlogimporterCreateImportsStream extends Migration
{

    /**
     * The stream definition.
     *
     * @var array
     */
    protected $stream = [
        'slug' => 'imports',
         'title_column' => 'name',
         'translatable' => true,
         'trashable' => false,
         'searchable' => false,
         'sortable' => false,
    ];

    /**
     * The stream assignments.
     *
     * @var array
     */
    protected $assignments = [
        'name' => [
            'translatable' => true,
            'required' => true,
        ],
        'slug' => [
            'unique' => true,
            'required' => true,
        ],
        'importer' => [
            'required' => true,
            'translatable' => false,
        ]
    ];

}
