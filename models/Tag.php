<?php

namespace DamianLewis\Blog\Models;

use Model;
use October\Rain\Database\Traits\Sluggable;
use October\Rain\Database\Traits\Validation;

class Tag extends Model
{

    use Sluggable;
    use Validation;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'damianlewis_blog_tags';

    /**
     * The 'belongs to many' (many-to-many) relationships.
     *
     * @var array
     */
    public $belongsToMany = [
        'posts' => [
            Post::class,
            'table' => 'damianlewis_blog_post_tag',
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
        'name' => [
            'required',
        ],
    ];

    /**
     * The messages used when validation fails.
     *
     * @var array
     */
    public $customMessages = [
        'name.required' => 'A name for the tag is required.',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
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
     * Checks whether the tag can be deleted. Tags can always be deleted. A foreign key constraint should be
     * in place to delete any relations between posts and the tag.
     *
     * @return bool
     */
    public function isDeletable(): bool
    {
        return true;
    }
}
