<?php
include "admin/controller/reservasi.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="public/images/logo.jpg">
    <title>Fatamorgana Coffee House Reservation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container-fluid {
            position: relative;
            color: rgb(0, 0, 0);
            background-size: cover;
            background-image: url('public/images/kopibg4.png');
            height: 100vh;
        }

        .reservation-form {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .reservation-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .reservation-form .form-group label {
            font-weight: bold;
        }

        .reservation-form .btn {
            width: 100%;
            background: #6c757d;
            border: none;
        }

        .reservation-form .btn:hover {
            background: #5a6268;
        }

        .availability-info {
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="reservation-form">
                    <h2>Reservasi Tempat</h2>
                    <form method="POST" id="reservationForm">
                        <div class="form-group">
                            <label for="customer_name">Nama Pemesan</label>
                            <input name="customer_name" type="text" class="form-control form-control-lg rounded-4" id="customer_name" placeholder="Masukkan nama Anda" pattern="[A-Za-z ]+" title="Hanya huruf dan spasi yang diperbolehkan" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_contact">Nomor WA</label>
                            <input name="customer_contact" type="text" class="form-control form-control-lg rounded-4" id="customer_contact" placeholder="Masukkan nomor WhatsApp Anda" pattern="[0-9]+" title="Hanya angka yang diperbolehkan" maxlength="13" required>
                        </div>
                        <div class="form-group">
                            <label for="reservation_date">Tanggal Reservasi</label>
                            <input name="reservation_date" type="date" class="form-control form-control-lg rounded-4" id="reservation_date" required>
                            <div class="availability-info" id="availabilityInfo"></div>
                        </div>
                        <div class="form-group">
                            <label for="reservation_time">Jam Reservasi</label>
                            <input name="reservation_time" type="time" class="form-control form-control-lg rounded-4" id="reservation_time" required>
                        </div>
                        <div class="form-group">
                            <label for="num_of_people">Jumlah Orang</label>
                            <input name="num_of_people" type="number" class="form-control form-control-lg rounded-4" id="num_of_people" placeholder="Masukkan jumlah orang" required>
                        </div>
                        <button name="tambah_reservasi" type="submit" class="btn btn-primary">Reservasi Sekarang</button>
                    </form>
                    <br>
                </div>
                <button class="btn btn-danger"><a class="btn btn-danger" href="index.php">Kembali</a></button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="fullReservationModal" tabindex="-1" role="dialog" aria-labelledby="fullReservationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fullReservationModalLabel">Reservasi Penuh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Mohon maaf, reservasi untuk tanggal tersebut sudah penuh.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="successReservationModal" tabindex="-1" role="dialog" aria-labelledby="successReservationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successReservationModalLabel">Reservasi Berhasil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="success_message">

                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorReservationModal" tabindex="-1" role="dialog" aria-labelledby="errorReservationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorReservationModalLabel">Reservasi Penuh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="errorModalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $('#reservationForm').submit(function(e) {
            var selectedDate = $('#reservation_date').val();
            var currentDate = '<?php echo $currentDate; ?>';
            var totalReservations = <?php echo $totalReservations; ?>;
            var maxDailyReservations = <?php echo $maxDailyReservations; ?>;

            // Memeriksa apakah tanggal yang dipilih adalah hari ini
            if (selectedDate === currentDate) {
                // Memeriksa apakah reservasi untuk hari ini sudah mencapai batas maksimal
                if (totalReservations >= maxDailyReservations) {
                    e.preventDefault(); // Mencegah pengiriman formulir
                    $('#fullReservationModal').modal('show');
                }
            } else {
                // Memeriksa apakah reservasi untuk tanggal yang dipilih sudah mencapai batas maksimal
                if (totalReservations > maxDailyReservations + 1) {
                    e.preventDefault(); // Mencegah pengiriman formulir
                    $('#fullReservationModal').modal('show');
                } else {
                    // Jika masih tersedia slot, tampilkan modal sukses
                    $('#successReservationModal').modal('show');
                }
            }
        });

        var today = new Date().toISOString().split('T')[0];

        // Mengatur tanggal maksimal di input tanggal ke hari ini
        $('#reservation_date').attr('min', today);

        // Mengatur placeholder untuk menampilkan informasi ketersediaan reservasi saat halaman dimuat
        updateAvailabilityInfo(today);

        // Memperbarui informasi ketersediaan saat tanggal berubah
        $('#reservation_date').change(function() {
            var selectedDate = $(this).val();
            updateAvailabilityInfo(selectedDate);
        });

        function updateAvailabilityInfo(date) {
            $.ajax({
                url: 'admin/controller/check_availability.php',
                method: 'POST',
                data: {
                    reservation_date: date
                },
                success: function(response) {
                    $('#availabilityInfo').html(response);
                }
            });
        }

        $(document).ready(function() {
            <?php
            if (isset($_SESSION['success_message'])) {
                $successMessage = $_SESSION['success_message'];
                unset($_SESSION['success_message']);
            ?>
                $('#successReservationModal .modal-body').html('<?php echo $successMessage; ?>');
                $('#successReservationModal').modal('show');
            <?php } ?>

            <?php
            if (isset($_SESSION['error_message'])) {
                $errorMessage = $_SESSION['error_message'];
                unset($_SESSION['error_message']);
            ?>
                $('#errorReservationModal .modal-body').html('<?php echo $errorMessage; ?>');
                $('#errorReservationModal').modal('show');
            <?php } ?>
        });
    </script>

</body>

</html>