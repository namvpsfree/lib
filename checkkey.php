<?php
header("Content-Type: application/json");

// Lấy dữ liệu từ POST
$keyUser = $_POST['uname'] ?? null;
$uid = $_POST['cs'] ?? null;

// Kiểm tra đầu vào
if (empty($keyUser) || empty($uid)) {
    echo json_encode(["status" => "fail", "reason" => "Vui lòng nhập Key"]);
    exit;
}

$filename = "Key.json";
if (!file_exists($filename)) {
    echo json_encode(["status" => "fail", "reason" => "Cơ sở dữ liệu không tồn tại"]);
    exit;
}

// Đọc và giải mã JSON
$raw_data = file_get_contents($filename);
$data = json_decode($raw_data, true);

// Nếu JSON không hợp lệ
if ($data === null) {
    echo json_encode(["status" => "fail", "reason" => "Lỗi cấu trúc file Key.json"]);
    exit;
}

// CHUẨN HÓA: Nếu Key.json chỉ có 1 key (không nằm trong mảng []), ta đưa nó vào mảng
if (isset($data['key'])) {
    $data = [$data];
}

$found = false;
foreach ($data as $index => $item) {
    // So sánh key (dùng trim để loại bỏ khoảng trắng thừa)
    if (trim($item['key']) === trim($keyUser)) {
        $found = true;

        // 1. Kiểm tra HWID (Nếu key đã có UID và khác với máy đang đăng nhập)
        if (!empty($item['Uid']) && $item['Uid'] !== $uid) {
            echo json_encode(["status" => "fail", "reason" => "Key này đã bị khóa cho thiết bị khác"]);
            exit;
        }

        // 2. Kiểm tra thời hạn (Create_date là timestamp hết hạn)
        // Nếu Create_date nhỏ hơn thời gian hiện tại => Hết hạn
        if (isset($item['Create_date']) && time() > $item['Create_date']) {
            echo json_encode(["status" => "fail", "reason" => "Key của bạn đã hết hạn"]);
            exit;
        }

        // 3. Cập nhật UID nếu key mới tinh
        if (empty($item['Uid'])) {
            $data[$index]['Uid'] = $uid;
            file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
        }

        // 4. Trả về cho C++ (Phải là status "abc" để bValid = true)
        echo json_encode([
            "status" => "abc",
            "Lib" => "https://elghacker.ct.ws/libELG.so",
            "Dias" => isset($item['Create_date']) ? date("d/m/Y", $item['Create_date']) : "Vĩnh viễn",
            "check" => "Oke"
        ]);
        exit;
    }
}

// Nếu chạy hết vòng lặp mà không thấy key
echo json_encode(["status" => "fail", "reason" => "Key không chính xác hoặc chưa đăng ký"]);
