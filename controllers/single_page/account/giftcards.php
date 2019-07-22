<?php

namespace Concrete\Package\CommunityStoreBuyGiftCard\Controller\SinglePage\Account;

use Concrete\Core\Page\Controller\AccountPageController;
use Concrete\Package\CommunityStore\Src\CommunityStore\Customer\Customer;
use Concrete\Package\CommunityStoreBuyGiftCard\Src\GiftCardList;
use Config;
use Database;

class Giftcards extends AccountPageController
{

    public function view()
    {
        $customer = new Customer;
        $uID = $customer->getUserID();
        $gcl = new GiftCardList();
        $gcl->filter('giftingEmail', $customer->getEmail());
        $gcl->setItemsPerPage(2);
        $gcs = $gcl->getPage();
        $this->set('gcl', $gcl);
        $this->set('gcs', $gcs);
        $this->set('uID', $uID);
    }
}