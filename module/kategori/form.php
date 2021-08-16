<?php

    $kategori_id = isset($_GET['kategori_id']) ? $_GET['kategori_id'] : false;

    $kategori = "";
    $status = "";
    $button = "Add";

    if($kategori_id){
        $queryKategori = mysqli_query($koneksi, "SELECT * FROM kategori WHERE kategori_id='$kategori_id'");
        $row = mysqli_fetch_assoc($queryKategori);

        $kategori = $row['kategori'];
        $status = $row['status'];
        $button = "Update";
    }

?>

<form action="<?php echo BASE_URL."module/kategori/action.php?kategori_id=$kategori_id";?>" method="POST">
    <?php
    // notif kategori sudah ada
        // if (isset($_GET['notif'])) {

        //     if ($_GET['notif'] == "gagal_add") {
        //         echo "<div class='notif' id='notif'>Gagal menginput data, Nama Kategori tidak boleh sama!</div>";
        //     } elseif ($_GET['notif'] == "gagal_update") {
        //         echo "<div class='notif' id='notif'>Gagal Memperbaharui, Nama kategori tidak boleh sama!</div>";
        //     }
            
        // }
            
        if ( isset($_GET['notif']) ) {
            echo notifTransaksi($_GET['notif'], "Kategori");
        }
    //====///
    ?>

    <input type="hidden" name="kategori_lama" value="<?php echo $kategori; ?>">

    <div class="element-form">
        <label>Kategori</label>
        <span><input type="text" name="kategori" value="<?php echo $kategori; ?>" required/></span>
    </div>

    <div class="element-form">
        <label>Status</label>
        <span>
              <input type="radio" name="status" value="on" <?php if($status == "on"){ echo "checked='true'"; } ?> required/>On
              <input type="radio" name="status" value="off" <?php if($status == "off"){ echo "checked='true'"; } ?> />Off
        </span >
    </div>

    <div class="element-form">
        <span><input type="submit" name="button" value="<?php echo $button; ?>" /></span>
    </div>

</form>