## ISCP Wordpress Theme

This current version of the ISCP Wordpress Theme was originally configured and deployed in 2014/15. Many aspects of the theme became deprecated over the years and in order to modernize and upgrade the website to the latest version of Wordpress, a number of code edits and upgrades were put into place starting late 2024. However, much was left in its original state and not rebuilt if still working properly.

### Wordpress Structure

* The theme uses a minimal number plugins including, most importantly, ACF.
* The `functions.php` file contains a great number of custom query functions as well as custom post type configurations directly within it.
* The default `page.php` template file makes references to the `/sections` directory for a majority of the custom post type template content areas.
* Within `/sections` you can also find partials for overview page `/loops`, `/items` within those loops, and `/params` for query results in those loops.
* There is also a `/filters` directory that contains the filter UI and functions for `events`, `journals` (publications), and `residents`.

### Local Config, NPM, and Source Files

To configure the theme locally:
