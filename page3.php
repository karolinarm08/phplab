<?php
$start_time = microtime(true);

$x = "Сторінка 3";
$y = "Рудих Кароліна ІС-32";

$texts = [
    "Suspendisse non feugiat odio. Maecenas posuere mauris sit amet felis aliquet venenatis. In erat enim, scelerisque sed convallis eu, sagittis in ex. Aliquam nibh turpis, euismod id convallis sed, rutrum eget diam. Fusce mauris justo, dictum eget elit sit amet, placerat placerat tortor. Nulla eleifend lobortis massa, sed aliquet sapien feugiat et. In vulputate laoreet enim id suscipit. Sed lectus quam, venenatis a sollicitudin a, efficitur et nisl. Maecenas ac mattis lorem. Fusce rhoncus ante nec malesuada aliquet. Quisque feugiat, mi sed imperdiet dapibus, justo felis tristique ex, sit amet varius libero massa vitae eros. Nulla tristique orci quis eleifend vulputate. Morbi augue orci, interdum sit amet magna ac, finibus laoreet magna. Integer aliquet efficitur purus, eget faucibus sem tincidunt vulputate.",
    "<h2>Карта посилань на цікаві сайти</h2>
    <img src='images/map.png' usemap='#buttons' width='600' height='200' alt='Кнопки'>
    <map name='buttons'>
        <area shape='rect' coords='0,0,200,200' href='https://google.com' alt='Google' target='_blank'>
        <area shape='rect' coords='200,0,400,200' href='https://wikipedia.org' alt='Wikipedia' target='_blank'>
        <area shape='rect' coords='400,0,600,200' href='https://php.net' alt='PHP' target='_blank'>
    </map>",
    "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. <br><br>Nulla feugiat, lacus vitae auctor consectetur, arcu lorem tristique urna, efficitur semper lorem nulla non lorem. <br><br>Nunc et nibh euismod arcu feugiat vestibulum.",
    "Praesent tempus id ante at venenatis. Proin ultrices, tortor sollicitudin vulputate pulvinar, tortor ante volutpat dui, et imperdiet risus eros quis orci. <br>Vestibulum magna nisi, ullamcorper vel ex eu, porttitor varius tortor. Sed porttitor consectetur dolor.",
    "Aenean a commodo elit. Integer non auctor dui. Vivamus bibendum, nulla sed ornare sodales, ex justo varius purus, nec porttitor tortor odio a magna.",
];

?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title><?= $x ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <div class="block block1">
        <p><?= $texts[0] ?></p>
        <div class="x-box"><?= $x ?></div>
    </div>

    <div class="block block2">
        <?php include("menu.php"); ?>
    </div>

    <div class="block block3">
        <p><?= $texts[1] ?></p>
    </div>

    <div class="block block4">
        <p><?= $texts[3] ?></p>
    </div>    
    
    <div class="block block5">
        <p><?= $texts[2] ?></p>
    </div>

    <div class="block block6">
        <p><?= $texts[4] ?></p>
        <div class="y-box"><?= $y ?></div>
    </div>

</div>
<script src="script.js"></script>
<script>
    window.__serverGenMs = <?php echo round((microtime(true) - $start_time) * 1000, 1); ?>;
</script>
</body>
</html>
