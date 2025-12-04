<?php
$start_time = microtime(true);
$x = "Сторінка 1 (лаб5)";
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
    <style>
        .work-layer {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 700px; 
            height: 500px;
            background-color: #ffffff;
            border: 2px solid #333;
            z-index: 9999;
            display: none; 
            box-shadow: 0 0 50px rgba(0,0,0,0.5);
            flex-direction: column;
        }

        .controls {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            background: #f1f1f1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .anim-area {
            flex-grow: 1;
            margin: 5px;
            border: 5px solid red; 
            background-color: #fafafa;
            position: relative;
            overflow: hidden; 
        }

        .anim-texture {
            width: 100%;
            height: 100%;
            opacity: 0.2;
            background-image: repeating-linear-gradient(45deg, #ccc 25%, transparent 25%, transparent 75%, #ccc 75%, #ccc), repeating-linear-gradient(45deg, #ccc 25%, #f0f0f0 25%, #f0f0f0 75%, #ccc 75%, #ccc);
            background-position: 0 0, 16px 16px;
            background-size: 32px 32px;
        }

        .ball {
            width: 20px; 
            height: 20px;
            background-color: yellow; 
            border-radius: 50%;
            position: absolute;
            top: 0;
            left: 0;
            display: none;
            border: 1px solid orange;
        }

        .btn-green { background: #28a745; color: white; border: none; padding: 10px 20px; cursor: pointer; font-size: 16px; }
        .btn-red { background: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; margin-left: auto;}
        .btn-ctrl { background: #007bff; color: white; border: none; padding: 5px 15px; cursor: pointer; }

        .report-container { margin-top: 20px; max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: white;}
        .report-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .report-table th, .report-table td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        .log-box { font-family: monospace; font-size: 12px; color: blue; margin-left: 10px;}
    </style>
</head>
<body>
<div class="container">

    <div class="block block1">
        <div class="x-box"><?= $x ?></div>
    </div>

    <div class="block block2">
        <?php include("menu.php"); ?>
    </div>

    <div class="block block3">        
        <button id="btnPlay" class="btn-green">▶ Запустити</button>

        <div id="reportArea" class="report-container">
            <p>Звіт:</p>
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

<div id="workLayer" class="work-layer">
    <div class="controls">
        <button id="btnControl" class="btn-ctrl">Start</button>
        <div id="messageBox" class="log-box">Готове до запуску</div>
        <button id="btnClose" class="btn-red">Close ✖</button>
    </div>
    <div id="animArea" class="anim-area">
        <div class="anim-texture"></div>
        <div id="ball" class="ball"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btnPlay = document.getElementById('btnPlay');
    const workLayer = document.getElementById('workLayer');
    const btnClose = document.getElementById('btnClose');
    const btnControl = document.getElementById('btnControl');
    const ball = document.getElementById('ball');
    const animArea = document.getElementById('animArea');
    const messageBox = document.getElementById('messageBox');
    const reportArea = document.getElementById('reportArea');

    let interval = null;
    let state = 'ready'; 
    let x = 0, y = 0;
    let dx = 3, dy = 3;
    let width = 0, height = 0;
    let eventCount = 0;

    fetch('api_lab5.php?action=clear', { method: 'POST' }).catch(err => console.error("Помилка API:", err));
    localStorage.removeItem('lab5_events');

    btnPlay.addEventListener('click', () => {
        workLayer.style.display = 'flex';
        width = animArea.clientWidth;
        height = animArea.clientHeight;
        resetBall();
    });

    btnClose.addEventListener('click', () => {
        workLayer.style.display = 'none';
        stopAnim();
        showReport();
    });

    btnControl.addEventListener('click', () => {
        if (state === 'ready' || state === 'stopped') {
            startAnim();
        } else if (state === 'moving') {
            stopAnim();
        } else if (state === 'exited') {
            resetBall();
        }
    });

    function startAnim() {
        state = 'moving';
        btnControl.innerText = "Stop";
        ball.style.display = 'block';
        
        logEvent("Start pressed");
        
        if (interval) clearInterval(interval);
        interval = setInterval(move, 20); 
    }

    function stopAnim(isExit = false) {
        if (interval) clearInterval(interval);
        state = isExit ? 'exited' : 'stopped';
        btnControl.innerText = isExit ? "Reload" : "Start";
        if (!isExit) logEvent("Stop pressed");
    }

    function resetBall() {
        x = 0; 
        y = 0;
                
        dx = 2 + Math.random() * 6; 
        dy = 1 + Math.random() * 9;
        
        ball.style.left = x + 'px';
        ball.style.top = y + 'px';
        ball.style.display = 'none';
        
        state = 'ready';
        btnControl.innerText = "Start";
        logEvent("Reset/Reload");
    }

    function move() {
        x += dx;
        y += dy;
        let msg = "";

        if (y <= 0) {
            y = 0; dy = -dy; 
            msg = "Hit Top";
        }
        if (y >= height - 20) { // 20 
            y = height - 20; dy = -dy; 
            msg = "Hit Bottom";
        }
        if (x <= 0) {
            x = 0; dx = -dx; 
            msg = "Hit Left";
        }
        
        if (x > width) {
            logEvent("Exited Right Wall");
            stopAnim(true);
            return;
        }

        ball.style.left = x + 'px';
        ball.style.top = y + 'px';

        if (msg) logEvent(msg);
    }

    function logEvent(text) {
        eventCount++;
        const now = new Date();
        const timeStr = now.toLocaleTimeString() + "." + now.getMilliseconds();
        messageBox.innerText = text; 

        const data = {
            event_id: eventCount,
            client_time: timeStr,
            message: text
        };

        const local = JSON.parse(localStorage.getItem('lab5_events') || '[]');
        local.push(data);
        localStorage.setItem('lab5_events', JSON.stringify(local));

        fetch('api_lab5.php?action=save', {
            method: 'POST',
            body: JSON.stringify(data)
        }).then(r => r.json()).then(res => {
            if(!res.success) console.warn("Помилка збереження:", res);
        }).catch(err => console.error(err));
    }

    function showReport() {
        reportArea.innerHTML = "Завантаження даних з БД";
        
        fetch('api_lab5.php?action=get')
        .then(r => r.json())
        .then(res => {
            const serverData = res.data || [];
            const localData = JSON.parse(localStorage.getItem('lab5_events') || '[]');

            let html = `<h4>Порівняння даних (подій: ${localData.length})</h4>`;
            html += `<div style="display:flex; gap:20px;">`;
            
            html += `<div style="flex:1"><h5>LocalStorage</h5><table class="report-table">`;
            html += `<tr><th>#</th><th>Client Time</th><th>Event</th></tr>`;
            localData.forEach(row => {
                html += `<tr><td>${row.event_id}</td><td>${row.client_time}</td><td>${row.message}</td></tr>`;
            });
            html += `</table></div>`;

            html += `<div style="flex:1"><h5>Server DB</h5><table class="report-table">`;
            html += `<tr><th>#</th><th>Server Time</th><th>Event</th></tr>`;
            serverData.forEach(row => {
                html += `<tr><td>${row.event_id}</td><td>${row.server_time}</td><td>${row.message}</td></tr>`;
            });
            html += `</table></div></div>`;

            reportArea.innerHTML = html;
        });
    }
});
</script>

<script>
    window.__serverGenMs = <?php echo round((microtime(true) - $start_time) * 1000, 1); ?>;
</script>
</body>
</html>