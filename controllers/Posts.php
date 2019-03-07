<?php

namespace DamianLewis\Blog\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use BackendMenu;
use Backend\Classes\Controller;
use Model;
use DamianLewis\Blog\Models\Category;
use DamianLewis\Blog\Models\Post;
use DamianLewis\Blog\Models\Status;
use DamianLewis\Blog\Models\Tag;

class Posts extends Controller
{

    /**
     * Backend behaviours implemented by this controller.
     *
     * @var array
     */
    public $implement = [
        FormController::class,
        ListController::class,
    ];

    /**
     * Form behaviour configuration file.
     *
     * @var string
     */
    public $formConfig = 'config_post_form.yaml';

    /**
     * List behaviour configuration files.
     *
     * @var array
     */
    public $listConfig = [
        'posts'      => 'config_posts_list.yaml',
        'categories' => 'config_categories_list.yaml',
        'tags'       => 'config_tags_list.yaml',
        'statuses'   => 'config_statuses_list.yaml',
    ];

    /**
     * The permissions which are required to access this controller.
     *
     * @var array
     */
    public $requiredPermissions = [
        'damianlewis.blog.access_posts',
    ];

    public function __construct()
    {
        if ($type = post('type')) {
            $this->vars['type'] = $type;
            $this->formConfig   = "config_${type}_form.yaml";
        }

        parent::__construct();

        BackendMenu::setContext('DamianLewis.Blog', 'blog');
    }

    /**
     * Overrides the ListController index method to change the bodyClass property.
     *
     * @return void
     */
    public function index()
    {
        $this->asExtension('ListController')->index();
        $this->bodyClass = 'compact-container';
    }

    /**
     * Returns a CSS class name for a row in the posts list.
     *
     * @param Model $record
     *
     * @return string
     */
    public function listInjectRowClass(Model $record): string
    {
        if (!$record instanceof Post) {
            return '';
        }

        return ($record->status == 'Archived') ? 'safe disabled' : '';
    }

    /**
     * Returns the form for creating a post attribute.
     *
     * @return mixed
     * @throws \SystemException
     */
    public function onCreateForm()
    {
        $this->asExtension('FormController')->create();

        return $this->makePartial('create_form');
    }

    /**
     * Creates the post attribute record and updates the related definition lists.
     *
     * @return mixed
     */
    public function onCreateRecord()
    {
        $this->asExtension('FormController')->create_onSave();

        return $this->refreshDefinitionLists();
    }

    /**
     * Returns the form for updating a post attribute.
     *
     * @return mixed
     * @throws \SystemException
     */
    public function onUpdateForm()
    {
        $id = post('id');

        $this->asExtension('FormController')->update($id);

        return $this->makePartial('update_form', [
            'id'                => $id,
            'isRecordDeletable' => $this->isRecordDeletable(),
        ]);
    }

    /**
     * Updates the post attribute record and updates the related definition lists.
     *
     * @return mixed
     */
    public function onUpdateRecord()
    {
        $this->asExtension('FormController')->update_onSave(post('id'));

        return $this->refreshDefinitionLists();
    }

    /**
     * Deletes the post attribute record and updates the related definition lists.
     *
     * @return mixed
     */
    public function onDeleteRecord()
    {
        $this->asExtension('FormController')->update_onDelete(post('id'));

        return $this->refreshDefinitionLists();
    }

    /**
     * Bulk deletes selected posts and updates the related lists.
     *
     * @return array
     */
    public function index_onDelete(): array
    {
        $this->asExtension('ListController')->index_onDelete();

        return $this->refreshDefinitionLists();
    }

    /**
     * Checks whether the post attribute record can be deleted.
     *
     * @return bool
     */
    protected function isRecordDeletable(): bool
    {
        $model = $this->formGetModel();

        if (!($model instanceof Category || $model instanceof Tag || $model instanceof Status)) {
            return false;
        }

        return $model->isDeletable();
    }

    /**
     * Refresh all the related definition lists.
     *
     * @return array
     */
    protected function refreshDefinitionLists(): array
    {
        return array_merge(
            $this->listRefresh('posts'),
            $this->listRefresh('categories'),
            $this->listRefresh('tags'),
            $this->listRefresh('statuses')
        );
    }
}
