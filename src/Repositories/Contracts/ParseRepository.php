<?php

namespace LaraParse\Repositories\Contracts;

use Illuminate\Support\Collection;
use Parse\ParseObject;

interface ParseRepository
{

    /**
     * @param bool $shouldUse
     *
     * @return $this
     */
    public function useMasterKey($shouldUse = false);

    /**
     * @return ParseObject
     */
    public function all();

    /**
     * @param int $perPage
     *
     * @return Collection|ParseObject[]
     */
    public function paginate($perPage = 1);

    /**
     * @param array $data
     *
     * @return ParseObject
     */
    public function create(array $data);

    /**
     * @param       $id
     * @param array $data
     *
     * @return ParseObject
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
     *
     * @return ParseObject
     */
    public function find($id);

    /**
     * @param $field
     * @param $value
     *
     * @return ParseObject
     */
    public function findBy($field, $value);

    /**
     * @param $column
     * @param $latitude
     * @param $longitude
     * @param $limit
     *
     * @return Collection|ParseObject[]
     */
    public function near($column, $latitude, $longitude, $limit = 10);

    /**
     * @param $column
     * @param $latitude
     * @param $longitude
     * @param $distance
     *
     * @return Collection|ParseObject[]
     */
    public function within($column, $latitude, $longitude, $distance);

    /**
     * @param $column
     * @param $swLatitude
     * @param $swLongitude
     * @param $neLatitude
     * @param $neLongitude
     *
     * @return Collection|ParseObject[]
     */
    public function withinBox($column, $swLatitude, $swLongitude, $neLatitude, $neLongitude);
}
