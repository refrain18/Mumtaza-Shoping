<?php
    ob_start(); //menagatasi double header
    session_start();

    include_once("function/koneksi.php");
    include_once("function/helper.php");


    $page = isset($_GET['page']) ? $_GET['page'] : false; 
    $kategori_id = isset($_GET['kategori_id']) ? $_GET['kategori_id'] : false; 
    $bb_id = isset($_GET['bb_id']) ? $_GET['bb_id'] : false; 
    $no_member = isset($_GET['no_member']) ? $_GET['no_member'] : false; 

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
    $id_member = isset($_SESSION['id_member']) ? $_SESSION['id_member'] : false;
    $nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : false;
    $level = isset($_SESSION['level']) ? $_SESSION['level'] : false;
    $keranjang = isset($_SESSION['keranjang']) ? $_SESSION['keranjang'] : array();
    $totalBarang = count($keranjang);

    // Menarik data API
    $list_provinsi = curl_get('https://api.rajaongkir.com/starter/province');
    ob_flush(); //menagatasi double header
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mumtaza Shoping</title>

    <link href="<?php echo BASE_URL."css/fontawesome-free-5.15.1-web/css/all.min.css"; ?>" type="text/css" rel="stylesheet" />
    <link href="<?php echo BASE_URL."css/style.css"; ?>" type="text/css" rel="stylesheet" />
		<link href="<?php echo BASE_URL."css/banner.css"; ?>" type="text/css" rel="stylesheet" />
		<link href="<?php echo BASE_URL."libs/lightslider-master/dist/css/lightslider.css"; ?>" type="text/css" rel="stylesheet" />
    
		
		<script src="<?php echo BASE_URL."js/jquery-3.1.1.min.js"; ?>"></script>
		<script src="<?php echo BASE_URL."js/Slides-SlidesJS-3/source/jquery.slides.min.js"; ?>"></script>
		<script src="<?php echo BASE_URL."js/script.js"; ?>"></script>
		<script src="<?php echo BASE_URL."libs/lightslider-master/dist/js/lightslider.js"; ?>"></script>
		
    <!-- banner -->
		<script>
		$(function() {
			$('#slides').slidesjs({
				height: 350,
				play: { auto : true,
					    interval : 3000
					  },
				navigation : false
			});
		});
		</script>		


</head>
<body>
    
    <div id="container">
        <div id="header">
        <a href="<?php echo BASE_URL."index.php"; ?>">
                <img src="<?php echo BASE_URL."images/logo11.png"; ?>"/>
                </a>

            <div id="menu">
                <div id="user">
                <?php
                    if($level == "superadmin"){
                      $laporan = "<a href='".BASE_URL."index.php?page=laporan&module=laporan_pesanan&action=list'>Laporan</a>";
                    }else if ($level == "customer") {
                      $laporan = "";
                    }else{
                      $laporan = "";
                    }
                        if($user_id){
                            echo "Hi <b>$nama</b>, 
                                  <a href='".BASE_URL."index.php?page=my_profile&module=pesanan&action=list'>My Profile</a>
                                  {$laporan}";
                        }else{
                            echo "<a href='".BASE_URL."?page=register'>Daftar</a>";
                        }
                    ?>
                  <?php
                      if($level == "superadmin"){
                         $style = 'style="display: inline-block; padding-left: 65px;"';
                      }else if ($level == "customer") {
                         $style = 'style="display: inline-block; padding-left: 135px;"';
                      }else{
                        $style = 'style="display: inline-block; padding: 0px 220px 0px 254px;"';

                      }
                  ?>
                  <form action="<?php echo BASE_URL."penulusuran.php"; ?>" <?php echo $style ?>   method="GET">
                          <input type="text" name="keyword"  size="50px" style="border: none; height: 38px; position: relative; bottom: 4px;"/>
                          <button class="button-search"><svg style="width:35px;height:35px" viewBox="0 0 24 24">
                          <path fill="currentColor" d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" />
                          </svg>
                          </button>
                  </form>
                    <?php
                    if($level == "superadmin"){
                      $laporan = "<a href='".BASE_URL."index.php?page=laporan&module=laporan_pesanan&action=list'>Laporan</a>";
                    }else if ($level == "customer") {
                      $laporan = "";
                    }else{
                      $laporan = "";
                    }
                        if($user_id){
                            echo "<a href='".BASE_URL."logout.php'>Logout</a>";
                        }else{
                            echo "<a href='".BASE_URL."?page=login'>Masuk</a>";
                        }
                    ?>

                </div>
                <a href="<?php echo BASE_URL."?page=keranjang"; ?>" id="button-keranjang">
                    <img src="<?php echo BASE_URL."images/cart1.png"; ?>"/>
                    <?php
                        if($totalBarang != 0){
                            echo "<span class='total-barang'>$totalBarang</span>";
                        }
                    ?>
                </a>
            </div>        
        </div>

        <div id="content">
            <?php
                $filename = "$page.php";

                if(file_exists($filename)){
                    include_once($filename);
                }else{
                    include_once("main.php");
                }
            ?>
        </div>
        
        <div id="datacont">

        </div>
        
        <div id="footer1" >
            <p style="display: inline-block;">Kontak Kami :</p>
            <p style="display: inline-block;">081211288172</p>
            <p style="display: inline-block;">/</p>
            <p style="display: inline-block;">mumtaza_jewerly@gmail.com</p>
        <!-- </div>
        <div id="footer" style="text-align: center;"> -->
            <p style="display: inline-block; padding-left: 14.7cm;">&copy; 2020 Mumtaza Jewerly</p>
        </div>

    </div>

    <!-- library sweetalert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php
        if (isset($_GET['add_status'])) {
            echo "
            <script>
            $(document).ready(function(){
                swal({
                  text: 'Berhasil memasukan barang ke keranjang',
                  button: 'Oke'
                })
                window.history.replaceState({}, document.title, '/' + 'index.php')
            })
            </script>";
        }
    ?>

<!-- JS Manual -->

<!-- banner branded -->
<script>
  $(document).ready(function() {
    $("#content-slider").lightSlider({
        auto: true,
        pauseOnHover: true,
        item: 4,
        loop: true,
        // slideMargin: 3,
        // slideWidth: 200,
    });
  });
</script>

<!-- banner beautyProduk -->
<script>
  $(document).ready(function() {
    $("#beauty-slider").lightSlider({
        auto: true,
        pauseOnHover: true,
        item: 4,
        loop: true,
        // slideMargin: 3,
        // slideWidth: 200,
    });
  });
</script>

<!-- banner healthyProduk -->
<script>
  $(document).ready(function() {
    $("#healthy-slider").lightSlider({
        auto: true,
        pauseOnHover: true,
        item: 4,
        loop: true,
        // slideMargin: 3,
        // slideWidth: 200,
    });
  });
</script>

<!-- banner foodProduk -->
<script>
  $(document).ready(function() {
    $("#food-slider").lightSlider({
        auto: true,
        pauseOnHover: true,
        item: 4,
        loop: true,
        // slideMargin: 3,
        // slideWidth: 200,
    });
  });
</script>

<!-- banner clothingProduk -->
<script>
  $(document).ready(function() {
    $("#clothing-slider").lightSlider({
        auto: true,
        pauseOnHover: true,
        item: 4,
        loop: true,
        // slideMargin: 3,
        // slideWidth: 200,
    });
  });
</script>

<script>

    // Menghilangkan Notif dalam interval waktu tertentu
    $('#notif').delay(3000).fadeOut(300);

    // Sub Func untuk menarik list kota berdasarkan provinsi tujuan
    function get_list_kota(id_provinsi) {
      const xhr = new XMLHttpRequest();
      var params = `?id_province=${id_provinsi}`;
      var res;

      // Response Blok - Get City List
      xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

          // Convert Json String to JSON
          res = JSON.parse(this.responseText);

          // Response Handler
          if (res.status) {
            // Clear <Option> from Data List of data-kota
            document.querySelector('#data-kota').innerHTML = '';

            // Deploy Response
            for (key in res.data.rajaongkir.results) {
              // Create  Element Data List City by Province
              var datalist_kota = document.querySelector('#data-kota');
              var opt = document.createElement("OPTION");
              opt.setAttribute("value", res.data.rajaongkir.results[key]['city_name']);
              datalist_kota.appendChild(opt);
            }
          } else {
            // Error Response
            alert(`Terjadi kesalahan pada Server ${res.message}`);
          }

          // debugg
          console.log(
            res.data.rajaongkir.results[key]['city_id'],
            res.data.rajaongkir.results[key]['city_name']
          );
        }
      };

      // Send Request
      xhr.open("GET", "request/get_list_kota_by_provinsi.php" + params, true);
      xhr.send();
    }

    // Func untuk update list kota tujuan
    function update_list_kota(nama_provinsi_tujuan) {
      alert("Menjalankan Fungsi Update List Kota! Tunggu beberapa detik sampai list pada input kota terupdate...");
      const xhr = new XMLHttpRequest();
      var res;
      var id_provinsi_tujuan;

      // Response Blok
      xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          // Convert Json String to JSON
          res = JSON.parse(this.responseText);

          // Response Handler
          if (res.status) {
            // Deploy Response
            for (key in res.data.rajaongkir.results) {
              // Get Province ID by given Province NAME
              if (res.data.rajaongkir.results[key]['province'] == nama_provinsi_tujuan) {
                // Get Province ID
                id_provinsi_tujuan = res.data.rajaongkir.results[key]['province_id'];
              }
            }

            // Get List City by Province ID
            if (id_provinsi_tujuan) {
              // Jalankan Fungsi
              get_list_kota(id_provinsi_tujuan);
            }
          } else {
            // Error Response
            alert(`Terjadi kesalahan pada Server ${res.message}`);
          }

          // Debug
          // console.log(
          //   res.data.rajaongkir.results[key]['province_id'],
          //   res.data.rajaongkir.results[key]['province']
          // );
        }
      };

      // Send Request
      xhr.open("GET", "request/get_list_provinsi.php", true);
      xhr.send();
    }

    // Sub Func untuk mengecek jangkauan Kurir Toko Mumtaza
    function cek_jangkauan_kurir_toko(kota_tujuan, metode_pembayaran) {
      const xhr = new XMLHttpRequest();
      const params = `?kota_tujuan=${kota_tujuan}`;
      var res;

      // Response Blok
      xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          // Convert Json String to JSON
          res = JSON.parse(this.responseText);

          // Response Handler
          if (res.status) {
            // Persiapan Parent
            var parent_kurir = document.querySelector('#daftar_metode_pengiriman');

            // Response
            if (res.data != '') {
              if (metode_pembayaran == 'COD') {
                if (parent_kurir.querySelectorAll('.kurir_tambahan')) {
                  var opt_grp = parent_kurir.querySelectorAll(".kurir_tambahan");
                  opt_grp.forEach(function(el) {
                    el.remove();
                  });
                }
              }
              // Info Kurir Mumtaza
              alert(res.message);
            } else {
              if (metode_pembayaran == 'Transfer') {
                if (parent_kurir.querySelector('#kurir_mumtaza')) {
                  var kurir_opt = document.querySelector("#kurir_mumtaza");
                  kurir_opt.parentNode.removeChild(kurir_opt);
                }
              } else if (metode_pembayaran == 'COD') {
                // Membersihkan Opsi Metode Pengiriman
                if (parent_kurir.querySelector('#kurir_mumtaza')) {
                  // Hapus Semua Opsi Metode Pengiriman
                  parent_kurir.innerHTML = '';

                  // Membuat Option Default
                  var new_opt = document.createElement("OPTION");
                  new_opt.innerHTML = '-Pilih-';
                  parent_kurir.appendChild(new_opt);
                }
              }

              // Info Kurir Mumtaza
              alert(res.message);
            }
          } else {
            // Error Response
            alert(`Terjadi kesalahan pada Server ${res.message}`);
          }

          // Debug
          console.log(res);
        }
      };

      // Send Request
      xhr.open("GET", "request/cek_jangkauan_kurir_mumtaza.php" + params, true);
      xhr.send();
    }

    // Func untuk mengecek ongkir berdasarkan kota asal & tujuan
    function cek_ongkir(metode_pembayaran) {
      alert("Menjalankan Fungsi Cek Ongkir! Tunggu beberapa detik sampai list Metode Pengiriman terupdate...");
      const xhr = new XMLHttpRequest();
      var kota_tujuan = document.querySelector('#input_kota').value ? document.querySelector('#input_kota').value : '';
      const params = `?kota_tujuan=${kota_tujuan}`;
      var res;

      // Response Blok
      xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          // Convert Json String to JSON
          res = JSON.parse(this.responseText);

          // Response Handler
          if (res.status) {
            // Persiapan Parent
            var parent_kurir = document.querySelector('#daftar_metode_pengiriman');

            // Bersihkan Option Metode Pengiriman
            if (parent_kurir.querySelector('#kurir_mumtaza')) {
              var kurir_opt = document.querySelector("#kurir_mumtaza");
              kurir_opt.parentNode.removeChild(kurir_opt);
            }
            if (parent_kurir.querySelectorAll('.kurir_tambahan')) {
              var opt_grp = parent_kurir.querySelectorAll(".kurir_tambahan");
              opt_grp.forEach(function(el) {
                el.remove();
              });
            }

            // Membuat Option Group Default
            var new_opt_group = document.createElement("OPTGROUP");
            new_opt_group.setAttribute("label", "MUMTAZA");
            new_opt_group.setAttribute("id", "kurir_mumtaza");
            // Membuat Daftar Paket Kurir
            var new_opt = document.createElement("OPTION");
            new_opt.setAttribute("value", 'mumtaza_express_0');
            new_opt.innerHTML = `Paket Express (Free)`;
            new_opt_group.appendChild(new_opt);
            // Menambahkan Opt Group ke Parent
            parent_kurir.appendChild(new_opt_group);

            // Deploy Response for JNE
            if (res.data.jne) {
              // Membuat Option Group
              var new_opt_group = document.createElement("OPTGROUP");
              new_opt_group.setAttribute("label", "JNE");
              new_opt_group.setAttribute("class", "kurir_tambahan");

              // Membuat Daftar Paket Kurir
              for (key in res.data.jne) {
                var new_opt = document.createElement("OPTION");
                new_opt.setAttribute("value", `jne_${res.data.jne[key]['service']}_${res.data.jne[key].cost[0].value}`);
                new_opt.innerHTML = `Paket ${res.data.jne[key]['service']} (${res.data.jne[key].cost[0].value})`;
                new_opt_group.appendChild(new_opt);
              }

              // Menambahkan Opt Group ke Parent
              parent_kurir.appendChild(new_opt_group);
            }

            // Deploy Response for TIKI
            if (res.data.tiki) {
              // Membuat Option Group
              var new_opt_group = document.createElement("OPTGROUP");
              new_opt_group.setAttribute("label", "TIKI");
              new_opt_group.setAttribute("class", "kurir_tambahan");

              // Membuat Daftar Paket Kurir
              for (key in res.data.tiki) {
                var new_opt = document.createElement("OPTION");
                new_opt.setAttribute("value", `tiki_${res.data.tiki[key]['service']}_${res.data.tiki[key].cost[0].value}`);
                new_opt.innerHTML = `Paket ${res.data.tiki[key]['service']} (${res.data.tiki[key].cost[0].value})`;
                new_opt_group.appendChild(new_opt);
              }

              // Menambahkan Opt Group ke Parent
              parent_kurir.appendChild(new_opt_group);
            }

            // Cek Metode Pembayaran
            if (metode_pembayaran == 'Transfer') {
              // Cek Jangkauan Kurir Mumtaza
              cek_jangkauan_kurir_toko(kota_tujuan, metode_pembayaran);
            } else if (metode_pembayaran == 'COD') {
              // Cek Jangkauan Kurir Mumtaza
              cek_jangkauan_kurir_toko(kota_tujuan, metode_pembayaran);
            }

          } else {
            // Error Response
            alert(`Terjadi kesalahan pada Server ${res.message}`);
          }

          // Debug
          console.log(res);
        }
      };

      // Send Request
      xhr.open("GET", "request/get_ongkir.php" + params, true);
      xhr.send();
    }

</script>

</body>
</html>