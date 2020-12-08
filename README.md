# IT Source Providers

A php sdk to interface with (https://institution.itsourceproviders.com//)

# Classes

  - ITSourceProviders\Institution\User
  - ITSourceProviders\Institution\Course
  - ITSourceProviders\Institution\InstitutionClass
  - ITSourceProviders\Institution\Subject
  - ITSourceProviders\Institution\Level


You can pull your data and display information on your personal website about `Subject`s offered at different levels and in different `InstitutionClass`s. 


### Installation



```sh
composer require wwwitsourceproviders/institution
```


### Development

```sh
require 'vendor/autoload.php';
try {
    ITSourceProviders\Institution\Config\Setting::setCredentials('path\to\credentials.json');
    ITSourceProviders\Institution\User::setParameters(['levels' => true, 'classes' => true, 'courses' => true]);
    ITSourceProviders\Institution\User::setLimit(1);
    $users = ITSourceProviders\Institution\User::at(0);
    //$users = ITSourceProviders\Institution\User::get();
    //$users = ITSourceProviders\Institution\User::next();
    //$users = ITSourceProviders\Institution\User::previous();
    $size = ITSourceProviders\Institution\User::size();
    $pages = ITSourceProviders\Institution\User::pages();
    echo 'pages:' . $pages . ' out of '.$size.' <br/>';
    for ($i = 0; $i < count($users); $i++) {
        echo json_encode($users[$i]->toJson());
    }
} catch (Error $e) {
    echo $e->getMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}
```
```sh
try {
    echo '<br/>';
    ITSourceProviders\Institution\Config\Setting::setCredentials('path\to\credentials.json');
    ITSourceProviders\Institution\Subject::setParameters(['keyword'=>'math']);
    $subjects = ITSourceProviders\Institution\Subject::get();
    //$classes = ITSourceProviders\Institution\InstitutionClass::get();
    //$levels = ITSourceProviders\Institution\Level::get();
    //$courses = ITSourceProviders\Institution\Course::get();
    $size = ITSourceProviders\Institution\Subject::size();
    $pages = ITSourceProviders\Institution\Subject::pages();
    echo 'pages:' . $pages . ' out of '.$size.' <br/>';
    for ($i = 0; $i < count($subjects); $i++) {
        echo json_encode($subjects[$i]->toJson());
    }
} catch (Error $e) {
    echo $e->getMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}
```
