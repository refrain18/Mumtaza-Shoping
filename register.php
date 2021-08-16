<?php

    if($user_id) {
        header("location: ".BASE_URL);
    }

?>


<div id="container-user-akses">

    <form action="<?php echo BASE_URL."proses_register.php"; ?>" method="POST">   
        
    <?php
        $notif = isset($_GET['notif']) ? $_GET['notif'] : false;
        $nama_lengkap = isset($_GET['nama_lengkap']) ? $_GET['nama_lengkap'] : false;
        $email = isset($_GET['email']) ? $_GET['email'] : false;
        $phone = isset($_GET['phone']) ? $_GET['phone'] : false;
        $provinsi = isset($_GET['provinsi']) ? $_GET['provinsi'] : false;
        $kota = isset($_GET['kota']) ? $_GET['kota'] : false;
        $kode_pos = isset($_GET['kode_pos']) ? $_GET['kode_pos'] : false;
        $alamat = isset($_GET['alamat']) ? $_GET['alamat'] : false;

        if($notif == 'require'){
            echo "<div class='notif'>Maaf, kamu harus melengkapi form dibawah ini</div>";
        }else if($notif == 'phone'){
            echo "<div class='notif'>Maaf, nomor telepon yang dimasukan harus angka</div>";    
        }else if($notif == 'password'){
            echo "<div class='notif'>Maaf, password yang kamu masukan tidak sama</div>";
        }else if($notif == 'email'){
            echo "<div class='notif'>Maaf, email yang kamu masukan sudah terdaftar</div>";    
        }elseif ($notif == 'nama_lengkap') {
            echo "<div class='notif'>Maaf, nama yang kamu masukan harus huruf</div>";
        }elseif ($notif == 'passwordChar') {
            echo "<div class='notif'>Maaf, password harus menyertakan setidaknya satu huruf besar dan satu angka</div>";
        }elseif ($notif == 'kodepos') {
            echo "<div class='notif'>Maaf, kode pos harus 5 angka</div>";
        }
    ?>

        <div class="element-form">
            <label>Nama Lengkap</label>
            <span><input type="text" name="nama_lengkap" value="<?php echo $nama_lengkap; ?>" required/></span>
        </div>
    
        <div class="element-form">
            <label>Email</label>
            <span><input style=" width : 98%; height : 23px;" type="email" name="email" value="<?php echo $email; ?>" required/></span>
        </div>

        <div class="element-form">
            <label>Nomor Telepon/Handphone</label>
            <span><input type="phone" style=" width : 98%; height : 23px;" name="phone" minlength="11" maxlength="12" value="<?php echo $phone; ?>" required/></span>
        </div>

        <div class="element-form">
            <label>Provinsi</label>
            <span><input type="text" id="input_provinsi" name="provinsi" list="data-provinsi" value="<?php echo $provinsi; ?>" onchange="update_list_kota(this.value);"  required/></span>
        </div>
            
        <?php if(isset($list_provinsi) && is_array($list_provinsi)) : ?>
            <datalist id="data-provinsi">
                <?php foreach ($list_provinsi['rajaongkir']['results'] as $key => $value) : ?>
                    <option><?php echo $value['province'] ?></option>
                <?php endforeach; ?>
            </datalist>
        <?php endif; ?>

        <div class="element-form">
            <label>Kota</label>
            <span><input type="text" id="input_kota" name="kota" value="<?php echo $kota ?>" list="data-kota"   required/></span>
            
            <datalist id="data-kota">
                <!-- Data Kota -->
            </datalist>
        </div>

        <div class="element-form">
            <label>Kode Pos</label>
            <span><input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
            style=" width : 98%; height : 23px;" min="0" maxlength="5" type="number"  name="kode_pos" value="<?php echo $kode_pos ?>"  required/></span>
        </div>

        <div class="element-form">
            <label>Alamat Lengkap</label>
            <span><textarea name="alamat" required><?php echo $alamat; ?></textarea></span>
        </div>

        <div class="element-form">
            <div class="label-password">
                <label>Password</label>
                <i class="btn-hide-show far fa-eye-slash" title="show password" required></i>
            </div>
            <span><input type="password" minlength="8" name="password" class="input-password" /></span>
        </div>

        <div class="element-form">
            <label>Re-type password</label>
            <span><input type="password" name="re_password" class="input-password" /></span>
        </div>

        <div class="element-form">
            <span><input type="submit" value="register" /></span>
        </div>
    </form>

</div>