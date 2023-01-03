<?php
    // $postsPerPage = 5;
    // $pageNumber = (isset($_GET['page'])) ? intval($_GET['page']) : 0;
    // $pageMod = count($posts) % $postsPerPage;
    $pageCount = $totalPostCount / $postsPerPage; // +1? Hmm...
?>

<div align="center" class="page_selector">
<?php
    for($i = 0; $i < $pageCount; ++$i)
    {
        if ($i != $pageNumber)
            echo('<a href="?page=' . $i . '">' . $i . '</a> ');
        else
            echo(strval($i) . ' ');
    }
?>
</div>