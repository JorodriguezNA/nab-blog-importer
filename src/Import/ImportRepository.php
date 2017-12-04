<?php namespace Nabmarketingteam\BlogimporterModule\Import;

use Nabmarketingteam\BlogimporterModule\Import\Contract\ImportRepositoryInterface;
use Anomaly\Streams\Platform\Entry\EntryRepository;

class ImportRepository extends EntryRepository implements ImportRepositoryInterface
{

    /**
     * The entry model.
     *
     * @var ImportModel
     */
    protected $model;

    /**
     * Create a new ImportRepository instance.
     *
     * @param ImportModel $model
     */
    public function __construct(ImportModel $model)
    {
        $this->model = $model;
    }
}
