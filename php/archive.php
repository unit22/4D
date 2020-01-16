<?php header('refresh: 300'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>ЧД.Галерея</title>
    <link rel="shortcut icon" href="icon.png" type="image/png">

    <!-- Bootstrap --> <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- GALLERY START -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <!-- GALLERY STOP -->

</head>


<body>
<!----------------------------------------------------------------------------------------------------------------->
<!--ВЕРХНЯЯ НАВИГАЦИЯ-->
<!--    <nav class="navbar navbar-inverse navbar-fixed-top">-->
<nav class="navbarmirro">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
            </button>
            <a class="navbar-brand" href="" style="color: #C0C0C0; font-size: 35px">4D</a>
            <?php include 'header.php'?>
        </div>
    </div>
</nav>
<!--ВЕРХНЯЯ НАВИГАЦИЯ-->
<!----------------------------------------------------------------------------------------------------------------->


<!----------------------------------------------------------------------------------------------------------------->
<!--ФУНКЦИЯ удаления изображения-->
<?php
function deleteImage($path)
{
    print_r($path);
    if(file_exists($path)) unlink($path);
    if(file_exists($path) == FALSE) echo $path." файл удален";
}
//<!----------------------------------------------------------------------------------------------------------------->
//<!--ФУНКЦИЯ подсчета количества изображений-->
function countImagesInDate($mastera2, $path1)
{
    $imgQuantity = 0;
    foreach ($mastera2 as $master) {
        if ($master == 'thumbs'){continue;}
        $path2 = $path1 . '/' . $master;
        if (is_dir($path2) && ($master != '.') && ($master != '..')) {
            $files = scandir($path2);

            foreach ($files as $file){
                if (($file != '.') && ($file != '..') && ($file != 'Thumbs.db')){
                    $imgQuantity +=1;
                }
            }
        }
    } return $imgQuantity;
}
//<!----------------------------------------------------------------------------------------------------------------->
//<!--ФУНКЦИЯ создания миниатюры изображения-->
function createThumb($imgName, $imgPath, $thumbPath)
{
    $infile = $imgPath.'/'.$imgName;

    $image = imagecreatefromjpeg($infile);
    $filename = $thumbPath.'/'.$imgName;

    $thumb_width = 300;
    $thumb_height = 300;

    $width = imagesx($image);
    $height = imagesy($image);

    $original_aspect = $width / $height;
    $thumb_aspect = $thumb_width / $thumb_height;

    if ( $original_aspect >= $thumb_aspect )
    {
        // If image is wider than thumbnail (in aspect ratio sense)
        $new_height = $thumb_height;
        $new_width = $width / ($height / $thumb_height);
    }
    else
    {
        // If the thumbnail is wider than the image
        $new_width = $thumb_width;
        $new_height = $height / ($width / $thumb_width);
    }

    $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );

// Resize and crop
    imagecopyresampled($thumb,
        $image,
        0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
        0 - ($new_height - $thumb_height) / 2, // Center the image vertically
        0, 0,
        $new_width, $new_height,
        $width, $height);
    imagejpeg($thumb, $filename, 80);

}
?>
<!----------------------------------------------------------------------------------------------------------------->



<!----------------------------------------------------------------------------------------------------------------->
<table width="100%">
    <tr><td align="center"><br></td></tr>
    <tr>
        <td align="center">

            <table width="70%">
                <!--                <div class='container gallery'>-->-->
                <tr>
                    <td align="center">
                        <div id="wrap">

                            <!-- ГАЛЕРЕЯ -->
                            <?php

                            $dir    = 'gallery/masters';
                            $todayfolder = $dir.'/'.date("Y.m.d");### Создать папку Сегодня
                            if (!file_exists($todayfolder)) {mkdir($todayfolder, 0777, true);}

                            if (array_key_exists('delete_file', $_POST)) {
                                $filename = $_POST['delete_file'];
                                if (file_exists($filename)) {
                                    $path2finished = $todayfolder.'/'.'ЧД ЗАВЕРШЕНО';
                                    $imgname = end(explode("/", $filename));

                                    copy($filename, $path2finished.'/'.$imgname);
                                    unlink($filename);
                                }
                            }
                            $counter = 0;


                            $dates2 = scandir($dir);
                            $dates2 = array_diff($dates2, array('.', '..', 'Thumbs.db'));# удалить папки с именами '.' и '..' и 'Thumbs.db'

                            ### Не получается сделать обратную сортировку, пхп спотыкается
                            $dates = rsort($dates2);

                            #По каждой дате
                            foreach ($dates2 as $date){


                                #Создать каталоги с фамилиями мастеров в папке Сегодня
                                $mastersArray = array('_ТКО_НО_', 'Морозов ДА', 'Самарин СИ', 'Прибытков СН', 'Смирнов МГ', 'Строганцев МА', 'Князев ДА', 'ЧД ЗАВЕРШЕНО', 'thumbs');
                                foreach ($mastersArray as $ma){
                                    if (!is_dir('gallery/masters/'.$date.'/'.$ma)) {
                                        mkdir('gallery/masters/'.$date.'/'.$ma, 0777);
                                    }}


                                $addresses = array();
                                $path = $dir.'/'.$date;
                                $name = $dir.'/'.$date.'/Список от '.$date.'.txt';
                                if (is_dir($path) && ($date != '.') && ($date != '..')){
                                    $mastera = scandir($path);

                                    echo "\r\n<div class='container gallery'>";
                                    if (countImagesInDate($mastera, $path) == 0){
                                        continue;
                                    } else {
                                        echo '<table style="width:100%; background-color:#98FB98; margin-top: 50px;"><tbody><tr><td align="left" style="padding-left: 10px;"><h1>'.'&nbsp;'.$date.' ('.countImagesInDate($mastera, $path).')'.'</h1></td><td align="right" style="padding-right: 10px;"><a href="'.$name.'" download="" style="font-weight: normal; font-size: 1.3em">Скачать списком</a></td></tr></tbody></table>';
                                    }
                                    echo '</div>';
                                    #По каждому мастеру
                                    foreach ($mastera as $master){
                                        $path2 = $path.'/'.$master;
                                        if (is_dir($path2) && ($master != '.') && ($master != '..')){
                                            $files = scandir($path2);
                                            $files = array_diff($files, array('.', '..', '...Thumbs.db', 'Thumbs.db'));# удалить папки с именами . и ..
                                            $len = count($files);
                                            if ($len==0 or $master=='thumbs' or $master=='THUMBS'){
                                                #rmdir($path2);
                                                continue;
                                            }
                                            echo "<br><br>\r\n\r\n<div class='container gallery'>\r\n";
                                            echo '<h2 align="left" class="mastermirro"><small> '.$master.'</small></h2>';
                                            $forth=0;
                                            # По каждому изображению
                                            foreach ($files as $file){
//                                                print_r($file);
                                                if (($file != '.') && ($file != '..') && ($file != 'Thumbs.db')&& ($file != '...Thumbs.db')){
                                                    $thumbnailpath = $path.'/thumbs/'.$file;
                                                    if(!is_file($thumbnailpath)){createThumb($file, $path2, $path.'/'.'thumbs'); }# создать миниатюры в папке thumbs
                                                    if ($forth == 4){
                                                        echo '<br><div style="clear: both;"></div><br>';
                                                        $forth=0;}
                                                    $nojpg = str_replace('.jpeg', '', $file);
                                                    if ($master!='thumbs' and $master!='THUMBS' and $master!='ЧД ЗАВЕРШЕНО'){array_push($addresses, $master.' - '.$nojpg);}
                                                    $fullPath = $path2.'/'.$file;
                                                    echo '<div class="col-md-3 col-sm-6 col-xs-6" style="float: left">';
                                                    if ($master == 'ЧД ЗАВЕРШЕНО'){
                                                        echo '<a href ="'.$fullPath.'" target="_blank" class="finishedd2" data-fancybox="gallery"><img src="'.$thumbnailpath.'" alt="none" class="img-thumbnail img-responsive" width="300px"></a><span class="addressmirrogray">'.$nojpg.'</span><br>';
                                                    } else{
                                                        echo '<a href ="'.$fullPath.'" target="_blank" class="detail" data-fancybox="gallery"><img src="'.$thumbnailpath.'" alt="none" class="img-thumbnail img-responsive" width="300px"></a><span class="addressmirrored">'.$nojpg.'</span><br>';
                                                        echo '<form method="post">';
                                                        echo '<input type="hidden" value="'.$fullPath.'" name="delete_file" />';
                                                        echo '<input type="submit" value="Завершено" />';
                                                    }
                                                    echo '</form>';
                                                    $forth+=1;
                                                    echo '</div>';
                                                }}
                                            echo '</div>';
                                            echo '</div>';}
                                    }

                                    #$addresses2 = sort($addresses);
                                    $fh = fopen($name, 'wb+');# создать файл в папке даты
                                    sort($addresses);
                                    foreach ($addresses as $addr){
                                        fwrite($fh, "$addr");
                                        fwrite($fh, "\r\n");}
                                }
                            }
                            echo '<div id="push"> </div>';
                            echo '<div id="footer"><p class="text-muted" align="center">Нижний Новгород <br>'.date("d.m.Y").'</p></div>';
                            ?>
                        </div>
                        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                        <!-- Latest compiled and minified JavaScript -->
                        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>

</body>
</html>
