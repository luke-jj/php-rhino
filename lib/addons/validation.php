<?php

/**
 * Validation class. Include this class in your datamodel to gain access
 * to the convenient `validate` function to perform datamodel or http request
 * body validation.
 */

class Val {

  /**
   * validate an object against a schema.
   *
   * @param $obj Array - target object
   * @param $schema Array - schema or rule to test against.
   * @return Array - Error object containing validation error information -
   *                 or `null` if validation was successful.
   */
  function validate($obj, $schema) {

    $error = array();

    $difference = array_diff(array_keys($obj), array_keys($schema));

    // return an error if $obj has members not present on the schema.
    if (!empty($difference)) {
      return new ValError("Invalid properties: "
        . implode(", ", $difference));
    }

    // return an error if $obj is missing a required property
    foreach ($schema as $property => $required) {
      if ($required && (!array_key_exists($property, $obj))) {
        return new ValError("Missing a property: $property");
      }
    }

    return null;
  }
}

/**
 * An instance of this class is returned if the validation failed.
 */

class ValError {

  public $message;

  public function __construct($message) {
    $this->message = $message;
  }
}
