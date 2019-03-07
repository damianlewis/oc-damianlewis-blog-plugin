<?php

namespace DamianLewis\Blog\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Illuminate\Pagination\LengthAwarePaginator;
use DamianLewis\Blog\Models\Category;
use DamianLewis\Blog\Models\Post;
use DamianLewis\Blog\Models\Tag;

class Posts extends ComponentBase
{

    /**
     * Parameter to use for the page number.
     *
     * @var string
     */
    public $pageParam;

    /**
     * A collection of posts to display.
     *
     * @var LengthAwarePaginator
     */
    public $posts;

    /**
     * Message to display when there are no posts.
     *
     * @var string
     */
    public $noPostsMessage;

    public function componentDetails(): array
    {
        return [
            'name'        => 'Posts List',
            'description' => 'Displays a list of blog posts on the page.',
        ];
    }

    public function defineProperties(): array
    {
        return [
            'pageNumber'     => [
                'title'       => 'Page number',
                'description' => 'This value is used to determine what page the user is on.',
                'type'        => 'string',
                'default'     => '{{ :page }}',
            ],
            'categoryFilter' => [
                'title'       => 'Category filter',
                'description' => 'Enter a category slug or URL parameter to filter the posts by. Leave empty to show all posts.',
                'type'        => 'string',
                'default'     => '',
            ],
            'tagFilter'      => [
                'title'       => 'Tag filter',
                'description' => 'Enter a tag slug or URL parameter to filter the posts by. Leave empty to show all posts.',
                'type'        => 'string',
                'default'     => '',
            ],
            'postsPerPage'   => [
                'title'             => 'Posts per page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format of the posts per page value',
                'default'           => '6',
            ],
            'noPostsMessage' => [
                'title'             => 'No posts message',
                'description'       => 'Message to display in the posts list when there are no posts.',
                'type'              => 'string',
                'default'           => 'No posts found',
                'showExternalParam' => false,
            ],
            'postPage'       => [
                'title'       => 'Post page',
                'description' => 'Name of the post page file for the post links.',
                'type'        => 'dropdown',
                'default'     => 'blog/post',
            ],
            'sortOrder'      => [
                'title'       => 'Sort order',
                'description' => 'The post attribute to order the list by.',
                'type'        => 'dropdown',
                'options'     => [
                    'title'        => 'Title',
                    'published_at' => 'Published Date',
                ],
                'default'     => 'published_at',
            ],
            'orderDirection' => [
                'title'       => 'Order direction',
                'description' => 'The order to list the posts.',
                'type'        => 'dropdown',
                'options'     => [
                    'asc'  => 'Ascending',
                    'desc' => 'Descending',
                ],
                'default'     => 'desc',
            ],
            'exceptPost'     => [
                'title'       => 'Except post',
                'description' => 'Enter ID/URL or variable with post ID/URL you want to exclude.',
                'type'        => 'string',
                'default'     => '',
            ],
        ];
    }

    public function onRun()
    {
        $this->pageParam      = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->noPostsMessage = $this->page['noPostsMessage'] = $this->property('noPostsMessage');
        $this->posts          = $this->page['posts'] = $this->getPublishedPosts();
    }

    /**
     * Returns a list of CMS pages for the 'postPage' property dropdown options.
     *
     * @return array
     */
    public function getPostPageOptions(): array
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * Returns a list of CMS pages for the 'categoryPage' property dropdown options.
     *
     * @return array
     */
    public function getCategoryPageOptions(): array
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * Returns an ordered collection of published posts.
     *
     * @return LengthAwarePaginator
     */
    protected function getPublishedPosts(): LengthAwarePaginator
    {
        $sortOrder      = $this->property('sortOrder');
        $orderDirection = $this->property('orderDirection');
        $postsPerPage   = $this->property('postsPerPage');
        $pageNumber     = $this->property('pageNumber');
        $category       = $this->getCategoryBySlug($this->property('categoryFilter'));
        $tag            = $this->getTagBySlug($this->property('tagFilter'));
        $exceptPost     = $this->property('exceptPost') ?: null;

        $query = Post::isPublished();

        if ($category) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('id', $category->id);
            });
        }

        if ($tag) {
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('tag_id', $tag->id);
            });
        }

        if ($exceptPost) {
            if (is_numeric($exceptPost)) {
                $query->where('id', '<>', $exceptPost);
            } else {
                $query->where('slug', '<>', $exceptPost);
            }
        }

        $posts = $query->orderBy($sortOrder, $orderDirection)->paginate($postsPerPage, $pageNumber);

        $this->setPageUrlForPosts($posts);

        return $posts;
    }

    /**
     * Returns a post category by the given slug.
     *
     * @param string $slug
     *
     * @return Category|null
     */
    protected function getCategoryBySlug(string $slug)
    {
        if (!$slug) {
            return null;
        }

        $category = Category::where('slug', $slug)->first();

        return $category ?: null;
    }

    /**
     * Returns a post tag by the given slug.
     *
     * @param string $slug
     *
     * @return Tag|null
     */
    protected function getTagBySlug(string $slug)
    {
        if (!$slug) {
            return null;
        }

        $tag = Tag::where('slug', $slug)->first();

        return $tag ?: null;
    }

    /**
     * Sets the page url for the a collection of posts.
     *
     * @param LengthAwarePaginator $posts
     *
     * @return void
     */
    protected function setPageUrlForPosts(LengthAwarePaginator $posts)
    {
        $posts->each(function (Post $post) {
            $post->setUrl($this->property('postPage'), $this->controller);
        });
    }
}
