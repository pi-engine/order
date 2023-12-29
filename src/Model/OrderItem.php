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
    private ?int $gift;
    private ?int $price;
    private int $status;
    private ?string $information;
    private int $time_create;
    private int $time_update;
    private int $time_delete;

    public function __construct(
        int $id,
        int $user_id,
        int $order_id,
        ?string $ordered_slug = null,
        ?string $ordered_type = null,
        ?int $ordered_id = null,
        ?int $quantity = null,
        ?int $unit_price = null,
        ?int $tax = null,
        ?int $discount = null,
        ?int $gift = null,
        ?int $price = null,
        int $status = 1,
        ?string $information = null,
        int $time_create = 0,
        int $time_update = 0,
        int $time_delete = 0
    ) {
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

    public function getOrderId(): int
    {
        return $this->order_id;
    }

    public function setOrderId(int $order_id): void
    {
        $this->order_id = $order_id;
    }

    public function getOrderedId(): ?int
    {
        return $this->ordered_id;
    }

    public function setOrderedId(?int $ordered_id): void
    {
        $this->ordered_id = $ordered_id;
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

    public function getGift(): mixed
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

    public function getInformation(): ?array
    {
        return $this->information;
    }

    public function setInformation(?array $information): void
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

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }
}
