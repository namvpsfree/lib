<?php
// Đường dẫn file trên GitHub (Link cố định không kèm token)
// Nếu repo của bạn là Public, dùng link này:
$url = "https://raw.githubusercontent.com/namvpsfree/lib/main/libELG.so";

// Tên file lưu tại local (cùng folder với script)
$file_name = "libELG.so";

echo "Đang kiểm tra và tải file mới nhất...\n";

// Sử dụng file_get_contents để lấy dữ liệu (đơn giản cho file nhỏ)
// Hoặc dùng cURL nếu file nặng hoặc cần xác thực
$data = @file_get_contents($url);

if ($data !== false) {
    // Ghi đè trực tiếp vào file libELG.so trong cùng thư mục
    if (file_put_contents($file_name, $data)) {
        echo "Thành công: Đã cập nhật libELG.so (Size: " . strlen($data) . " bytes).\n";
    } else {
        echo "Lỗi: Không có quyền ghi file vào thư mục này.\n";
    }
} else {
    echo "Lỗi: Không thể tải file. Nếu đây là Repo Private, bạn phải dùng Token.\n";
}
?>
