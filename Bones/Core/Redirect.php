<?php
/**
 * Used to redirect users to a given route.
 *
 * @author Mehmet Uyanik <mehmet.uyanik@live.com.au>
 */
namespace Bones\Core;

trait Redirect {
    private $route;

    /**
    * Setup the redirect paramters
    *
    * @param string $to
    * @param boolean $go    If you want to just use $this->redirect(),
    *                       set this parameter to 'true`
    * @return Redirect
    */
    public function redirect($route, $go = false) {
        $this->route = $route;

        // Force redirect straight away
        if($go) $this->go();

        return $this;
    }

    /**
    * Pass data along with the redirect. This data can
    * be retrieved using the old() function within a view
    *
    * E.G. old('first_name')
    *
    * @param array $parameters
    * @return Redirect
    */
    public function with(array $parameters = []) {
        if(!empty($parameters) && is_array($parameters)) {
            foreach($parameters as $param => $value) {
                $_SESSION['with'][$param] = $value;
            }
        }

        return $this;
    }

    /**
    * Process the redirect
    */
    public function go() {
        header('Location: '.url($this->route));
        exit;
    }
}
