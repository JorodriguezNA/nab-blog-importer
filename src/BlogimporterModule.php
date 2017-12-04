<?php namespace Nabmarketingteam\BlogimporterModule;

use Anomaly\Streams\Platform\Addon\Module\Module;

class BlogimporterModule extends Module
{

    /**
     * The navigation display flag.
     *
     * @var bool
     */
    protected $navigation = true;

    /**
     * The addon icon.
     *
     * @var string
     */
    protected $icon = 'fa fa-cloud-download';

    /**
     * The module sections.
     *
     * @var array
     */
    protected $sections = [
        'importers' => [
            'buttons' => [
                'new_importer',
            ],
        ],
        'imports' => [
            'buttons' => [
                'new_import',
            ],
        ],
    ];

}
