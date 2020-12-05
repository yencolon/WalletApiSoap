<?php
namespace App\Services;

use SoapFault;
/**
 * An example of a class that is used as a SOAP gateway to application functions.
 */
class DemoService
{

    public function Hola()
    {
        # code...
        return "Hola";
    }

    /*
    |--------------------------------------------------------------------------
    | Utility
    |--------------------------------------------------------------------------
    */
    
    /**
     * Convert array of KeyValue objects to associative array, non-recursively.
     *
     * @param \Viewflex\Zoap\Demo\Types\KeyValue[] $objects
     * @return array
     */
    protected static function arrayOfKeyValueToArray($objects)
    {
        $return = array();
        foreach ($objects as $object) {
            $return[$object->key] = $object->value;
        }

        return $return;
    }

}
