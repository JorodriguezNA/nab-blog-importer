<?php namespace Nabmarketingteam\BlogimporterModule\Http\Controller\Admin;

use Nabmarketingteam\BlogimporterModule\Importer\Form\ImporterFormBuilder;
use Nabmarketingteam\BlogimporterModule\Importer\Table\ImporterTableBuilder;
use Anomaly\Streams\Platform\Http\Controller\AdminController;

class ImportersController extends AdminController
{

    /**
     * Display an index of existing entries.
     *
     * @param ImporterTableBuilder $table
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ImporterTableBuilder $table)
    {
        return $table->render();
    }

    /**
     * Create a new entry.
     *
     * @param ImporterFormBuilder $form
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(ImporterFormBuilder $form)
    {
        return $form->render();
    }

    /**
     * Edit an existing entry.
     *
     * @param ImporterFormBuilder $form
     * @param        $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(ImporterFormBuilder $form, $id)
    {
        return $form->render($id);
    }
}
