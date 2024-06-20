<?php
session_start();
if(!isset($_SESSION['log'])){
    header('location:login.php');
    exit();
}

$idorder = $_GET['id'] ?? ''; // Ensure $idorder is set

include 'dbconnect.php';

if(isset($_POST['confirm'])) {
    $userid = $_SESSION['id'];
    $idorder = mysqli_real_escape_string($conn, $_POST['idorder']); // Use the hidden field value
    $veriforderid = mysqli_query($conn, "SELECT * FROM cart WHERE orderid='$idorder'");
    $liat = mysqli_num_rows($veriforderid);

    if($liat > 0) {
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $metode = mysqli_real_escape_string($conn, $_POST['metode']);
        $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
        
        $kon = mysqli_query($conn, "INSERT INTO konfirmasi (orderid, userid, payment, namarekening, tglbayar) 
        VALUES ('$idorder', '$userid', '$metode', '$nama', '$tanggal')");
        
        if($kon) {
            $up = mysqli_query($conn, "UPDATE cart SET status='Confirmed' WHERE orderid='$idorder'");
            
            echo "<div class='alert alert-success'>
                Terima kasih telah melakukan konfirmasi, team kami akan melakukan verifikasi.
                Informasi selanjutnya akan dikirim via Email
                </div>
                <meta http-equiv='refresh' content='7; url=index.php' />";
        } else {
            echo "<div class='alert alert-warning'>
                Gagal Submit, silakan ulangi lagi.
                </div>
                <meta http-equiv='refresh' content='3; url=konfirmasi.php' />";
        }
    } else {
        echo "<div class='alert alert-danger'>
            Kode Order tidak ditemukan, harap masukkan kembali dengan benar
            </div>
            <meta http-equiv='refresh' content='4; url=konfirmasi.php' />";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OKE JASA - Konfirmasi Pembayaran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Let's Work, Kelompok 10" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
        function hideURLbar(){ window.scrollTo(0,1); } </script>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery-1.11.1.min.js"></script>
    <link href='//fonts.googleapis.com/css?family=Raleway:400,100,100italic,200,200italic,300,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="js/move-top.js"></script>
    <script type="text/javascript" src="js/easing.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".scroll").click(function(event){        
                event.preventDefault();
                $('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
            });
        });
    </script>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navigastion.php'; ?>

    <div class="breadcrumbs">
        <div class="container">
            <ol class="breadcrumb breadcrumb1 animated wow slideInLeft" data-wow-delay=".5s">
                <li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Home</a></li>
                <li class="active">Konfirmasi</li>
            </ol>
        </div>
    </div>

    <div class="register">
        <div class="container">
            <h2>Konfirmasi</h2>
            <div class="login-form-grids">
                <h3>Kode Order</h3>
                <form method="post">
                    <strong>
                        <input type="text" value="<?php echo htmlspecialchars($idorder); ?>" disabled>
                    </strong>
                    <input type="hidden" name="idorder" value="<?php echo htmlspecialchars($idorder); ?>">
                    <h6>Informasi Pembayaran</h6>
                    <input type="text" name="nama" placeholder="Nama Pemilik Rekening / Sumber Dana" required>
                    <br>
                    <h6>Rekening Tujuan</h6>
                    <select name="metode" class="form-control" required>
                        <?php
                        $metode = mysqli_query($conn, "SELECT * FROM pembayaran");
                        while($a = mysqli_fetch_array($metode)) {
                        ?>
                            <option value="<?php echo htmlspecialchars($a['metode']); ?>"><?php echo htmlspecialchars($a['metode']); ?> | <?php echo htmlspecialchars($a['norek']); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <br>
                    <h6>Tanggal Bayar</h6>
                    <input type="date" class="form-control" name="tanggal" required>
                    <input type="submit" name="confirm" value="Kirim">
                </form>
            </div>
            <div class="register-home">
                <a href="index.php">Batal</a>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var defaults = {
                containerID: 'toTop', 
                containerHoverID: 'toTopHover', 
                scrollSpeed: 4000,
                easingType: 'linear' 
            };
            $().UItoTop({ easingType: 'easeOutQuart' });
        });
    </script>
    <script src="js/skdslider.min.js"></script>
    <link href="css/skdslider.css" rel="stylesheet">
    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery('#demo1').skdslider({'delay':5000, 'animationSpeed': 2000,'showNextPrev':true,'showPlayButton':true,'autoSlide':true,'animationType':'fading'});
            jQuery('#responsive').change(function(){
              $('#responsive_wrapper').width(jQuery(this).val());
            });
        });
    </script>    
</body>
</html>
		