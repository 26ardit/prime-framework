<?php namespace core;

class View
{
    public function __construct($path)
    {
        $this->path = $path;
    }

    public function parse($view, $variables)
    {
        return preg_replace_callback(
            '/(\\{)(\\{)((?:[a-zA-Z]*))(\\})(\\})/',
            function ($match) use ($variables) {
                return $variables[$match[3]];
            },
            $view
        );
    }

    public function display($name, $data = [])
    {
        $file_path = $this->path . $name.'.php';

        if (file_exists($file_path)) {
            //echo $this->parse(file_get_contents($file_path), $data);
        
            extract($data);

            return require_once($file_path);
        }
        
        throw new \Exception("View does not exist: " . $file_path);
    }
}
