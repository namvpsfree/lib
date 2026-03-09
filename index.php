<?php
function randomKey()
{
    $key = "ELG-";
    $count = 0;
    $up = range('A', 'Z');
    $low = range('a', 'z');
    $number = range('0', '9');

    while ($count < 11) {
        $rand = rand(0, 2);

        if ($rand == 0) {
            $key .= $up[rand(0, 25)];
        } else if ($rand == 1) {
            $key .= $low[rand(0, 25)];
        } else {
            $key .= $number[rand(0, 9)];
        }

        $count++;
    }

    return $key;
}

$key = "";
$filenames = "Key.json";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['generate'])) {
        $key = randomKey();
        $date = date("Y-m-d");

        $data = [
            "key" => $key,
            "date" => $date,
            "expire" => date("Y-m-d", strtotime("+1 day")),
            "serial" => "",
            "lib" => "https://raw.githubusercontent.com/namvpsfree/lib/main/libELG.so"
        ];

        #write in file
        $read = file_get_contents($filenames);
        $old_data = json_decode($read, true);

        $old_data[] = $data;
        $json = json_encode($old_data, JSON_PRETTY_PRINT);
        file_put_contents($filenames, $json);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Lấy Key - ELG</title>

    <style>
        :root {
            --primary-bg: #32096f;
            --accent-purple: #bb94f7;
            --glass-white: rgba(255, 255, 255, 0.95);
        }

        body {
            margin: 0;
            padding: 0;
            background: radial-gradient(circle at center, #4b0db3, #1a0533);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .wrap {
            background-color: var(--accent-purple);
            width: 100%;
            max-width: 400px;
            height: 620px;
            border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .title-box {
            background: white;
            margin-top: 30px;
            padding: 10px 30px;
            border-radius: 50px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .title-box h1 {
            margin: 0;
            font-size: 22px;
            color: #32096f;
            letter-spacing: 2px;
        }

        .box-input {
            background: var(--glass-white);
            border-radius: 25px;
            width: 85%;
            height: 380px;
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .btn-key {
            padding: 15px 35px;
            background: linear-gradient(135deg, #32096f, #6211d6);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-key:hover {
            transform: translateY(-3px);
        }

        .note {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }

        #keytext {
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>

</head>

<body>

    <div class="wrap">

        <div class="title-box">
            <h1>GET KEY</h1>
        </div>

        <div class="box-input">

            <form method="POST">

                <?php if ($key == ""): ?>
                    <button type="submit" name="generate" class="btn-key">Lấy Key Ngay</button>
                <?php endif; ?>

            </form>

            <p class="note">Mỗi mã key có hiệu lực trong vòng 24 giờ kể từ khi kích hoạt.</p>

            <h2 id="keytext">
                <?php
                if ($key != "") {
                    echo $key;
                }
                ?>
            </h2>

            <?php if ($key != ""): ?>
                <button class="btn-key" onclick="copyKey()">Copy Key</button>
            <?php endif; ?>

        </div>

        <p style="margin-top:20px;color:white;">© Bản quyền thuộc ELG 2026</p>

    </div>

    <script>
        function copyKey() {

            var text = document.getElementById("keytext").innerText;

            if (text.trim() == "") {
                alert("Chưa có key để copy!");
                return;
            }

            navigator.clipboard.writeText(text).then(function() {
                alert("Đã copy key: " + text);
            });

        }
    </script>

</body>

</html>