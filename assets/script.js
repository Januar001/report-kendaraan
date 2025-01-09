$(document).ready(function () {
    // Ambil data JSON yang disimpan di dalam elemen <script>
    var passengers = JSON.parse($('#passengerData').text());
    var drivers = JSON.parse($('#passengerData').text());

    // Tambahkan data ke dalam <select> secara otomatis saat halaman dimuat
    passengers.forEach(function (passenger) {
        $('#passengerName').append(
            `<option value="${passenger.value}">${passenger.name}</option>`
        );
    }); 

    drivers.forEach(function (driver) {
        $('#driverName').append(
            `<option value="${driver.value}">${driver.name}</option>`
        );
    });

    // Refresh selectpicker jika menggunakan plugin
    $('.selectpicker').selectpicker('refresh');
});

// Function to get URL parameters
function getUrlParameter(name) {
    const params = new URLSearchParams(window.location.search);
    return params.get(name);
}

$(document).ready(function () {
    // Validate URL parameters
    const petugas = getUrlParameter('p');

    // Check if any parameter is missing
    if (!petugas) {
        Swal.fire({
            title: 'Error!',
            text: 'Link salah atau data tidak lengkap!',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        $('#btnLaporanMasuk').prop('disabled', true);
        $('#btnLaporanKeluar').prop('disabled', true);
        return; // Stop further execution
    }

    // Populate form fields with URL parameters
    $('#officer').val(petugas || '');

    // Form handling
    const container = $('#container');
    const formContainer = $('#formContainer');
    const reportForm = $('#reportForm');
    const btnLaporanMasuk = $('#btnLaporanMasuk');
    const btnLaporanKeluar = $('#btnLaporanKeluar');
    const cancelButton = $('#cancelButton');
    const kmLabel = $('#kmLabel');
    const timeLabel = $('#timeLabel');
    const reportType = $('#reportType');
    const judul = $('h1 span');

    // Ensure form is initially hidden
    formContainer.addClass('d-none');

    // Show form for "Laporan Masuk"
    btnLaporanMasuk.on('click', function () {
        container.hide();
        formContainer.removeClass('d-none'); // Show form
        judul.text('Masuk');
        kmLabel.text('Kilometer Masuk');
        timeLabel.text('Jam Masuk');
        reportForm.attr('data-type', 'masuk'); // Set report type
        reportType.val('masuk');
        btnLaporanMasuk.addClass('d-none');
        btnLaporanKeluar.addClass('d-none');
    });

    // Show form for "Laporan Keluar"
    btnLaporanKeluar.on('click', function () {
        container.hide();
        formContainer.removeClass('d-none'); // Show form
        judul.text('Keluar');
        kmLabel.text('Kilometer Keluar');
        timeLabel.text('Jam Keluar');
        reportForm.attr('data-type', 'keluar'); // Set report type
        reportType.val('keluar');
        btnLaporanMasuk.addClass('d-none');
        btnLaporanKeluar.addClass('d-none');
    });

    // Hide form on cancel button click
    cancelButton.on('click', function () {
        location.reload();
    });
});

// Function to show loading animation using jQuery
function showLoading() {
    $('#cancelButton').hide();
    // Change the button text to "Processing"
    $('#buttonText').text('Processing...');

    // Create a spinner element
    var spinner = $('<div>').addClass('spinner');

    // Add the spinner to the button
    $('#submitButton').prepend(spinner);

    // Disable the submit button to prevent multiple submissions
    $('#submitButton').prop('disabled', true);
}

 // Initialize the selectpicker for better UI
 document.addEventListener("DOMContentLoaded", function () {
    $('.selectpicker').selectpicker();
});