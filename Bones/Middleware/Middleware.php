<?php
/**
 * Middleware serves as a layer between routing
 * requests and displaying views.
 *
 * @author Mehmet Uyanik <mehmet.uyanik@live.com.au>
 */
namespace Bones\Middleware;

use Bones\Core\Log;
use Bones\Core\Redirect;
use Bones\Middleware\IMiddleware;

abstract class Middleware implements IMiddleware {
    use Redirect, Log;
}
