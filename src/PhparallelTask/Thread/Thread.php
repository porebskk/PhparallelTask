<?php

namespace PhparallelTask\Thread;

use PhparallelTask\Promise;

class Thread
{
    /**
     * @param string $cmd
     *
     * @return Promise
     */
    public function run($cmd)
    {
        $filePathSuffix = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . uniqid('parallel.', true);
        $fileStart = $filePathSuffix . '.started';
        $fileError = $filePathSuffix . '.error';
        $fileSuccess = $filePathSuffix . '.success';
        
        $wrappedCmd = sprintf(PHP_BINARY . ' ' . __DIR__ . '/Util/tinyCmdWrapper.php ' . $filePathSuffix . ' ' . base64_encode($cmd));
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $finalCommand = "start /B $wrappedCmd";
            pclose(popen($finalCommand, "r"));
        } else {
            exec("$wrappedCmd 2> nul >nul");
        }
        
        return new Promise($fileSuccess, $fileError, $fileStart, $cmd);
    }
}