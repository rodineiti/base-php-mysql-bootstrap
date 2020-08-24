# RDNBASE

    this is a base template for your frontend and backend applications using PHP, MYSQL and Bootstrap in the MVC standard, using good practices to 
    create your applications in an organized manner.

Clone the repository

    git clone git@github.com:rodineiti/base-php-mysql-bootstrap.git base

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



Prints:

Home

![image](https://user-images.githubusercontent.com/25492122/90269331-d9133d00-de2e-11ea-92bf-24ecf89ca0ef.png)


Dashboard Admin

![image](https://user-images.githubusercontent.com/25492122/90269436-0102a080-de2f-11ea-8c66-e5a12d8ca9fa.png)