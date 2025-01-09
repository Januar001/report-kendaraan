<?php
session_start();

require 'vendor/autoload.php';
use Carbon\Carbon;
// Token dari Bot Telegram
$telegramToken = "";
// ID Chat Telegram (dapat ditemukan setelah berbicara dengan bot Anda)
$chatId = "";

Carbon::setLocale('id');
date_default_timezone_set("Asia/Jakarta");
$date = Carbon::now()->translatedFormat('l, d F Y H:i:s');

// Fungsi untuk mengirim pesan
function sendMessage($message) {
    global $telegramToken, $chatId;
    $url = "https://api.telegram.org/bot$telegramToken/sendMessage";
    
    // Data yang akan dikirim sebagai array
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'Markdown'
    ];
    
    // Mengirim request menggunakan cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Mengubah array menjadi query string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

// Fungsi untuk mengirim gambar
function sendPhoto($photoPath, $caption = "") {
    global $telegramToken, $chatId;
    $url = "https://api.telegram.org/bot$telegramToken/sendPhoto";
    
    // Data yang akan dikirim sebagai array
    $data = [
        'chat_id' => $chatId,
        'caption' => $caption,
        'parse_mode' => 'Markdown',
        'photo' => new CURLFile(realpath($photoPath)) // Menambahkan file gambar
    ];
    
    // Mengirim request menggunakan cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Mengirim array sebagai POST field
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}


// Collect form data
$penumpang = implode(", ", $_POST['passengerName']); // Menggabungkan array dengan koma sebagai pemisah
$penumpang = str_replace(',', "\n✅", $penumpang);    // Mengganti koma dengan baris baru dan simbol ✅
// $penumpang = "✅ " . $penumpang;                      // Menambahkan simbol ✅ di awal string
$laporan = $_POST['reportType'];
$laporan .= ($laporan === "keluar") ? " ⬆️" : " ⬇️";
$vehicleType = $_POST['vehicleType'];
$driverName = $_POST['driverName'];
$km = $_POST['km'];
$time = $_POST['time'];
$description = $_POST['description'];
$officer = ucfirst($_POST['officer']);

$message = "🚙 *LAPORAN BARU TELAH DITERIMA!* 🚙\n";
$message .= "_(" . $date . " WIB)_\n\n";
$message .= "========================\n";
$message .= "*Jenis Laporan:* Kendaraan" . (ucfirst($laporan) ?? 'Tidak Diketahui') . "\n";
$message .= "*Mobil:* " . ($vehicleType ?? 'Tidak Diketahui') . "\n";
$message .= "*Driver:* " . ($driverName ?? 'Tidak Diketahui') . "\n";
$message .= "*Penumpang:*\n" . ("✅ " . $penumpang ?? 'Tidak Ada') . "\n";
$message .= "*KM ".ucfirst($_POST['reportType']).":* " . ($km ?? 'Tidak Diketahui') . " KM\n";
$message .= "*Jam ".ucfirst($_POST['reportType']).":* " . ($time ?? 'Tidak Diketahui') . " WIB\n";
$message .= "*Keterangan:* " . ($description ?? 'Tidak Ada') . "\n";
$message .= "*Petugas:* " . ($officer ?? 'Tidak Diketahui') . "\n";
$message .= "========================\n";




// Cek jika ada file yang diupload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_FILES['images']['tmp_name'])) {
        // print($_POST);
        // die;
        $imagePath = $_FILES['images']['tmp_name'];
        $caption = $message;
        sendPhoto($imagePath, $caption);
        $_SESSION['message'] = 'Data berhasil disimpan!';
        $_SESSION['alert_type'] = 'success';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        // print($_POST);
        // die;
        $message = $message;
        if (!empty($message)) {
            sendMessage($message);
            $_SESSION['message'] = 'Data berhasil disimpan!';
            $_SESSION['alert_type'] = 'success';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
}
?>
