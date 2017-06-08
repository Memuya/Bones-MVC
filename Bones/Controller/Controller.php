<?php
/**
 * Main controller class
 *
 * @author Mehmet Uyanik <mehmet.uyanik@live.com.au>
 */

namespace Bones\Controller;

use Bones\Core\Log;
use Bones\Core\Session;
use Bones\Core\Redirect;

class Controller {
    use Redirect, Log;

    protected $user;
    protected $title;
    private $scripts;

    /**
     * Not used right now
     *
     * @param string $task
     */
    public function __construct() {

    }

    /**
     * Render a view
     *
     * @param string $view
     * @param array $data
     * @param string $template
     */
    public function render($view, array $data = null, $template = "layout") {
        // Set title for layout
        $title = $this->title;
        $appName = config('app')['app_name'];

        // Pass the controller
        $controller = $this;

        // Takes the data a associates it to a variable
        if (!empty($data) && is_array($data)) {
            foreach ($data as $key => $value) {
                ${$key} = $value;
            }
        }

        // Get the contents of the view
        include "Bones/Views/" . $view . ".php";
        $content = ob_get_clean();

        // Display the layout with the view data inside
        require_once "Bones/Views/template/{$template}.php";

        // Destroy any flash messages after sending them through
        Session::destroy(['flash', 'with']);

        ob_end_flush();
    }

    /**
     * Adds another view inside a view. Data can be passed to these views
     * in the same way that the render method does
     *
     * @param string $path
     * @param array $data
     */
    public function addView($path, array $data = []) {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                ${$key} = $value;
            }
        }

        include "Bones/Views/$path.php";
    }

    /**
     * Add scripts to the page
     *
     * @param array $scripts
     */
    public function addScripts(array $scripts) {
        if(!empty($scripts) && is_array($scripts)) {
            foreach($scripts as $script) {
                $this->scripts .= '<script src="'.$script.'"></script>'.PHP_EOL;
            }
        }
    }

    /**
     * Loads the scripts added in addScripts() into the main template
     *
     * @return string
     */
    public function renderScripts() {
        return $this->scripts;
    }

    /**
     * Used when permission is denied to a user level
     */
    public function permissionDenied() {
        $this->title = "Permission Denied";
        $this->render("errors/permission.denied");
        exit;
    }

    /**
     * Displays the 404 view
     */
    public function get404() {
        $this->title = "404 Error | Page not found";
        $this->render("errors/404");
        exit;
    }

    /**
     * Used when something could not be found
     */
    public function getResourceNotFound() {
        $this->title = "Resource Not Found";
        $this->render("errors/resource.not.found");
        exit;
    }
}
