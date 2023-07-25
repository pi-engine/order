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
    private ?int $gift;
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
     * @param int|null $gift
     * @param int|null $total_amount
     * @param string|null $information
     * @param string|null $payment
     * @param string|null $payment_method
     * @param int $time_create
     * @param int $time_update
     * @param int $time_delete
     */
    public function __construct(
        int     $id,
        string  $slug,
        int     $user_id,
        string  $order_type,
        string  $entity_type,
        string  $status,
        ?int    $subtotal,
        ?int    $tax,
        ?int    $discount,
        ?int    $gift,
        ?int    $total_amount,
        ?string $information,
        ?string $payment_method,
        ?string $payment,
        int     $time_create,
        int     $time_update,
        int     $time_delete
    )
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
        $this->gift = $gift;
        $this->total_amount = $total_amount;
        $this->information = $information;
        $this->payment_method = $payment_method;
        $this->payment = $payment;
        $this->time_create = $time_create;
        $this->time_update = $time_update;
        $this->time_delete = $time_delete;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getOrderType(): string
    {
        return $this->order_type;
    }

    /**
     * @param string $order_type
     */
    public function setOrderType(string $order_type): void
    {
        $this->order_type = $order_type;
    }

    /**
     * @return string
     */
    public function getEntityType(): string
    {
        return $this->entity_type;
    }

    /**
     * @param string $entity_type
     */
    public function setEntityType(string $entity_type): void
    {
        $this->entity_type = $entity_type;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int|null
     */
    public function getSubtotal(): ?int
    {
        return $this->subtotal;
    }

    /**
     * @param int|null $subtotal
     */
    public function setSubtotal(?int $subtotal): void
    {
        $this->subtotal = $subtotal;
    }

    /**
     * @return int|null
     */
    public function getTax(): ?int
    {
        return $this->tax;
    }

    /**
     * @param int|null $tax
     */
    public function setTax(?int $tax): void
    {
        $this->tax = $tax;
    }

    /**
     * @return int|null
     */
    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    /**
     * @param int|null $discount
     */
    public function setDiscount(?int $discount): void
    {
        $this->discount = $discount;
    }

    /**
     * @return int|null
     */
    public function getGift(): ?int
    {
        return $this->gift;
    }

    /**
     * @param int|null $gift
     */
    public function setGift(?int $gift): void
    {
        $this->gift = $gift;
    }

    /**
     * @return int|null
     */
    public function getTotalAmount(): ?int
    {
        return $this->total_amount;
    }

    /**
     * @param int|null $total_amount
     */
    public function setTotalAmount(?int $total_amount): void
    {
        $this->total_amount = $total_amount;
    }

    /**
     * @return string|null
     */
    public function getInformation(): ?string
    {
        return $this->information;
    }

    /**
     * @param string|null $information
     */
    public function setInformation(?string $information): void
    {
        $this->information = $information;
    }

    /**
     * @return string|null
     */
    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    /**
     * @param string|null $payment_method
     */
    public function setPaymentMethod(?string $payment_method): void
    {
        $this->payment_method = $payment_method;
    }

    /**
     * @return string|null
     */
    public function getPayment(): ?string
    {
        return $this->payment;
    }

    /**
     * @param string|null $payment
     */
    public function setPayment(?string $payment): void
    {
        $this->payment = $payment;
    }

    /**
     * @return int
     */
    public function getTimeCreate(): int
    {
        return $this->time_create;
    }

    /**
     * @param int $time_create
     */
    public function setTimeCreate(int $time_create): void
    {
        $this->time_create = $time_create;
    }

    /**
     * @return int
     */
    public function getTimeUpdate(): int
    {
        return $this->time_update;
    }

    /**
     * @param int $time_update
     */
    public function setTimeUpdate(int $time_update): void
    {
        $this->time_update = $time_update;
    }

    /**
     * @return int
     */
    public function getTimeDelete(): int
    {
        return $this->time_delete;
    }

    /**
     * @param int $time_delete
     */
    public function setTimeDelete(int $time_delete): void
    {
        $this->time_delete = $time_delete;
    }

}