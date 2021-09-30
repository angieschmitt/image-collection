<?php

require('./assets/php/database-class.php');

$tags = $db->select('SELECT * FROM tags ORDER BY `tag` ASC', array(), array());
foreach ($tags as $tag) {
    $tagArr[$tag->id] = $tag->tag;
}

$images = $db->select('SELECT i.* FROM images i WHERE i.deleted = ? ORDER BY RAND() LIMIT 50', array(1), array('%d'));

if ( !empty($image[0]->tagList) ) {
    $tagList = explode(',', $image[0]->tagList);
} else {
    $tagList = '';
}

foreach ($tags as $tag) {
    $tagArrj[] = $tag->tag."||".$tag->id;
}
$jsonTags = json_encode($tagArrj);

$directory = './storage';
?>
<html>
    <head>
        <title>Image Labeler</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="./js/jQuery.js"></script>
        <script type="text/javascript" src="./js/scripts.js"></script>
        <link rel="stylesheet" href="./assets/css/style.css" />
    </head>
    <body>
        <div id="navigation">
            <a href="/projects/images/">Home</a>
            <a href="./tagless.php">Tagless</a>
            <a class="upload">Upload Image</a>
        </div>
        <div id="uploadBox">
            <form action="/assets/php/upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload Image" name="submit">
            </form>
        </div>
        <div id="tagList" class="container">
            <script type="text/javascript">var tags = <?=$jsonTags?>;</script>
        </div>
        <div id="images" class="container">
        <?php
        foreach ( $images as $image ) :
            ?>
            <div class="item">
                <a><img src="<?=$directory."/".$image->filename?>" /></a>
                <div class="data">
                    Created on: <?=date ("F d Y H:i:s", strtotime($image->createdate)); ?><br/>
                    ID: <?=$image->id?>
                </div>
            </div>
            <?php
            $a++;
        endforeach;
        ?>
        </div>
    </body>
</html
