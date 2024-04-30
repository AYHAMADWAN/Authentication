<?php

require __DIR__ . '/vendor/autoload.php';

// use PragmaRX\Google2FA\Google2FA;
use PragmaRX\Google2FAQRCode\Google2FA;

$google2fa = new Google2FA();

// $qrCodeUrl = $google2fa->getQRCodeUrl(
//     'don\'t care',
//     'dc',
//     'L2FOTVOH5KVAESDE'
// );

$inlineUrl = $google2fa->getQRCodeInline(
    'pragmarx',
    'google2fa@pragmarx.com',
    'L2FOTVOH5KVAESDE'
);



?>

<?= $inlineUrl ?>
