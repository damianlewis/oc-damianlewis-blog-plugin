<?php

namespace DamianLewis\Blog\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use DamianLewis\Blog\Models\Post;
use October\Rain\Database\Collection;

class LatestPosts extends ComponentBase
{

    /**
     * A collection of posts.
     *
     * @var Collection
     */
    public $posts;

    public function componentDetails(): array
    {
        return [
            'name'        => 'Latest Posts',
            'description' => 'Display a list of latest posts.',
        ];
    }

    public function defineProperties(): array
    {
        return [
            'numberOfPosts' => [
                'title'             => 'Number of posts',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format for number of posts value',
                'default'           => '5',
            ],
            'postPage'      => [
                'title'       => 'Post page',
                'description' => 'Name of the blog post page file.',
                'type'        => 'dropdown',
                'default'     => 'blog/post',
            ],
            'exceptPost'    => [
                'title'       => 'Except post',
                'description' => 'Enter ID/URL or variable with post ID/URL you want to exclude.',
                'type'        => 'string',
                'default'     => '',
            ],
        ];
    }

    public function onRun()
    {
        $this->posts = $this->page['latestPosts'] = $this->getLatestPosts();
    }

    /**
     * Returns a list of CMS pages for the 'postPage' property dropdown options.
     *
     * @return array
     */
    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * Returns a limited number of published posts ordered by the published date.
     *
     * @return Collection
     */
    protected function getLatestPosts()
    {
        $exceptPost    = $this->property('exceptPost') ?: null;
        $numberOfPosts = $this->getNumberOfPosts();

        $query = Post::isPublished();

        if ($exceptPost) {
            if (is_numeric($exceptPost)) {
                $query->where('id', '<>', $exceptPost);
            } else {
                $query->where('slug', '<>', $exceptPost);
            }
        }

        $query->orderBy('published_at', 'desc');

        if (!is_null($numberOfPosts)) {
            $query->take($numberOfPosts);
        }

        $posts = $query->get();

        $this->setPageUrlForPosts($posts);

        return $posts;
    }

    /**
     * Returns the number of posts to display.
     *
     * @return int
     */
    protected function getNumberOfPosts()
    {
        $numberOfPosts = $this->property('numberOfPosts');

        if (!is_numeric($numberOfPosts)) {
            return null;
        }

        return $numberOfPosts;
    }

    /**
     * Sets the page url to the blog post page for a collection of posts.
     *
     * @param Collection $posts
     *
     * @return void
     */
    protected function setPageUrlForPosts(Collection $posts)
    {
        $posts->each(function (Post $post) {
            $post->setUrl($this->property('postPage'), $this->controller);
        });
    }
}
