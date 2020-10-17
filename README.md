# zen-do.ru

Contemporary Zen practice in Russian (and a bit of English).

_(Static mirror)_

When the main site https://zen-do.ru/ does not work, come here:

# https://s.zen-do.ru/

# The Procedure _(work in progress)_

How to convert articles from Joomla (or othe DB-based engine) to Static HTML files:

(1) Export articles from the database.

For example, with `phpMyAdmin` export Joomla site table `<prefix>_content` to JSON file.

(2) Run `export.php` from this repository, it would read the JSON file line by line, writing the data in separate HTML files, with directory structure according to categories. See `export.php` for details and tune its options.

(3) Run wrap.sh to wrap the files in header-&-footer template (static HTML).

(4) ToDo: List categories, blogs etc. Static HTML + Javascript should work. Maybe GitHub actions to update info on changes.
