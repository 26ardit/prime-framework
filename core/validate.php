<?php namespace core;

class Validate
{
    private $_errors = [];
    private $_db = null;

    public function __construct()
    {
        $this->_db = DB::run();
    }

    public function check(array $items, $source)
    {
        foreach ($items as $iteam => $rules) {

            foreach ($rules as $rule => $rule_value) {

                if (method_exists($this, $rule)) {
                    $this->$rule($source, $iteam, $rule_value);
                }
            }
        }

        return $this;
    }

    private function required($source, $iteam, $rule_value)
    {
        $check = (empty($source[$iteam]) && $rule_value);

        $iteam = ucfirst($iteam);

        $this->addError("{$iteam} is required.", $check);
    }

    private function min($source, $iteam, $rule_value)
    {
        $iteam_value = trim($source[$iteam]);
        $check = (strlen($iteam_value) < $rule_value);

        $iteam = ucfirst($iteam);

        $this->addError("{$iteam} must be at least {$rule_value} characters.", $check);
    }

    private function max($source, $iteam, $rule_value)
    {
        $iteam_value = trim($source[$iteam]);
        $check = (strlen($iteam_value) > $rule_value);
        
        $iteam = ucfirst($iteam);

        $this->addError("{$iteam} must be at most {$rule_value} characters.", $check);
    }

    private function matches($source, $iteam, $rule_value)
    {
        $check = ($source[$iteam] != $source[$rule_value]);

        $iteam = ucfirst($rule_value);

        $this->addError("{$iteam}s must match.", $check);
    }

    private function unique($source, $iteam, $rule_value)
    {
        $check = $this->_db->get($rule_value, [$iteam, '=', $source[$iteam]]);

        $this->addError("This {$iteam} already exists.", $check->count());
    }

    public function addError($error, $check = true)
    {
        if ($check) {
            $this->_errors[] = $error;
        }
    }

    public function errors()
    {
        return $this->_errors;
    }

    public function passed()
    {
        return (empty($this->_errors));
    }
}
