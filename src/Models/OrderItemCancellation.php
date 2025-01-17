<?php

namespace HarmSmits\BolComClient\Models;

/**
 * @method null|string getOrderItemId()
 * @method self setOrderItemId(string $orderItemId)
 * @method null|string getReasonCode()
 */
final class OrderItemCancellation extends AModel
{
    const REASON_CODE_OUT_OF_STOCK = 'OUT_OF_STOCK';
    const REASON_CODE_REQUESTED_BY_CUSTOMER = 'REQUESTED_BY_CUSTOMER';
    const REASON_CODE_BAD_CONDITION = 'BAD_CONDITION';
    const REASON_CODE_HIGHER_SHIPCOST = 'HIGHER_SHIPCOST';
    const REASON_CODE_INCORRECT_PRICE = 'INCORRECT_PRICE';
    const REASON_CODE_NOT_AVAIL_IN_TIME = 'NOT_AVAIL_IN_TIME';
    const REASON_CODE_NO_BOL_GUARANTEE = 'NO_BOL_GUARANTEE';
    const REASON_CODE_ORDERED_TWICE = 'ORDERED_TWICE';
    const REASON_CODE_RETAIN_ITEM = 'RETAIN_ITEM';
    const REASON_CODE_TECH_ISSUE = 'TECH_ISSUE';
    const REASON_CODE_UNFINDABLE_ITEM = 'UNFINDABLE_ITEM';
    const REASON_CODE_OTHER = 'OTHER';

    /**
     * The id for the order item (1 order can have multiple order items).
     * @var string
     */
    protected string $orderItemId;

    /**
     * The code representing the reason for cancellation of this item.
     * @var string
     */
    protected string $reasonCode;

    public function setReasonCode(string $reasonCode): self
    {
        $this->_checkEnumBounds($reasonCode, [
            "OUT_OF_STOCK",
            "REQUESTED_BY_CUSTOMER",
            "BAD_CONDITION",
            "HIGHER_SHIPCOST",
            "INCORRECT_PRICE",
            "NOT_AVAIL_IN_TIME",
            "NO_BOL_GUARANTEE",
            "ORDERED_TWICE",
            "RETAIN_ITEM",
            "TECH_ISSUE",
            "UNFINDABLE_ITEM",
            "OTHER",
        ]);
        $this->reasonCode = $reasonCode;
        return $this;
    }
}
