<?php

use Anomaly\Streams\Platform\Database\Migration\Migration;

class NabmarketingteamModuleBlogimporterCreateBlogimporterFields extends Migration
{

    /**
     * The addon fields.
     *
     * @var array
     */
    protected $fields = [
        'name'      => 'anomaly.field_type.text',
        'site_name' => 'anomaly.field_type.text',
        'site_key'  => 'anomaly.field_type.text',
        'blog_id'   => 'anomaly.field_type.text',
        'importer'  =>  [
            'type'  => 'anomaly.field_type.relationship',
            'config' => [
                'title_name' => 'name',
                'related' => Anomaly\Streams\Platform\Model\Blogimporter\BlogimporterImportersEntryModel::class,
                'mode'  => 'dropdown',
            ],
        ],
        'default_author' => 'anomaly.field_type.text',
        
        'slug'      => [
            'type' => 'anomaly.field_type.slug',
            'config' => [
                'slugify' => 'name',
                'type' => '-'
            ],
        ],
    ];

}
