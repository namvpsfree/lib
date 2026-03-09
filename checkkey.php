<?php
header("Content-Type: application/json");

if (!isset($_POST['uname']) || !isset($_POST['cs'])) {
    echo json_encode([
        "status" => "fail",
        "reason" => "Key không hợp lệ!"
    ]);
    exit;
}

$filename = "Key.json";

$key = $_POST['uname'];
$uid = $_POST['cs'];

$dataKey = file_get_contents($filename);
$result = json_decode($dataKey, true);

$found = false;

foreach ($result as $index => $row) {

    if ($row['key'] == $key) {

        $found = true;

        // kiểm tra HWID
        if (empty($row['serial']) || $row['serial'] == $uid) {

            // kiểm tra hạn
            if (strtotime($row['expire']) > time()) {

                // nếu chưa có hwid thì lưu
                if (empty($row['serial'])) {
                    $result[$index]['serial'] = $uid;
                    file_put_contents($filename, json_encode($result, JSON_PRETTY_PRINT));
                }

                echo json_encode([
                    "status" => "abc",
                    "Lib" => "https://yourdomain.com/lib.so",
                    "Dias" => $row['expire'],
                    "check" => md5($key . $uid)
                ]);
                exit;
            } else {

                echo json_encode([
                    "status" => "fail",
                    "reason" => "Key hết hạn"
                ]);
                exit;
            }
        } else {

            echo json_encode([
                "status" => "fail",
                "reason" => "Key này đã được sử dụng"
            ]);
            exit;
        }
    }
}

if (!$found) {

    echo json_encode([
        "status" => "fail",
        "reason" => "Key sai!"
    ]);
}
