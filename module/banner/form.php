<?php
       
    $banner_id = isset($_GET['banner_id']) ? $_GET['banner_id'] : "";
       
    $barang_id = "";
    $banner = "";
    $link = "";
    $gambar = "";
	$keterangan_gambar = "";
	$status = "";
	
    $button = "Add";
	
	// Query Barang untuk Add New Banner
	$queryBarang = mysqli_query($koneksi, "SELECT barang_id, nama_barang FROM barang WHERE barang_id NOT IN (SELECT barang_id FROM banner) AND barang.status = 'on' ORDER BY nama_barang ASC;") OR die(mysqli_error($koneksi));
	
    if($banner_id != "")
    {
        $button = "Update";
		
        $queryBanner = mysqli_query($koneksi, "SELECT * FROM banner WHERE banner_id='$banner_id'");
        $row=mysqli_fetch_array($queryBanner);
           
		$barang_id = $row["barang_id"];
		$banner = $row["banner"];
		$link = $row["link"];
		$gambar = "<img src='". BASE_URL."images/slide/$row[gambar]' style='width: 200px;vertical-align: middle;' />";
		$keterangan_gambar = "(klik 'Pilih Gambar' hanya jika tidak ingin mengganti gambar)";
		$status = $row["status"];

		// Query Barang untuk Update Banner
		$queryBarang = mysqli_query($koneksi, "SELECT barang_id, nama_barang FROM barang WHERE barang_id NOT IN (SELECT barang_id FROM banner WHERE barang_id != '$barang_id') AND status = 'on' ORDER BY nama_barang ASC;") OR die(mysqli_error($koneksi));

    }   
?>

<form action="<?php echo BASE_URL."module/banner/action.php?banner_id=$banner_id"?>" method="post" enctype="multipart/form-data">
	
	<?php
		$notif = isset($_GET['notif']) ? $_GET['notif'] : false;

        if($notif == 'tipefile') {
            echo "<div class='notif' id='notif'>Tipe file tidak didukung!</div>";
        }elseif($notif == 'ukuranfile') {
            echo "<div class='notif' id='notif'>Ukuran file tidak boleh lebih dari 1MB</div>";
        }

		if (isset($_GET['notif'])) {
			echo notifTransaksi($_GET['notif'] ,"link");
		}
	?>

	<div class="element-form">
		<label>Banner</label>	
		<span><input type="text" name="banner" value="<?php echo $banner; ?>" required/></span>
	</div>	

    <div class="element-form">
        <label>Link Barang</label>
        <span>
        
            <select name="barang_id">
                <?php
					while($row2=mysqli_fetch_assoc($queryBarang)){
                        if($barang_id == $row2['barang_id']) {
                            echo "<option value='$row2[barang_id]' selected='true'>$row2[nama_barang]</option>";
                        }else{
                            echo "<option value='$row2[barang_id]'>$row2[nama_barang]</option>";
                        }
                    }
                ?>
            </select>
        
        </span>
    </div>	   

	<div class="element-form">
		<label>Gambar <?php echo $keterangan_gambar; ?></label>	
		<span><input type="file" name="file" /><?php echo $gambar; ?></span>
	</div>	  

	<div class="element-form">
		<label>Status</label>	
		<span>
			<input type="radio" value="on" name="status" <?php if($status == "on"){ echo "checked"; } ?> /> On
			<input type="radio" value="off" name="status" <?php if($status == "off"){ echo "checked"; } ?> /> Off		
		</span>
	</div>	   
	   
	<div class="element-form">
		<span><input type="submit" name="button" value="<?php echo $button; ?>" class="submit-my-profile" /></span>
	</div>	
</form>