<?php

    if($user_id) {
        header("location: ".BASE_URL);
    }

?>

<div id="container-user-akses">

    <form action="<?php echo BASE_URL."proses_login.php"; ?>" method="POST">   

    <?php

        $notif = isset($_GET['notif']) ? $_GET['notif'] : false;

        if($notif == true){
            echo "<div class='notif'>Maaf, email atau password yang kamu masukan tidak cocok</div>";
        }

    ?>

        <div class="element-form">
            <label>Id Member (Opsional)</label>
            <span><input type="text" name="id_member" /></span>
        </div>
        
        <div class="element-form">
            <label>Email</label>
            <span><input type="text" name="email" /></span>
        </div>

        <div class="element-form">
            <div class="label-password">
                <label>Password</label>
                <i class="btn-hide-show fas fa-eye-slash title="show password"></i>
            </div>
            <span><input type="password" name="password" class="input-password" /></span>
        </div>

        <div class="element-form">
            <span><input type="submit" value="login" /></span>
        </div>
    </form>

</div>