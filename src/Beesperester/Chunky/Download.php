<?php

namespace Beesperester\Chunky;

class Download
{
    protected $url, $chunksize;

    public function __construct($url = '', $chunksize = 0) {
        $this->url = $url;
        $this->chunksize = $chunksize;
    }

    public function download($target = '') {
        $chunksize = $this->chunksize;
        $parts = parse_url($this->url);
        $i_handle = fsockopen($parts['host'], 80, $errstr, $errcode, 5);
        $o_handle = fopen($target, 'wb');

        if (!empty($parts['query'])) {
            $parts['path'] .= '?' . $parts['query'];
        }

        // Send the request to the server for the file
        $request = "GET {$parts['path']} HTTP/1.1\r\n";
        $request .= "Host: {$parts['host']}\r\n";
        $request .= "User-Agent: Mozilla/5.0\r\n";
        $request .= "Keep-Alive: 115\r\n";
        $request .= "Connection: keep-alive\r\n\r\n";
        fwrite($i_handle, $request);

        // Now read the headers from the remote server. We'll need
        // to get the content length.
        $headers = array();
        while(!feof($i_handle)) {
            $line = fgets($i_handle);
            if ($line == "\r\n") break;
            $headers[] = $line;
        }

        // Look for the Content-Length header, and get the size
        // of the remote file.
        $length = 0;
        foreach($headers as $header) {
            if (stripos($header, 'Content-Length:') === 0) {
                $length = (int)str_replace('Content-Length: ', '', $header);
                break;
            }
        }

        /**
        * Start reading in the remote file, and writing it to the
        * local file one chunk at a time.
        */
        $cnt = 0;
        while(!feof($i_handle)) {
            $buf = '';
            $buf = fread($i_handle, $chunksize);
            $bytes = fwrite($o_handle, $buf);
            if ($bytes == false) {
                return false;
            }
            $cnt += $bytes;

            /**
            * We're done reading when we've reached the conent length
            */
            if ($cnt >= $length) break;
        }

        fclose($i_handle);
        fclose($o_handle);

        return $this;
    }
}
