<?php
    $search = isset($_GET["search"]) ? $_GET["search"] : false;

    $where = "";
    $search_url = "";
    if($search){
        $search_url = "&search=$search";
        $where = "WHERE user.nama LIKE '%$search%' OR 
                        user.email LIKE '%$search%' OR
                        user.phone LIKE '%$search%' OR
                        user.status LIKE '%$search%'";
    }
?> 
<div id="frame-tambah">
	<div id="left">
        <form action="<?php echo BASE_URL."index.php"; ?>" method="GET">
                <input type="hidden" name="page" value="<?php echo $_GET["page"] ?>" />
                <input type="hidden" name="module" value="<?php echo $_GET["module"] ?>" />
                <input type="hidden" name="action" value="<?php echo $_GET["action"] ?>" />
                <input type="text" name="search" value="<?php echo $search; ?>" />
                <input type="submit" value="Search" />
        </form>
    </div>
</div>

    <?php
		if (isset($_GET['notif'])) {
			echo notifTransaksi($_GET['notif'] ,"User");
		}
	?>

<?php

    $pagination = isset($_GET["pagination"]) ? $_GET["pagination"] : 1;
    $data_per_halaman = 10;
    $mulai_dari = ($pagination-1) * $data_per_halaman;

    $no=1 + $mulai_dari;
      
    $queryAdmin = mysqli_query($koneksi, "SELECT * FROM user $where LIMIT $mulai_dari, $data_per_halaman");
      
    if(mysqli_num_rows($queryAdmin) == 0)
    {
        echo "<h3>Saat ini belum ada data user yang dimasukan</h3>";
    }
    else
    {
        echo "<table class='table-list'>";
          
            echo "<tr class='baris-title'>
                    <th class='kolom-nomor'>No</th>
                    <th class='kiri'>Nama</th>
                    <th class='kiri'>Email</th>
                    <th class='kiri'>Phone</th>
                    <th class='kiri'>Level</th>
                    <th class='tengah'>Status</th>
                    <th class='tengah'>Action</th>
                 </tr>";
  
            while($rowUser=mysqli_fetch_array($queryAdmin))
            {
                echo "<tr>
                        <td class='kolom-nomor'>$no</td>
                        <td>$rowUser[nama]</td>
                        <td>$rowUser[email]</td>
                        <td>$rowUser[phone]</td>
                        <td>$rowUser[level]</td>
                        <td class='tengah'>$rowUser[status]</td>
                        <td class='tengah'><a class='tombol-action' href='".BASE_URL."index.php?page=my_profile&module=user&action=form&user_id=$rowUser[user_id]"."'>Edit</a>
                        <a class='tombol-action' href='".BASE_URL."module/user/action.php?button=Delete&user_id=$rowUser[user_id]'>Delete</a></td>
                     </tr>";
              
                $no++;
            }
          
        //AKHIR DARI TABLE
        echo "</table>";
        $queryHitungUser= mysqli_query($koneksi, "SELECT * FROM user $where");
        pagination($queryHitungUser, $data_per_halaman, $pagination, "index.php?page=my_profile&module=user&action=list$search_url");
    }
?>