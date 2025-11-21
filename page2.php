<?php
$start_time = microtime(true);

$x = "Сторінка 2";
$y = "Рудих Кароліна ІС-32";

$texts = [
    "Aliquam imperdiet justo dui, iaculis convallis mauris porta ac. Quisque quis nisl eget nulla scelerisque egestas vitae sit amet leo. <br>Donec mollis urna enim, sed pulvinar ipsum vestibulum eu. Vestibulum dictum maximus purus, id accumsan justo posuere tristique. Etiam sodales mollis purus eget vulputate. Fusce id congue mauris, ut accumsan purus. Duis nec ipsum, et consequat ante.",
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed faucibus, tellus eleifend egestas ultricies, tortor nunc ornare justo, eu bibendum libero mi quis purus. In hac habitasse platea dictumst. Suspendisse potenti.",
    "Aenean a commodo elit. Integer non auctor dui. Vivamus bibendum, nulla sed ornare sodales, ex justo varius purus, nec porttitor tortor odio a magna.",
    "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. <br><br>Nulla feugiat, lacus vitae auctor consectetur, arcu lorem tristique urna, efficitur semper lorem nulla non lorem. <br><br>Nunc et nibh euismod arcu feugiat vestibulum.",
    "Praesent tempus id ante at venenatis. Proin ultrices, tortor sollicitudin vulputate pulvinar, tortor ante volutpat dui, et imperdiet risus eros quis orci. <br>Vestibulum magna nisi, ullamcorper vel ex eu, porttitor varius tortor. Sed porttitor consectetur dolor.",
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
    <div class="block block1" id="block1">
        <p><?= $texts[0] ?></p>
        <div class="x-box"><?= $x ?></div>
    </div>

    <div class="block block2" id="block2">
        <?php include("menu.php"); ?>
    </div>

    <div class="block block3" id="block3">
        <p><?= $texts[1] ?></p>
    </div>

    <div class="block block4" id="block4">
        <p><?= $texts[3] ?></p>
    </div>    
    
    <div class="block block5" id="block5">
        <p><?= $texts[2] ?></p>
    </div>

    <div class="block block6" id="block6">
        <p><?= $texts[4] ?></p>
        <div class="y-box"><?= $y ?></div>
    </div>
</div>

<div class="time-info" id="time-info"></div>

<script src="script.js"></script>
<script>
    window.__serverGenMs = <?php echo round((microtime(true) - $start_time) * 1000, 1); ?>;
</script>
</body>
</html>
