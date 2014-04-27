## kitFramework::TemplateTools ##

&copy; 2014 phpManufaktur by Ralf Hertsch

MIT License (MIT) - <http://www.opensource.org/licenses/MIT>

kitFramework - <https://kit2.phpmanufaktur.de>

**0.14** - 2014-04-27

* added constant `PAGE_HAS_CHILD` and function `$template['cms']->page_has_child()`
* added `Breadcrumb` and `Nav` Navigation for Bootstrap
* added `$template['bootstrap']->alert()` function
* added template examples `tt_classic_one`, `tt_classic_two`, `tt_bootstrap_one` and `tt_bootstrap_two` - the examples will be installed as Templates in the CMS
* add `twig` function `file_exists()`
* added many patterns and completed the [WIKI for the TemplateTools](https://github.com/phpManufaktur/kfTemplateTools/wiki)  

**0.13** - 2014-04-16

* fixed: constant `PARENT` is not available in BlackCat CMS

**0.12** - 2014-04-16

* fixed: constant `LEVEL` is not available in BlackCat CMS

**0.11** - 2014-04-16

* fixed: define `CMS_USER_ACCOUNT_URL`, `CMS_LOGIN_URL`, `CMS_LOGIN_FORGOTTEN_URL`, `CMS_LOGIN_SIGNUP_URL`, `CMS_LOGOUT_URL`
* fixed #1: ErrorException: Warning: mysql_fetch_array() 

**0.10** - 2014-04-15

* initial release
