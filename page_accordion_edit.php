<?php include 'db.php'; 

$x = "Сторінка 1 (лаб4)";
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
    <title>Редактор акордеону</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="block block1">
        <div><h1>Редактор</h1></div>
        <div class="x-box"><?= $x ?></div>
    </div>

    <div class="block block2">
        <?php include("menu.php"); ?>
    </div>

    <div class="block block3" style="overflow-y: auto;">
        <h3>Створення акордеону</h3>
        <div id="editor-list"></div>
        
        <div style="margin-top: 15px;">
            <button onclick="addItem()" class="btn btn-add">+ Додати елемент</button>
            <button onclick="saveData()" class="btn btn-save">Зберегти на сервер</button>
        </div>
        <p id="message" style="height: 20px; color: green; font-weight: bold;"></p>
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
    function addItem(title = '', content = '') {
        const div = document.createElement('div');
        div.className = 'editor-item';
        div.innerHTML = `
            <input type="text" placeholder="Заголовок" class="inp-title" value="${title}">
            <textarea rows="3" placeholder="Вміст вкладки" class="inp-content">${content}</textarea>
            <button onclick="this.parentElement.remove()" class="btn btn-del">Видалити</button>
        `;
        document.getElementById('editor-list').appendChild(div);
    }

    function saveData() {
        const items = [];
        document.querySelectorAll('.editor-item').forEach(el => {
            items.push({
                title: el.querySelector('.inp-title').value,
                content: el.querySelector('.inp-content').value
            });
        });

        fetch('api_accordion.php?action=save', {
            method: 'POST',
            body: JSON.stringify({ data: items })
        })
        .then(r => r.json())
        .then(res => {
            const msg = document.getElementById('message');
            if(res.success) {
                msg.innerText = "Збережено успішно! " + new Date().toLocaleTimeString();
                setTimeout(() => msg.innerText = '', 3000);
            } else {
                msg.innerText = "Помилка збереження";
                msg.style.color = "red";
            }
        });
    }
    
    addItem();
</script>
</body>
</html>