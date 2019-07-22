<?php
if ($remove_gcid) {
    ?>

    <div class="alert-message block-message error"
         style="  background-color: #4674a1;padding: 20px; margin-bottom: 20px; color:#FFF;"><a class="close"
                                                                                                href="<?php echo $this->action('clear_warning'); ?>">×</a>
        <p><strong>
                <?php echo t('This is a warning!'); ?>
            </strong></p>
        <br/>
        <p><?php echo t('Are you sure you want to delete ') . '"' . t($remove_name) . '"?'; ?></p>
        <p><?php echo t('This action may not be undone!'); ?></p>
        <div class="alert-actions"><a class="btn btn-danger"
                                      href="<?php echo BASE_URL; ?>/index.php/dashboard/store/giftcards/delete/<?php echo $remove_gcid; ?>/<?php echo base64_encode($remove_name); ?>/">
                <?php echo t('Yes Remove This'); ?>
            </a> <a class="btn btn-primary" href="<?php echo $this->action('clear_warning'); ?>">
                <?php echo t('Cancel'); ?>
            </a></div>
    </div>
<?php } ?>
    <style>
        .ccm-ui table.ccm-results-list td {
            vertical-align: middle !important;;
            padding: 5px;
        }

        .ccm-ui .ccm-results-list thead th {
            background-color: #006699 !important;
            color: #eaeaea !important;
            font-weight: 300;
            vertical-align: middle !important;
            padding: 5px;
        }

        .ccm-ui .ccm-results-list thead th a {
            color: #eaeaea;
        }

        .ccm-ui .ccm-results-list thead th:hover {
            background: #00496e !important;
        }

        .ccm-ui .ccm-pagination-wrapper {
            text-align: center;
        }</style>
    <form method="get" action="<?php echo $this->action('view') ?>" style="float:left; margin-bottom:20px; width:100%;">
        <?php

        $form = Loader::helper('form');
        $sections[0] = '** All';
        asort($sections);
        ?>
        <table class="ccm-results-list" style="float:left; width: 100%" cellpadding="10">
            <tr>
                <th><strong><?php echo t('By Code') ?></strong></th>
                <th><strong><?php echo t('By Email') ?></strong></th>
                <th><strong><?php echo t('Item per Page') ?></strong></th>
                <th></th>
            </tr>
            <tr>

                <td><?php echo $form->text('code') ?></td>
                <td><?php echo $form->text('giftingEmail') ?></td>
                <td><?php echo $form->select('numResults', array(
                        '10' => '10',
                        '20' => '20',
                        '50' => '50',
                        '100' => '100',
                        '250' => '250',
                        '500' => '500',
                        '1000' => '1000',
                    ), $_REQUEST['numResults']) ?></td>
                <td><?php echo $form->submit('submit', t('Search')) ?>
                </td>

            </tr>
        </table>
    </form>
    <br/>
<?php
if ($gcl->getTotal() > 0) {
    $gcl->displaySummary();
    ?>
    <table border="0" class="ccm-results-list table table-striped article_list_icon" cellspacing="10" cellpadding="0">
        <thead>
        <tr>
            <th style="width:100px"><?php echo t('Serial No.') ?>
            </th>
            <th class="<?php echo $gcl->getSearchResultsClass('code'); ?>"><a
                        href="<?= $gcl->getSortByURL('code', 'asc') ?>"><?php echo t('Voucher Code') ?></a>
            </th>
            <th class="<?php echo $gcl->getSearchResultsClass('giftingEmail'); ?>"><a
                        href="<?= $gcl->getSortByURL('giftingEmail', 'asc') ?>"><?php echo t('Email') ?></a>
            </th>
            <th class="<?php echo $gcl->getSearchResultsClass('giftCardValue'); ?>"><a
                        href="<?= $gcl->getSortByURL('giftCardValue', 'asc') ?>"><?php echo t('Value') ?></a>
            </th>
            <th class="<?php echo $gcl->getSearchResultsClass('usedAmount'); ?>"><a
                        href="<?= $gcl->getSortByURL('usedAmount', 'asc') ?>"><?php echo t('Used Amount') ?></a>
            </th>
            <th><?php echo t('Used Orders') ?>
            </th>
            <th class="<?php echo $gcl->getSearchResultsClass('balanceAmount'); ?>"><a
                        href="<?= $gcl->getSortByURL('balanceAmount', 'asc') ?>"><?php echo t('Balance Amount') ?></a>
            </th>
            <th> <?php echo t('Actions') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $key = $gcl->getSummary()->currentStart;
        foreach ($gcs as $gc) {
            $gc_ui = UserInfo::getByEmail($gc->getGiftingEmail());
            if(is_object($gc_ui)){
                $usedOrders = Database::getArray('SELECT CSO.oID,CSO.oTotal FROM GiftCards GC, CommunityStoreOrders CSO WHERE GC.code = CSO.transactionReference and CSO.pmName="Gift Card" and GC.code="' . $gc->getCode() . '" and CSO.cID="' . $gc_ui->getUserID() . '"');
            }

            ?>
            <tr>
                <td> <?= $key; ?>
                </td>
                <td> <?= $gc->getCode() ?></td>
                <td> <?= $gc->getGiftingEmail() ?></td>
                <td><?= Config::get('community_store.symbol') . $gc->getGiftCardValue() ?>
                </td>
                <td><?= Config::get('community_store.symbol') . $gc->getUsedAmount() ?>
                </td>
                <td><?php if (sizeof($usedOrders) > 0) {
                        foreach ($usedOrders as $usedOrder) {
                            if ($usedOrder['oID'] > 0) {

                                ?>
                                <a
                                        href="<?= URL::to('/dashboard/store/orders/order', $usedOrder['oID']) ?>"
                                        class="ordr_det button"
                                        title="<?= t("View") ?>"><?= t('#') . $usedOrder['oID'] . ' (' . Config::get('community_store.symbol') . number_format($usedOrder['oTotal'], 2) . ')' ?></a><br>
                                <?php
                            }
                        }
                    } else {
                        echo "-";
                    } ?></td>
                <td><?= Config::get('community_store.symbol') . $gc->getBalanceAmount() ?></td>
                <td>
                    <a href="<?php echo $this->url('/dashboard/store/giftcards', 'delete_check', $gc->getGiftCardID(), base64_encode($gc->getCode())) ?>"
                       class="pagetooltip btn btn-danger">Delete</a>
                </td>
            </tr>
            <?php $key++;
        } ?>
        </tbody>
    </table>
    <br/>
    <?php echo $gcl->displayPagingV2();
} else {
    print t('No Vouchers found.');
}
?>