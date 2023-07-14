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
     * @var AdapterInterface
     */
    private AdapterInterface $db;

    /**
     * @var Order
     */
    private Order $orderPrototype;

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
        Order           $orderPrototype,
        OrderItem           $orderItemPrototype
    )
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
        $this->orderPrototype = $orderPrototype;
        $this->orderItemPrototype = $orderItemPrototype;
    }

    /**
     * @param array $params
     *
     * @return HydratingResultSet|array
     */
    public function getOrderList(array $params = []): HydratingResultSet|array
    {

        $where = ['receiver_id IN (' . $params['user_id'] . ') OR  sender_id IN (' . $params['user_id'] . ') OR type="global" '];
        if (isset($params['status']) && !empty($params['status'])) {
            $where['status'] = $params['status'];
        }
        if (isset($params['viewed']) && !empty($params['viewed'])) {
            $where['viewed'] = $params['viewed'];
        }
        if (isset($params['sent']) && !empty($params['sent'])) {
            $where['sent'] = $params['sent'];
        }
        if (isset($params['id']) && !empty($params['id'])) {
            $where['id'] = $params['id'];
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
        return $this->getOrder($id);
    }

    /**
     * @param string $parameter
     * @param string $type
     *
     * @return object|array
     */
    public function getOrder($parameter, $type = 'id'): object|array
    {
        $where = [$type => $parameter];

        $sql = new Sql($this->db);
        $select = $sql->select($this->tableOrder)->where($where);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new RuntimeException(
                sprintf(
                    'Failed retrieving blog post with identifier "%s"; unknown database error.',
                    $parameter
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
        $update->where(['id' => $params['id'], 'receiver_id' => $params['receiver_id']]);

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
}