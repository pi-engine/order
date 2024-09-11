<?php

namespace Order\Model;
class Coupon
{
    private mixed $id;
    private mixed $code;
    private mixed $type;
    private mixed $value;
    private mixed $rule;
    private mixed $count_limit;
    private mixed $count_used;
    private mixed $status;
    private mixed $information;
    private mixed $time_create;
    private mixed $time_start;
    private mixed $time_update;
    private mixed $time_expired;

    /**
     * @return mixed
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(mixed $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCode(): mixed
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode(mixed $code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getType(): mixed
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType(mixed $type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getRule(): mixed
    {
        return $this->rule;
    }

    /**
     * @param mixed $rule
     */
    public function setRule(mixed $rule): void
    {
        $this->rule = $rule;
    }

    /**
     * @return mixed
     */
    public function getCountLimit(): mixed
    {
        return $this->count_limit;
    }

    /**
     * @param mixed $count_limit
     */
    public function setCountLimit(mixed $count_limit): void
    {
        $this->count_limit = $count_limit;
    }

    /**
     * @return mixed
     */
    public function getCountUsed(): mixed
    {
        return $this->count_used;
    }

    /**
     * @param mixed $count_used
     */
    public function setCountUsed(mixed $count_used): void
    {
        $this->count_used = $count_used;
    }

    /**
     * @return mixed
     */
    public function getStatus(): mixed
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus(mixed $status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getInformation(): mixed
    {
        return $this->information;
    }

    /**
     * @param mixed $information
     */
    public function setInformation(mixed $information): void
    {
        $this->information = $information;
    }

    /**
     * @return mixed
     */
    public function getTimeCreate(): mixed
    {
        return $this->time_create;
    }

    /**
     * @param mixed $time_create
     */
    public function setTimeCreate(mixed $time_create): void
    {
        $this->time_create = $time_create;
    }

    /**
     * @return mixed
     */
    public function getTimeStart(): mixed
    {
        return $this->time_start;
    }

    /**
     * @param mixed $time_start
     */
    public function setTimeStart(mixed $time_start): void
    {
        $this->time_start = $time_start;
    }

    /**
     * @return mixed
     */
    public function getTimeUpdate(): mixed
    {
        return $this->time_update;
    }

    /**
     * @param mixed $time_update
     */
    public function setTimeUpdate(mixed $time_update): void
    {
        $this->time_update = $time_update;
    }

    /**
     * @return mixed
     */
    public function getTimeExpired(): mixed
    {
        return $this->time_expired;
    }

    /**
     * @param mixed $time_expired
     */
    public function setTimeExpired(mixed $time_expired): void
    {
        $this->time_expired = $time_expired;
    }


}
