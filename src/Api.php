<?php
namespace ArrayApi;

class Api
{
    public static function merge(array &$array1, array &$array2): array
    {
        $merged = &$array1;

        foreach ($array2 as $key => $value) {
            if (is_array($value) && array_key_exists($key, $merged) && is_array($merged[$key])) {
                $merged[$key] = static::merge($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    public static function walk(array &$array, callable $callback): void
    {
        foreach ($array as $key => &$value) {

            $callback($value, $key);

            if (is_array($value)) {
                static::walk($value, $callback);
            } else {
                //$callback($value, $key);
            }
        }
    }

    public static function reduce(array $array, callable $callback, mixed $initial = null): mixed
    {
        $accumulator = $initial;

        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = static::reduce($value, $callback, $initial);
            }

            $accumulator = $callback($accumulator, $value, $key);

            unset($array[$key]);
        }

        return $accumulator;
    }
}