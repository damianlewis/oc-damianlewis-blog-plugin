<?php

namespace DamianLewis\Blog\Components;

use Cms\Classes\ComponentBase;
use DamianLewis\Blog\Models\Post as PostModel;

class Post extends ComponentBase
{

    /**
     * The post model used for display.
     *
     * @var PostModel
     */
    public $post;

    public function componentDetails(): array
    {
        return [
            'name'        => 'Post',
            'description' => 'Displays a blog post on the page.',
        ];
    }

    public function defineProperties(): array
    {
        return [
            'slug' => [
                'title'       => 'Post slug',
                'description' => 'Retrieve the blog post using the supplied slug value or URL parameter.',
                'default'     => '{{ :slug }}',
                'type'        => 'string',
            ],
        ];
    }

    public function onRun()
    {
        $this->post = $this->page['post'] = PostModel::where('slug', $this->property('slug'))->firstOrFail();
    }
}
