<?php

namespace DamianLewis\Blog\Models;

use Cms\Classes\Controller;
use Model;
use October\Rain\Database\Traits\Sluggable;
use October\Rain\Database\Traits\Validation;

class Category extends Model
{

    use Sluggable;
    use Validation;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'damianlewis_blog_categories';

    /**
     * The 'has many' (one-to-many) relationships.
     *
     * @var array
     */
    public $hasMany = [
        'posts' => [
            Post::class,
            'table' => 'damianlewis_blog_posts',
            'order' => 'published_at desc',
            'scope' => 'isPublished',
        ],
    ];

    /**
     * Don't use timestamps on the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The validation rules to be applied to the data.
     *
     * @var array
     */
    public $rules = [
        'name' => 'required',
    ];

    /**
     * The messages used when validation fails.
     *
     * @var array
     */
    public $customMessages = [
        'name.required' => 'A name for the post category is required.',
    ];

    /**
     * List of attributes to generate unique slugs for.
     *
     * @var array
     */
    protected $slugs = [
        'slug' => 'name',
    ];

    /**
     * Returns the formatted name.
     *
     * @return string
     */
    public function getNameAttribute($value): string
    {
        return ucwords($value);
    }

    /**
     * Returns the number of posts attached the category.
     *
     * @return int
     */
    public function getPostCountAttribute(): int
    {
        return $this->posts()->count();
    }

    /**
     * Checks whether the blog category can be deleted. Categories are optional. Make sure a foreign key constraint is
     * in place to set the category id on the posts attached to this category to null.
     *
     * @return bool
     */
    public function isDeletable(): bool
    {
        return true;
    }

    /**
     * Sets a url attribute for the category page.
     *
     * @param string     $pageName
     * @param Controller $controller
     *
     * @return void
     */
    public function setUrl(string $pageName, Controller $controller)
    {
        $params = [
            'slug' => $this->slug,
        ];

        $this->attributes['url'] = $controller->pageUrl($pageName, $params);
    }
}
