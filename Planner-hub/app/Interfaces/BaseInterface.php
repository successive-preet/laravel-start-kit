<?php

namespace App\Interfaces;

/**
 * Interface BaseInterface.
 */
interface BaseInterface
{
    public function all(array $columns = ['*']);

    public function count();

    public function create(array $data);

    public function createMultiple(array $data);

    public function createAndReturnId(array $data);

    public function delete();

    public function deleteById(int $id);

    public function deleteMultipleById(array $ids);

    public function first(array $columns = ['*']);

    public function get(array $columns = ['*']);

    public function getById(int $id, array $columns = ['*']);

    public function getByColumn($item, $column, array $columns = ['*']);

    public function paginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null);

    public function updateById(int $id, array $data, array $options = []);

    public function limit($limit);

    public function orderBy($column, $value);

    public function where($column, $value, $operator = '=');

    public function whereIn($column, $value);

    public function with($relations);

    public function getDataByCondition(array $filters, array $columns = ['*']);
}
