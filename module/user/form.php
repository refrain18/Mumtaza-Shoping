<?php
      
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : "";
      
	$button = "Update";
	$queryUser = mysqli_query($koneksi, "SELECT * FROM user WHERE user_id='$user_id'");
	 
	$row=mysqli_fetch_array($queryUser);
	
	$nama = $row["nama"];
	$email = $row["email"];
	$phone = $row["phone"];
	$alamat = $row["alamat"];
	$status = $row["status"];
	$level = $row["level"];
	// var_dump($nama, $email, $phone, $alamat, $status, $level."<br>"."<br>". $user_id); die();
	
?>
<form action="<?php echo BASE_URL."module/user/action.php?user_id=$user_id"?>" method="POST">

	<?php
		$notif = isset($_GET['notif']) ? $_GET['notif'] : false;

        if($notif == 'nama') {
            echo "<div class='notif' id='notif'>Maaf, nama yang kamu masukan harus huruf</div>";
        }elseif($notif == 'phone'){
            echo "<div class='notif' id='notif'>Maaf, nomor telepon yang dimasukan harus angka</div>";    
        }elseif($notif == 'email'){
            echo "<div class='notif' id='notif'>Maaf, email yang kamu masukan sudah terdaftar</div>";    
		}	
		if (isset($_GET['notif'])) {
			echo notifTransaksi($_GET['notif'] ,"Email");
		}
	?>
	 
	 <input type="hidden" name="email_lama" value="<?php echo $email; ?>"> 

	<div class="element-form">
		<label>Nama Lengkap</label>	
		<span><input type="text" name="nama" value="<?php echo $nama; ?>" required/></span>
	</div>	

	<div class="element-form">
		<label>Email</label>	
		<span><input type="email" style=" width : 98%; height : 23px;" name="email" value="<?php echo $email; ?>" required/></span>
	</div>		

	<div class="element-form">
		<label>Phone</label>	
		<span><input type="phone" style=" width : 98%; height : 23px;" minlength="11" maxlength="12" name="phone" value="<?php echo $phone; ?>" required/></span>
	</div>	

	<div class="element-form">
		<label>Alamat</label>	
		<span><input type="text" name="alamat" value="<?php echo $alamat; ?>" required/></span>
	</div>		

	<div class="element-form">
		<label>Level</label>	
		<span>
			<input type="radio" value="superadmin" name="level" <?php if($level == "superadmin"){ echo "checked"; } ?> /> Superadmin
			<input type="radio" value="customer" name="level" <?php if($level == "customer"){ echo "checked"; } ?> /> Customer			
		</span>
	</div>	

	<div class="element-form">
		<label>Status</label>	
		<span>
			<input type="radio" value="on" name="status" <?php if($status == "on"){ echo "checked"; } ?> /> on
			<input type="radio" value="off" name="status" <?php if($status == "off"){ echo "checked"; } ?> /> off		
		</span>
	</div>		
	  
	<div class="element-form">
		<span><input type="submit" name="button" value="<?php echo $button; ?>" class="submit-my-profile" /></span>
	</div>	
</form>