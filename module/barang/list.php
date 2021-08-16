<?php
       $search = isset($_GET["search"]) ? $_GET["search"] : false;
    
        $where = "";
        $search_url = "";
        if($search){
            $search_url = "&search=$search";
            $where = "WHERE barang.nama_barang LIKE '%$search%' || kategori.kategori LIKE '%$search%' || banner_branded.banner_branded LIKE '%$search%'";                
        } 
?>

<div id="frame-tambah">
    <div id="left">
        <form action="<?php echo BASE_URL."index.php"; ?>" method="GET">
                <input type="hidden" name="page" value="<?php echo $_GET["page"] ?>" />
                <input type="hidden" name="module" value="<?php echo $_GET["module"] ?>" />
                <input type="hidden" name="action" value="<?php echo $_GET["action"] ?>" />
                <input type="text" name="search" value="<?php echo $search; ?>" size="30px" placeholder="Ketikan Nama Barang atau Kategori"/>
                <input type="submit" value="Search" />
        </form>
    </div>
    <div id="right">
            <a class="tombol-action" href="#popup">Cetak</a>
            <a href="<?php echo BASE_URL."index.php?page=my_profile&module=barang&action=form"; ?>" class="tombol-action">+ Tambah Barang</a>
    </div>
</div>

<div id="popup">
    <div class="windowStok">
        <a href="#" class="close-button" title="Close">X</a>
        <h4><center> Cetak PDF Laporan Stok</center></h4>

        <form action="<?php echo "module/barang/cetak.php"; ?>" target='_BLANK' method="POST">
        <div class="element-form">      
            <label>Stok</label>
            <input type="text" name="stk_a"  size="3px" /> S/D <input type="text" name="stk_b"  size="3px" />
        </div> 
        <script type="text/javascript">
            function run(){
                var cb = document.getElementById("cb");
                
                if(document.getElementById("cekbox").checked == true){
                    cb.disabled = true;
                }else{
                    cb.disabled = false;
                }
                
            }
        </script> 
        <input type="checkbox" name="a" id="cekbox" onclick="run();" />Disable Brand<br /><br />
        <div class="element-form"> 
            <label>Brand</label>
            <span style="width: 50%;"> 
                <select name="brand_cetak" id="cb"                  >
                    <?php
                        $query = mysqli_query($koneksi, "SELECT banner_branded FROM banner_branded WHERE status='on' ORDER BY banner_branded ASC");
                        while($row=mysqli_fetch_assoc($query)){
                            if($brand == $row['banner_branded']) {
                                echo "<option value='$row[banner_branded]' selected='true'>$row[banner_branded]</option>";
                            }else{
                                echo "<option value='$row[banner_branded]'>$row[banner_branded]</option>";
                            }
                        }
                    ?>
                </select>
            </span>
        </div>
        <div class="element-form" >
            <button class='tombol-action' style="display:inline-block;">Cetak</button>
           
        </div>
        </form>
   </div>
</div>

<?php 
    // Menyiapkan Query
    $query = "SELECT stok FROM barang WHERE stok < 1";
    // Eksekusi Query 
    $sql = mysqli_query($koneksi, $query) OR die("Terdapat Error");

    $result = mysqli_num_rows($sql);
?>

<?php if($result > 0) : ?>
    <div class="notif" style="background-color: #e63636;">
        Terdapat barang dengan stok yang menipis!
    </div>
<?php endif; ?>
<?php
    // Cek jika var notif pada url tersedia
    if (isset($_GET['notif'])) {
        
        // Pengkondisian untuk menampilkan tipe notif
        if ($_GET['notif'] == "sukses_add") {
            echo "<div class='notif' id='notif'>Data berhasil dimasukkan!</div>";
        } elseif ($_GET['notif'] == "sukses_update") {
            echo "<div class='notif' id='notif'>Data berhasil diperbaharui!</div>";
        } elseif ($_GET['notif'] == "sukses_delete") {
            echo "<div class='notif' id='notif'>Data berhasil dihapus!</div>";
        } 
    }
?>

<?php
    if (isset($_GET['notif']) && $_GET['notif'] == "sukses") {
        echo "<div class='notif' id='notif'>Data berhasil dimasukkan!</div>";
    }
?>

<?php

    $pagination = isset($_GET["pagination"]) ? $_GET["pagination"] : 1;
    $data_per_halaman = 5;
    $mulai_dari = ($pagination-1) * $data_per_halaman;

    $query = mysqli_query($koneksi, "SELECT barang.*, kategori.kategori, banner_branded.banner_branded FROM barang JOIN kategori ON barang.kategori_id=kategori.kategori_id JOIN banner_branded ON barang.bb_id=banner_branded.bb_id $where LIMIT $mulai_dari, $data_per_halaman");

    if(mysqli_num_rows($query) == 0){
        echo "<h3>saat ini belum ada barang di dalam tabel barang";
    }else{

        echo "<table class='table-list'>";

        echo "<tr class='baris-title'>
                <th class='kolom-nomor'>No</th>
                <th class='kiri'>Barang</th>
                <th class='kiri'>Kategori </th>
                <th class='kiri'>Harga</th>
                <th class='kiri'>Harga Distributor</th>
                <th class='kiri'>Diskon</th>
                <th class='kiri'>Brand</th>
                <th class='kiri'>Stok</th>
                <th class='tengah'>Status</th>
                <th class='tengah'>Action</th>
              </tr>";
         
        $no=1 + $mulai_dari;
        while($row=mysqli_fetch_assoc($query)) {
            
            if ($row['stok'] < 1) {
                $warning = "style='background-color: #e63636; color: white;'";
            } else {
                $warning = "";
            }

            echo "<tr {$warning}>
                    <td class='kolom-nomor'>$no</td>
                    <td class='kiri'>$row[nama_barang]</td>
                    <td class='kiri'>$row[kategori]</td>
                    <td class='kiri'>".rupiah($row["harga"])."</td>
                    <td class='kiri'>".rupiah($row["harga_distributor"])."</td>
                    <td class='kiri'>$row[diskon]%</td>
                    <td class='kiri'>$row[banner_branded]</td>
                    <td class='kiri'>$row[stok]</td>
                    <td class='tengah'>$row[status]</td>
                    <td class='tengah'>
                        <a class='tombol-action' sty href='".BASE_URL."index.php?page=my_profile&module=barang&action=form&barang_id=$row[barang_id]'>Edit</a>
                        <a class='tombol-action' href='".BASE_URL."module/barang/action.php?button=Delete&barang_id=$row[barang_id]'>Delete</a>
                    </td>
                  </tr>";
                  
            $no++;      

        }      
        echo "</table>";
         
        
        $queryHitungBarang = mysqli_query($koneksi, "SELECT * FROM barang JOIN kategori ON barang.kategori_id=kategori.kategori_id JOIN banner_branded ON barang.bb_id=banner_branded.bb_id $where");
        pagination($queryHitungBarang, $data_per_halaman, $pagination, "index.php?page=my_profile&module=barang&action=list$search_url");
    }

?>