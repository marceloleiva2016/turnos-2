<?php
header('content-type:text/css');
 
$colorPrincipal = '#007e47';
 
echo <<<FINCSS

fieldset {
    width: 50%;
}

.page {
    margin: 50px auto;
    text-align: center;
    height: 350px;
    width: 500px;
    background: $colorPrincipal;
    border: 1px solid rgba(0,0,0,.05);
    box-shadow: 0px 0px 0px 20px rgba(0,0,0,0.03);
}

@media screen and (max-width: 400px) {

    #listado #tipodoc {
        display: none;
    }

    #listado #nrodoc {
        display: none;
    }
    
}

.button-secondary {
    color: white;
    border-radius: 4px;
    background: $colorPrincipal; /* this is a light blue */
    height: 40px;
 }

.topright {
    position: absolute;
    top: 45px;
    right: 16px;
    font-size: 18px;
}

FINCSS;
?>