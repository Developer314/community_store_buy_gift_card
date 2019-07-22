<div class="row">
    
     <div class="medium-3 columns profilesidebar sidebar">
        <?php
                $stack = Stack::getByName('My Account Links');
               $stack->display();
        ?>

    </div>

    
    
    <div class="medium-9 columns profilecontent">
        <section class="content">
            <div class="container">
                <div class="row my-orders">
                    
                    
                    
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <h3 class="page-header"><?php echo t('My Gift Cards') ?></h3>
                        <?php if ($gcl->getTotal() > 0) {
                            $gcl->displaySummary();
                            ?>
                            <div class="row hidden-xs hidden-sm form-group">
                                <div class="col-md-1 text-center"><h5><?= t("Serial No.") ?></h5></div>
                                <div class="col-md-2 text-center"><h5><?= t("Voucher Code") ?></h5></div>
                                <div class="col-md-2 text-center"><h5><?= t("Value") ?></h5></div>
                                <div class="col-md-2 text-center"><h5><?= t("Used Amount") ?></h5></div>
                                <div class="col-md-3 text-center"><h5><?= t("Used Orders") ?></h5></div>
                                <div class="col-md-2 text-center"><h5><?= t("Balance Amount") ?></h5></div>
                            </div>

                            <?php
                            $key = $gcl->getSummary()->currentStart;
                            foreach ($gcs as $gc) {
                                if ($uID > 0) {
                                    $usedOrders = Database::getArray('SELECT CSO.oID,CSO.oTotal FROM GiftCards GC, CommunityStoreOrders CSO WHERE GC.code = CSO.transactionReference and CSO.pmName="Gift Card" and GC.code="' . $gc->getCode() . '" and CSO.cID="' . $uID . '"');
                                }
                                ?>
                                <div class="row hidden-xs hidden-sm form-group">
                                    <div class="col-md-1 text-center"><?= $key ?>
                                    </div>
                                    <div class="col-md-2 text-center"><?= $gc->getCode() ?></div>
                                    <div class="col-md-2 text-center"><?= Config::get('community_store.symbol') . $gc->getGiftCardValue() ?></div>
                                    <div class="col-md-2 text-center"><?= Config::get('community_store.symbol') . $gc->getUsedAmount() ?></div>
                                    <div class="col-md-2 text-center">
                                        <?php if (sizeof($usedOrders) > 0) {
                                            foreach ($usedOrders as $usedOrder) {
                                                if ($usedOrder['oID'] > 0) {
                                                    ?>
                                                    <a
                                                            href="<?= URL::to('/account/orders/detail', $usedOrder['oID']) ?>"
                                                            class="ordr_det button"
                                                            title="<?= t("View") ?>"><?= t('#') . $usedOrder['oID'] . ' (' . Config::get('community_store.symbol') . number_format($usedOrder['oTotal'], 2) . ')' ?></a><br>
                                                    <?php
                                                }
                                            }
                                        } else {
                                            echo "-";
                                        } ?>
                                    </div>
                                    <div class="col-md-2 text-center"><?= Config::get('community_store.symbol') . $gc->getBalanceAmount() ?></div>
                                </div>
                                <div class="row visible-xs visible-sm">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="panel panel-success">
                                            <div class="panel-heading">Voucher Code: <?= $gc->getCode(); ?>
                                            </div>
                                            <div class="panel-body">

                                                <div class="row form-group">
                                                    <div class="col-sm-6 col-xs-6">
                                                        <span><?= t("Value") ?></span>
                                                    </div>
                                                    <div class="col-sm-6 col-xs-6">
                                                        <?= Config::get('community_store.symbol') . $gc->getGiftCardValue() ?>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-sm-6 col-xs-6">
                                                        <span><?= t("Used Amount") ?></span>
                                                    </div>
                                                    <div class="col-sm-6 col-xs-6">
                                                        <?= Config::get('community_store.symbol') . $gc->getUsedAmount() ?>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-sm-6 col-xs-6">
                                                        <span><?= t("Balance Amount") ?></span>
                                                    </div>
                                                    <div class="col-sm-6 col-xs-6">
                                                        <?= Config::get('community_store.symbol') . $gc->getBalanceAmount() ?>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $key++;
                            }
                            ?>
                            <div class="text-center"><?php $gcl->displayPagingV2() ?></div>
                            <?php
                        } else {
                            echo 'You currently have no Gift Cards';
                        } ?>

                    </div>
                </div>
            </div>
        </section>

    </div>
  
 