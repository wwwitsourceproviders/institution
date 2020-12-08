<?php

namespace ITSourceProviders\Institution;

class User {

    public static $pager;
    public $institutionclass;
    public $level;
    public $institution_id;
    public $courses = [];
    public $course_id = [];
    public $user_id;
    public $firstname;
    public $middlename;
    public $lastname;
    public $dob;
    public $address;
    public $email;
    public $house;
    public $idnumber;
    public $guardian;
    public $phone;
    public $gender;
    public $shift;
    public $path;
    public $hc;
    public $doa;
    public $cas;
    public $ern;
    public $srn;
    public $description;
    public $dateadded;
    public $dateupdated;
    public $roleId;
    public $roleName;
    public $banned;

    public function __construct($user_id) {
        $this->user_id = $user_id;
    }

    public function set(
            $firstname,
            $middlename,
            $lastname,
            $dob,
            $address,
            $email,
            $house,
            $idnumber,
            $guardian,
            $phone,
            $gender,
            $shift,
            $path,
            $roleId,
            $roleName,
            $banned,
            $dateadded,
            $dateupdated,
            $level,
            $classinstitution,
            $institution_id
    ) {
        $this->firstname = $firstname;
        $this->middlename = $middlename;
        $this->lastname = $lastname;
        $this->dob = $dob;
        $this->address = $address;
        $this->email = $email;
        $this->house = $house;
        $this->idnumber = $idnumber;
        $this->guardian = $guardian;
        $this->phone = $phone;
        $this->gender = $gender;
        $this->shift = $shift;
        $this->path = $path;
        $this->roleId = $roleId;
        $this->roleName = $roleName;
        $this->banned = $banned;
        $this->level = $level;
        $this->institutionclass = $classinstitution;
        $this->institution_id = $institution_id;
        $this->dateupdated = $dateupdated;
        $this->dateadded = $dateadded;
        return $this;
    }

    public function setOther(
            $hc,
            $doa,
            $cas,
            $ern,
            $srn,
            $description
    ) {
        $this->hc = $hc;
        $this->doa = $doa;
        $this->cas = $cas;
        $this->ern = $ern;
        $this->srn = $srn;
        $this->description = $description;
        return $this;
    }

    public function setCourses($courses) {
        $this->courses = $courses;
        return $this;
    }

    public static function fromJson(
            $data
    ) {
        $idn = explode('-', $data['idnumber']);
        $rawlevels = \ITSourceProviders\Institution\Query\ResultSet::parse([array_key_exists('levels', $data) ? $data['levels'] : []]);
        $level = count($rawlevels) > 0 ? \ITSourceProviders\Institution\Level::fromJson($rawlevels[0]) : null;
        $rawinstitutionclass = \ITSourceProviders\Institution\Query\ResultSet::parse([array_key_exists('classes', $data) ? $data['classes'] : []]);
        $institutionclass = count($rawinstitutionclass) > 0 ? \ITSourceProviders\Institution\InstitutionClass::fromJson($rawinstitutionclass[0]) : null;
        $rawcourses = \ITSourceProviders\Institution\Query\ResultSet::parse([array_key_exists('courses', $data) ? $data['courses'] : []]);
        $courses = [];
        $len = count($rawcourses);
        for ($i = 0; $i < $len; $i++) {
            $courses[] = \ITSourceProviders\Institution\Course::fromJson($rawcourses[$i]);
        }
        $user = new User($data['user_id']);
        $user->set(
                $data['first_name'],
                $data['middle_name'],
                $data['last_name'],
                date('Y-m-d H:i:s', strtotime($data['dob'])),
                $data['address'],
                $data['email'],
                $data['house'],
                $idn[count($idn) - 1],
                $data['guardian'],
                $data['phone'],
                $data['gender'],
                $data['shift'],
                strval($data['path']) == '0' ? false : true,
                $data['role_id'],
                $data['role_name'],
                strval($data['banned']) == '0' ? false : true,
                date('Y-m-d H:i:s', strtotime($data['dateadded'])),
                date('Y-m-d H:i:s', strtotime($data['dateupdated'])),
                $level,
                $institutionclass,
                $data['institution_id']
        );
        $user->setCourses($courses);
        $user->setOther(
                $data['hc'],
                date('Y-m-d H:i:s', strtotime($data['doa'])),
                $data['cas'],
                $data['ern'],
                $data['srn'],
                $data['description']
        );
        $user->course_id = $data['course_id'];
        return $user;
    }

    public function toJson() {
        $courses = [];
        $len = count($this->courses);
        for ($i = 0; $i < $len; $i++) {
            $courses[] = $this->courses[$i]->toJson();
        }
        $data = [
            'user_id' => $this->user_id,
            'first_name' => $this->firstname,
            'middle_name' => $this->middlename,
            'last_name' => $this->lastname,
            'dob' => $this->dob,
            'address' => $this->address,
            'email' => $this->email,
            'house' => $this->house,
            'idnumber' => $this->idnumber,
            'guardian' => $this->guardian,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'shift' => $this->shift,
            'path' => $this->path ? '1' : '0',
            'hc' => $this->hc,
            'doa' => $this->doa,
            'cas' => $this->cas,
            'ern' => $this->ern,
            'srn' => $this->srn,
            'description' => $this->description,
            'role_id' => $this->roleId,
            'role_name' => $this->roleName,
            'banned' => $this->banned ? '1' : '0',
            'dateadded' => $this->dateadded,
            'dateupdated' => $this->dateupdated,
            'institution_id' => $this->institution_id,
            'level' => empty($this->level) ? null : $this->level->toJson(),
            'class' => empty($this->institutionclass) ? null : $this->institutionclass->toJson(),
            'courses' => $courses,
            'course_id' => $this->course_id
        ];
        return $data;
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
        $users = [];
        for ($i = 0; $i < $len; $i++) {
            $users[] = \ITSourceProviders\Institution\User::fromJson($data[$i]);
        }
        return $users;
    }

    public static function next() {
        $data = self::$pager->next();
        $len = count($data);
        $users = [];
        for ($i = 0; $i < $len; $i++) {
            $users[] = \ITSourceProviders\Institution\User::fromJson($data[$i]);
        }
        return $users;
    }

    public static function previous() {
        $data = self::$pager->previous();
        $len = count($data);
        $users = [];
        for ($i = 0; $i < $len; $i++) {
            $users[] = \ITSourceProviders\Institution\User::fromJson($data[$i]);
        }
        return $users;
    }

    public static function at($index) {
        $data = self::$pager->at($index);
        $len = count($data);
        $users = [];
        for ($i = 0; $i < $len; $i++) {
            $users[] = \ITSourceProviders\Institution\User::fromJson($data[$i]);
        }
        return $users;
    }

    public static function size() {
        return self::$pager->getSize();
    }

    public static function pages() {
        return self::$pager->getPages();
    }

}

\ITSourceProviders\Institution\User::$pager = new \ITSourceProviders\Institution\Query\ResultSet('/users', '/users/count', [], 10);
