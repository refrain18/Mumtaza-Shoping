<?php
    $search = isset($_GET["search"]) ? $_GET["search"] : false;

    $where = "";
    $search_url = "";
    if($search){
        $search_url = "&search=$search";
        $where = "WHERE user.nama LIKE '%$search%'";
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
    <div id="right">
        <a class="tombol-action" href="<?php echo BASE_URL."index.php?page=my_profile&module=member&action=form"; ?>">+ Tambah Member</a>
    </div>
</div>

    <?php
		if (isset($_GET['notif'])) {
			echo notifTransaksi($_GET['notif'] ,"Member");
		}
	?>

<?php


    $pagination = isset($_GET["pagination"]) ? $_GET["pagination"] : 1;
    $data_per_halaman = 10;
    $mulai_dari = ($pagination-1) * $data_per_halaman;

    $no=1 + $mulai_dari;
        
    $queryMember = mysqli_query($koneksi, "SELECT member.*, user.nama FROM member JOIN user ON member.user_id=user.user_id $where LIMIT $mulai_dari, $data_per_halaman");
        
    if(mysqli_num_rows($queryMember) == 0)
    {
        echo "<h3>Saat ini belum ada member di dalam database</h3>";
    }
    else
    {
        echo "<table class='table-list'>";
            
            echo "<tr class='baris-title'>
                    <th class='kolom-nomor'>No</th>
                    <th class='kiri'>Nama Member</th>
                    <th class='tengah'>Id Member</th>
                    <th class='tengah'>Status</th>
                    <th class='tengah'>Action</th>
                 </tr>";
    
            while($rowMember=mysqli_fetch_array($queryMember))
            {
                echo "<tr>
                        <td class='kolom-nomor'>$no</td>
                        <td>$rowMember[nama]</td>
                        <td class='tengah'>$rowMember[id_member]</td>
                        <td class='tengah'>$rowMember[status]</td>
                        <td class='tengah'><a class='tombol-action' href='".BASE_URL."index.php?page=my_profile&module=member&action=form&no_member=$rowMember[no_member]"."'>Edit</a>
                        <a class='tombol-action' href='".BASE_URL."module/member/action.php?button=Delete&no_member=$rowMember[no_member]'>Delete</a></td>
                     </tr>";
                
                $no++;
            }
            
        echo "</table>";

        $queryHitungBanner= mysqli_query($koneksi, "SELECT member.*, user.nama FROM member JOIN user ON member.user_id=user.user_id $where");
        pagination($queryHitungBanner, $data_per_halaman, $pagination, "index.php?page=my_profile&module=member&action=list$search_url");
    }
?>