<?php
/**
 * This controller is for any generic view that does not belong to a group
 *
 * @author Mehmet Uyanik <mehmet.uyanik@live.com.au>
 */

namespace Bones\Controller;

class PageController extends Controller {
    public function home() {
        $this->title = "Welcome";
        $this->render("home");
    }
}
