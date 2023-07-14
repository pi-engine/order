<?php

namespace Order\Model;

class Order
{
    private int $id;
    private string $slug;
    private int $user_id;
    private string $type;
    private string $status;
    private ?int $subtotal;
    private ?int $tax;
    private ?int $discount;
    private ?int $gift;
    private ?int $total_amount;
    private ?string $information;
    private int $time_create;
    private int $time_update;
    private int $time_delete;

    public function __construct(
        int $id,
        string $slug,
        int $user_id,
        string $type = 'order',
        string $status = 'waiting',
        ?int $subtotal = null,
        ?int $tax = null,
        ?int $discount = null,
        ?int $gift = null,
        ?int $total_amount = null,
        ?string $information ,
        int $time_create = 0,
        int $time_update = 0,
        int $time_delete = 0
    ) {
        $this->id = $id;
        $this->slug = $slug;
        $this->user_id = $user_id;
        $this->type = $type;
        $this->status = $status;
        $this->subtotal = $subtotal;
        $this->tax = $tax;
        $this->discount = $discount;
        $this->gift = $gift;
        $this->total_amount = $total_amount;
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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getSubtotal(): int
    {
        return $this->subtotal;
    }

    public function setSubtotal(int $subtotal): void
    {
        $this->subtotal = $subtotal;
    }

    public function getTax(): int
    {
        return $this->tax;
    }

    public function setTax(int $tax): void
    {
        $this->tax = $tax;
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): void
    {
        $this->discount = $discount;
    }

    public function getGift(): int
    {
        return $this->gift;
    }

    public function setGift(int $gift): void
    {
        $this->gift = $gift;
    }

    public function getTotalAmount(): int
    {
        return $this->total_amount;
    }

    public function setTotalAmount(int $total_amount): void
    {
        $this->total_amount = $total_amount;
    }

    public function getInformation(): array
    {
        return $this->information;
    }

    public function setInformation(array $information): void
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
