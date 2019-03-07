<?php

namespace DamianLewis\Blog;

use Backend;
use DamianLewis\Blog\Components\Categories;
use DamianLewis\Blog\Components\LatestPosts;
use DamianLewis\Blog\Components\Post;
use DamianLewis\Blog\Components\Posts;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'Blog',
            'description' => 'Provides blogging features.',
            'author'      => 'Damian Lewis',
            'icon'        => 'icon-newspaper-o',
        ];
    }

    public function registerNavigation(): array
    {
        return [
            'blog' => [
                'label'       => 'News',
                'url'         => Backend::url('damianlewis/blog/posts'),
                'icon'        => 'icon-newspaper-o',
                'iconSvg'     => 'plugins/damianlewis/blog/assets/images/text-lines.svg',
                'permissions' => ['damianlewis.blog.*'],
                'order'       => 502,
            ],
        ];
    }

    public function registerPermissions(): array
    {
        return [
            'damianlewis.blog.access_post'       => [
                'tab'   => 'Blog',
                'label' => 'Manage the posts',
                'order' => 101,
            ],
            'damianlewis.blog.access_categories' => [
                'tab'   => 'Blog',
                'label' => 'Manage the blog categories',
                'order' => 102,
            ],
            'damianlewis.blog.access_tags'       => [
                'tab'   => 'Blog',
                'label' => 'Manage the blog tags',
                'order' => 103,
            ],
            'damianlewis.blog.access_statuses'   => [
                'tab'   => 'Blog',
                'label' => 'Manage the blog statuses',
                'order' => 104,
            ],
        ];
    }

    public function registerComponents(): array
    {
        return [
            Posts::class       => 'blogPosts',
            Post::class        => 'blogPost',
            Categories::class  => 'blogCategories',
            LatestPosts::class => 'blogLatestPosts',
        ];
    }
}
