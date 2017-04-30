![](https://iamevan.me/wp-content/uploads/2016/10/logo.png)

# Requirements

* PHP7
* MySQL 5.6
* [Composer](https://getcomposer.org/)

# To Compile

## 1) Download and use the installer

```bash
composer global require thejokersthief/granitecms-installer
granitecms new project_name
```

Before executing the second command, ensure that `~/.composer/vendor/bin` is in your path.

## 2) Adding Your Logins

Go to `GraniteCMS/database/seeds/DummySiteSeeder.php` and fill in your social IDs for Twitter and Facebook towards the bottom of the file.

* [Facebook tool to find ID](https://findmyfbid.com/)
* [Twitter tool to find ID](http://www.idfromuser.com/)

## 3) Vagrant

The best way to test and run the environment is to use [vagrant](https://www.vagrantup.com/).

```bash
cd project_name/
vagrant up
```

# File Authorship

*All folders except the following contain my own code mixed with boilerplate code from the Laravel framework*

* vendor
* src
* bootstrap
* storage

