<?php

namespace LaraParse\Repositories\Contracts;

interface ParseRepository
{

    /**
     * @param bool $shouldUse
     *
     * @return $this
     */
    public function useMasterKey($shouldUse = false);

    /**
     * @return \Parse\ParseObject
     */
    public function all();

    /**
     * @param int $perPage
     *
     * @return \Parse\ParseObject[]
     */
    public function paginate($perPage = 1);

    /**
     * @param array $data
     *
     * @return \Parse\ParseObject
     */
    public function create(array $data);

    /**
     * @param array $data
     * @param       $id
     *
     * @return \Parse\ParseObject
     */
    public function update(array $data, $id);

    /**
     * @param $id
     *
     * @return bool Success
     */
    public function delete($id);

    /**
     * @param $id
     *
     * @return \Parse\ParseObject
     */
    public function find($id);

    /**
     * @param $field
     * @param $value
     *
     * @return \Parse\ParseObject
     */
    public function findBy($field, $value);

    /**
     * @param $column
     * @param $latitude
     * @param $longitude
     * @param $limit
     *
     * @return \Parse\ParseObject[]
     */
    public function near($column, $latitude, $longitude, $limit = 10);

    /**
     * @param $column
     * @param $latitude
     * @param $longitude
     * @param $distance
     *
     * @return \Parse\ParseObject[]
     */
    public function within($column, $latitude, $longitude, $distance);

    /**
     * @param $column
     * @param $swLatitude
     * @param $swLongitude
     * @param $neLatitude
     * @param $neLongitude
     *
     * @return \Parse\ParseObject[]
     */
    public function withinBox($column, $swLatitude, $swLongitude, $neLatitude, $neLongitude);
}
