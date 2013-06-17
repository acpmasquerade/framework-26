26framework 
===========

Probably an ultra lightweight PHP framework

Yet another ?
-----------

Something which fits my taste :) . I hope it does fit yours too :P 

MVC ?
-----
Do you seriously need that ? If yes, just create separate Helpers as Models. Rest of the acronyms are already fabricated.

Controllers
-----------
All Controllers reside inside a folder *controllers*.
And each controller with filename *xyz.php* and class name *Controller_Xyz* is routable. Public methods inside the controller can be accessed via a sanitized URL request.
Index method is the default method.
````php
<?php 
  // the controller
  class Controller_Home extends Facetroller{  
    // the constructor
    public function __construct(){
      parent::__construct();
    }
    
    // the index page
    public function index(){
      Tempalte::set("index", array());
    }
  }
?>
````

Helpers
-------
All Helpers reside inside a folder *helpers*. A helper is loaded as simply as 
````php
Loader::helper("some-helper");
````
And by convention, each helper method is a static method.


View and Theming
----------------
View files reside inside *templates* folder and each view file is loaded by either of the two helper methods of Template class.

*First method*
Loads the view file inline.
````php
Template::load("view");
````

*Second Method*
Renders the container for the theme and places the view file at the respective placeholder.
````php
$viewdata = array();
$viewdata["name"] = "acpmasquerade";
// searches for a file name view.php insde templates/themes/some-theme/ folder
Template::set("view", $viewdata);
````

Themes
------
Themes stay inside templates/themes/ folder 

Directory Structure
-------------------
      .
    	├── controllers
    	│   ├── account.php
    	│   ├── admin.php
    	│   ├── dashboard.php
    	│   ├── docs.php
    	│   ├── home.php
    	│   ├── login.php
    	│   ├── logs.php
    	│   └── user.php
    	├── helpers
    	│   ├── acl.php
    	│   ├── admin.php
    	│   ├── dashboard.php
    	│   ├── general.php
    	│   ├── helper.php
    	│   ├── pagination.php
    	│   ├── report.php
    	│   ├── sparrowsms.php
    	│   ├── template.php
    	│   └── user.php
    	├── index.php
    	├── Lib
    	│   ├── _Bootstrap.php
    	│   ├── _Config.class.php
    	│   ├── _Controllers.class.php
    	│   ├── _Database.class.php
    	│   ├── _DB.class.php
    	│   ├── _ezsql.class.php
    	│   ├── _ezsql.core.class.php
    	│   ├── _Loader.class.php
    	│   ├── _Router.class.php
    	│   ├── schema
    	│   │   └── framework26.sql
    	│   ├── _Session.class.php
    	│   ├── _Template.class.php
    	│   └── _utils.functions.php
    	├── README.md
    	├── templates
    	│   ├── 403.php
    	│   ├── 404.php
    	│   ├── 500.php
    	│   ├── ...
    	│   ├── ...
    	│   ├── ...
    	│   ├── admin
    	│   │   └── index.php
    	│   ├── blank.php
    	│   ├── dashboard
    	│   │   └── index.php
    	│   ├── docs
    	│   │   └── index.php
    	│   ├── themes
    	│   │   └── default
    


Contact
-----------
info@acpmasquerade.com
twitter : @acpmasquerade
