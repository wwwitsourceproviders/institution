<?php

namespace ITSourceProviders\Institution\Query;

class SResponse {

    //TODO
    public $id;
    public $messages = array();
    public $event = array();
    public $body = array();
    public $other = array();
    public $raw = array();

    public function __construct(
            $i,
            $m,
            $b
    ) {
        $this->id = $i;
        $this->messages = $m;
        $this->raw = $b;
        $len = count($this->raw);
        for ($k = 1; $k < $len; $k++) {
            $current = $this->raw[$k];
            if ($current['date'] != null || $current['code'] != null || $current['reference'] != null || $current['added'] != null || $current['updated'] != null) {
                $this->other = $current;
                $this->body[] = $current;
            } else if ($current['-event'] != null) {
                $this->event = $current;
                $this->body[] = $current;
            } else {
                $this->body[] = $current;
            }
        }
    }

}
