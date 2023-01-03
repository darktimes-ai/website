<?php
    date_default_timezone_set('America/Chicago');
    require_once('header.php');
    require_once('core/posts.php');

    $postsPerPage = 5;
    $pageNumber = (isset($_GET['page'])) ? intval($_GET['page']) : 0;
    $startIndex = ($pageNumber * $postsPerPage);

    $posts = posts::getAllFolderPosts('editing');
    $totalPostCount = count($posts);

    if ($totalPostCount == 0)
    {
        require_once('firstpost.php');
    }
    else
    {
        // Just get the last 10 anyway
        if ($startIndex > count($posts))
            $startIndex = count($posts) - $postsPerPage;

        if ($startIndex < 0)
            $startIndex = 0;

        // Pagify, if the page argument exists...
        $currentPosts = array_slice($posts, $startIndex, $postsPerPage);

        echo('<div id="content" class="content">');
        foreach ($currentPosts as $post)
        {
            // Nothing to do?
            if ($post->markdown_content === null)
                continue;

            $authorText = $post->metadata_content->{'author'};
            $createdTime = $post->metadata_content->{'created'};
            $modifiedTime = $post->metadata_content->{'modified'};

            $createdText = date("l, F jS Y H:i:s T", $createdTime);
            $modifiedText = date("l, F jS Y H:i:s T", $modifiedTime);
            echo('<div id="post-' . $createdTime . '" class="blogpost" align="left">');
            echo $post->markdown_content;
            echo('<div id="post-data-' . $createdTime . '" class="blogdata" align="left">');
            echo('Posted by <b>' . $authorText . '</b><br />');
            echo('Created: ' . $createdText . '<br />Last Modified: ' . $modifiedText);
            echo('</div>');
            echo('</div>');
        }
        echo('</div>');

        require_once('page_selector.php');
    }
    require_once('footer.php');
?>