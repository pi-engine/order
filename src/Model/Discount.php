<?php

namespace Order\Model;
class Discount
{
    private mixed $id;
    private mixed $code;
    private mixed $type;
    private mixed $value;
    private mixed $rule;
    private mixed $count_limit;
    private mixed $count_used;
    private mixed $status;
    private mixed $time_create;
    private mixed $time_start;
    private mixed $time_update;
    private mixed $time_expired;

    // Constructor
    public function __construct($id, $code, $type, $value, $rule, $count_limit, $count_used, $status, $time_create, $time_start, $time_update, $time_expired)
    {
        $this->id = $id;
        $this->code = $code;
        $this->type = $type;
        $this->value = $value;
        $this->rule = $rule;
        $this->count_limit = $count_limit;
        $this->count_used = $count_used;
        $this->status = $status;
        $this->time_create = $time_create;
        $this->time_start = $time_start;
        $this->time_update = $time_update;
        $this->time_expired = $time_expired;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getRule()
    {
        return $this->rule;
    }

    public function getCountLimit()
    {
        return $this->count_limit;
    }

    public function getCountUsed()
    {
        return $this->count_used;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getTimeCreate()
    {
        return $this->time_create;
    }

    public function getTimeStart()
    {
        return $this->time_start;
    }

    public function getTimeUpdate()
    {
        return $this->time_update;
    }

    public function getTimeExpired()
    {
        return $this->time_expired;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setRule($rule)
    {
        $this->rule = $rule;
    }

    public function setCountLimit($count_limit)
    {
        $this->count_limit = $count_limit;
    }

    public function setCountUsed($count_used)
    {
        $this->count_used = $count_used;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setTimeCreate($time_create)
    {
        $this->time_create = $time_create;
    }

    public function setTimeStart($time_start)
    {
        $this->time_start = $time_start;
    }

    public function setTimeUpdate($time_update)
    {
        $this->time_update = $time_update;
    }

    public function setTimeExpired($time_expired)
    {
        $this->time_expired = $time_expired;
    }
}
