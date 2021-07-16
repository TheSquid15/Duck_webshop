# Duck Webshop CMS
### This is a depricated version of the Duck Webshop CMS 

The Duck Webshop was a final project where we were supposed to create our own CMS with the following requirments.
- Full CRUD functionality
- Implement basic security
- Be able to run on a live server
- The application should implement some kind of design pattern
- It should be built in pure PHP, no frameworks or libraries allowed
- It should have a fully modeled and realized database, which also has to run on a live server
- The application needs to have these components:
  - A cart
  - Login and registration system
  - A backend for the admin users
  - A recommendation system
  - A contact form

When cloning the webshop make sure that you create a new constant file in `Duck_webshop/includes` named `const.php` that contains the database connection details like so:
```
define(DB_SERVER, "server.hostname.com");
define(DB_USER, "username");
define(DB_PASSWORD, "strongpassword");
define(DB_NAME, "dws_db_name");
```

All controllers inherit from the model, so all classes can make use fo the functions inside of the `model.php` file to:
- Make queries to the database
- Check if the user is logged in
- Create login guards on pages reserved for users
