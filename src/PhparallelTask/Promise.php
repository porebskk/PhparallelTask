<?php

namespace PhparallelTask;

class Promise
{
    private $fileSuccess;
    private $fileError;
    private $fileStart;
    private $cmd;
    
    /**
     * Promise constructor.
     *
     * @param $fileSuccess
     * @param $fileError
     * @param $fileStart
     * @param $cmd
     */
    public function __construct($fileSuccess,
                                $fileError,
                                $fileStart,
                                $cmd)
    {
        $this->fileSuccess = $fileSuccess;
        $this->fileError = $fileError;
        $this->fileStart = $fileStart;
        $this->cmd = $cmd;
    }
    
    function __destruct()
    {
        @unlink($this->fileStart);
        @unlink($this->fileError);
        @unlink($this->fileSuccess);
    }
    
    public function isStarted()
    {
        return file_exists($this->fileStart);
    }
    
    public function isRun()
    {
        return file_exists($this->fileSuccess) || file_exists($this->fileError);
    }
    
    public function waitForStart($timeoutSeconds = 30)
    {
        while (
            $timeoutSeconds >= 0
            &&
            (
                file_exists($this->fileStart) === false
                &&
                file_exists($this->fileError) === false
                &&
                file_exists($this->fileSuccess) === false
            )
        ) {
            $timeoutSeconds -= 0.01;
            usleep(10000);
        }
    }
    
    public function waitForFinish()
    {
        while ($this->isRun() === false) {
            usleep(10000);
        }
    }
    
    /**
     * @codeCoverageIgnore
     * @return mixed
     */
    public function getCmd()
    {
        return $this->cmd;
    }
}
