<?php

// klik tombol mulai
if (isset($_POST['mulai'])) {
    $list = preg_replace('/\s+/', ' ', $_POST['list']); // hapus spasi yang berlebihan
    $list_pieces = explode(' ', $list); // setiap data dimasukkan ke dalam array
    $total = count($list_pieces); // total data
    // sorting data (algoritma bubble sort)
    for ($i = 0; $i < $total - 1; $i++) {
        for ($j = 0; $j < $total; $j++) {
            if ($j < $total - 1) {
                if ($list_pieces[$j] > $list_pieces[$j + 1]) {
                    $swap1 = $list_pieces[$j];
                    $swap2 = $list_pieces[$j + 1];
                    $list_pieces[$j + 1] = $swap1;
                    $list_pieces[$j] = $swap2;
                }
            }
        }
    }

    // data yang sudah disorting, diurutkan di dalam array satuan
    $satuan = [];
    for ($i = 0; $i < $total; $i++) {
        array_push($satuan, $list_pieces[$i]);
        if (isset($list_pieces[$i + 1])) {
            if ($list_pieces[$i] == $list_pieces[$i + 1]) {
                continue;
            }
        }
    }

    // menghitung jumlah data yang sama
    // akan ditampilkan pada tabel data satuan menggunakan foreach
    $number = [];
    $total_masing2_data = [];
    for ($i = 0; $i < count($satuan); $i++) {
        $save = 0;
        for ($j = 0; $j < $total; $j++) {
            if ($satuan[$i] == $list_pieces[$j]) {
                $save += 1;
            }
        }

        array_push($number, $list_pieces[$i]); // data yang akan dijadikan key
        array_push($total_masing2_data, $save); // jumlah data yang akan dijadikan value
    }
    $data = array_combine($number, $total_masing2_data); // data disatukan menjadi key & value


    // rumus statistika dasar

    $min = current($satuan); // nilai terkecil
    $max = end($satuan); // nilai terbesar
    $r = $max - $min; // mencari selisih nilai

    // jika user input jumlah kelas
    if ($_POST['p'] > 0 and $_POST['p'] !== NULL) {
        $p = $_POST['p']; // jumlah kelas berdasarkan inputan
        $k = ceil($r / $p); // panjang kelas
    } else {
        // jika tidak input jumlah kelas
        $k = ceil(1 + (3.3 * log10($total))); // jumlah kelas
        $p = ceil($r / $k); // panjang kelas
    }

    $interval = []; // array interval tiap kelas
    $total_frekuensi_interval = []; // array total frekuensi tiap kelas

    for ($i = 0; $i < $k; $i++) {
        $frekuensi = 0; // reset nilai frekuensi tiap kelas
        if ($i == 0) { // di awal looping
            $bawah = $min + (($p - 1) * $i);  // data terendah tiap kelas
            $atas = $bawah + ($p - 1); // data tertinggi tiap kelas
            // $data berasal dari data satuan

        } else { // jika bukan awal looping
            $bawah = $min + (($p) * $i); // data terendah tiap kelas
            $atas = $bawah + ($p - 1); // data tertinggi tiap kelas
        }
        foreach ($data as $key => $value) {
            // membandingkan data satuan dengan nilai terendah dan tertinggi tiap kelas
            // jika benar, maka jumlah dari tiap data ditambahkan ke frekuensi tiap kelas
            if ($key >= $bawah and $key <= $atas) {
                $frekuensi += $value;
            }
        }
        array_push($interval, $bawah . ' - ' . $atas); // nilai "bawah - atas" dari tiap kelas
        array_push($total_frekuensi_interval, $frekuensi); // total frekuensi tiap kelas
    }

    $data_kelompok = array_combine($interval, $total_frekuensi_interval); // data disatukan menjadi key & value
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous">

    <title>Data kelompok</title>
</head>

<body>
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 mt-4">
                <h4>Masukkan Angka</h4>
                <small>Pisahkan data satu dengan yang lain dengan spasi</small>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    Lihat Contoh
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Silahkan copas</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-left">
                                Panjang kelas : 10
                                <br>
                                Data :
                                <br>
                                31 32 54 53 65 66 70 71 36 31 38 53 54 52 52 57 62 63 76 37 32 45 55 55 55 59 46 47 67 41 32 60 61 83 83 75 76 40 41 51 72 64 64 73 74 48 49 48 49 68 31 32 54 53 65 66 70 71 36 31 38 53 54 52 52 57 62 63 76 37 32 45 55 100 55 59 46 47 67 41 32 91 61 83 83 75 76 40 41 51 72 64 64 98 74 48 49 48 49 68
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <form method="post" class="text-left">
                    <label for=""></label>
                    <input type="number" name="p" id="uintTextBox" placeholder="Jumlah kelas (bisa dikosongkan)" class="form-control" onkeypress="return isNumber(event)" style="width: 300px !important;">
                    <br>
                    <textarea name="list" placeholder="Data" cols="30" rows="10" class="form-control" onkeypress="return isNumber(event)"></textarea>
                    <input type="submit" value="Mulai" name="mulai" class="btn btn-primary btn-block my-2">
                </form>
            </div>

            <?php if (isset($_POST['mulai'])) : ?>
                <div class="col-lg-6 mx-auto mt-5">
                    <h4>Data Satuan</h4>
                    <small>Berikut hasil sorting ascending dan juga tabel frekuensi</small>
                    <hr>
                    <table class="table table-bordered table-hovered text-center">
                        <tr>
                            <td>Data</td>
                            <td>Jumlah</td>
                        </tr>
                        <?php if (isset($_POST['mulai'])) : ?>
                            <?php foreach ($data as $key => $value) : ?>
                                <tr>
                                    <td><?php echo $key; ?></td>
                                    <td><?php echo $value; ?></td>
                                </tr>
                                <?php $data_terbesar = $key; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <tr>
                            <td>Total</td>
                            <td><?php echo $total; ?></td>
                        </tr>
                    </table>
                </div>


                <div class="col-lg-6 mx-auto mt-5">
                    <h4>Data Kelompok</h4>
                    <small>Berikut tabel frekuensi data kelompok</small>
                    <hr>
                    <table class="table table-bordered table-hovered text-center">
                        <tr>
                            <td>Data</td>
                            <td>Jumlah</td>
                        </tr>
                        <?php if (isset($_POST['mulai'])) : ?>
                            <?php foreach ($data_kelompok as $key => $value) : ?>
                                <tr>
                                    <?php if ($value == 0) {
                                        break;
                                    }
                                    ?>
                                    <td><?php echo $key; ?></td>
                                    <td><?php echo $value; ?></td>
                                </tr>
                                <?php $interval_terakhir = $key; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <tr>
                            <td>Total</td>
                            <td><?php echo $total; ?></td>
                        </tr>
                    </table>

                    <?php
                    // interval terakhir dan terbesar dari data kelompok
                    $pieces = explode(' ', $interval_terakhir); // dijadikan array
                    $interval_terakhir = array_pop($pieces); // array terakhir diambil untuk mendapatkan data terbesar
                    // cek apakah interval terakhir lebih kecil dari data terbesar dari semua data
                    // jika iya, maka muncul peringatan
                    if ($interval_terakhir < $data_terbesar) {
                        echo '<div class="text-danger text-left">';
                        echo 'Data tidak valid karena jumlah kelas kurang. Silahkan ganti jumlah kelas!';
                        echo '</div>';
                    }
                    ?>
                    <p class="text-left">
                        <small>
                            * panjang kelas = <?php echo $p; ?>
                            <br>
                            * jumlah kelas = <?php echo $k; ?>

                        </small>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>



    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper.js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/js/bootstrap.bundle.min.js" integrity="sha384-BOsAfwzjNJHrJ8cZidOg56tcQWfp6y72vEJ8xQ9w6Quywb24iOsW913URv1IS4GD" crossorigin="anonymous"></script>
    <script>
        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 32 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
    </script>
</body>

</html>