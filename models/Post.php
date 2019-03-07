<?php

namespace DamianLewis\Blog\Models;

use Cms\Classes\Controller;
use Model;
use October\Rain\Database\Builder;
use October\Rain\Database\Traits\Nullable;
use October\Rain\Database\Traits\Sluggable;
use October\Rain\Database\Traits\Validation;

class Post extends Model
{

    use Nullable;
    use Sluggable;
    use Validation;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'damianlewis_blog_posts';

    /**
     * The 'belongs to' relationships.
     *
     * @var array
     */
    public $belongsTo = [
        'category' => [
            Category::class,
            'table' => 'damianlewis_blog_categories',
        ],
        'status'   => [
            Status::class,
            'table' => 'damianlewis_blog_statuses',
        ],
    ];

    /**
     * The 'belongs to many' (many-to-many) relationships.
     *
     * @var array
     */
    public $belongsToMany = [
        'tags' => [
            Tag::class,
            'table' => 'damianlewis_blog_post_tag',
            'order' => 'name',
        ],
    ];

    /**
     * The validation rules to be applied to the data.
     *
     * @var array
     */
    public $rules = [
        'title'        => 'required',
        'slug'         => [
            'sometimes',
            'required',
            'unique:damianlewis_blog_posts',
            'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i',
        ],
        'body'         => 'required',
        'category'     => 'required',
        'status'       => 'required',
        'published_at' => 'required',
    ];

    /**
     * The messages used when validation fails.
     *
     * @var array
     */
    public $customMessages = [
        'title.required'        => 'A title for the post is required.',
        'slug.required'         => 'A slug for the post is required.',
        'slug.unique'           => 'The updated slug needs to be unique.',
        'slug.regex'            => 'The slug format is invalid. It needs to be URL friendly.',
        'body.required'         => 'The body content for the post is required.',
        'category.required'     => 'The category for the post needs to be set.',
        'status.required'       => 'The status for the post needs to be set.',
        'published_at.required' => 'A published date is required for the post.',
    ];

    /**
     * Attributes which can be null.
     *
     * @var array
     */
    protected $nullable = [
        'featured_image',
        'featured_image_title',
        'featured_image_description',
    ];

    /**
     * List of attributes to generate unique slugs for.
     *
     * @var array
     */
    protected $slugs = [
        'slug' => 'title',
    ];

    /**
     * Filter the posts by the given category ids.
     *
     * @param Builder $query
     * @param array   $ids
     *
     * @return Builder
     */
    public function scopeFilterCategory(Builder $query, array $ids): Builder
    {
        return $query->whereHas('category', function ($q) use ($ids) {
            $q->whereIn('category_id', $ids);
        });
    }

    /**
     * Filter the posts by the given status ids.
     *
     * @param Builder $query
     * @param array   $ids
     *
     * @return Builder
     */
    public function scopeFilterStatus(Builder $query, array $ids): Builder
    {
        return $query->whereHas('status', function ($q) use ($ids) {
            $q->whereIn('status_id', $ids);
        });
    }

    /**
     * Get only the published events.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeIsPublished(Builder $query): Builder
    {
        $status = Status::where('code', 'published')->firstOrFail();

        return $query->where('status_id', $status->id)
            ->where('published_at', '<=', date('Y-m-d'));
    }

    /**
     * Sets a url attribute for the post page.
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
