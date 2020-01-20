<?php declare(strict_types=1);

namespace app\models;

class Shortener extends \Phalcon\Mvc\Model
{
    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $url;

    public function __set($key, $value)
    {
        $this->{$key} = $value;
    }

    /**
     * this generates hash key for the next record, sets and returns it
     *
     * @return string
     */
    public function generateHash()
    {
        return $this->hash = str_pad(base_convert(self::count() + 1, 10, 36), 5, 'z', STR_PAD_LEFT);
    }
}
