<?php

namespace Concrete\Package\CommunityStoreBuyGiftCard\Src\CommunityStore\Payment\Methods\CommunityStoreGiftCard;

use Core;
use Config;
use Concrete\Core\Http\Request;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Payment\Method as StorePaymentMethod;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Calculator as StoreCalculator;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Customer\Customer as StoreCustomer;
use \Concrete\Package\CommunityStoreBuyGiftCard\Src\Repository\GiftCard;

class CommunityStoreGiftCardPaymentMethod extends StorePaymentMethod
{

    public function dashboardForm()
    {

    }

    public function save(array $data = [])
    {
    }

    public function validate($args, $e)
    {
        return $e;
    }

    public function checkoutForm()
    {
        $pmID = StorePaymentMethod::getByHandle('community_store_gift_card')->getID();
        $this->set('pmID', $pmID);
    }

    public function isEligible()
    {
        return false;
    }

    public function submitPayment()
    {

        $gift_card_code = $this->post('gift_card_code');
        $customer = new StoreCustomer();
        $customer_email = $customer->getEmail();
        $total = StoreCalculator::getGrandTotal();
        $gc = new GiftCard();
        if ($gift_card_code == '') {
            return array('error' => 1, 'errorMessage' => 'Gift Card Code required.');
        } else {
            $gcentry = $gc->getValidGiftCard($gift_card_code, $total, $customer_email);
            if (is_object($gcentry)) {
                $previousBalance = $gcentry->getBalanceAmount();
                $balanceAmount = $gcentry->getBalanceAmount() - $total;
                $usedAmount = $gcentry->getUsedAmount() + $total;
                $data = array(
                    'usedAmount' => $usedAmount,
                    'balanceAmount' => $balanceAmount,
                );

                $gc = new GiftCard();
                $gc->updateEntry($gcentry, $data);

                /*sending gift card status email*/
                $request = Core::make(Request::class);
                $mh = Core::make('mail');
                $fromName = Config::get('community_store.emailalertsname');
                $fromEmail = Config::get('community_store.emailalerts');
                if (!$fromEmail) {
                    $fromEmail = "store@" . $request->getHost();
                }
                if ($fromName) {
                    $mh->from($fromEmail, $fromName);
                } else {
                    $mh->from($fromEmail);
                }
                $mh->to($customer_email);
                $mh->addParameter('siteName', Config::get('concrete.site'));
                $mh->addParameter('fullName', $customer->getValue('billing_first_name') . ' ' . $customer->getValue('billing_last_name'));
                $mh->addParameter('voucherCode', $gcentry->getCode());
                $mh->addParameter('voucherValue', Config::get('community_store.symbol') . number_format($gcentry->getGiftCardValue(), 2));
                $mh->addParameter('previousBalance', Config::get('community_store.symbol') . number_format($previousBalance, 2));
                $mh->addParameter('orderTotal', Config::get('community_store.symbol') . number_format($total, 2));
                $mh->addParameter('currentBalance', Config::get('community_store.symbol') . number_format($data['balanceAmount'], 2));
                $mh->load('gift_card_balance_notification', 'community_store_buy_gift_card');
                $mh->sendMail();


                return array('error' => 0, 'transactionReference' => $gift_card_code);
            } else {
                return array('error' => 1, 'errorMessage' => 'Invalid Gift Card Code.');
            }
        }
    }

    public function getPaymentMethodName()
    {
        return 'Gift Card';
    }

    public function getPaymentMethodDisplayName()
    {
        return $this->getPaymentMethodName();
    }

}

return __NAMESPACE__;