<?php

namespace DamianLewis\Blog\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use DamianLewis\Blog\Models\Category;
use October\Rain\Database\Collection;

class Categories extends ComponentBase
{

    /**
     * A collection of categories to display.
     *
     * @var Collection
     */
    public $categories;

    /**
     * Reference to the current category slug.
     *
     * @var string
     */
    public $currentCategorySlug;

    public function componentDetails()
    {
        return [
            'name'        => 'Category List',
            'description' => 'Displays a list of blog categories on the page.',
        ];
    }

    public function defineProperties()
    {
        return [
            'displayEmpty' => [
                'title'       => 'Display empty categories',
                'description' => 'Show categories that do not have any posts.',
                'type'        => 'checkbox',
                'default'     => 0,
            ],
            'categoryPage' => [
                'title'       => 'Category page',
                'description' => 'Name of the category page file for the category links.',
                'type'        => 'dropdown',
                'default'     => 'blog/category',
            ],
        ];
    }

    public function onRun()
    {
        $this->currentCategorySlug = $this->page['currentCategorySlug'] = $this->property('slug');
        $this->categories          = $this->page['categories'] = $this->getCategories();
    }

    /**
     * Returns a list of CMS pages for the 'categoryPage' property dropdown options.
     *
     * @return array
     */
    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * Returns the blog categories being used by posts. If displayEmpty option is selected, will return all categories.
     *
     * @return Collection
     */
    protected function getCategories(): Collection
    {
        $displayEmpty = $this->property('displayEmpty');

        if (!$displayEmpty) {
            $categories = Category::whereHas('posts', function ($query) {
                $query->isPublished();
            })->get();
        } else {
            $categories = Category::get();
        }

        $this->setPageUrlForCategories($categories);

        return $categories;
    }

    /**
     * Sets the page url for a collection of categories.
     *
     * @param Collection $categories
     *
     * @return void
     */
    protected function setPageUrlForCategories(Collection $categories)
    {
        $categories->each(function (Category $category) {
            $category->setUrl($this->property('categoryPage'), $this->controller);
        });
    }
}
