<?php

class cli
{
    protected $_argv = array();

    function __construct($arguments)
    {
        $this->_argv = $arguments;
        $this->run();
    }

    private function run()
    {
        if(empty($this->_argv[1]))
        {

            echo PHP_EOL . 'Доступные команды' . PHP_EOL . PHP_EOL;

            $this->_scanCommands(setup::$CMD_DIR);

        }
        else
        {
            $class_name = 'Command_' . $this->_argv[1];

            echo PHP_EOL . 'Создание команды ' . $class_name . PHP_EOL . PHP_EOL;

            $class = new $class_name;
            if($class instanceof Command)
            {


                $class->execute();

                echo PHP_EOL . 'Завершение команды' . PHP_EOL . PHP_EOL;
                die();
            }
            else
            {
                throw new Exception('Неправильно созданна команда!' . PHP_EOL);
            }
        }
    }

    private function _scanCommands($dir)
    {
        foreach(scandir($dir) as $value)
        {
            if($value == ".." | $value == "." | $value == ".DS_Store")
            {
                //
            }
            else
            {

                $path = realpath($dir . '/' . $value);
                if(is_dir($path))
                {
                    $this->_scanCommands($path);
                }
                elseif(is_file($path))
                {
                    $cmd = substr($path, strlen(setup::$CMD_DIR), -4);
                    $cmd = str_replace('/', '_', $cmd);
                    echo "\t-- " . $cmd . PHP_EOL;
                }

            }
        }
    }

}
