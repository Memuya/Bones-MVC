<?php
/**
 * Validates input, mostlu used with form data.
 *
 * @author Mehmet Uyanik <mehmet.uyanik@live.com.au>
 */

namespace Bones\Core;

class Validator {
    private $messages = [];
    private $data;

    /**
     * Starts validation
     *
     * @param array $data       ['name' => 'Bob', 'email' => 'bob@email.com']
     * @param array $rules      ['name' => 'required', 'email' => 'required|email']
     */
    public function __construct(array $data, array $rules) {
        $this->data = $data;

        foreach($rules as $field => $rule) {
            // Multiple rules supplied
            if(strpos($rule, '|') !== false) {
                $_rules = explode('|', $rule);
                foreach($_rules as $r) {
                    $this->rules($r, $field);
                }

            // One rule supplied
            } else {
                $this->rules($rule, $field);
            }
        }
    }

    /**
     * List of rules
     *
     * @param string $rule
     * @param string $field
     */
    private function rules($rule, $field) {
        switch($rule) {
            case 'required':
                $this->required($field);
                break;
            case 'string':
                $this->isString($field);
                break;
            case 'integer':
                $this->isInteger($field);
                break;
            case 'email':
                $this->isEmail($field);
                break;
            case 'date':
                $this->isDate($field);
                break;
            case (strpos($rule, ':') !== false):
                $_rule = explode(':', $rule);
                $rule = $_rule[0];
                $value = $_rule[1];
                $this->complexRules($field, $rule, $value);
                break;
        }
    }

    /**
     * List of rules that require more information
     * to be processed. Such as database lookups.
     *
     * @param string $field
     * @param string $rule
     * @param string $value
     */
    private function complexRules($field, $rule, $value) {
        switch($rule) {
            case 'exists':
                $this->exists($field, $value);
                break;
            case 'min':
                $this->minCheck($field, $value);
                break;
            case 'max':
                $this->maxCheck($field, $value);
                break;
            case 'unique':
                $this->unique($field, $value);
                break;
        }
    }

    /**
     * Checks if a field is empty
     *
     * @param string $field
     */
    private function required($field) {
        if(empty($this->data[$field])) {
            $this->messages[$field][] = 'The '.$this->clean($field).' field is required';
        }
    }

    /**
     * Checks if a field is a string
     *
     * @param string $field
     */
    private function isString($field) {
        if(!is_string($this->data[$field])) {
            $this->messages[$field][] = 'The '.$this->clean($field).' field must be a string';
        }
    }

    /**
     * Checks if a field is an integer
     *
     * @param string $field
     */
    private function isInteger($field) {
        if(!is_integer($this->data[$field])) {
            $this->messages[$field][] = 'The '.$this->clean($field).' field must be an integer';
        }
    }

    /**
     * Checks if a field is a valid email address
     *
     * @param string $field
     */
    private function isEmail($field) {
        if(!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->messages[$field][] = 'The '.$this->clean($field).' field is not a valid email address';
        }
    }

    /**
     * Checks if an ID exists in a table
     *
     * Use as exists:table
     *
     * @param string $value
     * @param string $field
     */
    private function exists($field, $value) {
        $_model = '\\Bones\\Model\\'.ucfirst($value);
        $model = new $_model;
        $get = $model->get($this->data[$field]);

        if(!$get->count) {
            $this->messages[$field][] = 'The '.$this->clean($field).' field does not exists';
        }
    }

    /**
     * Check if a field is unique in a table
     *
     * Use as unique:table,column
     *
     * @param string $field
     * @param string $value
     */
    private function unique($field, $value) {
        if(strpos($value, ',') !== false) {
            $_value = explode(',', $value);
            $table = $_value[0];
            $column = $_value[1];

            $q = DB::$db->prepare("SELECT id FROM {$table} WHERE {$column} = :value");
            $q->execute([':value' => $this->data[$field]]);
            $c = $q->rowCount();

            if($c > 0) {
                $this->messages[$field][] = 'That '.$this->clean($field).' is already in use';
            }
        }
    }

    /**
     * Checks if a field meets a minumum length requirement
     *
     * Use as min:n
     *
     * @param string $field
     * @param string $value
     */
    private function minCheck($field, $value) {
        if(strlen($this->data[$field]) < $value) {
            $this->messages[$field][] = 'The '.$this->clean($field).' field must contain at least '.$value.' character(s)';
        }
    }

    /**
     * Checks if a field is over a maximum length
     *
     * Use as max:n
     *
     * @param string $field
     * @param string $value
     */
    private function maxCheck($field, $value) {
        if(strlen($this->data[$field]) > $value) {
            $this->messages[$field][] = 'The '.$this->clean($field).' field must be '.$value.' character(s) or less';
        }
    }

    /**
     * Check if a field is a date
     *
     * @param string $field
     */
    private function isDate($field) {
        $date = $this->data[$field];

        if(\DateTime::createFromFormat('Y-m-d', $date) === false) {
            $this->messages[$field][] = 'The '.$this->clean($field).' field must be a date';
        }
    }

    /**
     * Checks whether validation has passed
     */
    public function passed() {
        return empty($this->messages);
    }

    /**
     * Checks whether validation has failed
     */
    public function failed() {
        return !$this->passed();
    }

    /**
     * Returns the first message for each field
     */
    public function messages() {
        $messages = [];
        if(!empty($this->messages)) {
            foreach($this->messages as $field => $value) {
                $messages[] = $this->messages[$field][0];
            }
        }

        return $messages;
    }

    /**
     * Returns the full associative array of messages
     */
    public function allMessages() {
        return $this->messages;
    }

    /**
     * Remove characters for friendly display
     *
     * @param string $field
     */
    private function clean($field) {
        return str_replace(['_', '-'], '', $field);
    }
}
