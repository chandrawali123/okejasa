<?php
class Controller 
{
    function __construct($arguments = null) {
    }       
    public function home(){
        include 'home.php';
    }
    public function orderan(){
        include 'daftarorder.php';
    }
    public function cekoutbarang(){
        include 'cart.php';
    }
    public function order(){
        include 'order.php';
    }
    public function loundry(){
        $_GET['idkategori'] = 5; // Manually set idkategori
        include 'kategori.php';
    }
    public function crs(){
        $_GET['idkategori'] = 10; // Manually set idkategori
        include 'kategori.php';
    }
    public function servsMTR(){
        $_GET['idkategori'] = 11; // Manually set idkategori
        include 'kategori.php';
    }
    public function servAC(){
        $_GET['idkategori'] = 12; // Manually set idkategori
        include 'kategori.php';
    }
    public function daftar(){
        include 'registered.php';
    }
    

}
?>
