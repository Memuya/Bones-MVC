<?php
namespace Bones\Core;

class Request {
    public function input($name, $default = null) {
        return $_REQUEST[$name] ?: $default;
    }

    public function all() {
        return $_REQUEST;
    }
}
