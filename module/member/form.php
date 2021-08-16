<?php
       
    $no_member = isset($_GET['no_member']) ? $_GET['no_member'] : "";
       
    $user_id = "";
    $id_member = "";
    $status = "";
       
    $button = "Add";
       
    if($no_member != "")
    {
        $button = "Update";
		
        $queryMember = mysqli_query($koneksi, "SELECT * FROM member WHERE no_member='$no_member'");
        $row=mysqli_fetch_array($queryMember);
           
		$user_id = $row["user_id"];
		$id_member = $row["id_member"];
		$status = $row["status"];
    }   
?>

<form action="<?php echo BASE_URL."module/member/action.php?no_member=$no_member"?>" method="post">
	
    <?php
    
    $notif = isset($_GET['notif']) ? $_GET['notif'] : false;

    if($notif == 'idmember') {
        echo "<div class='notif' id='notif'>Maaf, id member yang dimasukan sudah ada</div>";
    }
    
	if ( isset($_GET['notif']) ) {
		echo notifTransaksi($_GET['notif'],"member");
	}

	?>
<input type="hidden" name="userid_lama" value="<?php echo $user_id; ?>">
<input type="hidden" name="idmember_lama" value="<?php echo $id_member; ?>">

<div class="element-form">
        <label>Nama Member</label>
        <span>
        
            <select name="user_id">
                <?php
                    $query = mysqli_query($koneksi, "SELECT user_id, nama FROM user WHERE status='on' ORDER BY nama ASC");
                    while($row=mysqli_fetch_assoc($query)){
                        if($user_id == $row['user_id']) {
                            echo "<option value='$row[user_id]' selected='true'>$row[nama]</option>";
                        }else{
                            echo "<option value='$row[user_id]'>$row[nama]</option>";
                        }
                    }
                ?>
            </select>
        
        </span>
    </div>	

	<div class="element-form">
		<label>Id Member </label>	
		<span><input type="text" minlength="5" maxlength="10" name="id_member" value="<?php echo $id_member; ?>" /></span>
	</div>	  

	<div class="element-form">
		<label>Status</label>	
		<span>
			<input type="radio" value="on" name="status" <?php if($status == "on"){ echo "checked"; } ?> required/> On
			<input type="radio" value="off" name="status" <?php if($status == "off"){ echo "checked"; } ?> /> Off		
		</span>
	</div>	   
	   
	<div class="element-form">
		<span><input type="submit" name="button" value="<?php echo $button; ?>" class="submit-my-profile" /></span>
	</div>	
</form>