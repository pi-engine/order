<?php

namespace Order\Repository;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Predicate\Expression;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Update;
use Laminas\Hydrator\HydratorInterface;
use Order\Model\Coupon;
use Order\Model\Order;
use Order\Model\OrderItem;
use RuntimeException;
use InvalidArgumentException;


class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Order Table name
     *
     * @var string
     */
    private string $tableOrder = 'order_order';

    /**
     * Order Item Table name
     *
     * @var string
     */
    private string $tableOrderItem = 'order_item';

    /**
     * Order Coupon Table name
     *
     * @var string
     */
    private string $tableOrderCoupon = 'order_coupon';

    /**
     * @var AdapterInterface
     */
    private AdapterInterface $db;

    /**
     * @var Order
     */
    private Order $orderPrototype;
    /**
     * @var Coupon
     */
    private Coupon $discountPrototype;

    /**
     * @var OrderItem
     */
    private OrderItem $orderItemPrototype;

    /**
     * @var HydratorInterface
     */
    private HydratorInterface $hydrator;

    public function __construct(
        AdapterInterface  $db,
        HydratorInterface $hydrator,
        Order             $orderPrototype,
        Coupon          $discountPrototype,
        OrderItem         $orderItemPrototype
    )
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
        $this->orderPrototype = $orderPrototype;
        $this->discountPrototype = $discountPrototype;
        $this->orderItemPrototype = $orderItemPrototype;
    }

    /**
     * @param array $params
     *
     * @return HydratingResultSet|array
     */
    public function getOrderList(array $params = []): HydratingResultSet|array
    {

        $where = [];
        if (isset($params['status']) && !empty($params['status'])) {
            $where['status'] = $params['status'];
        }
        if (isset($params['user_id']) && !empty($params['user_id'])) {
            $where['user_id'] = $params['user_id'];
        }
        if (isset($params['id']) && !empty($params['id'])) {
            $where['id'] = $params['id'];
        }
        if (isset($params['payment_method']) && !empty($params['payment_method'])) {
            $where['payment_method'] = $params['payment_method'];
        }
        if (isset($params['ref_id']) && !empty($params['ref_id'])) {
            $where['payment LIKE ?'] = '%' . $params['ref_id'] . '%';
        }
        if (isset($params['postal_code']) && !empty($params['postal_code'])) {
            $where[' 1>0 AND information LIKE ?'] =  '%"zip_code":"'.$params['postal_code'].'"%';
        }
        if (isset($params['name'])&&!empty($params['name'])) {
            $where[' 2>1 AND information LIKE ?'] ='%'.$params['name'].'%';
        }
        if (isset($params['phone'])&&!empty($params['phone'])) {
            $where[' 3>2 AND information LIKE ?'] ='%"phone":"'.$params['phone'].'"%';
        }
        if (isset($params['address'])&&!empty($params['address'])) {
            $where[' 4>3 AND information LIKE ?'] = '%'.$params['address'].'%';
        }
        if (isset($params['product'])&&!empty($params['product'])) {
            $where[' 5>4 AND information LIKE ?'] = '%"slug":"'.$params['product'].'"%';
        }
        if (isset($params['data_from']) && !empty($params['data_from'])) {
            $where['time_create >= ?'] = $params['data_from'];
        }
        if (isset($params['data_to']) && !empty($params['data_to'])) {
            $where['time_create <= ?'] = $params['data_to'];
        }


        $sql = new Sql($this->db);
        $select = $sql->select($this->tableOrder)->where($where)->order($params['order'])->offset($params['offset'])->limit($params['limit']);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->orderPrototype);
        $resultSet->initialize($result);

        return $resultSet;
    }
    public function getOrderCount(array $params = []): int
    {
        $where = [];
        if (isset($params['status']) && !empty($params['status'])) {
            $where['status'] = $params['status'];
        }
        if (isset($params['user_id']) && !empty($params['user_id'])) {
            $where['user_id'] = $params['user_id'];
        }
        if (isset($params['id']) && !empty($params['id'])) {
            $where['id'] = $params['id'];
        }
        if (isset($params['payment_method']) && !empty($params['payment_method'])) {
            $where['payment_method'] = $params['payment_method'];
        }
        if (isset($params['ref_id']) && !empty($params['ref_id'])) {
            $where['payment LIKE ?'] = '%' . $params['ref_id'] . '%';
        }
        if (isset($params['postal_code']) && !empty($params['postal_code'])) {
            $where[' 1>0 AND information LIKE ?'] =  '%"zip_code":"'.$params['postal_code'].'"%';
        }
        if (isset($params['name'])&&!empty($params['name'])) {
            $where[' 2>1 AND information LIKE ?'] ='%'.$params['name'].'%';
        }
        if (isset($params['phone'])&&!empty($params['phone'])) {
            $where[' 3>2 AND information LIKE ?'] ='%"phone":"'.$params['phone'].'"%';
        }
        if (isset($params['address'])&&!empty($params['address'])) {
            $where[' 4>3 AND information LIKE ?'] = '%'.$params['address'].'%';
        }
        if (isset($params['product'])&&!empty($params['product'])) {
            $where[' 5>4 AND information LIKE ?'] = '%"slug":"'.$params['product'].'"%';
        }

        $columns = ['count' => new Expression('count(*)')];
        $sql = new Sql($this->db);
        $select = $sql->select($this->tableOrder)->columns($columns)->where($where);
        $statement = $sql->prepareStatementForSqlObject($select);
        $row = $statement->execute()->current();
        return (int)$row['count'];
    }

    public function getCustomerCount(array $params = []): int
    {
        $where = [];
        if (isset($params['status']) && !empty($params['status'])) {
            $where['status'] = $params['status'];
        }
        if (isset($params['user_id']) && !empty($params['user_id'])) {
            $where['user_id'] = $params['user_id'];
        }
        if (isset($params['id']) && !empty($params['id'])) {
            $where['id'] = $params['id'];
        }
        if (isset($params['payment_method']) && !empty($params['payment_method'])) {
            $where['payment_method'] = $params['payment_method'];
        }
        if (isset($params['ref_id']) && !empty($params['ref_id'])) {
            $where['payment LIKE ?'] = '%' . $params['ref_id'] . '%';
        }
        if (isset($params['postal_code']) && !empty($params['postal_code'])) {
            $where[' 1>0 AND information LIKE ?'] =  '%"zip_code":"'.$params['postal_code'].'"%';
        }
        if (isset($params['name'])&&!empty($params['name'])) {
            $where[' 2>1 AND information LIKE ?'] ='%'.$params['name'].'%';
        }
        if (isset($params['phone'])&&!empty($params['phone'])) {
            $where[' 3>2 AND information LIKE ?'] ='%"phone":"'.$params['phone'].'"%';
        }
        if (isset($params['address'])&&!empty($params['address'])) {
            $where[' 4>3 AND information LIKE ?'] = '%'.$params['address'].'%';
        }
        if (isset($params['product'])&&!empty($params['product'])) {
            $where[' 5>4 AND information LIKE ?'] = '%"slug":"'.$params['product'].'"%';
        }

        $columns = ['count' => new Expression('count(distinct user_id)')];
        $sql = new Sql($this->db);
        $select = $sql->select($this->tableOrder)->columns($columns)->where($where);
        $statement = $sql->prepareStatementForSqlObject($select);
        $row = $statement->execute()->current();
        return (int)$row['count'];
    }

    /**
     * @param array $params
     *
     * @return array|object
     */
    public function addOrder(array $params): object|array
    {
        $insert = new Insert($this->tableOrder);
        $insert->values($params);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during blog post insert operation'
            );
        }
        $id = $result->getGeneratedValue();
        return $this->getOrder(['id' => $id]);
    }

    /**
     * @param string $parameter
     * @param string $type
     *
     * @return object|array
     */
    public function getOrder($params): object|array
    {
        $where = $params;

        $sql = new Sql($this->db);
        $select = $sql->select($this->tableOrder)->where($where);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new RuntimeException(
                sprintf(
                    'Failed retrieving blog post with identifier "%s"; unknown database error.',
                    ''
                )
            );
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->orderPrototype);
        $resultSet->initialize($result);
        $item = $resultSet->current();

        if (!$item) {
            return [];
        }

        return $item;
    }

    /**
     * @param array $params
     *
     * @return array|object
     */
    public function updateOrder(array $params): object|array
    {
        $update = new Update($this->tableOrder);
        $update->set($params);
        $update->where(['id' => $params['id']]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during update operation'
            );
        }
        return $this->getOrder($params['id']);
    }

    /**
     * @param array $params
     *
     * @return void
     */
    public function deleteOrder(array $params): void
    {
        $delete = new Delete($this->tableOrder);
        $delete->set($params);
        $delete->where(['id' => $params['id']]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();
    }

    /**
     * @param string $parameter
     * @param string $type
     *
     * @return object|array
     */
    public function getOrderItem($params): object|array
    {
        $where = $params;

        $sql = new Sql($this->db);
        $select = $sql->select($this->tableOrderItem)->where($where);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new RuntimeException(
                sprintf(
                    'Failed retrieving blog post with identifier "%s"; unknown database error.',
                    ''
                )
            );
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->orderPrototype);
        $resultSet->initialize($result);
        $item = $resultSet->current();

        if (!$item) {
            return [];
        }

        return $item;
    }

    /**
     * @param array $params
     *
     * @return array|object
     */
    public function addOrderItem(array $params): object|array
    {
        $insert = new Insert($this->tableOrderItem);
        $insert->values($params);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during blog post insert operation'
            );
        }
        $id = $result->getGeneratedValue();
        return $this->getOrderItem(['id' => $id]);
    }

    /**
     * @param $params
     * @return object|array
     */
    public function getCoupon($params): object|array
    {
        $where = $params;

        $sql = new Sql($this->db);
        $select = $sql->select($this->tableOrderCoupon)->where($where);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new RuntimeException(
                sprintf(
                    'Failed retrieving blog post with identifier "%s"; unknown database error.',
                    ''
                )
            );
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->discountPrototype);
        $resultSet->initialize($result);
        $item = $resultSet->current();

        if (!$item) {
            return [];
        }

        return $item;
    }

    /**
     * @param array $params
     *
     * @return array|object
     */
    public function updateCoupon(array $params): object|array
    {
        $update = new Update($this->tableOrderCoupon);
        $update->set($params);
        $update->where(['code' => $params['code']]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during update operation'
            );
        }
        return $this->getCoupon(['code' => $params['code']]);
    }

    /**
     * @param array $params
     *
     * @return HydratingResultSet|array
     */
    public function getCouponList(array $params = []): HydratingResultSet|array
    {

        $where = [];
        if (isset($params['status']) && !empty($params['status'])) {
            $where['status'] = $params['status'];
        }
        if (isset($params['id']) && !empty($params['id'])) {
            $where['id'] = $params['id'];
        }
        if (isset($params['data_from']) && !empty($params['data_from'])) {
            $where['time_create >= ?'] = $params['data_from'];
        }
        if (isset($params['data_to']) && !empty($params['data_to'])) {
            $where['time_create <= ?'] = $params['data_to'];
        }

        $sql = new Sql($this->db);
        $select = $sql->select($this->tableOrderCoupon)->where($where)->order($params['order'])->offset($params['offset'])->limit($params['limit']);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->discountPrototype);
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function getCouponCount(array $params = []): int
    {
        $where = [];
        if (isset($params['status']) && !empty($params['status'])) {
            $where['status'] = $params['status'];
        }
        if (isset($params['id']) && !empty($params['id'])) {
            $where['id'] = $params['id'];
        }
        if (isset($params['data_from']) && !empty($params['data_from'])) {
            $where['time_create >= ?'] = $params['data_from'];
        }
        if (isset($params['data_to']) && !empty($params['data_to'])) {
            $where['time_create <= ?'] = $params['data_to'];
        }

        $columns = ['count' => new Expression('count(*)')];
        $sql = new Sql($this->db);
        $select = $sql->select($this->tableOrderCoupon)->columns($columns)->where($where);
        $statement = $sql->prepareStatementForSqlObject($select);
        $row = $statement->execute()->current();
        return (int)$row['count'];
    }

    public function addCoupon(array $params): object|array
    {
        $insert = new Insert($this->tableOrderCoupon);
        $insert->values($params);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during blog post insert operation'
            );
        }
        $id = $result->getGeneratedValue();
        return $this->getCoupon(['id' => $id]);
    }
}