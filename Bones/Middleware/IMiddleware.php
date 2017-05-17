<?php
/**
 * Middleware serves as a layer between routing
 * requests and displaying views.
 *
 * @author Mehmet Uyanik <mehmet.uyanik@live.com.au>
 */
namespace Bones\Middleware;

interface IMiddleware {
    public function handle();
}
