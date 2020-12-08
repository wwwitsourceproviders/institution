<?php

namespace ITSourceProviders\Institution\Query;

class ResultSet {

    private $isSet;
    private $index;
    private $limit;
    private $size;
    private $skip;
    private $datalink;
    private $countlink;
    private $parameters;

    public function __construct($dl, $cl, $params, $limit) {
        $this->datalink = $dl;
        $this->countlink = $cl;
        $this->index = -1;
        $this->limit = $limit;
        $this->size = 0;
        $this->skip = 0;
        $this->isSet = false;
        $this->parameters = array();
        if ($params != null) {
            foreach ($params as $key => $value) {
                $this->parameters[$key] = $value;
            }
        }
    }

    public function setParameters($params) {
        foreach ($params as $key => $value) {
            $this->parameters[$key] = $value;
        }
    }

    public function clear() {
        $this->index = 0;
        $this->size = 0;
        $this->skip = 0;
        $this->isSet = false;
    }

    public static function parse(
            $data
    ) {
        if (count($data) == 0) {
            return [];
        }
        $first = $data[0];
        $keys = array_keys($first);
        if (count($keys) == 0) {
            return [];
        }
        $data_len = count($data[0][$keys[0]]);
        $key_len = count($keys);
        $data_set = [];
        $added = 0;
        for ($i = 0; $i < $data_len; $i++) {
            $newData = array();
            for ($j = 0; $j < $key_len; $j++) {
                $newData[$keys[$j]] = $data[0][$keys[$j]][$i];
                $added++;
            }
            if ($added > 0) {
                $data_set[] = $newData;
            }
        }
        return $data_set;
    }

    public function set() {
        $this->clear();
        $parameters = array();
        foreach ($this->parameters as $key => $value) {
            $parameters[$key] = $value;
        }
        $parameters['skip'] = $this->skip;
        $parameters['limit'] = $this->limit;
        $response = \ITSourceProviders\Institution\Config\Setting::get($this->datalink, $parameters);
        $this->index = 0;
        $data_set = \ITSourceProviders\Institution\Query\ResultSet::parse($response->body);
        $this->isSet = true;
        if (count($response->body) > 1) {
            $this->size = array_key_exists('count', $response->body[1]) ? $response->body[1]['count'] : 0;
        }
        return $data_set;
    }

    public function reset() {
        $this->clear();
        return $this->set();
    }

    private function fetch($skip, $step) {
        $parameters = array();
        foreach ($this->parameters as $key => $value) {
            $parameters[$key] = $value;
        }
        $parameters['skip'] = $skip;
        $parameters['limit'] = $this->limit;
        $response = \ITSourceProviders\Institution\Config\Setting::get($this->datalink, $parameters);
        $data_set = \ITSourceProviders\Institution\Query\ResultSet::parse($response->body);
        $this->isSet = true;
        if (count($response->body) > 1) {
            $this->size = array_key_exists('count', $response->body[1]) ? $response->body[1]['count'] : 0;
        }
        $this->index = $step;
        if ($this->index * $this->limit > $this->size) {
            $this->index -= 1;
        }
        return $data_set;
    }

    public function getSize() {
        if (empty($this->size)) {
            $parameters = array();
            foreach ($this->parameters as $key => $value) {
                $parameters[$key] = $value;
            }
            $response = \ITSourceProviders\Institution\Config\Setting::get($this->countlink, $parameters);
            $this->size = intval($response->body[0]['count']);
        }
        return $this->size;
    }

    public function next() {
        $this->skip = ($this->index + 1) * $this->limit;
        return $this->fetch($this->skip, $this->index + 1);
    }

    public function previous() {
        $this->skip = ($this->index - 1) * $this->limit;
        if ($this->skip < 0) {
            $this->skip = 0;
            return $this->fetch($this->skip, 0);
        }
        return $this->fetch($this->skip, $this->index - 1);
    }

    public function at($index) {
        $steps = $index;
        $this->skip = $index * $this->limit;
        $this->index = $index;
        //update index if data fetched is not empty
        return $this->fetch($this->skip, $steps);
    }

    public function getIndex() {
        return $this->index;
    }

    public function getPages() {
        $over = $this->size % $this->limit;
        $steps = ($this->size - $over) / $this->limit;
        if ($over > 0) {
            $steps += 1;
        }
        return $steps;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function getSkip() {
        return $this->skip;
    }

    public function setLimit($limit) {
        $this->limit = $limit;
    }

    public function setSkip($skip) {
        $this->skip = $skip;
    }

}
