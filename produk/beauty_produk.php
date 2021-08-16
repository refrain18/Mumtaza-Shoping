<?php
    include_once("../function/koneksi.php");
    include_once("../function/helper.php");

    session_start();

    $kategori_id = isset($_GET['kategori_id']) ? $_GET['kategori_id'] : false; 

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
    $id_member = isset($_SESSION['id_member']) ? $_SESSION['id_member'] : false;
    $nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : false;
    $level = isset($_SESSION['level']) ? $_SESSION['level'] : false;
    $keranjang = isset($_SESSION['keranjang']) ? $_SESSION['keranjang'] : array();
    $totalBarang = count($keranjang);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lihat Barang Beauty</title>

        <link href="<?php echo BASE_URL."css/fontawesome-free-5.13.1-web/css/all.min.css"; ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo BASE_URL."css/style.css"; ?>" type="text/css" rel="stylesheet" />
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
    <div id="kanan">                    
        <div id="frame-barang">
        <ul>
            <?php
            // Prepare Diskon Logic
            if ($id_member) {
                $query = mysqli_query($koneksi, "SELECT barang.*, kategori.kategori, banner_branded.banner_branded FROM barang JOIN kategori ON barang.kategori_id=kategori.kategori_id JOIN banner_branded ON barang.bb_id=banner_branded.bb_id WHERE kategori='Beauty' AND barang.status='on' AND barang.stok > 0 ORDER BY rand() DESC ") OR die(mysqli_error($koneksi));
            } else {
                $query = mysqli_query($koneksi, "SELECT barang.*, kategori.kategori, banner_branded.banner_branded FROM barang JOIN kategori ON barang.kategori_id=kategori.kategori_id JOIN banner_branded ON barang.bb_id=banner_branded.bb_id WHERE kategori='Beauty' AND barang.status='on' AND barang.stok > 0 ORDER BY rand() DESC ");
            }
                
                $no=1;

                // Prepare Strike Style
                $stripOp = '';
                $stripEd = '';

                while($row=mysqli_fetch_assoc($query)){

                    $kategori = strtolower($row["kategori"]);
                    $barang = strtolower($row["nama_barang"]);
                    $barang = str_replace(" ", "-", $barang);
                    $brand = strtolower($row["banner_branded"]);


                    $style=false;
                    if($no == 3){
                        $style="style='margin-right:0px'";
                        $no=0;
                    }
                    
                    // Pengkondisian untuk menampilkan harga distributor
                    if ($level == "superadmin") {
                        $show_harga_dist = "<p class='priced'>".rupiah($row['harga_distributor'])."</p>";
                    } else {
                        $show_harga_dist = "";
                    }

                    if ($id_member) {
                        $hrg_asli = $row['harga'];
                        $disc = $row['diskon'];
                        $hrg_disc = '';

                        // Perhitungan Diskon
                        $hrg_disc = $hrg_asli * ($disc/100);
                        $total_harga_diskon = $hrg_asli - $hrg_disc;
                        $show_harga_disc = "<p class='diskon'>"."<span>{$disc}%</span> ".rupiah($total_harga_diskon)."</p>";

                        if ($disc != 0) {
                            $stripOp = '<del>';
                            $stripEd = '</del>';
                        }else {
                            $stripOp = '';
                            $stripEd = '';
                            $show_harga_disc = '';
                        }
                    } else {
                        $show_harga_disc = '';
                    }

                    echo "<li $style>
                            <div>
                            <p class='price'>{$stripOp}".rupiah($row['harga'])."{$stripEd}</p>
                            {$show_harga_dist}
                            {$show_harga_disc}
                            </div>
                            <a href='".BASE_URL."$row[barang_id]/$kategori/$barang.html'>
                                <img src='".BASE_URL."images/barang/$row[gambar]' />
                            </a> 
                            <div class='keterangan-gambar'> 
                                <p><a href='".BASE_URL."$row[barang_id]/$kategori/$barang.html'>$row[nama_barang]</a></p>
                                <span>Stok : $row[stok]</span>
                                <p class='brand'>".$row['banner_branded']."</p>
                            </div>
                            <div class='button-add-cart'>
                                <a href='".BASE_URL."tambah_keranjang.php?barang_id=$row[barang_id]'>+ add to cart</a>
                            </div>";

                    $no++;        
                }

            ?>
        
        </ul>
        
        </div>
    </div>
</div>
    
        <div id="footer">
            <p>copyright Mumtaza Jewerly 2020</p>
        </div>

    </div>

</body>
</html>