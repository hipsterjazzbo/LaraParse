<?php

namespace LaraParse\Repositories;

use LaraParse\Repositories\Contracts\ParseRepository;
use Parse\ParseGeoPoint;
use Parse\ParseObject;
use Parse\ParseQuery;
use Illuminate\Support\Collection;

/**
 * Class AbstractParseRepository
 * @package LaraParse\Repositories
 */
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
        $that = clone $this;
        $that->useMasterKey = $shouldUse;

        return $that;
    }

    /**
     * @param $keyToInclude = []
     * @return Collection|ParseObject[]
     */
    public function all($keyToInclude = [])
    {
        // TODO: Make this deal with actual pagination
        $this->query->limit(1000);
        for ($i = 0; $i < count($keyToInclude); $i++) {
            $this->query->includeKey($keyToInclude[$i]);
        }

        return Collection::make($this->query->find($this->useMasterKey));
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
        $subClass =  ParseObject::getRegisteredSubclass($this->getParseClass());
        $parseClass = new $subClass();
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
     * @param array $keyToInclude
     *
     * @return ParseObject
     */
    public function find($id, $columns = ['*'], $keyToInclude = [])
    {
        for ($i = 0; $i < count($keyToInclude); $i++) {
            $this->query->includeKey($keyToInclude[$i]);
        }

        return $this->query->get($id, $this->useMasterKey);
    }

    /**
     * @param       $field
     * @param       $value
     * @param array $columns
     * @param array $keyToInclude
     *
     * @return ParseObject
     */
    public function findBy($field, $value, $columns = ['*'], $keyToInclude = [])
    {
        $this->query->equalTo($field, $value);
        for ($i = 0; $i < count($keyToInclude); $i++) {
            $this->query->includeKey($keyToInclude[$i]);
        }

        return $this->query->first($this->useMasterKey);
    }

    /**
     * @param $column
     * @param $latitude
     * @param $longitude
     * @param $limit
     * @param array $keyToInclude
     *
     * @return Collection|ParseObject[]
     */
    public function near($column, $latitude, $longitude, $limit = 10, $keyToInclude = [])
    {
        $location = new ParseGeoPoint($latitude, $longitude);

        $this->query->near($column, $location);
        $this->query->limit($limit);
        for ($i = 0; $i < count($keyToInclude); $i++) {
            $this->query->includeKey($keyToInclude[$i]);
        }

        return Collection::make($this->query->find($this->useMasterKey));
    }

    /**
     * @param $column
     * @param $latitude
     * @param $longitude
     * @param $distance
     * @param array $keyToInclude
     *
     * @return Collection|ParseObject[]
     */
    public function within($column, $latitude, $longitude, $distance, $keyToInclude = [])
    {
        $location = new ParseGeoPoint($latitude, $longitude);
        for ($i = 0; $i < count($keyToInclude); $i++) {
            $this->query->includeKey($keyToInclude[$i]);
        }

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

        return Collection::make($this->query->find($this->useMasterKey));
    }

    /**
     * @param $column
     * @param $swLatitude
     * @param $swLongitude
     * @param $neLatitude
     * @param $neLongitude
     * @param array $keyToInclude
     *
     * @return Collection|ParseObject[]
     */
    public function withinBox($column, $swLatitude, $swLongitude, $neLatitude, $neLongitude, $keyToInclude = [])
    {
        $southWest = new ParseGeoPoint((float)$swLatitude, (float)$swLongitude);
        $northEast = new ParseGeoPoint((float)$neLatitude, (float)$neLongitude);

        $this->query->withinGeoBox($column, $southWest, $northEast);
        for ($i = 0; $i < count($keyToInclude); $i++) {
            $this->query->includeKey($keyToInclude[$i]);
        }

        return Collection::make($this->query->find($this->useMasterKey));
    }

    /**
     * Returns all objects where a given field matches a given value
     * @param string $field
     * @param mixed $value
     * @param array $keyToInclude
     *
     * @return Collection|ParseObject[]
     */
    public function findAllBy($field, $value, $keyToInclude = [])
    {
        $this->query->equalTo($field, $value);
        for ($i = 0; $i < count($keyToInclude); $i++) {
            $this->query->includeKey($keyToInclude[$i]);
        }

        return Collection::make($this->query->find($this->useMasterKey));
    }

    /**
     * @param array $data
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
