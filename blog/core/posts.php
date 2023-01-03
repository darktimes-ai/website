<?php
    require_once(dirname(__FILE__) . '/parsedown.php');
    require_once(dirname(__FILE__) . '/emoji.php');

    class post
    {
        var $markdown_file;
        var $markdown_path;
        var $markdown_content;

        var $metadata_file;
        var $metadata_path;
        var $metadata_content;
    };

    class posts
    {
        static function sortFile($a, $b)
        {
            return intval($b->metadata_content->{'created'}) - intval($a->metadata_content->{'created'});
        }

        static function getAllFolderPosts($folderName)
        {
            $parsedown = new Parsedown();
            $coredir = dirname(__FILE__);
            $datapath = $coredir . '/../' . $folderName . '/';
            $dir = new DirectoryIterator($datapath);
            $posts = array();
            foreach ($dir as $fileinfo)
            {
                if (!$fileinfo->isFile())
                    continue;

                $filetime = $fileinfo->getMTime();
                $filename = $fileinfo->getFilename();

                // Markdown only
                if (!str_contains($filename, '.md'))
                    continue;

                $newPost = new post;
                $newPost->markdown_file = $filename;
                $newPost->markdown_path = $datapath . $newPost->markdown_file;
                $newPost->markdown_content = null;

                $newPost->metadata_file = basename($newPost->markdown_path, ".md") . ".json";
                $newPost->metadata_path = $datapath . $newPost->metadata_file;
                $newPost->metadata_content = null;

                if (file_exists($newPost->metadata_path))
                {
                    $metac = file_get_contents($newPost->metadata_path);
                    if ($metac !== false && $metac !== null)
                    {
                        $newPost->metadata_content = json_decode($metac);
                    }
                }
                else
                {
                    continue;
                }

                if (file_exists($newPost->markdown_path))
                {
                    $postc = file_get_contents($newPost->markdown_path);
                    if ($postc !== false && $postc !== null)
                    {
                        $newPost->markdown_content = emoji::enhanced($parsedown->text($postc)); // emoji::classic($parsedown->text($postc));
                    }
                }
                else
                {
                    continue;
                }

                array_push($posts, $newPost);
            }
            usort($posts, "posts::sortFile");
            return $posts;
        }

        static function getFolderPosts($folderName, $timeStart, $timeEnd, $maximum = 10)
        {
            $res = array();
            $posts = getAllFolderPosts($folderName);
            foreach ($posts as $post)
            {
                if ($post->metadata_content->{'created'} > $timeStart && $post->metadata_content->{'created'} <= $timeEnd)
                {
                    array_push($res, $post);

                    if (count($res) >= $maximum)
                        break;
                }
            }
            return $res;
        }

        static function getProductionPosts($timeStart, $timeEnd)
        {
            return posts::getFolderPosts('prod', $timeStart, $timeEnd);
        }

        static function getEditingPosts($timeStart, $timeEnd)
        {
            return posts::getFolderPosts('editing', $timeStart, $timeEnd);
        }

        static function getAllEditingPosts()
        {
            return posts::getAllFolderPosts('editing');
        }

        static function getAllProductionPosts()
        {
            return posts::getAllFolderPosts('prod');
        }
    };
?>