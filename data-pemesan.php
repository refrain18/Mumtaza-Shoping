<?php
    if($user_id == false){
        $_SESSION["proses_pesanan"] = true;

        header("location: ".BASE_URL."login.html");
        exit;
    }

    
    if (isset($_GET['edit']) && $_GET['edit'] == 'on') {
        $edit = 'on';
        $button = "Update";        
    } else {
        $edit = 'off';
        $button = "Edit";
    }
    
    // Cek Jika Data Diri Di Ubah
    if (!empty($_POST)) {

        $nama_penerima = $_POST["nama_penerima"];
        $nomor_telepon = $_POST["nomor_telepon"];
        $kode_pos = $_POST["kode_pos"];

        // validasi alamat
        if(!preg_match("/^[a-zA-Z\s]*$/",$nama_penerima)){
            header("location: ".BASE_URL."index.php?page=data-pemesan&edit=on&user_id=$user_id&notif=nama_penerima");
        }elseif(strlen($kode_pos) < 5){
            header("location: ".BASE_URL."index.php?page=data-pemesan&edit=on&user_id=$user_id&notif=kode_pos");         
        }elseif(!preg_match("/^[0-9]*$/",$nomor_telepon)){
            header("location: ".BASE_URL."index.php?page=data-pemesan&edit=on&user_id=$user_id&notif=nomor_telepon");    
        }else{
        // Update Data
        $data_diri_baru = [
            'nm_penerima' => $_POST['nama_penerima'],
            'no_tlp' => $_POST['nomor_telepon'],
            'provinsi' => $_POST['provinsi'],
            'kota' => $_POST['kota'],
            'kode_pos' => $_POST['kode_pos'],
            'alamat' => $_POST['alamat']
        ];
        }
    }
        
    

    // var_dump($post_data_diri, $data_diri_baru);

    //Query Select 
    $query = mysqli_query($koneksi, "SELECT user.nama, user.phone, user.provinsi, user.kota, user.kode_pos, user.alamat FROM user WHERE user_id = $user_id");
    $row=mysqli_fetch_array($query);

    $nama = $row["nama"];
    $phone = $row["phone"];
    $provinsi = $row["provinsi"];
    $kota = $row["kota"];
    $kode_pos = $row["kode_pos"];
    $alamat = $row["alamat"];



    if($edit == 'on') {
        $akses = '';
        $hidden = '';
        $form_act = 'index.php?page=data-pemesan';
        $dis = 'disabled';
    } else {
        $akses = 'readonly';
        $hidden = 'hidden';
        $form_act = 'index.php?page=proses_pemesanan';
        $dis = '';
  
    }
?>

<div id="frame-data-pengiriman">

    <h3 class="label-data-pengiriman">Alamat Pengiriman Barang</h3>

    <div id="frame-form-pengiriman">

        <form action="<?php echo BASE_URL.$form_act; ?>" method="POST">

        <?php
            $notif = isset($_GET['notif']) ? $_GET['notif'] : false;
            
            if($notif == 'nama_penerima') {
                echo  "<div class='notif' id='notif'>Maaf, nama yang kamu masukan harus huruf</div>";
            }elseif($notif == 'nomor_telepon'){
                echo "<div class='notif' id='notif'>Maaf, nomor telepon yang dimasukan harus angka</div>";
            }elseif ($notif == 'kode_pos'){ 
                echo "<div class='notif' id='notif'>Maaf, kode pos harus 5 angka</div>"; 
            }
        ?>        
            <div class="element-form">
                <label>Nama Penerima</label>	
                <span><input type="text" name="nama_penerima" value="<?php echo !empty($data_diri_baru) ? $data_diri_baru['nm_penerima'] : $nama; ?>" <?php echo $akses?> required/></span>
            </div>

            <div class="element-form">
                <label>Nomor Telepon</label>	
                <span><input style=" width : 98%; height : 23px;" type="phone" minlength="11" maxlength="12" name="nomor_telepon" value="<?php echo !empty($data_diri_baru) ? $data_diri_baru['no_tlp'] : $phone; ?>" <?php echo $akses?> required/></span>
            </div>	

            <div class="element-form">
                <label>Provinsi</label>
                <span><input type="text" id="input_provinsi" name="provinsi" list="data-provinsi" onchange="update_list_kota(this.value);" value="<?php echo !empty($data_diri_baru) ? $data_diri_baru['provinsi'] : $provinsi; ?>" <?php echo $akses?> required/></span>
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
                <span><input type="text" id="input_kota" name="kota" list="data-kota" value="<?php echo !empty($data_diri_baru) ? $data_diri_baru['kota'] : $kota; ?>" <?php echo $akses?> required/></span>
                
                <datalist id="data-kota">
                    <!-- Data Kota -->
                </datalist>
            </div>

            <div class="element-form">
                <label>Kode Pos</label>
                <span><input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                style=" width : 98%; height : 23px;" min="0" maxlength="5" type="number" name="kode_pos" value="<?php echo !empty($data_diri_baru) ? $data_diri_baru['kode_pos'] : $kode_pos; ?>" <?php echo $akses?> required/></span>
            </div>

            <div class="element-form">
                <label>Alamat Pengiriman</label>	
                <span><textarea name="alamat" id="" cols="30" rows="5" <?php echo $akses?> required><?php echo !empty($data_diri_baru) ? $data_diri_baru['alamat'] : $alamat; ?> </textarea></span>
            </div>

            <div class="element-form">
            <?php if($edit == "on") : ?>
                <span style="text-align: right">
                    <input type="submit" name="button" value="<?php echo $button; ?>" class="submit-my-profile" />
                    <!-- <a href="<?php //echo BASE_URL."index.php?page=data-pemesan&edit=off" ;?>" class="submit-my-profile"><?php //echo $button; ?></a> -->
                </span>
            <?php else : ?>
                <span style="text-align: right">
                    <a href="<?php echo BASE_URL."index.php?page=data-pemesan&edit=on" ;?>" class="submit-my-profile"><?php echo $button; ?></a>
                </span>
            <?php endif; ?>
            </div>

            <span><hr></span>
 
            <div class="element-form">
                <label>Metode Pembayaran : </label>
                <span>
                    <input type="radio" id="COD" name="metode_pembayaran" value="COD" onclick="cek_ongkir(this.value)" <?php echo $dis ?> required>
                    <span style="display: inline-block;">
                        <label for="COD">COD</label>
                    </span>
                    <input type="radio" id="Transfer" name="metode_pembayaran" value="Transfer" onclick="cek_ongkir(this.value)" <?php echo $dis ?> required>
                    <span style="display: inline-block;">
                        <label for="Transfer">Transfer Bank</label>
                    </span>
                </span>
            </div>

            <div class="element-form">
                <label>Metode Pengiriman : </label>
                <span>
                    <select name="metode_pengiriman" id="daftar_metode_pengiriman" <?php echo $dis ?> required >
                        <option value="" selected>-Pilih-</option>
                        <!-- Opsi akan muncul setelah memilih Metode Pembayaran -->
                    </select>
                </span>
            </div>

            <div class="element-form">
                <span><input type="submit" value="submit"/></span>
            </div>

        </form>
        
        <!-- Menghapus data POST -->
        <?php if (isset($_POST)) {
            $_POST = array();
        } ?>

    </div>

</div>

<div id="frame-data-detail">
    <h3 class="label-data-pengiriman">Detail Order</h3>
    
    <div id="frame-detail-order">
        
        <table class="table-list">
            <tr>
                <th class='kiri'>Nama Barang</th>
                <th class='tengah'>Qty</th>
                <th class='kanan'>Total</th>
            </tr>
            <?php
                $subtotal = 0;
                $total_harga_diskon = 0;
                foreach($keranjang AS $key => $value){

                    $barang_id = $key;

                    $nama_barang = $value['nama_barang'];
                    $harga = $value['harga'];
                    $quantity = $value['quantity'];
                    $diskon = $value["diskon"];

                    $harga_diskon = $harga * ($diskon/100);
                    $total_harga_diskon = $harga - $harga_diskon;

                    if ($id_member) {
                        $total = $quantity * $total_harga_diskon;
                    }else{
                        $total = $quantity * $harga;
                    }
                    $subtotal = $subtotal + $total;

                    echo "<tr>
                            <td class='kiri'>$nama_barang</td>
                            <td class='tengah'>$quantity</td>
                            <td class='kanan'>".rupiah($total)."</td>
                          </tr>";
                }
                echo "<tr>
                        <td colspan='2' class='kanan'><b>Sub Total</b></td>
                        <td class='kanan'><b>".rupiah($subtotal)."</b></td>
                     </tr>"; 
            ?>


        </table>

    </div>

</div>