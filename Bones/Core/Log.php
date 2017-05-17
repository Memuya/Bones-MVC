<?php
/**
 * Log files to the log directory. New files are created daily
 *
 * @author Mehmet Uyanik <mehmet.uyanik@live.com.au>
 */
namespace Bones\Core;

trait Log {
    /**
     * Write to a log file
     *
     * @param string $message
     * @param $string $to
     */
    public function log($message, $to = './logs/bones.log') {
        if(!file_exists($to)) {
            touch($to);
        }

        $contents  = "[".date('Y-m-d H:i:s')."] ";
        $contents .= $message."\n";

        file_put_contents($to, $contents, FILE_APPEND);
    }

    /**
     * Add a message to todays log
     * Useful to log important information
     *
     * @param string $message
     */
    public function dailyLog($message) {
        $file = "./logs/".date("Y-m-d").".txt";
        $timestamp = '['.date("H:i:s Y-m-d").'] ';
        $message = $timestamp.$message.PHP_EOL;
        file_put_contents($file, $message, FILE_APPEND);
    }
}
