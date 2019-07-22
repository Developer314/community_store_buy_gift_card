<?php

namespace Concrete\Package\CommunityStoreBuyGiftCard\Src;

use \Concrete\Core\Legacy\DatabaseItemList;
use Loader;
use Core;
use Page;
use \Concrete\Package\CommunityStoreBuyGiftCard\Src\Repository\GiftCard;
use Database;

class GiftCardList extends DatabaseItemList
{
    /*protected $autoSortColumns = array('dateSubmitted');*/
    protected $itemsPerPage = 10;

    function __construct()
    {
        $this->setQuery('select gcID from GiftCards');

    }

    public function get($itemsToGet = 0, $offset = 0)
    {
        $records = array();
        $r = parent::get($itemsToGet, $offset);

        foreach ($r as $row) {
            $item = GiftCard::getGiftCardByID($row['gcID']);
            $records[] = $item;
        }

        return $records;
    }

}