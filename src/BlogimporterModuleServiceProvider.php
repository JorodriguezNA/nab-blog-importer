<?php namespace Nabmarketingteam\BlogimporterModule;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Nabmarketingteam\BlogimporterModule\Import\Contract\ImportRepositoryInterface;
use Nabmarketingteam\BlogimporterModule\Import\ImportRepository;
use Anomaly\Streams\Platform\Model\Blogimporter\BlogimporterImportsEntryModel;
use Nabmarketingteam\BlogimporterModule\Import\ImportModel;
use Nabmarketingteam\BlogimporterModule\Importer\Contract\ImporterRepositoryInterface;
use Nabmarketingteam\BlogimporterModule\Importer\ImporterRepository;
use Anomaly\Streams\Platform\Model\Blogimporter\BlogimporterImportersEntryModel;
use Nabmarketingteam\BlogimporterModule\Importer\ImporterModel;
use Illuminate\Routing\Router;

class BlogimporterModuleServiceProvider extends AddonServiceProvider
{

    /**
     * Additional addon plugins.
     *
     * @type array|null
     */
    protected $plugins = [];

    /**
     * The addon Artisan commands.
     *
     * @type array|null
     */
    protected $commands = [];

    /**
     * The addon's scheduled commands.
     *
     * @type array|null
     */
    protected $schedules = [];

    /**
     * The addon API routes.
     *
     * @type array|null
     */
    protected $api = [];

    /**
     * The addon routes.
     *
     * @type array|null
     */
    protected $routes = [
        'admin/blogimporter/imports'            => 'Nabmarketingteam\BlogimporterModule\Http\Controller\Admin\ImportsController@index',
        'admin/blogimporter/imports/create'     => 'Nabmarketingteam\BlogimporterModule\Http\Controller\Admin\ImportsController@create',
        'admin/blogimporter/imports/edit/{id}'  => 'Nabmarketingteam\BlogimporterModule\Http\Controller\Admin\ImportsController@edit',
        'admin/blogimporter/imports/run/{id}'   => 'Nabmarketingteam\BlogimporterModule\Http\Controller\Admin\ImportsController@import',
        'admin/blogimporter/imports/importpost/{siteid}/{postid}' => 'Nabmarketingteam\BlogimporterModule\Http\Controller\Admin\ImportsController@importpost',
        'admin/blogimporter/imports/importauthor/{siteid}/{authoremail}' => 'Nabmarketingteam\BlogimporterModule\Http\Controller\Admin\ImportsController@createauthor',
        'admin/blogimporter'           => 'Nabmarketingteam\BlogimporterModule\Http\Controller\Admin\ImportersController@index',
        'admin/blogimporter/create'    => 'Nabmarketingteam\BlogimporterModule\Http\Controller\Admin\ImportersController@create',
        'admin/blogimporter/edit/{id}' => 'Nabmarketingteam\BlogimporterModule\Http\Controller\Admin\ImportersController@edit',
    ];

    /**
     * The addon middleware.
     *
     * @type array|null
     */
    protected $middleware = [
        //Nabmarketingteam\BlogimporterModule\Http\Middleware\ExampleMiddleware::class
    ];

    /**
     * The addon route middleware.
     *
     * @type array|null
     */
    protected $routeMiddleware = [];

    /**
     * The addon event listeners.
     *
     * @type array|null
     */
    protected $listeners = [
        //Nabmarketingteam\BlogimporterModule\Event\ExampleEvent::class => [
        //    Nabmarketingteam\BlogimporterModule\Listener\ExampleListener::class,
        //],
    ];

    /**
     * The addon alias bindings.
     *
     * @type array|null
     */
    protected $aliases = [
        //'Example' => Nabmarketingteam\BlogimporterModule\Example::class
    ];

    /**
     * The addon class bindings.
     *
     * @type array|null
     */
    protected $bindings = [
        BlogimporterImportsEntryModel::class => ImportModel::class,
        BlogimporterImportersEntryModel::class => ImporterModel::class,
    ];

    /**
     * The addon singleton bindings.
     *
     * @type array|null
     */
    protected $singletons = [
        ImportRepositoryInterface::class => ImportRepository::class,
        ImporterRepositoryInterface::class => ImporterRepository::class,
    ];

    /**
     * Additional service providers.
     *
     * @type array|null
     */
    protected $providers = [
        //\ExamplePackage\Provider\ExampleProvider::class
    ];

    /**
     * The addon view overrides.
     *
     * @type array|null
     */
    protected $overrides = [
        //'streams::errors/404' => 'module::errors/404',
        //'streams::errors/500' => 'module::errors/500',
    ];

    /**
     * The addon mobile-only view overrides.
     *
     * @type array|null
     */
    protected $mobile = [
        //'streams::errors/404' => 'module::mobile/errors/404',
        //'streams::errors/500' => 'module::mobile/errors/500',
    ];

    /**
     * Register the addon.
     */
    public function register()
    {
        // Run extra pre-boot registration logic here.
        // Use method injection or commands to bring in services.
    }

    /**
     * Boot the addon.
     */
    public function boot()
    {
        // Run extra post-boot registration logic here.
        // Use method injection or commands to bring in services.
    }

    /**
     * Map additional addon routes.
     *
     * @param Router $router
     */
    public function map(Router $router)
    {
        // Register dynamic routes here for example.
        // Use method injection or commands to bring in services.
    }

}
