<?php

namespace Order\Service;

use Content\Service\ItemService;
use Content\Service\MetaService;
use Product\Service\ServiceInterface;
use stdClass;
use User\Service\AccountService;
use User\Service\UtilityService;
use function explode;
use function in_array;
use function is_object;
use function json_decode;

class AddressService implements ServiceInterface
{

    /** @var AccountService */
    protected AccountService $accountService;

    /** @var UtilityService */
    protected UtilityService $utilityService;

    /* @var array */
    protected array $config;

    /** @var ItemService */
    protected ItemService $itemService;

    public function __construct(
        AccountService $accountService,
        UtilityService $utilityService,
        ItemService    $itemService,
                       $config
    )
    {
        $this->accountService = $accountService;
        $this->utilityService = $utilityService;
        $this->itemService = $itemService;
        $this->config = $config;
    }


    public function addAddress(object|array $requestBody, mixed $account): object|array
    {
        $params = [
            'user_id'=>$account['id'],
            'type'=>'address',
            'status'=>1,
            'slug'=>sprintf('address-%s-%s', $account['id'], time()),
        ];
        $information = array_merge($requestBody,$params);
        $params['information'] = json_encode($information);
        return $this->itemService->addItem($params,$account);
    }

    public function getAddressList(object|array $requestBody): array
    {
        return $this->itemService->getItemList($requestBody);
    }

}
