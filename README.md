moodle-repository-boxnetsso
===========================

Inherits from Boxnet repository that comes with Moodle 2.6 and up.  Provides extra features for SSO integration to box.com, and streamlines the log in process.

Installation
------------

Simply clone this to your Moodle installation and visit the Notifications page on your Moodle site.

```bash
# Go to your Moodle directory
cd /var/www/moodle

# Clone this repo
git clone https://github.com/ucsf-ckm/moodle-repository-boxnetsso.git repository/boxnetsso
```

You can also add this as a git submodule:
```bash
git submodule add https://github.com/ucsf-ckm/moodle-repository-boxnetsso.git repository/boxnetsso
```

### Notes
* This Moodle repository shares the same Client ID and Client secret as in the built-in Box.net repository.  Make sure you select 'Enabled and visible' or 'Enabled but hidden' for the built-in Box.net and fill in the Client ID and Client secret first.
* You may also want to make the following changes to this Moodle file, `/repository/filepicker.js`, so that the login window is large enough to see the 'Authorize' button on the bottom of the page.

Search for this line:
```php
                        window.open(loginurl, 'repo_auth', 'location=0,status=0,width=500,height=300,scrollbars=yes');
```
to:
```php
                        window.open(loginurl, 'repo_auth', 'location=0,status=0,width=500,height=600,scrollbars=yes');
```
