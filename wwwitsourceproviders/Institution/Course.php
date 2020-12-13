<?php

namespace ITSourceProviders\Institution;

class Course {

    public static $pager;
    public $course_id;
    public $institutionclass;
    public $subject;
    public $dateadded;
    public $dateupdated;

    public function __construct($course_id) {
        $this->course_id = $course_id;
    }

    public function set(
            $institutionclass,
            $subject,
            $dateadded,
            $dateupdated
    ) {
        $this->institutionclass = $institutionclass;
        $this->subject = $subject;
        $this->dateupdated = $dateupdated;
        $this->dateadded = $dateadded;
        return $this;
    }

    public static function fromJson(
            $data
    ) {
        $institutionclasses = \ITSourceProviders\Institution\Query\ResultSet::parse([array_key_exists('classes', $data) ? $data['classes'] : []]);
        $subjects = \ITSourceProviders\Institution\Query\ResultSet::parse([array_key_exists('subjects', $data) ? $data['subjects'] : []]);
        $course = new \ITSourceProviders\Institution\Course($data['course_id']);
        $course->set(
                count($institutionclasses) > 0 ? \ITSourceProviders\Institution\InstitutionClass::fromJson($institutionclasses[0]) : null,
                count($subjects) > 0 ? \ITSourceProviders\Institution\Subject::fromJson($subjects[0]) : null,
                date('Y-m-d H:i:s', strtotime($data['dateadded'])),
                date('Y-m-d H:i:s', strtotime($data['dateupdated']))
        );
        return $course;
    }

    public function toJson() {
        return [
            'course_id' => $this->course_id,
            'subject' => empty($this->subject) ? null : $this->subject->toJson(),
            'institutionclass' => empty($this->institutionclass) ? null : $this->institutionclass->toJson(),
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

    public function google_id() {
        $response = \ITSourceProviders\Institution\Config\Setting::get('/course/'.$this->course_id.'/google_id', $parameters);
        $data = \ITSourceProviders\Institution\Query\ResultSet::parse($response->body);
	return $data[0]['google_id'];
    }

    public static function get() {
        $data = self::$pager->set();
        $len = count($data);
        $courses = [];
        for ($i = 0; $i < $len; $i++) {
            $courses[] = \ITSourceProviders\Institution\Course::fromJson($data[$i]);
        }
        return $courses;
    }

    public static function next() {
        $data = self::$pager->next();
        $len = count($data);
        $courses = [];
        for ($i = 0; $i < $len; $i++) {
            $courses[] = \ITSourceProviders\Institution\Course::fromJson($data[$i]);
        }
        return $courses;
    }

    public static function previous() {
        $data = self::$pager->previous();
        $len = count($data);
        $courses = [];
        for ($i = 0; $i < $len; $i++) {
            $courses[] = \ITSourceProviders\Institution\Course::fromJson($data[$i]);
        }
        return $courses;
    }

    public static function at($index) {
        $data = self::$pager->at($index);
        $len = count($data);
        $courses = [];
        for ($i = 0; $i < $len; $i++) {
            $courses[] = \ITSourceProviders\Institution\Course::fromJson($data[$i]);
        }
        return $courses;
    }

    public static function size() {
        return self::$pager->getSize();
    }

    public static function pages() {
        return self::$pager->getPages();
    }

}

\ITSourceProviders\Institution\Course::$pager = new \ITSourceProviders\Institution\Query\ResultSet('/courses', '/courses/count', [], 10);
