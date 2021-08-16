<?php 
    // $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : "";
    
    if (isset($_GET['edit']) && $_GET['edit'] == 'on') {
        $edit = 'on';
        $button = "Update";        
    } else {
        $edit = 'off';
        $button = "Edit";
    }

    
    $queryUser = mysqli_query($koneksi, "SELECT * FROM user WHERE user_id='$user_id'");
    $row=mysqli_fetch_array($queryUser);
    
    $nama = $row["nama"];
    $email = $row["email"];
    $phone = $row["phone"];
    $provinsi = $row["provinsi"];
    $kota = $row["kota"];
    $kode_pos = $row["kode_pos"];
    $alamat = $row["alamat"];
    $password = $row["password"];

    // var_dump($user_id, $row, $row['nama'], $row['email'], $row['phone'], $row['alamat'], $row['password']);

    if($edit == 'on') {
        $akses = '';
        $hidden = '';
    } else {
        $akses = 'disabled';
        $hidden = 'hidden';
    }
  ?>
  <form action="<?php echo BASE_URL."module/data_diri/action.php?user_id=$user_id"?>" method="POST">
  
    <?php

        $notif = isset($_GET['notif']) ? $_GET['notif'] : false;

        if($notif == 'nama') {
            echo "<div class='notif' id='notif'>Maaf, nama yang kamu masukan harus huruf</div>";
        }elseif($notif == 'phone'){
            echo "<div class='notif' id='notif'>Maaf, nomor telepon yang dimasukan harus angka</div>";    
        }elseif($notif == 'email'){
            echo "<div class='notif' id='notif'>Maaf, email yang kamu masukan sudah terdaftar</div>";    
        }elseif ($notif == 'passwordChar') {
            echo "<div class='notif' id='notif'>Maaf, Harus menyertakan setidaknya satu huruf besar dan satu angka</div>";
        }elseif ($notif == 'kodepos') {
            echo "<div class='notif' id='notif'>Maaf, kode pos harus 5 angka</div>";
        }

        if (isset($_GET['pesan_err'])) {
                $pass_err = '<span id="notif" style="color: red; padding: 5px 10px; margin-bottom: 5px">*Password tidak sama</span>';
            }
        


        if ( isset($_GET['notif']) ) {
            echo notifTransaksi($_GET['notif'],"Data Diri");
        }
        
    ?>

    <input type="hidden" name="email_lama" value="<?php echo $email; ?>">
    
    <div class="element-form">
        <label>Nama Lengkap</label>	
        <span><input type="text" name="nama" value="<?php echo $nama; ?>" <?php echo $akses?> required/></span>
    </div>	

    <div class="element-form">
        <label>Email</label>	
        <span><input type="email" style=" width : 98%; height : 23px;" name="email" value="<?php echo $email; ?>" <?php echo $akses?> required/></span>
    </div>		

    <div class="element-form">
        <label>Phone</label>	
        <span><input type="phone" style=" width : 98%; height : 23px;" minlength="11" maxlength="12" name="phone" value="<?php echo $phone; ?>" <?php echo $akses?> required/></span>
    </div>	

    <div class="element-form">
        <label>Provinsi</label>
        <span><input type="text" id="input_provinsi" name="provinsi" value="<?php echo $provinsi; ?>" list="data-provinsi" onchange="update_list_kota(this.value);" <?php echo $akses?> required/></span>

        <?php if(isset($list_provinsi) && is_array($list_provinsi)) : ?>
            <datalist id="data-provinsi">
                <?php foreach ($list_provinsi['rajaongkir']['results'] as $key => $value) : ?>
                    <option><?php echo $value['province'] ?></option>
                <?php endforeach; ?>
            </datalist>
        <?php endif; ?>

    </div>

    <div class="element-form">
        <label>Kota</label>
        <span><input type="text" id="input_kota" name="kota" list="data-kota"  value="<?php echo $kota; ?>" <?php echo $akses?> required/></span>

        <datalist id="data-kota">
            <!-- Data Kota -->
        </datalist>
    </div>

    <div class="element-form">
        <label>Kode Pos</label>
        <span><input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
            style=" width : 98%; height : 23px;" min="0" maxlength="5" type="number" name="kode_pos" value="<?php echo $kode_pos; ?>" <?php echo $akses?> required/></span>
    </div>

    <div class="element-form">
        <label>Alamat Lengkap</label>	
        <span><textarea name="alamat" id="" cols="30" rows="5" <?php echo $akses?> required><?php echo $alamat; ?> </textarea></span>
    </div>		

    <div class="element-form" <?php echo $hidden ;?>>
        <div class="label-password">
        <label>Password</label>
        <i class="btn-hide-show fas fa-eye-slash title="show password"></i>
        </div>
        <span><input type="password" minlength="8" name="password" class="input-password"<?php echo $akses?>/></span>
    </div>		
 
    <div class="element-form" <?php echo $hidden?>>
        <label>Re-type Password</label>	
        <span><input type="password" name="repassword" class="input-password"/></span>
    </div>		

    <?php echo isset($pass_err) ? $pass_err : ''; ?>

    <div class="element-form">
        <?php if($edit == "on") : ?>
            <span style="text-align: right">
                <input type="submit"  name="button" value="<?php echo $button; ?>" class="submit-my-profile" />
            </span>
        <?php else : ?>
            <span style="text-align: right">
                <a href="<?php echo BASE_URL."index.php?page=my_profile&module=data_diri&action=form&edit=on" ;?>" class="submit-my-profile"><?php echo $button; ?></a>
            </span>
        <?php endif; ?>

    </div>	
  </form>