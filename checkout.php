<?php
session_start();
include 'dbconnect.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['log'])) {
    header('location:login.php');
    exit();
}

$uid = $_SESSION['id'];

// Ambil detail keranjang yang memiliki status 'Cart'
$caricart = mysqli_query($conn, "SELECT * FROM cart WHERE userid='$uid' AND status='Cart'");

// Cek apakah ada item dalam keranjang
$keranjang_kosong = mysqli_num_rows($caricart) === 0;

if (!$keranjang_kosong) {
    $fetc = mysqli_fetch_array($caricart);
    $orderidd = $fetc['orderid'];
    
    // Hitung jumlah transaksi untuk menampilkan di halaman checkout
    $itungtrans = mysqli_query($conn, "SELECT COUNT(detailid) AS jumlahtrans FROM detailorder WHERE orderid='$orderidd'");
    $itungtrans2 = mysqli_fetch_assoc($itungtrans);
    $itungtrans3 = $itungtrans2['jumlahtrans'];
} else {
    $orderidd = null;
    $itungtrans3 = 0;
}

// Proses checkout ketika tombol "I Agree and Check Out" diklik
if (isset($_POST["checkout"])) {
    if ($orderidd) {
        // Ubah status keranjang menjadi 'Payment'
        $q3 = mysqli_query($conn, "UPDATE cart SET status='Payment' WHERE orderid='$orderidd'");
        if ($q3) {
            echo "Berhasil Check Out";
            echo "<meta http-equiv='refresh' content='1; url=index.php'/>";
            exit();
        } else {
            echo "Gagal Check Out";
            echo "<meta http-equiv='refresh' content='1; url=index.php'/>";
            exit();
        }
    } else {
        echo "Tidak ada item di keranjang.";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>OKE JASA - Checkout</title>
    <!-- Meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Let's Work, Kelompok 10" />
    <!-- Stylesheets -->
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- JavaScript -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navigastion.php'; ?>

    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <ol class="breadcrumb breadcrumb1">
                <li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Home</a></li>
                <li class="active">Checkout</li>
            </ol>
        </div>
    </div>
    <!-- //Breadcrumbs -->

    <!-- Checkout Section -->
    <div class="checkout">
        <div class="container">
            <?php if ($keranjang_kosong): ?>
                <h1>Keranjang anda kosong, tidak ada yang di checkout</h1>
            <?php else: ?>
                <h1>Checkout</h1>
            <?php endif; ?>

            <!-- Tabel Keranjang -->
            <?php if (!$keranjang_kosong): ?>
                <div class="checkout-right">
                    <table class="timetable_sub">
                        <thead>
                            <tr>
                                <th>No.</th>    
                                <th>Produk</th>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Sub Total</th>
                                <th>Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $brg = mysqli_query($conn, "SELECT * FROM detailorder d INNER JOIN produk p ON d.idproduk=p.idproduk WHERE orderid='$orderidd' ORDER BY d.idproduk ASC");
                            $no = 1;
                            while ($b = mysqli_fetch_array($brg)) {
                                ?>
                                <tr class="rem1">
                                    <td class="invert"><?php echo $no++; ?></td>
                                    <td class="invert"><a href="product.php?idproduk=<?php echo $b['idproduk']; ?>"><img src="<?php echo $b['gambar']; ?>" width="100px" height="100px" /></a></td>
                                    <td class="invert"><?php echo $b['namaproduk']; ?></td>
                                    <td class="invert">
                                        <div class="quantity"> 
                                            <div class="quantity-select">                     
                                                <h4><?php echo $b['qty']; ?></h4>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="invert">Rp<?php echo number_format($b['hargaafter'] * $b['qty']); ?></td>
                                    <td class="invert">
                                        <div class="rem">
                                            <!-- Form untuk update dan hapus item -->
                                            <form method="post">
                                                <input type="hidden" name="idproduknya" value="<?php echo $b['idproduk']; ?>" />
                                                <input type="submit" name="update" class="form-control" value="Update" />
                                                <input type="submit" name="hapus" class="form-control" value="Hapus" />
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <!-- //Tabel Keranjang -->

            <!-- Total Harga yang harus dibayar -->
            <?php if (!$keranjang_kosong): ?>
                <div class="checkout-left">
                    <div class="checkout-left-basket">
                        <h4>Total Harga yang harus dibayar saat ini</h4>
                        <ul>
                            <?php 
                            $brg = mysqli_query($conn, "SELECT * FROM detailorder d INNER JOIN produk p ON d.idproduk=p.idproduk WHERE orderid='$orderidd' ORDER BY d.idproduk ASC");
                            $subtotal = 0;
                            while ($b = mysqli_fetch_array($brg)) {
                                $hrg = $b['hargaafter'];
                                $qtyy = $b['qty'];
                                $totalharga = $hrg * $qtyy;
                                $subtotal += $totalharga;
                            }
                            ?>
                            <h1><input type="text" value="Rp<?php echo number_format($subtotal); ?>" disabled /></h1>
                        </ul>
                    </div>

                    <!-- Kode Order -->
                    <div class="checkout-left-basket" style="width:80%;margin-top:60px;">
                        <div class="checkout-left-basket">
                            <h4>Kode Order Anda</h4>
                            <h1><input type="text" value="<?php echo $orderidd; ?>" disabled /></h1>
                        </div>
                    </div>

                    <div class="clearfix"> </div>
                </div>
            <?php endif; ?>
            <!-- //Total Harga yang harus dibayar -->

            <br>
            <hr>
            <br>
            <center>
                <?php if (!$keranjang_kosong): ?>
                    <h2>Total harga yang tertera di atas sudah termasuk ongkos kirim sebesar Rp10.000</h2>
                    <h2>Bila telah melakukan pembayaran, harap konfirmasikan pembayaran Anda.</h2>
                    <br>

                    <!-- Metode Pembayaran -->
                    <?php 
                    $metode = mysqli_query($conn, "SELECT * FROM pembayaran");
                    while ($p = mysqli_fetch_array($metode)) {
                    ?>
                    <img src="<?php echo $p['logo']; ?>" width="300px" height="200px"><br>
                    <h4><?php echo $p['metode']; ?> - <?php echo $p['norek']; ?><br>
                    a/n. <?php echo $p['an']; ?></h4><br>
                    <hr>
                    <?php
                    }
                    ?>
                    <br>
                    <p>Orderan anda Akan Segera kami proses 1x24 Jam Setelah Anda Melakukan Pembayaran ke ATM kami dan menyertakan informasi pribadi yang melakukan pembayaran seperti Nama Pemilik Rekening / Sumber Dana, Tanggal Pembayaran, Metode Pembayaran dan Jumlah Bayar.</p>
                    <br>

                    <!-- Tombol Checkout -->
                    <form method="post">
                        <input type="submit" class="form-control btn btn-success" name="checkout" value="I Agree and Check Out" />
                    </form>
                <?php endif; ?>
            </center>
        </div>
    </div>
    <!-- //Checkout Section -->


    <!-- Footer -->
    <?php include 'footer.php'; ?>
    <!-- //Footer -->

    <!-- JavaScript untuk smooth scrolling -->
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".scroll").click(function(event){		
                event.preventDefault();
                $('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
            });
        });
    </script>
</body>
</html>
