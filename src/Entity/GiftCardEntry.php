<?php

namespace Concrete\Package\CommunityStoreBuyGiftCard\Src\Entity;

use Core;
use Concrete\Core\Package\PackageService;
use Database;

/**
 * @Entity
 * @Table(
 *     name="GiftCards"
 * )
 */
class GiftCardEntry
{
    /**
     * @Id
     * @Column(name="gcID", type="integer", options={"unsigned"=true})
     * @GeneratedValue(strategy="AUTO")
     */
    protected $gcID;


    /**
     * @Column(type="integer")
     */
    protected $oID = 0;

    /**
     * @Column(type="integer",  options={"default": 0, "unsigned"=true})
     */
    protected $ouID = 0;

    /**
     * @Column(type="string")
     */
    protected $code;

    /**
     * @Column(type="string")
     */
    protected $giftingEmail;

    /**
     * @Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $giftCardValue;

    /**
     * @Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $usedAmount = '';

    /**
     * @Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $balanceAmount = '';

    public function getGiftCardID()
    {

        return $this->gcID;
    }

    /**
     * @param mixed $oID
     * @return GiftCardEntry
     */
    public function setOrderID($oID)
    {
        $this->oID = $oID;
        return $this;
    }

    public function getOrderID()
    {
        return $this->ouID;
    }

    /**
     * @param mixed $ouID
     * @return GiftCardEntry
     */
    public function setOrderedUID($ouID)
    {
        $this->ouID = $ouID;
        return $this;
    }

    public function getOrderedUID()
    {
        return $this->ouID;
    }

    /**
     * @param mixed $code
     * @return GiftCardEntry
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $giftingEmail
     * @return GiftCardEntry
     */
    public function setGiftingEmail($giftingEmail)
    {
        $this->giftingEmail = $giftingEmail;
        return $this;
    }

    public function getGiftingEmail()
    {
        return $this->giftingEmail;
    }

    /**
     * @param mixed $giftCardValue
     * @return GiftCardEntry
     */
    public function setGiftCardValue($giftCardValue)
    {
        $this->giftCardValue = $giftCardValue;
        return $this;
    }

    public function getGiftCardValue()
    {
        return $this->giftCardValue;
    }

    /**
     * @param mixed $usedAmount
     * @return GiftCardEntry
     */
    public function setUsedAmount($usedAmount)
    {
        $this->usedAmount = $usedAmount;
        return $this;
    }

    public function getUsedAmount()
    {
        return $this->usedAmount;
    }

    /**
     * @param mixed $balanceAmount
     * @return GiftCardEntry
     */
    public function setBalanceAmount($balanceAmount)
    {
        $this->balanceAmount = $balanceAmount;
        return $this;
    }

    public function getBalanceAmount()
    {
        return $this->balanceAmount;
    }


}

