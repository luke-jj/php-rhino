<?php

/*
 * php-rhino micro-framework
 * Copyright (c) 2019 Luca J
 * Licensed under the MIT license.
 */

/**
 * This exception is thrown to signal the end of the http response.
 * The http response output stream must be closed if this exception occurs.
 */

class EndResponse extends Exception {
}
