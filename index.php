<?php
// Start the session to access the message
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
     <!-- SweetAlert CSS -->
     <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.js"></script>

    <style>
        /* Custom styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 40px;
            text-align: center;
            animation: fadeIn 1s ease-out;
        }

        .btn {
            font-size: 1.1rem;
            padding: 15px 30px;
            border-radius: 50px;
            transition: all 0.3s ease;
            animation: slideIn 0.5s ease-out;
        }

        .btn-primary {
            background-color: #5C6BC0;
            border: none;
        }

        .btn-primary:hover {
            background-color: #3f4b8f;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
        }

        .btn-secondary {
            background-color: #8e8e8e;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a5a5a;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
        }

        /* Animation for fade-in and button sliding */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        #submitButton {
        display: inline-flex;
        align-items: center; /* Menyelaraskan spinner dan teks secara vertikal */
        justify-content: center; /* Menyelaraskan spinner dan teks secara horizontal */
        padding: 10px 20px; /* Menambahkan padding agar tombol cukup besar */
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px; /* Menambahkan jarak antara spinner dan teks */
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- Header -->
        <h1 class="text-center mb-4">Laporan Kendaraan <span></span></h1>

        <!-- Buttons -->
        <div class="container" id="container">
            <div class="row text-center">
                <!-- Tombol Laporan Masuk -->
                <div class="col-12 col-md-6 mb-3">
                    <button id="btnLaporanMasuk" class="btn btn-primary btn-lg w-100">Laporan Masuk</button>
                </div>
                <!-- Tombol Laporan Keluar -->
                <div class="col-12 col-md-6 mb-3">
                    <button id="btnLaporanKeluar" class="btn btn-secondary btn-lg w-100">Laporan Keluar</button>
                </div>
            </div>
        </div>
        <!-- Form Container -->
    <div id="formContainer" class="mt-5 d-none">
        <form id="reportForm" action="process.php" method="POST" enctype="multipart/form-data" onsubmit="showLoading();">
            <input type="hidden" name="reportType" id="reportType"> 
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="vehicleType" class="form-label">Jenis Kendaraan</label>
                        <select class="form-control" id="vehicleType" name="vehicleType" required>
                            <option value="" disabled selected>-- Pilih Jenis Kendaraan --</option>
                            <option value="Honda HRV">Honda HRV - ( W 1966 SJ )</option>
                            <option value="Toyota Avanza">Toyota AVANZA - ( W 1922 SH )</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-md-12">
                        <label for="driverName" class="form-label">Nama Driver</label>
                        <select class="form-control selectpicker" id="driverName" name="driverName" data-live-search="true" required>
                        <option value="" disabled selected>-- Pilih Nama Driver --</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="passengerName" class="form-label">Nama Penumpang</label>
                        <select class="form-control selectpicker" id="passengerName" name="passengerName[]" multiple data-live-search="true">
                            <!-- Data akan dimuat secara dinamis -->
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="km" class="form-label" id="kmLabel">Kilometer</label>
                        <input type="number" class="form-control" id="km" name="km" placeholder="Masukkan kilometer" required>
                    </div>
                    <div class="col-md-6">
                        <label for="time" class="form-label" id="timeLabel">Jam</label>
                        <input type="time" class="form-control" id="time" name="time" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Keterangan</label>
                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Tambahkan keterangan"></textarea>
                </div>

                <div class="mb-3">
                    <label for="images" class="form-label">Gambar</label>
                    <input type="file" class="form-control" id="images" name="images">
                    <div class="form-text" id="basic-addon4">Hanya masukkan saat ada kerusakan</div>
                </div>

                <div class="mb-3">
                    <label for="officer" class="form-label">Petugas</label>
                    <input type="text" class="form-control" id="officer" name="officer" value="<?php echo isset($_GET['p']) ? $_GET['p'] : ''; ?>" readonly>
                </div>

                <div class="text-center">
                    <button type="submit" id="submitButton" class="btn btn-success btn-lg">
                        <span id="buttonText">Simpan</span>
                    </button>
                    <button type="button" id="cancelButton" class="btn btn-danger btn-lg">Batal</button>
                </div>
            </form>
        </div>
    </div>
<!-- Bootstrap JS (for alerts) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/script.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>

<script id="passengerData" type="application/json">
    [
        {"value": "Enik Nurdayati", "name": "Enik Nurdayati"},
        {"value": "Harwanto", "name": "Harwanto"},
        {"value": "Tities Eko Prabowo", "name": "Tities Eko Prabowo"},
        {"value": "Misti Hariasih", "name": "Misti Hariasih"},  
        {"value": "Yuni Mafiati", "name": "Yuni Mafiati"},
        {"value": "Galuh Krishna Agustina", "name": "Galuh Krishna Agustina"},
        {"value": "Juwita Agustiningsih", "name": "Juwita Agustiningsih"},
        {"value": "Rizka Novianggi", "name": "Rizka Novianggi"},
        {"value": "Agus Pribadi", "name": "Agus Pribadi"},
        {"value": "Abdul Kawi", "name": "Abdul Kawi"},
        {"value": "Arie Sadewa", "name": "Arie Sadewa"},
        {"value": "Wahid Suryo Hendro", "name": "Wahid Suryo Hendro"},
        {"value": "Roudhotul Khikma", "name": "Roudhotul Khikma"},
        {"value": "Linda Intania Dewi", "name": "Linda Intania Dewi"},
        {"value": "Amy Nurjannah", "name": "Amy Nurjannah"},
        {"value": "Ayu Agus Ningtias", "name": "Ayu Agus Ningtias"},
        {"value": "Alfira Rizky Amalia", "name": "Alfira Rizky Amalia"},
        {"value": "Ryan Adi Pratama", "name": "Ryan Adi Pratama"},
        {"value": "Mustofah Januar", "name": "Mustofah Januar"},
        {"value": "Prima Hadi Prasetio", "name": "Prima Hadi Prasetio"},
        {"value": "Ganda Taruna Muslimin", "name": "Ganda Taruna Muslimin"},
        {"value": "M Mansyur Syarif", "name": "M Mansyur Syarif"},
        {"value": "Hari Megawati", "name": "Hari Megawati"},
        {"value": "Imam Mulyadi", "name": "Imam Mulyadi"},
        {"value": "M Indarmawan", "name": "M Indarmawan"},
        {"value": "Hari Widarto", "name": "Hari Widarto"}
    ]
</script>

<?php
// Check if there's a message in the session
if (isset($_SESSION['message'])):
    $message = $_SESSION['message'];
    $alertType = $_SESSION['alert_type']; // success or error
?>

    <script>
        // Show SweetAlert message based on session variables
        Swal.fire({
            title: '<?php echo ucfirst($alertType); ?>!',
            text: '<?php echo $message; ?>',
            icon: '<?php echo $alertType; ?>',
            confirmButtonText: 'OK'
        });
    </script>

    <?php
    // Clear the session variables after displaying the alert
    unset($_SESSION['message']);
    unset($_SESSION['alert_type']);
endif;
?>

</body>

</html>
