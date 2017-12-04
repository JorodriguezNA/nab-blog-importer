<?php namespace Nabmarketingteam\BlogimporterModule\Importer;

use Nabmarketingteam\BlogimporterModule\Importer\Contract\ImporterRepositoryInterface;
use Anomaly\Streams\Platform\Entry\EntryRepository;

class ImporterRepository extends EntryRepository implements ImporterRepositoryInterface
{

    /**
     * The entry model.
     *
     * @var ImporterModel
     */
    protected $model;

    /**
     * Create a new ImporterRepository instance.
     *
     * @param ImporterModel $model
     */
    public function __construct(ImporterModel $model)
    {
        $this->model = $model;
    }
}
