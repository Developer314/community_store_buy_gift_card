<?php
defined('C5_EXECUTE') or die("Access Denied.");

$subject = $siteName . ' - ' . t('Gift Card');

/**
 * HTML BODY START
 */
ob_start();

?>
    <!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
    <html>
    <head></head>
    <body>

    <h2 style="text-align: center"><?= $siteName ?> Gift Card</h2>

    <p>Dear Sir/Madam,</p>
    <br>
    <p><?= $fullName ?> has sent you a gift voucher to use a <a href="<?= BASE_URL ?>"
                                                                title="<?= $siteName ?>"><?= BASE_URL ?></a>. Below are
        the voucher details. You will be able to redeem your voucher at the checkout page on <a href="<?= BASE_URL ?>"
                                                                                                title="<?= $siteName ?>"><?= BASE_URL ?></a>.
    </p>

    <?php if ($giftMessage) {
        ?>
        <p><strong>Message from <?= $fullName ?>:</strong><br>
            <?= $giftMessage ?>
            <br><br>
        </p>
        <p>Voucher Details:</p>
        <p><strong>Voucher Code: </strong><?= $voucherCode; ?></p>
        <p><strong>Voucher Value: </strong><?= $voucherValue ?></p>
        <p>Valid from </strong><?= $voucherValidFrom; ?></p>
        <?php
    } ?>
    <br>
    <br>
    <p><strong>Instructions for using your voucher:</strong><br>
        Enter the above voucher code at the last stage of checkout on <a href="<?= BASE_URL ?>"
                                                                         title="<?= $siteName ?>"><?= BASE_URL ?></a>.
        The value of the voucher will then be applied to your order. Your could use you remaining balnce in your next
        order once your order total less than this balcne amount <br><br></p>
    <p style="text-align: center"><strong>Enjoy!</strong></p>
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