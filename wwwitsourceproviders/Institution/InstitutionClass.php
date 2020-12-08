<?php

namespace ITSourceProviders\Institution;

class InstitutionClass {

    public static $pager;
    public $class_id;
    public $name;
    public $level;
    public $institution_id;
    public $dateadded;
    public $dateupdated;

    public function __construct($classId) {
        $this->class_id = $classId;
    }

    public function set(
            $name,
            $size,
            $students,
            $level,
            $dateadded,
            $dateupdated
    ) {
        $this->name = $name;
        $this->size = $size;
        $this->students = $students;
        $this->level = $level;
        $this->dateupdated = $dateupdated;
        $this->dateadded = $dateadded;
        return $this;
    }

    public static function fromJson(
            $data
    ) {
        $levels = \ITSourceProviders\Institution\Query\ResultSet::parse([array_key_exists('levels', $data) ? $data['levels'] : []]);
        $class = new \ITSourceProviders\Institution\InstitutionClass($data['class_id']);
        $class->set(
                $data['name'],
                intval($data['size']),
                intval($data['students']),
                count($levels) > 0 ? \ITSourceProviders\Institution\Level::fromJson($levels[0]) : null,
                date('Y-m-d H:i:s', strtotime($data['dateadded'])),
                date('Y-m-d H:i:s', strtotime($data['dateupdated']))
        );
        return $class;
    }

    public function toJson() {
        return [
            'class_id' => $this->class_id,
            'name' => $this->name,
            'size' => $this->size,
            'students' => $this->students,
            'level' => empty($this->level) ? null : $this->level->toJson(),
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
        $classes = [];
        for ($i = 0; $i < $len; $i++) {
            $classes[] = \ITSourceProviders\Institution\InstitutionClass::fromJson($data[$i]);
        }
        return $classes;
    }

    public static function next() {
        $data = self::$pager->next();
        $len = count($data);
        $classes = [];
        for ($i = 0; $i < $len; $i++) {
            $classes[] = \ITSourceProviders\Institution\InstitutionClass::fromJson($data[$i]);
        }
        return $classes;
    }

    public static function previous() {
        $data = self::$pager->previous();
        $len = count($data);
        $classes = [];
        for ($i = 0; $i < $len; $i++) {
            $classes[] = \ITSourceProviders\Institution\InstitutionClass::fromJson($data[$i]);
        }
        return $classes;
    }

    public static function at($index) {
        $data = self::$pager->at($index);
        $len = count($data);
        $classes = [];
        for ($i = 0; $i < $len; $i++) {
            $classes[] = \ITSourceProviders\Institution\InstitutionClass::fromJson($data[$i]);
        }
        return $classes;
    }

    public static function size() {
        return self::$pager->getSize();
    }

    public static function pages() {
        return self::$pager->getPages();
    }

}

\ITSourceProviders\Institution\InstitutionClass::$pager = new \ITSourceProviders\Institution\Query\ResultSet('/classes', '/classes/count', [], 10);
