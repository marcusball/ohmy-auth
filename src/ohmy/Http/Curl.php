<?php namespace ohmy\Http;

/*
 * Copyright (c) 2014, Yahoo! Inc. All rights reserved.
 * Copyrights licensed under the New BSD License.
 * See the accompanying LICENSE file for terms.
 */

use ohmy\Http\Curl\Response;

class Curl implements Rest {

    public function __construct() {}

    public function POST($url, Array $arguments, Array $headers) {

        $self = $this;
        return new Response(function($resolve, $reject) use($self, $url, $arguments, $headers) {

            # initialize curl
            $handle = curl_init();

            # set curl options
            curl_setopt_array($handle, array(
                CURLOPT_POST       => true,
                CURLOPT_VERBOSE    => false,
                CURLOPT_URL        => $url,
                CURLOPT_POSTFIELDS => http_build_query($arguments, '', '&'),
                CURLOPT_HTTPHEADER => $self->_headers($headers),
                CURLOPT_HEADER     => true,
                CURLOPT_RETURNTRANSFER => true
            ));

            # execute curl
            $raw = curl_exec($handle);

            # close curl handle
            curl_close($handle);

            # resolve
            $resolve($raw);
        });
    }

    public function GET($url, Array $arguments, Array $headers) {

        $self = $this;
        return new Response(function($resolve, $reject) use($self, $url, $arguments, $headers) {

            # initialize curl
            $handle = curl_init();

            # set curl options
            curl_setopt_array($handle, array(
                CURLOPT_VERBOSE    => false,
                CURLOPT_URL        => $url.'?'.http_build_query($arguments),
                CURLOPT_HTTPHEADER => $self->_headers($headers),
                CURLOPT_HEADER     => true,
                CURLOPT_RETURNTRANSFER => true
            ));

            # execute curl
            $raw = curl_exec($handle);

            # close curl handle
            curl_close($handle);

            # resolve
            $resolve($raw);
        });
    }

    private function _headers($headers) {
        $output = array();
        if (!$headers) return $output;
        foreach($headers as $key => $value) {
            array_push($output, "$key: $value");
        }
        return $output;
    }
}
