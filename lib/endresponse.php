<?php

/**
 * This exception is thrown to signal the end of the http response.
 * The http response output stream must be closed if this exception occurs.
 */

class EndResponse extends Exception {
}
