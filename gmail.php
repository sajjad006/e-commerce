<?php
    $header='From: sajjad@techsajjad.tk' . "\r\n" .'Reply-To: info@techsajjad.tk';
    if (mail("order-update@techsajjad.tk", "Test", "Hello this mail is from sajjad to order-update@techsajjad.tk",$header)) {
        echo "success";
    }
    else{
        echo "failure";
    }
?>