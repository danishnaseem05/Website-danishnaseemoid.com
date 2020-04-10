=== wpCentral ===
Contributors: softaculous
Tags: wpcentral, softaculous, sites, manage sites, backup, plugins, themes, manage wordpress, 
Requires at least: 4.4
Tested up to: 5.3
Requires PHP: 5.3
Stable tag: 1.5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

wpCentral provides a single-login centralized panel where you can manage tons of your WordPress websites efficiently, unitedly as well as singularly.

== Description ==

If ever you wanted a single panel to manage tons of your Wordpress websites from and save the hassle to login all your website's dashboards separately, you have it here at last. [wpcentral](https://wpcentral.co/ "Manage Multiple WordPress Websites") provides a single panel where you can add infinite number of Wordpress websites for free.

Key Features:

*	Entire data of all your websites can be synced in wpcentral panel so that you may skim through the same without logging in separately. Even if you want to go into detail for any website, you can simply click and you will be logged into the website using the Single Sign On feature.

*	Should you want to activate/deactivate a plugin/theme on n number of websites, you can achieve this from wpcentral panel using wpcentral plugin. Also, you can install and update the plugins/themes on all the websites in one go.

*	You can also create Sets of common Plugins and Themes which you want to install on multiple websites together.

*	If you are worried about losing your data anytime in the future, we, hereby, resolve all your stress by providing you with the backup feature of your websites. In an unfortunate event, when you loose your website or your website gets corrupted, you can even restore the backup taken previously.

*	A new WordPress update is out and you are all stressed up to update your websites? No worries, wpcentral helps you achieve the same without the need to go into the dashboard of each website separately.

Many more to come! We have a number of other features in our To Do list which we will be adding in the upcoming versions, so stay tuned!

Should you have any suggestions to improve wpcentral, want to see some related features in wpcentral to help you in the websites management or if you have any queries, you can open a ticket with us at https://softaculous.deskuss.com/open.php

== Installation ==
1. Upload the plugin folder to your /wp-content/plugins/ folder
2. Activate the plugin through the "Plugins" menu in WordPress.
3. You can find the Connection Key by clicking on "View Connection Key" link that appears on the Plugins page.
4. Go to [panel.wpcentral.co](https://panel.wpcentral.co/ "Manage Multiple WordPress Websites") and create an account.
5. Add your website there by following the steps using the connection key.
6. It's Done! You can now start exploring.

== Changelog ==

= 1.5.3 = 
* [Task] : Added options to allow you as the admin to choose which IP address(s) are allowed to access wpCentral functionality of the plugin. 
* [Task] : You can now reset the wpCentral connection key from the WordPress Admin Panel.
* [Bug Fix] : The login feature of wpCentral was not working due to the IP restriction added earlier. This is fixed.

= 1.5.2 =
* [Security Fix] : Added IP address restriction in the current version so that calls are allowed only from wpCentral Servers. Even if a key is leaked by any chance this will have no impact as the keys will work only if they are from the wpCentral Servers. We are adding options to allow you as the admin to choose which IP addresses are allowed in the next version. Also manually resetting keys will be coming up. Please upgrade to this version ASAP. Note : Suggestion of IP restrictions is from the Softaculous Team as they are reviewing each and every aspect of wpCentral now.

= 1.5.1 =
* [Task] : Added index.php
* [Task] : Cleaned unnecessary filters and hooks left by the previous coders of wpCentral.
* [Security Fix] : This version includes a security fix to prevent disclosure of the connection key. We have re-checked the whole code and also re-written many other parts to make sure this issue does not occur again. Please update immediately. We would like to thank the WordFence team for reporting this issue. Full disclosure will be reported in a few days after we have launched this version.
* [Security Fix] : We are resetting the wpCentral Auth Keys for the users as a security precaution.

= 1.5.0 =
* [Security Fix] : We are resetting the wpCentral Auth Keys for the users as a security precaution.

= 1.4.8 =
* [Security Fix] : This version includes a very important security fix. Please update immediately.

= 1.4.7 =
* [Bug Fix] : Featured Image was not published while publishing the post using wpCentral
* [Bug Fix] : When a draft post was published using wpCentral, it created a new post instead of publishing the existing one.

= 1.4.6 =
* wpCentral Post Management support

= 1.4.5 =
* wpCentral promotional notice on admin panel to be displayed only when the website is connected to wpCentral panel.

= 1.4.4 =
* wpCentral promotional notice on admin panel after a day has passed since the plugin was activated.

= 1.4.3 =
* wpCentral notice on admin panel can now be dismissed manually.
* Added remove directory file function.
* Improved plugins/themes update code.
* Function to fetch wpCentral installed plugin version.

= 1.4.2 =
* Added 1-click website connection utility.
* Added rename file function.

= 1.4.1 =
* Added support and documentation links to the plugin notice.
* Activate Plugins after installation
* Use function call_user_func_array for more than one argument.
* Check if the user account is suphp or non-suphp

= 1.4 =
* Added validation on TLS connections.

= 1.3 =
* Resolved the bug in plugin activation after installation using Plugin Sets.

= 1.2 =
* Changes to the handling of connection key.
* WPCentral plugin can now be installed to your website via https://panel.wpcentral.co using your website's admin credentials.

= 1.1 =
* Hide Plugin Notice from website's dashboard on website connection/addition to https://panel.wpcentral.co
* Hide Plugin Notice for users not having 'Administrator' role.
* Disable wpcentral plugin when the site is diconnected/removed from https://panel.wpcentral.co

= 1.0 =
* Initial public release