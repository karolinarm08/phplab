<?php
$menu = [
    "index.php" => "Сторінка 1",
    "page2.php" => "Сторінка 2",
    "page3.php" => "Сторінка 3",
    "page4.php" => "Сторінка 4",
    "page5.php" => "Сторінка 5",
    "page_accordion_edit.php" => "Сторінка 1 (лаб4)",
    "page_accordion_view.php" => "Сторінка 2 (лаб4)",
];
?>

<ul class="menu">
<?php foreach($menu as $link=>$name): ?>
    <li><a href="<?php echo $link; ?>"><?php echo $name; ?></a></li>
<?php endforeach; ?>
</ul>