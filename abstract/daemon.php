<?php

abstract class daemon
{

    abstract protected function getPidFileName();

    abstract protected function execute();

    public function stopDaemon()
    {
        $pid_file = '/tmp/' . $this->getPidFileName() . '.pid';
        if(is_file($pid_file))
        {
            echo 'pid file found' . PHP_EOL;

            $pid = file_get_contents($pid_file);
            system('kill -9 ' . $pid);
            sleep(2);
            echo $this->getStatus();

        }
    }

    public function startDaemon()
    {
        fclose(STDIN);
        fclose(STDOUT);
        fclose(STDERR);

        $STDIN = fopen('/dev/null', 'r');
        $STDOUT = fopen(setup::$LOG_DIR . 'application.log', 'ab');
        $STDERR = fopen(setup::$LOG_DIR . 'daemon.log', 'ab');

        if($this->isDaemonActive())
        {
            echo 'Daemon ' . get_class($this) . ' already active' . PHP_EOL;

        }

        $pid = pcntl_fork();
        if($pid == -1)
        {
            die("could not fork");
        }
        if($pid)
        {
            // это родительский процесс

            echo date('r') . ' Daemon -=start=-' . PHP_EOL;

        }
        else
        {
            $this->setDaemonActive();

            echo date('r') . ' new service: ' . get_class($this) . ' -=start=-' . PHP_EOL;
            $this->execute();
        }
    }

    function isDaemonActive()
    {
        $pid_file = '/tmp/' . $this->getPidFileName() . '.pid';
        if(is_file($pid_file))
        {
            $pid = file_get_contents($pid_file);
            //проверяем на наличие процесса
            if(posix_kill($pid, 0))
            {
                //демон уже запущен
                return TRUE;
            }
            else
            {
                //pid-файл есть, но процесса нет
                if(!unlink($pid_file))
                {
                    //не могу уничтожить pid-файл. ошибка
                    exit(-1);
                }
            }
        }

        return FALSE;
    }

    public function getStatus()
    {

        if($this->isDaemonActive())
        {
            return date('r') . ' service: ' . get_class($this) . ' -=start=-' . PHP_EOL;

        }
        else
        {
            return date('r') . ' service: ' . get_class($this) . ' -=stop=-' . PHP_EOL;

        }
    }

    private function setDaemonActive()
    {
        $pid_file = '/tmp/' . $this->getPidFileName() . '.pid';
        file_put_contents($pid_file, getmypid());
    }


}
