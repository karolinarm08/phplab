<?php
$start_time = microtime(true);

$x = "Сторінка 5";
$y = "Рудих Кароліна ІС-32";

$texts = [
    "Quisque viverra purus sit amet eros pretium efficitur. Aenean id gravida nunc, ac facilisis nulla. In hac habitasse platea dictumst. Proin elementum sapien non arcu mattis euismod. In mattis dapibus mauris, eu lobortis urna sagittis in.",
    "Praesent tempus id ante at venenatis. Proin ultrices, tortor sollicitudin vulputate pulvinar, tortor ante volutpat dui, et imperdiet risus eros quis orci. <br>Vestibulum magna nisi, ullamcorper vel ex eu, porttitor varius tortor. Sed porttitor consectetur dolor.",
    "Aenean a commodo elit. Integer non auctor dui. Vivamus bibendum, nulla sed ornare sodales, ex justo varius purus, nec porttitor tortor odio a magna.",
    "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. <br><br>Nulla feugiat, lacus vitae auctor consectetur, arcu lorem tristique urna, efficitur semper lorem nulla non lorem. <br><br>Nunc et nibh euismod arcu feugiat vestibulum.",
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed faucibus, tellus eleifend egestas ultricies, tortor nunc ornare justo, eu bibendum libero mi quis purus. In hac habitasse platea dictumst. Suspendisse potenti.",
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
