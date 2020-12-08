<?php

namespace ITSourceProviders\Institution;

class Subject {

    public static $pager;
    public $subject_id;
    public $name;
    public $institution_id;
    public $dateadded;
    public $dateupdated;

    public function __construct($subjectId) {
        $this->subject_id = $subjectId;
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
        $subject = new \ITSourceProviders\Institution\Subject($data['subject_id']);
        $subject->set(
                $data['name'],
                $data['institution_id'],
                date('Y-m-d H:i:s', strtotime($data['dateadded'])),
                date('Y-m-d H:i:s', strtotime($data['dateupdated']))
        );
        return $subject;
    }

    public function toJson() {
        return [
            'subject_id' => $this->subject_id,
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
        $subjects = [];
        for ($i = 0; $i < $len; $i++) {
            $subjects[] = \ITSourceProviders\Institution\Subject::fromJson($data[$i]);
        }
        return $subjects;
    }

    public static function next() {
        $data = self::$pager->next();
        $len = count($data);
        $subjects = [];
        for ($i = 0; $i < $len; $i++) {
            $subjects[] = \ITSourceProviders\Institution\Subject::fromJson($data[$i]);
        }
        return $subjects;
    }

    public static function previous() {
        $data = self::$pager->previous();
        $len = count($data);
        $subjects = [];
        for ($i = 0; $i < $len; $i++) {
            $subjects[] = \ITSourceProviders\Institution\Subject::fromJson($data[$i]);
        }
        return $subjects;
    }

    public static function at($index) {
        $data = self::$pager->at($index);
        $len = count($data);
        $subjects = [];
        for ($i = 0; $i < $len; $i++) {
            $subjects[] = \ITSourceProviders\Institution\Subject::fromJson($data[$i]);
        }
        return $subjects;
    }

    public static function size() {
        return self::$pager->getSize();
    }

    public static function pages() {
        return self::$pager->getPages();
    }

}

\ITSourceProviders\Institution\Subject::$pager = new \ITSourceProviders\Institution\Query\ResultSet('/subjects', '/subjects/count', [], 10);
