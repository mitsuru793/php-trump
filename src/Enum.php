<?php
declare(strict_types=1);

namespace Trump;

abstract class Enum extends \MyCLabs\Enum\Enum
{
    /**
     * @return static
     */
    public function value()
    {
        return parent::getValue();
    }

    public function __call($calledMethod, $arguments)
    {
        $cacheKey = static::class . '_methods';
        if (!isset(static::$cache[$cacheKey])) {
            $array = static::toArray();
            $cache = [];
            foreach ($array as $constValue) {
                $method = sprintf('is%s', ucfirst(strtolower($constValue)));
                $const = strtoupper($constValue);
                $cache[$method] = $const;
            }

            static::$cache[$cacheKey] = $cache;
        }

        $cache = static::$cache[$cacheKey];

        if (isset($cache[$calledMethod]) || \array_key_exists($calledMethod, $cache)) {
            if (preg_match('/^is/', $calledMethod)) {
                $const = $cache[$calledMethod];
                return $this->getValue() === (static::toArray()[$const] ?? null);
            }
        }

        throw new \BadMethodCallException("No static method or enum constant '$calledMethod' in class " . static::class);
    }

    /**
     * @param int|string $value
     *
     * @return static
     */
    public static function of($value)
    {
        return new static($value);
    }
}
