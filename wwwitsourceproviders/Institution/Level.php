<?php

namespace ITSourceProviders\Institution;

class Level {

    public static $pager;
    public $level_id;
    public $name;
    public $institution_id;
    public $dateadded;
    public $dateupdated;

    public function __construct($levelId) {
        $this->level_id = $levelId;
    }

    public function set(
            $name,
            $institution_id,
            $dateadded,
            $dateupdated
    ) {
        $this->name = $name;
        $this->institution_id = $institution_id;
        $this->dateupdated = $dateupdated;
        $this->dateadded = $dateadded;
        return $this;
    }

    public static function fromJson(
            $data
    ) {
        $level = new \ITSourceProviders\Institution\Level($data['level_id']);
        $level->set(
                $data['name'],
                $data['institution_id'],
                date('Y-m-d H:i:s', strtotime($data['dateadded'])),
                date('Y-m-d H:i:s', strtotime($data['dateupdated']))
        );
        return $level;
    }

    public function toJson() {
        return [
            'level_id' => $this->level_id,
            'name' => $this->name,
            'institution_id' => $this->institution_id,
            'dateadded' => $this->dateadded,
            'dateupdated' => $this->dateupdated
        ];
    }

    public static function setLimit($limit) {
        self::$pager->setLimit($limit);
    }

    public static function setParameters($parameters) {
        self::$pager->setParameters($parameters);
    }

    public static function get() {
        $data = self::$pager->set();
        $len = count($data);
        $levels = [];
        for ($i = 0; $i < $len; $i++) {
            $levels[] = \ITSourceProviders\Institution\Level::fromJson($data[$i]);
        }
        return $levels;
    }

    public static function next() {
        $data = self::$pager->next();
        $len = count($data);
        $levels = [];
        for ($i = 0; $i < $len; $i++) {
            $levels[] = \ITSourceProviders\Institution\Level::fromJson($data[$i]);
        }
        return $levels;
    }

    public static function previous() {
        $data = self::$pager->previous();
        $len = count($data);
        $levels = [];
        for ($i = 0; $i < $len; $i++) {
            $levels[] = \ITSourceProviders\Institution\Level::fromJson($data[$i]);
        }
        return $levels;
    }

    public static function at($index) {
        $data = self::$pager->at($index);
        $len = count($data);
        $levels = [];
        for ($i = 0; $i < $len; $i++) {
            $levels[] = \ITSourceProviders\Institution\Level::fromJson($data[$i]);
        }
        return $levels;
    }

    public static function size() {
        return self::$pager->getSize();
    }

    public static function pages() {
        return self::$pager->getPages();
    }

}

\ITSourceProviders\Institution\Level::$pager = new \ITSourceProviders\Institution\Query\ResultSet('/levels', '/levels/count', [], 10);
