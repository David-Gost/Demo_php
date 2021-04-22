<?php

class AC_curl
{
    public function __construct($url = '')
    {
        if (strlen($url) > 0) {
            $this->ch = curl_init($url);
        } else {
            $this->ch = curl_init();
        }
    }

    public function get_curl_entity()
    {
        return $this->ch;
    }

    public function set_curl_entity($ch)
    {
        curl_close($this->ch);
        $this->ch = $ch;
    }

    public function curl_setopt_array($opt = [], $ch = null)
    {
        if (is_null($ch)) {
            $ch = $this->ch;
        }
        curl_setopt_array($ch, $opt);
        return $ch;
    }

    public function curl_setopt($key, $value, $ch = null)
    {
        $this->curl_setopt_array([constant($key) => $value], $ch);
    }

    public function curl_exec($ch = null, $RETURNTRANSFER = true)
    {
        if (is_null($ch)) {
            $ch = $this->ch;
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $RETURNTRANSFER);    //Don't Echo Out cURL is default opt.
        $result = curl_exec($ch);
        $err = curl_error($ch);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $result;
        }
    }

    public function curl_getinfo($ch = null)
    {
        if (is_null($ch)) {
            $ch = $this->ch;
        }
        return curl_getinfo($ch);
    }

    public function curl_errno($ch = null)
    {
        if (is_null($ch)) {
            $ch = $this->ch;
        }
        return curl_errno($ch);
    }

    public function curl_error($ch = null)
    {
        if (is_null($ch)) {
            $ch = $this->ch;
        }
        return curl_error($ch);
    }

    /**
     * Closes a cURL session and frees all resources. The cURL handle, ch, is also deleted.
     */
    public function __destruct()
    {
        curl_close($this->ch);
    }

    public function send_http_request($url, $method, $data = [], $header = [], $curl_setopt = [])
    {
        //不確定使用者在一開始呼叫時會不會指定url，既然這邊都會指定就把他關掉重新指定一次，避免有bug
        curl_close($this->ch);
        $this->ch = curl_init();

        $url_parts = parse_url($url);
        if (strtoupper($url_parts['scheme']) == 'HTTPS') {
            // $curl_setopt = $curl_setopt + [CURLOPT_SSL_VERIFYPEER => true];
        }
        if (strtoupper($method) == 'JSON') {
            $curl_setopt[CURLOPT_CUSTOMREQUEST] = 'POST';

            $data_string = (is_string($data) ? $data : json_encode($data, true));
            $curl_setopt[CURLOPT_POSTFIELDS] = $data_string;

            $no_CONTEN_TYPE = true;
            foreach ($header as $key => $value) {
                if (strtoupper($key) == 'CONTEN-TYPE') {
                    if (stripos($header[$key], 'application/json') === false) {
                        $header[$key] = 'application/json';
                    }
                    $no_CONTEN_TYPE = false;
                    break;
                }
            }
            if ($no_CONTEN_TYPE) {
                $header['Content-Type'] = 'application/json';
            }
        } elseif (strtoupper($method) == 'POST') {
            $curl_setopt[CURLOPT_POST] = true;
            $curl_setopt[CURLOPT_CUSTOMREQUEST] = 'POST';
            $curl_setopt[CURLOPT_POSTFIELDS] = http_build_query($data);
        } elseif (strtoupper($method) == 'GET') {
            $curl_setopt[CURLOPT_CUSTOMREQUEST] = 'GET';
            $query = [];
            if (array_key_exists('query', $url_parts)) {
                parse_str($url_parts['query'], $query);
            }
            $port = array_key_exists('port', $url_parts) ? $url_parts['port'] : '';
            $path = array_key_exists('path', $url_parts) ? $url_parts['path'] : '';
            if (!is_array($data)) {
                $data = (array) $data;
            }
            foreach ($data as $d_key => $d_value) {
                if (strlen($d_value) == 0) {
                    unset($data[$d_key]);
                }
            }
            $query = $data + $query;
            $url = $url_parts['scheme'] . '://' . $url_parts['host'] . ($port == '' ? '' : ':' . $port) . $path . (count($query) > 0 ? '?' . http_build_query($query) : '');
        }
        $header_array = [];
        foreach ($header as $key => $value) {
            $header_array[] = $key . ':' . $value;
        }

        $curl_setopt = $curl_setopt + [CURLOPT_URL => $url, CURLOPT_HTTPHEADER => $header_array];
        $this->curl_setopt_array($curl_setopt);
        return $this->curl_exec();
    }
}
