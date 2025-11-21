<?php include 'db.php'; 

$x = "Сторінка 2 (лаб4)";
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
    <title>Перегляд акордеону</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
     <div class="block block1">
        <div><h1>Перегляд</h1></div>
        <div class="x-box"><?= $x ?></div>
    </div>

    <div class="block block2">
        <?php include("menu.php"); ?>
    </div>

    <div class="block block3" style="overflow-y: auto;">
        <h3>Результат:</h3>
        <div id="accordion-container" class="acc-wrapper">
            <div style="padding:10px">Очікування даних...</div>
        </div>
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
    let currentHash = ''; 

    function loadData() {
        fetch('api_accordion.php?action=load')
            .then(r => r.json())
            .then(res => {
                if (res.hash !== currentHash) {
                    currentHash = res.hash;
                    renderAccordion(res.data);
                    console.log('Дані оновлено:', new Date().toLocaleTimeString());
                }
            })
            .catch(err => console.error(err));
    }

    function renderAccordion(items) {
        const container = document.getElementById('accordion-container');
        container.innerHTML = '';
        
        if (!items || items.length === 0) {
            container.innerHTML = '<div style="padding:15px">Немає елементів для відображення</div>';
            return;
        }

        items.forEach(item => {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'acc-item';

            const btn = document.createElement('button');
            btn.className = 'acc-btn';
            btn.innerText = item.title || '(Без назви)';
            
            const contentDiv = document.createElement('div');
            contentDiv.className = 'acc-content';
            contentDiv.innerHTML = `<p>${item.content}</p>`;

            btn.addEventListener('click', function() {
                this.classList.toggle('active');
                const panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                    panel.style.paddingTop = null;
                    panel.style.paddingBottom = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                    panel.style.paddingTop = "15px";
                    panel.style.paddingBottom = "15px";
                }
            });

            itemDiv.appendChild(btn);
            itemDiv.appendChild(contentDiv);
            container.appendChild(itemDiv);
        });
    }

    loadData();
    
    setInterval(loadData, 2000);
</script>
</body>
</html>