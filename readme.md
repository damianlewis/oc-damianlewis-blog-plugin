# Blog Plugin
Provides blogging features for OctoberCMS.

### Post list
The `blogPosts` component can be used to display a paginated list of posts. The default value is **{{ :page }}** to obtain the value from the route parameter `:page`.
- **pageNumber** - This value is used to determine what page the user is on.
- **categoryFilter** - A category slug to filter the posts by. If left blank, all posts are displayed.
- **tagFilter** - A tag slug to filter the posts by. If left blank, all posts are displayed.
- **postsPerPage** - How many posts to display on a single page. The default value is 6.
- **noPostsMessage** - Message to display in the empty post list. The default is `No posts found`.
- **postPage** - The name of the blog post details page. The default is `blog/post`.
- **sortOrder** - The column used for the sort order of the posts. The default is `published_at`.
- **orderDirection** - The direction used for the sort order. The default is `desc`.
- **exceptPost** - Ignore a single post by either its slug or ID.

The blogPosts component injects the following variables into the page where it's used:
- **pageParam** - The property used for the page number. The value of the component's `pageNumber` property.
- **posts** - A list of blog posts loaded from the database.
- **noPostsMessage** - The value of the component's `noPostsMessage` property.

### Post
The `blogPost` component can be used to display a single post.
- **slug** - The value used for retrieving a post by its slug. The default value is **{{ :slug }}** to obtain the value from the route parameter `:slug`.

The blogPost component injects the following variables into the page where it's used:
- **post** - The blog post model loaded from the database.

### Category list
The `blogCategories` component can be used to display a list of blog categories. It's useful for sidebars etc, when you want to list all the categories.
- **categoryPage** - The name of the category lists page. The default is `blog/category`.
- **displayEmpty** - Determines if empty categories should be displayed. The default value is false.

The blogCategories component injects the following variables into the page where it's used:
- **categories** - A list of blog categories loaded from the database.
- **currentCategorySlug** - The slug of the current category. This property is used for marking the current category in the category list.

### Latest Posts
The `blogLatestPosts` component can be used to display a static list of latest posts. It's useful for sidebars etc, when you want the same posts displayed across multiple pages when using pagination.
- **numberOfPosts** - How many posts to display. The default value is 5.
- **postPage** - The name of the blog post details page. The default is `blog/post`.
- **exceptPost** - Ignore a single post by either its slug or ID.

The blogLatestPosts component injects the following variables into the page where it's used:
- **latestPosts** - A list of latest blog posts loaded from the database.
