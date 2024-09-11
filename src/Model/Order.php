<?php

namespace Order\Model;

class Order
{
    private int $id;
    private string $slug;
    private int $user_id;
    private string $order_type;
    private string $entity_type;
    private string $status;
    private ?int $subtotal;
    private ?int $tax;
    private ?int $discount;
    private ?int $coupon_id;
    private mixed $gift;
    private ?int $total_amount;
    private ?string $information;
    private ?string $payment_method;
    private ?string $payment;
    private int $time_create;
    private int $time_update;
    private int $time_delete;

    /**
     * @param int $id
     * @param string $slug
     * @param int $user_id
     * @param string $order_type
     * @param string $entity_type
     * @param string $status
     * @param int|null $subtotal
     * @param int|null $tax
     * @param int|null $discount
     * @param int|null $coupon_id
     * @param mixed $gift
     * @param int|null $total_amount
     * @param string|null $information
     * @param string|null $payment_method
     * @param string|null $payment
     * @param int $time_create
     * @param int $time_update
     * @param int $time_delete
     */
    public function __construct(int $id, string $slug, int $user_id, string $order_type, string $entity_type, string $status, ?int $subtotal, ?int $tax, ?int $discount, ?int $coupon_id, mixed $gift, ?int $total_amount, ?string $information, ?string $payment_method, ?string $payment, int $time_create, int $time_update, int $time_delete)
    {
        $this->id = $id;
        $this->slug = $slug;
        $this->user_id = $user_id;
        $this->order_type = $order_type;
        $this->entity_type = $entity_type;
        $this->status = $status;
        $this->subtotal = $subtotal;
        $this->tax = $tax;
        $this->discount = $discount;
        $this->coupon_id = $coupon_id;
        $this->gift = $gift;
        $this->total_amount = $total_amount;
        $this->information = $information;
        $this->payment_method = $payment_method;
        $this->payment = $payment;
        $this->time_create = $time_create;
        $this->time_update = $time_update;
        $this->time_delete = $time_delete;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getOrderType(): string
    {
        return $this->order_type;
    }

    public function setOrderType(string $order_type): void
    {
        $this->order_type = $order_type;
    }

    public function getEntityType(): string
    {
        return $this->entity_type;
    }

    public function setEntityType(string $entity_type): void
    {
        $this->entity_type = $entity_type;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getSubtotal(): ?int
    {
        return $this->subtotal;
    }

    public function setSubtotal(?int $subtotal): void
    {
        $this->subtotal = $subtotal;
    }

    public function getTax(): ?int
    {
        return $this->tax;
    }

    public function setTax(?int $tax): void
    {
        $this->tax = $tax;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): void
    {
        $this->discount = $discount;
    }

    public function getCouponId(): ?int
    {
        return $this->coupon_id;
    }

    public function setCouponId(?int $coupon_id): void
    {
        $this->coupon_id = $coupon_id;
    }

    public function getGift(): mixed
    {
        return $this->gift;
    }

    public function setGift(mixed $gift): void
    {
        $this->gift = $gift;
    }

    public function getTotalAmount(): ?int
    {
        return $this->total_amount;
    }

    public function setTotalAmount(?int $total_amount): void
    {
        $this->total_amount = $total_amount;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(?string $information): void
    {
        $this->information = $information;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(?string $payment_method): void
    {
        $this->payment_method = $payment_method;
    }

    public function getPayment(): ?string
    {
        return $this->payment;
    }

    public function setPayment(?string $payment): void
    {
        $this->payment = $payment;
    }

    public function getTimeCreate(): int
    {
        return $this->time_create;
    }

    public function setTimeCreate(int $time_create): void
    {
        $this->time_create = $time_create;
    }

    public function getTimeUpdate(): int
    {
        return $this->time_update;
    }

    public function setTimeUpdate(int $time_update): void
    {
        $this->time_update = $time_update;
    }

    public function getTimeDelete(): int
    {
        return $this->time_delete;
    }

    public function setTimeDelete(int $time_delete): void
    {
        $this->time_delete = $time_delete;
    }

}