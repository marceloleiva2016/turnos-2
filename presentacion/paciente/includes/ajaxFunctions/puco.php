<?php
if (isset($_POST)) {
    
    $nro = $_POST['nrodoc'];

    $url = 'https://sisa.msal.gov.ar/sisa/services/rest/puco/'.$nro;

    $value = file_get_contents($url);

    $objeto = simplexml_load_string($value);

    echo json_encode($objeto);
}
?>