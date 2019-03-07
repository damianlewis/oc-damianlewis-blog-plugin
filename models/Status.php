<?php

namespace DamianLewis\Blog\Models;

use Model;
use October\Rain\Database\Traits\Sluggable;
use October\Rain\Database\Traits\Validation;

class Status extends Model
{

    use Sluggable;
    use Validation;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'damianlewis_blog_statuses';

    /**
     * The 'has many' (one-to-many) relationships.
     *
     * @var array
     */
    public $hasMany = [
        'posts' => [
            Post::class,
            'table' => 'damianlewis_blog_posts',
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
        'name.required' => 'A name for the post status is required.',
    ];

    /**
     * List of attributes to generate unique slugs for.
     *
     * @var array
     */
    protected $slugs = [
        'code' => 'name',
    ];

    /**
     * Checks whether the blog status can be deleted. Statuses should not be deleted whilst a post is attached
     * to it.
     *
     * @return bool
     */
    public function isDeletable(): bool
    {
        if ($this->posts()->count() === 0) {
            return true;
        }

        return false;
    }
}
