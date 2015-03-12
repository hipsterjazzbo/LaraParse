<?php

namespace LaraParse\Traits;

trait CastsParseProperties
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        switch ($key) {
            case 'objectId':
                return $this->getObjectId();
                break;

            case 'className':
                return $this->getClassName();
                break;

            case 'createdAt':
                return $this->getCreatedAt();
                break;

            case 'updatedAt':
                return $this->getUpdatedAt();
                break;

            default:
                return $this->get($key);
        }
    }

    /**
     * @param string $key
     *
     * @return mixed
     * @throws \Exception
     */
    public function get($key)
    {
        $value = parent::get($key);

        if ($value instanceof DateTime) {
            return $this->createCarbonFromDateTime($value);
        }

        return $value;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt()
    {
        $createdAt = parent::getCreatedAt();

        if (! is_null($createdAt)) {
            return $this->createCarbonFromDateTime($createdAt);
        }

        return $createdAt;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt()
    {
        $updatedAt = parent::getUpdatedAt();

        if (! is_null($updatedAt)) {
            return $this->createCarbonFromDateTime($updatedAt);
        }

        return $updatedAt;
    }

    /**
     * @param $value
     *
     * @return Carbon
     */
    private function createCarbonFromDateTime(DateTime $value)
    {
        return Carbon::createFromTimestampUTC($value->getTimestamp());
    }
}