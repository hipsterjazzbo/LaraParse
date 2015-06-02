<?php

namespace LaraParse\Repositories;

use LaraParse\Repositories\Contracts\ParseRepository;
use Parse\ParseGeoPoint;
use Parse\ParseObject;
use Parse\ParseQuery;

abstract class AbstractParseRepository implements ParseRepository
{

    /**
     * @var \Parse\ParseQuery
     */
    protected $query;

    /**
     * @var bool
     */
    protected $useMasterKey = false;

    public function __construct()
    {
        $this->query = new ParseQuery($this->getParseClass());
    }

    /**
     * Specify Parse class name
     *
     * @return string
     */
    abstract public function getParseClass();

    /**
     * @param bool $shouldUse
     *
     * @return $this
     */
    public function useMasterKey($shouldUse = false)
    {
        $that               = clone $this;
        $that->useMasterKey = $shouldUse;

        return $that;
    }

    /**
     * @return ParseObject[]
     */
    public function all()
    {
        // TODO: Make this deal with actual pagination
        $this->query->limit(1000);

        return $this->query->find($this->useMasterKey);
    }

    /**
     * @param       $perPage
     *
     * @return ParseObject[]
     */
    public function paginate($perPage = 1)
    {
        // TODO: Implement paginate() method.
    }

    /**
     * @param array $data
     *
     * @return ParseObject
     */
    public function create(array $data)
    {
        $parseClass = new ParseObject($this->getParseClass());

        $this->setValues($data, $parseClass);
        
        return $parseClass;
    }

    /**
     * @param       $id
     * @param array $data
     *
     * @return mixed
     */
    public function update($id, array $data)
    {
        $parseClass = $this->find($id);

        $this->setValues($data, $parseClass);
        
        return $parseClass;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        $parseClass = $this->find($id);

        $parseClass->destroy($this->useMasterKey);
    }

    /**
     * @param       $id
     * @param array $columns
     *
     * @return ParseObject
     */
    public function find($id, $columns = ['*'])
    {
        return $this->query->get($id, $this->useMasterKey);
    }

    /**
     * @param       $field
     * @param       $value
     * @param array $columns
     *
     * @return ParseObject
     */
    public function findBy($field, $value, $columns = ['*'])
    {
        $this->query->equalTo($field, $value);

        return $this->query->first($this->useMasterKey);
    }

    /**
     * @param $column
     * @param $latitude
     * @param $longitude
     * @param $limit
     *
     * @return \Parse\ParseObject[]
     */
    public function near($column, $latitude, $longitude, $limit = 10)
    {
        $location = new ParseGeoPoint($latitude, $longitude);

        $this->query->near($column, $location);
        $this->query->limit($limit);

        return $this->query->find($this->useMasterKey);
    }

    /**
     * @param $column
     * @param $latitude
     * @param $longitude
     * @param $distance
     *
     * @return \Parse\ParseObject[]
     */
    public function within($column, $latitude, $longitude, $distance)
    {
        $location = new ParseGeoPoint($latitude, $longitude);

        switch (config('parse.units', 'kilometers')) {
            case 'kilometers':
                $this->query->withinKilometers($column, $location, $distance);
                break;

            case 'miles':
                $this->query->withinMiles($column, $location, $distance);
                break;

            case 'radians':
                $this->query->withinRadians($column, $location, $distance);
                break;
        }

        return $this->query->find($this->useMasterKey);
    }

    /**
     * @param $column
     * @param $swLatitude
     * @param $swLongitude
     * @param $neLatitude
     * @param $neLongitude
     *
     * @return \Parse\ParseObject[]
     */
    public function withinBox($column, $swLatitude, $swLongitude, $neLatitude, $neLongitude)
    {
        $southWest = new ParseGeoPoint((float)$swLatitude, (float)$swLongitude);
        $northEast = new ParseGeoPoint((float)$neLatitude, (float)$neLongitude);

        $this->query->withinGeoBox($column, $southWest, $northEast);

        return $this->query->find($this->useMasterKey);
    }

    /**
     * @param array       $data
     * @param ParseObject $parseObject
     *
     * @return mixed
     */
    protected function setValues(array $data, $parseObject)
    {
        foreach ($data as $key => $value) {
            // If it's an array, we need to use different setter methods
            if (is_array($value)) {
                // Associative array
                if (count(array_filter(array_keys($value), 'is_string'))) {
                    $parseObject->setAssociativeArray($key, $value);
                } else {
                    $parseObject->setArray($key, $value);
                }
            } else {
                $parseObject->set($key, $value);
            }
        }

        return $parseObject->save($this->useMasterKey);
    }
}
