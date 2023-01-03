<?php
    function get_video_path()
    {
        $dir = new DirectoryIterator(dirname(__FILE__) . '/downloads/');
        $videos = array();
        foreach ($dir as $fileinfo)
        {
            if (!$fileinfo->isDot())
            {
                // var_dump($fileinfo->getFilename());
                if (str_contains($fileinfo->getFilename(), '.mp4'))
                {
                    array_push($videos, $fileinfo->getFilename());
                }
            }
        }
        $randomKey = array_rand($videos, 1);
        return 'downloads/' . $videos[$randomKey];
    }

    $VIDEOPATH = get_video_path();

    require_once('blog/header.php');
?>
<div id="content" class="content" align="center">
    <video width="1280" height="720" controls>
        <source src="<?php echo($VIDEOPATH); ?>" type="video/mp4">
    </video>
    <a href="https://discord.gg/PYhGaYEeAr" target="_blank">discord</a> &bull; <a href="blog/" target="_blank">blog</a>
</div>
<?php
    require_once('blog/footer.php');
?>