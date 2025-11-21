<?php
$start_time = microtime(true);
require_once 'db.php'; 

$db_start_time = microtime(true);

$page_id = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$stmt = $pdo->prepare("SELECT element_key, content_data FROM content WHERE page_id = ?");
$stmt->execute([$page_id]);
$saved_content = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); 
$db_time_ms = (microtime(true) - $db_start_time) * 1000;

$x = "Сторінка 1";
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

<script>
    window.__savedContent = <?= json_encode($saved_content, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    window.__serverGenMs = <?= round((microtime(true) - $start_time) * 1000, 1); ?>;
    window.__dbTimeMs = <?= round($db_time_ms, 1); ?>;
</script>
<script src="script.js"></script>
</body>
</html>