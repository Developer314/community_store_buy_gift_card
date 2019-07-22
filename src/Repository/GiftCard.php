<?php

namespace Concrete\Package\CommunityStoreBuyGiftCard\Src\Repository;

use Loader;
use User;
use UserInfo;
use Core;
use Concrete\Package\CommunityStoreBuyGiftCard\Src\Entity\GiftCardEntry;

class GiftCard
{
    public function addEntry($data)
    {
        $giftCardEntry = new GiftCardEntry();
        $giftCardEntry->setOrderID($data['oID']);
        $giftCardEntry->setOrderedUID($data['ouID']);
        $giftCardEntry->setCode($this->generateCouponCode());
        $giftCardEntry->setGiftingEmail($data['giftingEmail']);
        $giftCardEntry->setGiftCardValue($data['giftCardValue']);
        $giftCardEntry->setUsedAmount(0.00);
        $giftCardEntry->setBalanceAmount($data['giftCardValue']);
        $em = \ORM::entityManager();
        $em->persist($giftCardEntry);
        $em->flush();
        return $giftCardEntry;
    }

    public function generateCouponCode()
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < 10; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $res;
    }

    public function isValid($gift_card_code, $orderTotal, $customer_email)
    {
        $em = \ORM::entityManager();
        $r = $em->getRepository('Concrete\Package\CommunityStoreBuyGiftCard\Src\Entity\GiftCardEntry');
        $query = $r->createQueryBuilder('GCE')
            ->where('GCE.giftingEmail = :giftingEmail and GCE.balanceAmount >= :orderTotal and GCE.code = :code');
        $query->setParameter('code', $gift_card_code);
        $query->setParameter('giftingEmail', $customer_email);
        $query->setParameter('orderTotal', $orderTotal);
        $query->setMaxResults(1);
        $results = $query->getQuery()->getResult();
        if (sizeof($results)) return true; else return false;

    }

    public function getValidGiftCard($gift_card_code, $orderTotal, $customer_email)
    {
        $em = \ORM::entityManager();
        $r = $em->getRepository('Concrete\Package\CommunityStoreBuyGiftCard\Src\Entity\GiftCardEntry');
        $query = $r->createQueryBuilder('GCE')
            ->where('GCE.giftingEmail = :giftingEmail and GCE.balanceAmount >= :orderTotal and GCE.code = :code');
        $query->setParameter('code', $gift_card_code);
        $query->setParameter('giftingEmail', $customer_email);
        $query->setParameter('orderTotal', $orderTotal);
        $query->setMaxResults(1);
        $results = $query->getQuery()->getResult();
        if (is_array($results)) return @$results[0]; else return '';

    }

    /**
     * Modifies an existing entry
     *
     * @param GiftCardEntry $gcentry
     * @param string $data
     */
    public function updateEntry(GiftCardEntry $giftCardEntry, $data)
    {
        $giftCardEntry->setUsedAmount($data['usedAmount']);
        $giftCardEntry->setBalanceAmount($data['balanceAmount']);
        $em = \ORM::entityManager();
        $em->persist($giftCardEntry);
        $em->flush();
        return $giftCardEntry;
    }

    public function getGiftCardByID($gcID)
    {
        $em = \ORM::entityManager();
        $gc = $em->getRepository('Concrete\Package\CommunityStoreBuyGiftCard\Src\Entity\GiftCardEntry')->find($gcID);
        return $gc;
    }

    public function remove(GiftCardEntry $giftCardEntry)
    {
        $em = \ORM::entityManager();
        $em->remove($giftCardEntry);
        $em->flush();

    }
}
