## ISCP Wordpress Theme

This current version of the ISCP Wordpress Theme was originally configured and deployed in 2014/15. Many aspects of the theme became deprecated over the years and in order to modernize and upgrade the website to the latest version of Wordpress, a number of code edits and upgrades were put into place starting late 2024. However, much was left in its original state and not rebuilt if still working properly.

### Wordpress Structure

* The theme uses a minimal number plugins including, most importantly, ACF.
* The `functions.php` file contains a great number of custom query functions, general WP configs, as well as custom post type configurations directly within it.
* The default `page.php` template file makes references to the `/sections` directory for a majority of the custom post type template content areas.
* Within `/sections` you can also find partials for overview page `/loops`, `/items` within those loops, and `/params` for query results in those loops.
* There is also a `/filters` directory that contains the filter UI and functions for `events`, `journals` (publications), and `residents`.

### Local Config, NPM, and Source Files

To configure the theme locally:

1. Install Wordpress on your local machine (using Local or MAMP or something similar to run the virtual local server).
2. Pull the ISCP theme repo locally to your Wordpress install's `/wp-content/themes` directory.
3. Install the and activate current site's plugins locally.
4. Make sure NPM is installed locally, and using Node 18+.
5. `CD` to the root of the theme folder and run `npm run go` to run the CSS watch compiler for the SASS files in `/assets/sass`.

Other notes:

* Since this original website was built in 2014/15, much of the JS uses jQuery and is still in working order.
* The Residents Map uses WebGL and the map assets (as of 2024) are now included in the `/js/webgl` directory.

### Deployment

* Github Actions is configured in this Repo to manually deploy updates from both the `Master` (live website), and `Staging` (staging website) branches.
* After you've merged your working branch into either `Master` or `Staging`, tab over to `Actions` and choose the appropriate action and branch to deploy from when ready.
* Github Actions is configured with the LAMP Droplet on the ISCP DigitalOcean account using SSH keys and credentials.
