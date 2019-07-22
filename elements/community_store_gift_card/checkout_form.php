<?php defined('C5_EXECUTE') or die(_("Access Denied."));
extract($vars);
?>
<div class="panel panel-default credit-card-box">
    <div class="panel-body">
        <div style="display:none;" class="store-payment-errors an-payment-errors">
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <div class="input-group">
                        <input name="gift_card_code"
                               type="text"
                               class="form-control"
                               placeholder="<?= t('Gift Card Code'); ?>"
                               autocomplete="off"
                        />

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>