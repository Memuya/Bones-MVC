<?php
namespace Bones\Exception;

class MiddlewareNotFoundException extends \Exception {
    public function __construct($message) {
        parent::__construct($message);
    }
}
