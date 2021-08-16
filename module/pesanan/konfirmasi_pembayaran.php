<?php
    $pesanan_id = isset($_GET['pesanan_id']) ? $_GET['pesanan_id'] : false;
    // $pesanan_id = $_GET["pesanan_id"];

    $queryKonfirmasiPembayaran = mysqli_query($koneksi, "SELECT p.status, k.bukti_pembayaran, k.tanggal_transfer FROM konfirmasi_pembayaran k JOIN pesanan p ON k.pesanan_id = p.pesanan_id WHERE p.pesanan_id LIKE '$pesanan_id'") OR die(mysqli_error($koneksi));



?>

<table class="table-list">
    <?php if( $data = mysqli_fetch_array($queryKonfirmasiPembayaran)) :
    
    $gambar = $data['bukti_pembayaran'];
    $bukti_pembayaran = "<a target='_blank' href='".BASE_URL."images/bukti_pembayaran/$gambar'> <img src='".BASE_URL."images/bukti_pembayaran/$gambar' alt='bukti pembayaran' style='width: 200px; vertical-align: middle;'/>";
    $keterangan_gambar = "(Klik gambar jika ingin memperbesar)";


    ?>

        <form action=<?php echo BASE_URL."index.php?page=my_profile&module=pesanan&action=list"; ?> method="POST" enctype="multipart/form-data">

            <div class="element-form">
                <label>Bukti Pembayaran <?php echo $keterangan_gambar; ?></label>
                <span><input type="file" name="file" disabled/> <?php echo $bukti_pembayaran ?></span>
            </div>

            <div class="element-form">
                <label>Tanggal Transfer (format: yyyy-mm-dd)</label>
                <span><input type="date" name="tanggal_transfer" value="<?php echo $data['tanggal_transfer'] ?>" readonly/></span>
            </div>

            <div class="element-form">
                <span><input type="submit" value="Kembali" name="button" /></span>
            </div>
        </form> 
    <?php else : ?>
        <form action=<?php echo BASE_URL."module/pesanan/action.php?pesanan_id=$pesanan_id"; ?> method="POST" enctype="multipart/form-data">

        <?php
                $notif = isset($_GET['notif']) ? $_GET['notif'] : false;

                if($notif == 'tipefile') {
                    echo "<div class='notif' id='notif'>Tipe file tidak didukung!</div>";
                }elseif($notif == 'ukuranfile') {
                    echo "<div class='notif' id='notif'>Ukuran file tidak boleh lebih dari 1MB</div>";
                }
        ?>

            <div class="element-form">
                <label>Bukti Pembayaran</label>
                <span><input type="file" name="file"  required/></span>
            </div>

            <div class="element-form">
                <label>Tanggal Transfer (format: mm/dd/yyyy)</label>
                <span><input type="date" min="2020-11-10" max="2050-12-30" name="tanggal_transfer" required/></span>
            </div>

            <div class="element-form">
                <span><input type="submit" value="Konfirmasi" name="button" /></span>
            </div>
        </form> 
    <?php endif; ?>
</table>    