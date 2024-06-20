<?php

$url_array = explode('/', $_SERVER['REQUEST_URI']);
include_once 'controller.php';
$controller = new Controller();

$url = isset($url_array[2]) ? $url_array[2] : '';

$url = filter_var($url, FILTER_SANITIZE_STRING);

if ($url == '') {
    $controller->home();
} else {
    if (method_exists($controller, $url)) {
        $controller->$url();
    } else {
        echo "SALAH KETIKANNYAAA AYUUU SAYANGGG KUUUUU <3";
        echo '<script>
                setTimeout(function(){
                    window.location.href = "/Okejasa";
                }, 2000);
              </script>';
    }
}
?>
