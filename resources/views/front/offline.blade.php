<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakım Modu</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            background-color: #24252A; /* Yeni arka plan rengi */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .maintenance-animation {
            font-size: 32px; /* Metin boyutunu büyüttüm */
            color: #FF5733; /* Metin rengi */
            text-align: center; /* Metni ortala */
            animation: rotate 5s infinite linear, scale 5s infinite alternate;
        }
        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        @keyframes scale {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(1.1);
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-animation">🔧 Web Sitesi Bakımda! 🔧</div>
</body>
</html>
