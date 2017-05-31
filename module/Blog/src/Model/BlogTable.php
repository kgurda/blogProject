<?php
/**
 * Created by PhpStorm.
 * User: katarzynagurda
 * Date: 31.05.17
 * Time: 11:17
 */

namespace Blog\Model;


use Zend\Db\Exception\RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class BlogTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getBlog($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveBlog(Blog $blog)
    {
        $data = [
            'title' => $blog->title,
            'description'  => $blog->description,
        ];

        $id = (int) $blog->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getBlog($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteBlog($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}