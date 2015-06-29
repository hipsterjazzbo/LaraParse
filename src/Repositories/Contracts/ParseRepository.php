<?php

namespace LaraParse\Repositories\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface ParseRepository
 * @package LaraParse\Repositories\Contracts
 */
interface ParseRepository
{

    /**
     * @param bool $shouldUse
     *
     * @return $this
     */
    public function useMasterKey($shouldUse = false);

    /**
     * @param array $keyToInclude An Array of include Keys
     * @return \Parse\ParseObject
     */
    public function all($keyToInclude = []);

    /**
     * @param int $perPage
     *
     * @return \Parse\ParseObject[]|Collection
     */
    public function paginate($perPage = 1);

    /**
     * @param array $data
     *
     * @return \Parse\ParseObject
     */
    public function create(array $data);

    /**
     * @param       $id
     * @param array $data
     *
     * @return \Parse\ParseObject
     */
    public function update($id, array $data);

    /**
     * @param $id
     *
     * @return bool Success
     */
    public function delete($id);

    /**
     * @param $id
     * @param array $keyToInclude
     *
     * @return \Parse\ParseObject
     */
    public function find($id, $keyToInclude = []);

    /**
     * @param $field
     * @param $value
     * @param array $keyToInclude
     *
     * @return \Parse\ParseObject
     */
    public function findBy($field, $value, $keyToInclude = []);

    /**
     * @param $column
     * @param $latitude
     * @param $longitude
     * @param $limit
     * @param array $keyToInclude
     *
     * @return \Parse\ParseObject[]|Collection
     */
    public function near($column, $latitude, $longitude, $limit = 10, $keyToInclude = []);

    /**
     * @param $column
     * @param $latitude
     * @param $longitude
     * @param $distance
     * @param array $keyToInclude
     *
     * @return \Parse\ParseObject[]|Collection
     */
    public function within($column, $latitude, $longitude, $distance, $keyToInclude = []);

    /**
     * @param $column
     * @param $swLatitude
     * @param $swLongitude
     * @param $neLatitude
     * @param $neLongitude
     * @param array $keyToInclude
     *
     * @return \Parse\ParseObject[]|Collection
     */
    public function withinBox($column, $swLatitude, $swLongitude, $neLatitude, $neLongitude, $keyToInclude = []);

    /**
     * Returns all leads where a given field matches a given value
     * @param string $field
     * @param mixed $value
     * @param array $keyToInclude
     *
     * @return \Parse\ParseObject[]|Collection
     */
    public function findAllBy($field, $value, $keyToInclude = []);
}
