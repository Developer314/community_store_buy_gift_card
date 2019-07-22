<?php
defined('C5_EXECUTE') or die("Access Denied.");

$subject = $siteName . ' - ' . t('Gift Card Balance Updated');

/**
 * HTML BODY START
 */
ob_start();

?>
    <!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
    <html>
    <head></head>
    <body>
    <p>Dear <?= $fullName ?>,</p>
    <br>
    <p>You have purchased product(s) using your gift voucher. Current status of gift voucher as follows.</p>
    <p>Voucher Details</p>
    <p><strong>Voucher Code: </strong><?= $voucherCode; ?></p>
    <p><strong>Voucher Value: </strong><?= $voucherValue ?></p>
    <p><strong>Previous Balance: </strong><?= $previousBalance ?></p>
    <p><strong>Ordered Amount(-): </strong><?= $orderTotal ?></p>
    <p><strong>Current Balance: </strong><?= $currentBalance ?></p>
    <br>
    <br>
    </body>
    </html>

<?php
$bodyHTML = ob_get_clean();
/**
 * HTML BODY END
 *
 * =====================
 *
 * PLAIN TEXT BODY START
 */
ob_start();

?>