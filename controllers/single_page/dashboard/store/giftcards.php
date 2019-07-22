<?php

namespace Concrete\Package\CommunityStoreBuyGiftCard\Controller\SinglePage\Dashboard\Store;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Package\CommunityStoreBuyGiftCard\Src\GiftCardList;
use \Concrete\Package\CommunityStoreBuyGiftCard\Src\Repository\GiftCard;
use Config;
use Database;

class Giftcards extends DashboardPageController
{

    public function view()
    {
       /* Database::query('update GiftCards set balanceAmount = 1308.00 where code="9C5OW2WMOM"');*/

        $gcl = new GiftCardList();
        if ($_REQUEST['numResults'] > 0) {
            $gcl->setItemsPerPage($_REQUEST['numResults']);
        } else {
            $gcl->setItemsPerPage(10);
        }

        if ($_REQUEST['code'] != '') {
            $gcl->filter('code', $_REQUEST['code']);
        }
        if ($_REQUEST['giftingEmail'] != '') {
            $gcl->filter('giftingEmail', $_REQUEST['giftingEmail']);
        }
        $gcs = $gcl->getPage();
        $this->set('gcl', $gcl);
        $this->set('gcs', $gcs);
    }

    public function delete_check($gcID, $name = '')
    {
        $this->set('remove_name', base64_decode($name));
        $this->set('remove_gcid', $gcID);
        $this->view();
    }

    public function delete($gcID, $name = '')
    {
        $gcentry = GiftCard::getGiftCardByID($gcID);
        if (is_object($gcentry)) {
            $gc_repository = new GiftCard();
            $gc_repository->remove($gcentry);
            $this->set('message', t('"' . base64_decode($name) . '" has been deleted'));
        } else {
            $this->error->add('Invalid Gift Card');
        }
        $this->set('remove_name', '');
        $this->set('remove_gcid', '');
        $this->view();
    }
    public function clear_warning()
    {
        $this->set('remove_name', '');
        $this->set('remove_gcid', '');
        $this->view();
    }
}
