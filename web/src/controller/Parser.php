<?php
require_once(realpath(dirname(__FILE__)) . "/../util/ClassCastException.php");

/**
 * @author saifkhichi96
 * @version 1.0
 */
class Parser
{
    /**
     * Converts a JSON string into an object.
     * 
     * @param string $jsonString the JSON string to decode
     * @param object $typedInstance instance of typed class to decode string into
     * 
     * @throws ClassCastException
     * @return object instance of given typed class created from JSON string
     */
    public static function getTypedObject($jsonString, $typedInstance)
    {
        $stdObj = json_decode($jsonString);
        return self::cast($typedInstance, $stdObj);
    }

    /**
     * Converts an object into a JSON string.
     * 
     * @param object $typedInstance instance of a typed class
     * 
     * @return string JSON string representing the given object
     */
    public static function getJsonData($typedInstance) {
        if (method_exists($typedInstance, "getJsonData")) {
            return json_encode($typedInstance->getJsonData());
        } else {
            return json_encode($typedInstance);
        }
    } 

    /**
     * Prints a given object in a JSON document.
     * 
     * @param object $typedInstance class instance to convert to JSON
     */
    public static function echoJsonData($typedInstance)
    {
        header("Content-type: application/json");
        echo self::getJsonData($typedInstance);
    }

    /**
     * Cast a stdClass object to an object with type
     *
     * @param string $className
     * @param stdClass $object
     * @throws ClassCastException
     * @return mixed new typed object
     */
    private static function cast($new, stdClass &$object)
    {
        foreach ($object as $property => &$value) {
            if (!property_exists($new, $property)) {
                throw new ClassCastException();
            }
            $new->$property = &$value;
            unset($object->$property);
        }
        unset($value);
        $object = (unset)$object;
        return $new;
    }
}
?>