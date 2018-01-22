<?php

/*
 * This file is part of the Mall Digital Ecosystem (MDE) project.
 *
 * (c) <SCCD> <office@sccd.lu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scc\Cdn\Tests\Helper\Traits;

/**
 * Trait ReflectionTrait
 *
 * Provide useful method to get and set protected and private properties and methods
 *
 * @author Jason Benedetti <jason.benedetti@sccd.lu>
 */
trait ReflectionTrait
{
    /**
     * Set a property by reflection.
     *
     * @param mixed  $object
     * @param string $name
     * @param mixed  $value
     *
     * @return \ReflectionProperty
     *
     * @throws \ReflectionException if the class does not exist.
     */
    protected static function setProperty($object, string $name, $value)
    {
        $class = new \ReflectionClass($object);
        $property = $class->getProperty($name);
        $property->setAccessible(true);
        $property->setValue($object, $value);

        return $property;
    }

    /**
     * Get a property by reflection.
     *
     * @param mixed  $object
     * @param string $name
     *
     * @return mixed
     *
     * @throws \ReflectionException if the class does not exist.
     */
    protected static function getProperty($object, string $name)
    {
        $class = new \ReflectionClass($object);
        $property = $class->getProperty($name);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * Get a method by reflection
     *
     * @param mixed  $object
     * @param string $method
     *
     * @return \ReflectionMethod
     *
     * @throws \ReflectionException
     */
    public function getReflectedMethod($object, $method)
    {
        $reflectedInstance = new \ReflectionClass($object);
        $reflectedMethod = $reflectedInstance->getMethod($method);
        $reflectedMethod->setAccessible(true);

        return $reflectedMethod;
    }

    /**
     * Return the classes which implements the given class name
     *
     * @param string $classname
     *
     * @return array
     *
     * @throws \ReflectionException if the class does not exist.
     */
    protected function getInstanceOfTypes($classname)
    {
        $classes = get_declared_classes();
        $transformations = array();

        foreach($classes as $class) {
            $reflect = new \ReflectionClass($class);

            if($reflect->implementsInterface($classname) && !$reflect->isAbstract()) {
                if (strpos($class, 'Mock') !== false) {
                    continue;
                }

                $transformations[] = $class;
            }
        }

        return $transformations;
    }
}