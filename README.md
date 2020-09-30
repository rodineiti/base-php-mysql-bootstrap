# RDNBASE

    this is a base template for your frontend and backend applications using PHP, MYSQL and Bootstrap in the MVC standard, using good practices to 
    create your applications in an organized manner.

Clone the repository

    git clone git@gitlab.com:rodineiti/base-php-mysql-bootstrap.git base

Switch to the repo folder

    cd base
    cp config-example.php config.php
    cp htaccess-example.txt .htaccess
    
Edit file config.php, and set connection mysql

    $config["dbname"] = "database_base";
    $config["dbhost"] = "mysql";
    $config["dbuser"] = "root";
    $config["dbpass"] = "root";
    
Dump file database_base.sql into database

Run server php or your server (Wamp, Mamp, Xamp), and open in the browser localhost
  
    php -S localhost

Url ADMIN:

    Admin login: http://localhost/base/admin?login

    login: admin@admin.com
    password: 123456
    
LOGIN USER:

    login: http://localhost/base/auth?login

    login: user@user.com
    password: 123456
    
    
IMPORTANT

    Whenever creating routes in the admin, it is necessary to add this route in the helpers.php file in function check_url() for the routing to work.
    Without the parameters, only the final url. Example: admin/users/edit
    
    Example: 
    
    if your route admin: "admin/users/edit/5"
       
    add only: "admin/users/edit"

![image](https://user-images.githubusercontent.com/25492122/90270781-25f81300-de31-11ea-9514-2b10ebe3b9e4.png)


#### Selects
```php
<?php
use Src\Models\User;

// get one with where - alternative find one
$model = new User();
$user = $model->select(["id", "name"])->where("name", "=", "Fulano")->fisrt();

var_dump($user);

// get all with get - alternative find all
$model = new User();
$users = $model->select()->all();

var_dump($users);
```

#### findById

```php
<?php
use Src\Models\User;

$model = new User();
$user = $model->getById(2);
echo $user->name;
```

#### count

```php
<?php
use Src\Models\User;
$model = new User();

$count = $model->select()->count();
```

#### wheres
```php
<?php
use Src\Models\User;
$model = new User();

// find with where
$users = $model
    ->select()
    ->where("id", ">", 1)
    ->where("name", "=", "teste")
    ->all();

var_dump($users);

$model = new User();
// find with whereRaw
$users = $model
    ->select()
    ->whereRaw("name LIKE '%fulano%' ")
    ->all();

var_dump($users);

$model = new User();
// find with whereIn
$users = $model
    ->select()
    ->whereIn("id", [1,2])
    ->all();

var_dump($users);
```

#### joins
```php
<?php
use Src\Models\User;

$model = new User();
// find with join address
$users = $model
    ->select()
    ->join("address", "user_id", "id")
    ->all();

var_dump($users);

$model = new User();
// find with left join address
$users = $model
    ->select()
    ->leftJoin("address", "user_id", "id")
    ->all();

var_dump($users);

$model = new User();
// find with right join address
$users = $model
    ->select()
    ->rightJoin("address", "user_id", "id")
    ->all();

var_dump($users);
```



Prints:

Home

![image](https://user-images.githubusercontent.com/25492122/90269331-d9133d00-de2e-11ea-92bf-24ecf89ca0ef.png)


Dashboard Admin

![image](https://user-images.githubusercontent.com/25492122/90269436-0102a080-de2f-11ea-8c66-e5a12d8ca9fa.png)