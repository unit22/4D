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

<table width="100%">
    <tr><td align="center"><br></td></tr>
    <tr>
        <td align="center">
            <table width="70%">
                <tr>
                    <td align="left">
                        <br><br><br>
                        <div class="outer-cell mdl-cell mdl-cell--12-col mdl-shadow--2dp container gallery">
                            <p class="mdl-typography--title">Списки автомобилей</p>
                            <hr>
                            <p>1. РЭП <a href='/downloads/РЭП2.ini' download="">скачать</a>
                            <p>2. Чистый двор <a href='/downloads/ЧД-НН.ini' download="">скачать</a>
                            <p>3. Фарбе <a href='/downloads/Фарбе.ini' download="">скачать</a></p>
                            <br>
                            <p><strong>Сохранить в папку:</strong><br>C:\AutoGRAPH\CarsList</strong></p>
                        </div>

                        <br><br>

                        <div class="outer-cell mdl-cell mdl-cell--12-col mdl-shadow--2dp container gallery">
                            <p class="mdl-typography--title">Контрольные точки</p>
                            <hr>
                            <p>Общий <a href='/downloads/mi.chp' download="">скачать</a></p>
                            <br>
                            <p><strong>Сохранить в папку:</strong><br>C:\AutoGRAPH\Points</strong></p>
                        </div>

                        <br><br>

                        <div class="outer-cell mdl-cell mdl-cell--12-col mdl-shadow--2dp container gallery">
                            <p class="mdl-typography--title">Служебные файлы atg</p>
                            <hr>
                            <details>
                                <summary>Развернуть</summary>
                                <div class="column">
                                    <?php
                                    $path = 'downloads/DBF';
                                    $files = scandir($path);
                                    $files = array_diff($files, array('.', '..', 'Thumbs.db'));# удалить папки с именами '.' и '..' и 'Thumbs.db'
                                    foreach ($files as $file){
                                        $fullpath = $path.'/'.$file;
                                        echo '<a href="'.$fullpath.'" download=""><code>'.$file .'</code></a><br>';}
                                    ?>
                                </div>
                            </details>
                            <br>
                            <p><strong>Сохранить в папку:</strong><br>C:\AutoGRAPH\DBF</strong></p>
                        </div>

                        <br><br>

                        <div class="outer-cell mdl-cell mdl-cell--12-col mdl-shadow--2dp container gallery">
                            <p class="mdl-typography--title">Дополнительные обработки</p>
                            <hr>
                            <p>1. <a href='#' download="">скачать</a></p>
                            <p>2. <a href='#' download="">скачать</a></p>
                            <p>3. <a href='#' download="">скачать</a></p>
                            <p>4. <a href='#' download="">скачать</a></p>
                            <br>
                        </div>

                        <br><br>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


</body>
</html>
