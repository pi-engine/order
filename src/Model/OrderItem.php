<?php

namespace Order\Model;

class OrderItem
{
    private int $id;
    private int $user_id;
    private int $order_id;
    private ?string $ordered_slug;
    private ?string $ordered_type;

    private ?int $ordered_id;
    private ?int $quantity;
    private ?int $unit_price;
    private ?int $tax;
    private ?int $discount;
    private ?int $coupon_id;
    private ?int $gift;
    private ?int $price;
    private int $status;
    private ?string $information;
    private int $time_create;
    private int $time_update;
    private int $time_delete;

    /**
     * @param int $id
     * @param int $user_id
     * @param int $order_id
     * @param string|null $ordered_slug
     * @param string|null $ordered_type
     * @param int|null $ordered_id
     * @param int|null $quantity
     * @param int|null $unit_price
     * @param int|null $tax
     * @param int|null $discount
     * @param int|null $coupon_id
     * @param int|null $gift
     * @param int|null $price
     * @param int $status
     * @param string|null $information
     * @param int $time_create
     * @param int $time_update
     * @param int $time_delete
     */
    public function __construct(int $id, int $user_id, int $order_id, ?string $ordered_slug, ?string $ordered_type, ?int $ordered_id, ?int $quantity, ?int $unit_price, ?int $tax, ?int $discount, ?int $coupon_id, ?int $gift, ?int $price, int $status, ?string $information, int $time_create, int $time_update, int $time_delete)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->order_id = $order_id;
        $this->ordered_slug = $ordered_slug;
        $this->ordered_type = $ordered_type;
        $this->ordered_id = $ordered_id;
        $this->quantity = $quantity;
        $this->unit_price = $unit_price;
        $this->tax = $tax;
        $this->discount = $discount;
        $this->coupon_id = $coupon_id;
        $this->gift = $gift;
        $this->price = $price;
        $this->status = $status;
        $this->information = $information;
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

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getOrderId(): int
    {
        return $this->order_id;
    }

    public function setOrderId(int $order_id): void
    {
        $this->order_id = $order_id;
    }

    public function getOrderedSlug(): ?string
    {
        return $this->ordered_slug;
    }

    public function setOrderedSlug(?string $ordered_slug): void
    {
        $this->ordered_slug = $ordered_slug;
    }

    public function getOrderedType(): ?string
    {
        return $this->ordered_type;
    }

    public function setOrderedType(?string $ordered_type): void
    {
        $this->ordered_type = $ordered_type;
    }

    public function getOrderedId(): ?int
    {
        return $this->ordered_id;
    }

    public function setOrderedId(?int $ordered_id): void
    {
        $this->ordered_id = $ordered_id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getUnitPrice(): ?int
    {
        return $this->unit_price;
    }

    public function setUnitPrice(?int $unit_price): void
    {
        $this->unit_price = $unit_price;
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

    public function getGift(): ?int
    {
        return $this->gift;
    }

    public function setGift(?int $gift): void
    {
        $this->gift = $gift;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(?string $information): void
    {
        $this->information = $information;
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
