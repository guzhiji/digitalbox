<?php

/* ------------------------------------------------------------------
 * This program is a prototype version in the InterBox project,
 *  using the MIT License.
 * http://code.google.com/p/interbox/
 * ------------------------------------------------------------------
 */

/**
 * a simple stopwatch for timing
 * 
 * @author Gu Zhiji <gu_zhiji@163.com>
 * @copyright 2010-2012 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.util
 */
class Stopwatch {

    private $starttime;

    function __construct() {
        $this->starttime = self::GetMicro();
    }

    private static function GetMicro() {
        $mtime = explode(" ", microtime());
        return $mtime[1] + $mtime[0];
    }

    public function elapsedMillis() {
        $now = self::GetMicro();
        return ($now - $this->starttime) * 1000;
    }

}
