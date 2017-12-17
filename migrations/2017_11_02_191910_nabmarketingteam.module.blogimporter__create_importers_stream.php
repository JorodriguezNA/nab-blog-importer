<?php

use Anomaly\Streams\Platform\Database\Migration\Migration;

class NabmarketingteamModuleBlogimporterCreateImportersStream extends Migration
{

    /**
     * The stream definition.
     *
     * @var array
     */
    protected $stream = [
        'slug' => 'importers',
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
        'site_name' => [
            'translatable' => true,
            'required' => true,
        ],
        'site_key'  => [
            'translatable' => true,
            'required' => true,
        ],
        'blog_id'   => [
            'translatable' => true,
            'required' => true,
        ],
        'default_author' => [
            'translatable' => true,
            'required' => true,
        ],
        'slug' => [
            'unique' => true,
            'required' => true,
        ],

    ];

}
