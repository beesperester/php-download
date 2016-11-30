<?php

namespace Beesperester\Chunky\Facades;

use Beesperester\Chunky\Download;

class Chunky
{
    public static function download($url = '', $target = '') {
        $chunksize = 10 * (1024 * 1024); // 10Mb

        $download = new Download($url, $chunksize);

        return $download->download($target);
    }
}
