<?php

namespace Concrete\Package\CommunityStoreBuyGiftCard;

use Package;
use Whoops\Exception\ErrorException;
use Concrete\Core\Http\Request;
use Concrete\Core\Support\Facade\Events;
use Config;
use Core;
use \Concrete\Package\CommunityStoreBuyGiftCard\Src\Repository\GiftCard;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Payment\Method as PaymentMethod;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Customer\Customer as StoreCustomer;


defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package
{
    protected $pkgHandle = 'community_store_buy_gift_card';
    protected $appVersionRequired = '5.7.0';
    protected $pkgVersion = '0.0.2';
    protected $pkgDescription = "Community Store Gift Card Payment Method";
    protected $pkgName = "Community Store Gift Card Payment Method";

    protected $pkgAutoloaderRegistries = array(
        'src/CommunityStore' => 'Concrete\Package\CommunityStoreBuyGiftCard\Src\CommunityStore',
    );

    public function install()
    {
        $installed = Package::getInstalledHandles();
        if (!(is_array($installed) && in_array('community_store', $installed))) {
            throw new ErrorException(t('This package requires that Community Store be installed'));
        } else {
            $pkg = parent::install();
            $pm = new PaymentMethod();
            $pm->add('community_store_gift_card', 'Gift Card', $pkg);

        }
        $this->installContentFile('install.xml');
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->installContentFile('install.xml');
    }


    public function on_start()
    {
        Events::addListener('on_community_store_payment_complete', function ($event) {
            $order = $event->getOrder();
            $orderedCustomer = new StoreCustomer($order->getCustomerID());
            $dh = Core::make('helper/date');
            $orderItems = $order->getOrderItems();
            foreach ($orderItems

                     as $orderItem) {
                $product = $orderItem->getProductObject();
                $options = $orderItem->getProductOptions();
                if ($options) {
                    foreach ($options as $option) {
                        if ($option['oioKey'] == 'Enter recipient e-mail address') {
                            $giftingEmail = trim($option['oioValue']);
                        }
                        if ($option['oioKey'] == 'Message') {
                            $giftMessage = trim($option['oioValue']);
                        }
                    }
                }
                if ($product->getAttribute('gift_card')) {
                    $data = array(
                        'oID' => $order->getOrderID(),
                        'ouID' => $order->getCustomerID(),
                        'giftingEmail' => $giftingEmail,
                        'giftCardValue' => $orderItem->getPricePaid(),
                    );
                    $gc = new GiftCard();
                    $gcEntry = $gc->addEntry($data);
                    /*sending gift card email*/
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
                    $mh->to($giftingEmail);
                    $mh->addParameter('siteName', Config::get('concrete.site'));
                    $mh->addParameter('giftMessage', $giftMessage);
                    $mh->addParameter('fullName', $orderedCustomer->getValue('billing_first_name') . ' ' . $orderedCustomer->getValue('billing_last_name'));
                    $mh->addParameter('voucherCode', $gcEntry->getCode());
                    $mh->addParameter('voucherValue', Config::get('community_store.symbol') . number_format($gcEntry->getGiftCardValue(), 2));
                    $mh->addParameter('voucherValidFrom', $dh->formatDateTime($order->getPaid()));
                    $mh->load('gift_card_notification', 'community_store_buy_gift_card');
                    $mh->sendMail();
                }
            }

        });
    }

    public function uninstall()
    {
        $pm = PaymentMethod::getByHandle('community_store_gift_card');
        if ($pm) {
            $pm->delete();
        }
        parent::uninstall();
    }

}