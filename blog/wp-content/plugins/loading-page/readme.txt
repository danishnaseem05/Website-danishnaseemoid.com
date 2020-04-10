=== Loading Page with Loading Screen ===
Contributors: codepeople
Donate link: http://wordpress.dwbooster.com/content-tools/loading-page
Tags:loading page,loadin screen,animation,page performance,page effects,performance,render time,wordpress performance,image,images,load,loading,lazy,screen,lazy loading,fade effect,posts,Post,admin,plugin,fullscreen,ads
Requires at least: 3.0.6
Tested up to: 5.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Loading Page with Loading Screen plugin performs a pre-loading of images on your website and displays a loading progress screen with percentage of completion. Once everything is loaded, the screen disappears.

== Description ==

Loading Page with Loading Screen features:

	→ Displays a screen showing loading percentage of a given page
	→ Displays the page's content with an animation after complete the loading process
	→ Increase the WordPress performance
	→ Allows to select the colors of the loading progress screen,
	→ As background colors and images
	→ Allows to display or remove the text showing the loading percentage
	→ Pre-loads the page images

Loading Page with Loading Screen plugin performs a pre-loading of image on your website and displays a loading progress screen with percentage of completion. Once everything is loaded, the screen disappears.

**More about the Main Features:**

* Displays a screen showing loading percentage of a given page. In heavy pages the "Loading Page with Loading Screen" plugin allows to know when the page appearance is ready.
* Allows to display the loading screen on homepage only, or in all pages of website.
* Allows to select the colors of the loading progress screen, or select images as background. By default the colour of loading screen is black, but it may be modified to adjust the look and feel of the loading screen with website's design.
* Allows to display or remove the text showing the loading percentage.

The base plugin, available for free from the WordPress Plugin Directory, has all the features you need to displays an loading screen on your website.

**Premium Features:**

* Allows to choose a loading progress screen. The premium version of plugin includes multiple loading screens.
* Special attention to the "Logo Screen", that allows to use the website's logo or any other image in the loading progress.
* Allows to select from multiple possible animations, to display the page's content after complete the loading process.
* Improves the page performance.
* Lazy Loading feature allows to load faster and reduce the bandwidth consumption. The images are big consumers of bandwidth and loading time, so a WordPress website with multiple images can improve its performance and reduce the loading time with the lazy loading feature.
* Allows to select an image as a placeholder, to replace the real images during pre-loading. It's recommended to select the lighter images possible to increase the WordPress performance, the image selected will be used instead of the original images, in the loading page process.

**Demo of Premium Version of Plugin**

[https://demos.dwbooster.com/loading-page/wp-login.php](https://demos.dwbooster.com/loading-page/wp-login.php "Click to access the Administration Area demo")

[https://demos.dwbooster.com/loading-page/](https://demos.dwbooster.com/loading-page/ "Click to access the Public Page")



**What is Lazy Loading?**

Lazy Loading means that the original images are not loaded until finalize the loading of page. This action improves the download speed of webpages.

If you want more information about this plugin or another one don't doubt to visit my website:

[http://wordpress.dwbooster.com](http://wordpress.dwbooster.com "CodePeople WordPress Repository")

== Installation ==

**To install Loading Page with Loading Screen, follow these steps:**

1. Download the zipped plugin.
2. Go to the **Plugins** section on your Wordpress dashboard.
3. Click on **Add New**.
4. Click on the **Upload** link.
5. Browse and locate the zipped plugin that you have just downloaded.
6. Once installed, activate the plugin by clicking on **Activate**.

== Interface ==

To use Loading Page with Loading Screen on your website, simply activate the plugin. If you wish to modify any of the default options, go to the plugin's Settings. They can be found either by going to Settings > Loading Page on your Wordpress dashboard, or by going to Plugins; a link to Settings can be found in the plugin description.

The Loading Page with Loading Screen setup is divided in two sections: the first one is dedicated to the activation and  setup of the loading screen, and the second to the delayed loading of the images that are not shown immediately ( images that require on-page scrolling in order to be seen).

**Loading Screen Setup**

The setup options for the loading screen are:

* **Enable loading screen**: activates preloading of images and displays a loading screen while the webpage is loading.
* **Display the loading screen once per session**: display the loading screen only once per session.
* **Display the loading screen on**: display the loading screen with all screens sizes, or if the screens sizes satisfy the conditions.
* **Display loading screen only in**: displays a loading screen only on homepage, all pages, or specific pages or posts. In the last case the IDs of pages or posts should be separated by comma symbol ","
* **Exclude the loading screen from**: excludes the loading screen from pages or posts whose IDs are entered separated by comma symbol ","
* **Select the loading screen**: allows to choose a loading screen. The premium version of plugin include multiple loading screens.
* **Select background color**: allows to select the background color for your loading screen compatible with the design guidelines of your website.
* **Select images as background**: allows to display an image as loading screen background, the image can be displayed tiled or centered.
* **Display image in fullscreen**: allows to adjust the background image in fullscreen mode.
* **Select foreground color**: Allows to select the color of the graphics and texts that display the loading progress information.
* **Additional seconds**: Allows to add seconds before remove the loading screen at the end of the load process.
* **Include an ad, or your own block of code**: Allows to add ads, or other block of code, to the loading screen.
* **Apply the effect on page**: Display the page's content with an animation after complete the loading process.
* **Display loading percent**: Shows the percentage of loading. The loading percent is calculated in function of images in the page.

* **Troubleshoot Area - Loading Screen**: allows disabling/enabling the search in deep.

**Lazy Loading Setup (in premium version only)**

The options to set up Lazy Loading and increase the WordPress performance are:

* **Enable lazy loading**: Enables the delayed loading of images outside of the current viewing area of the user improving the rendering time of complete page.
* **Select the image to load by default**: Choose an image to be shown as a placeholder of the actual images, the loading of which will be delayed. It's recommended the selection of a light image to increase the WordPress performance.

* **Troubleshoot Area - Lazy Loading**: allows entering some texts to exclude the images tags with the entered texts in the classes or attributes.

== Frequently Asked Questions ==

= Q: What to do if the loading screen is stopping in determined percentage? =

A: Tick the checkbox: "Disable the search in deep", in the "Troubleshoot Area - Loading Screen" section of the settings page.

= Q: How to use a custom image for the loading progress? =

A: From the settings page of the plugin, selects the "Logo Screen" in the list of available screens (only in the pro version of the plugin), and select the image to use in the loading screen through the new input field associated to the "Logo Screen".

= Q: How the lazy loading increase the WordPress performance? =

A: The lazy loading doesn't load the website images until images be in the viewport. If the user never scrolls the webpage, some images won't download with a reduction in the bandwidth consumption.

= Q: I've installed a plugin for images galleries, that applies a lazy load to the images. How to prevent a conflict with the lazy loading of the "Loading Page" plugin? =

A: Simply, identify a class name, or the value of an attribute applied to the images tags by the gallery, and enter the text through the attribute: "Exclude images whose tag includes the class or attribute" in the "Troubleshoot Area - Lazy Loading" section of settings page.

= Q: Could I display the loading screen on homepage only? =

A: Yes, that's possible. Go to the settings page of plugin and check the option "homepage only".

= Q: Is possible display the loading screen in some pages only? =

A: Yes, that's possible. Go to the settings page of plugin and check the option "the specific pages", and enter the posts or pages IDs, separated by the comma symbol ",".

= Q: Might I display an image as loading screen background? =

A: Yes, that's possible. Go to the settings page of plugin and select the image in the option "Select image as background". The image can be displayed tiled or centred.

= Q: Are the loading screens supported by all browsers? =

A: There are some loading screens that require of the canvas object, all modern browsers include the canvas object. The screens with special requirements display a caveat text when are selected.

= Q: Why can't I see the animation effect after complete the loading screen? =

A: Please be sure you are using a browser with CSS3 support.

== Screenshots ==
1. Loading Page Preview
2. Loading Screen Available
3. Benefits to use Lazy Load
4. Plugin Settings

== Changelog ==

= 1.0 =

* First version released.

= 1.0.1 =

* Improves the plugin documentation.
* Performs a pre-loading of the images on your website, and displays a loading progress screen with percentage of completion.
* Allows to display an image as background of the loading screen.
* Allows to display the background image in fullscreen mode.
* Associates effects to the page loaded.
* Allows to display the loading screen only on homepage, all pages, or particular pages of website.
* Corrects an issue with the resources loaded in Internet Explorer.
* Reduces the interval of time to display the loading screen.
* Corrects an issue with the percentage text in the loading screen.
* Excludes some files from the loading process.

= 1.0.2 =

* Includes the feature to display the loading screen once per session.
* Includes the feature for excluding the loading screen from specific pages and posts.
* Modifies the behavior of the lazy loading images.

= 1.0.3 =

* Prevents the insertion multiple instances of the Loading Screen in a same page.

= 1.0.4 =

* Improves the module for parsing the config.ini files of the loading screens.

= 1.0.5 =

* Prevents to use the loading screen when the website is accessed from search crawlers or spiders.

= 1.0.6 =

* Improves the loading screen process.

= 1.0.7 =

* Optimizes the scripts in the admin section.

= 1.0.8 =

* Modifies the settings interface.

= 1.0.9 =

* Modifies the validation of the excluded pages to display the loading screen (thanks to WordPress member: publicusmordicus).

= 1.0.10 =

* Improves the management of sessions, and the loading screen behavior.

= 1.0.11 =

* Escape all attributes, SQL queries, and URL parameters, to prevent some vulnerabilities.

= 1.0.12 =

* Modifies some deprecated jQuery functions.

= 1.0.13 =

* Prevents that multiple instance of jQuery framework can overwrite the plugin's code.

= 1.0.14 =

* Assigns class names to the elements in the loading screen to allow more control over its appearance: lp-screen, lp-screen-text, lp-screen-graphic

= 1.0.15 =

* Reimplements the loading screen to improve its behavior in all devices.
* Includes the "Troubleshoot" sections for enabling/desabling the deep search, or exlude images from the lazy loading.

= 1.0.16 =

* Allows to add seconds before remove the loading screen at the end of the load process.
* Allows to add ads, or other block of code, to the loading screen.

= 1.0.17 =

* Allows to control the loading screen based on the screens sizes.

= 1.0.18 =

* Solves PHP notices in the settings page of the plugin.

= 1.0.19 =

* Improves the loading screen when there are images defined as tags backgrounds.

= 1.0.20 =

* Includes a new feature: Allow to display the loading screen once per page.

= 1.0.21 =

* Fixes an issue in the module "loading screen once per page".

= 1.0.22 =

* Improves the access to the plugin documentation.

= 1.0.23 =

* Modifies the module for accessing the WordPress reviews section.

= 1.0.24 =

* Fixes an issue in the promote banner.

= 1.0.25 =

* Modifies the module that includes the Ads in the loading screen.

= 1.0.26 =

* Includes a new option in the plugin's settings to allow remove the loading screen in the onload event of the window object, and not as soon as possible, like in the previous versions of the plugin.

= 1.0.27 =

* Includes some changes in the loading screen's design.
* The professional version takes into account the "srcset" attributes for the lazy loading.
* In the professional version the placeholder images are replaced sooner by the original ones.

= 1.0.28 =

* Fixes an issue that clears the plugin's settings when it is deactivated/activated.

= 1.0.29 =

* The plugin check if exists the global function "afterCompleteLoadingScreen", and calls it after the loading screen reach the 100%.

= 1.0.30 =

* Fixes an issue with the loopback requests when are being edited the code of plugins or themes in the WordPress editor.

= 1.0.31 =

* Modifies the activation/deactivation modules to facilitate both process.

= 1.0.32 =

* Includes a new loading screen in the plugin.

= 1.0.33 =

* Fixes a conflict with the Elementor editor.


= 1.0.34 =

* Hides the promotion banner for the majority of roles and fixes a conflict between the promotion banner and the Gutenberg editor.

= 1.0.35 =

* Modifies the loading screen logo.

= 1.0.36 =

* Optimizes the loading screen to increase the speed.

= 1.0.37 =

* The update causes the loading screen appears sooner.

= 1.0.38 =

* Improves the code.
* Removes the check for unnecessary elements.
* Applies transparency to the loading screen background color.

= 1.0.39 =

* Fixes a javascript error.

= 1.0.40 =

* Includes a new attribute in the settings page of the plugin for controlling the transparency of the background color.

= 1.0.41 =

* Removes redundant code.
* Adds new styles.
* Improves the loading screen appearance.

= 1.0.42 =

* Fixes a conflict with autoptimize.

= 1.0.43 =

* Fixes an issue with versions of WordPress previous to wp4.5
* Replaces the animated gifs in the logo screen with svg, solving an issue with the antialias on gifs.

= 1.0.44 =

* Fixes a conflict with the "Speed Booster Pack" plugin.

= 1.0.45 =

* Fixes an issue detecting the singular pages.

= 1.0.46 =

* Preloads the logo image when the "Logo Screen" is selected as loading screen.

= 1.0.47 =

* Fixes an issue between the Promote Banner and the official distribution of WP5.0

= 1.0.48 =

* Includes a close loading screen button.
* In the logo screen, clicking on the logo image, closes the loading screen.
* Improves the loading process.
* Fixes some script errors.

= 1.0.49 =

* Fixes a CSS issue.

= 1.0.50 =

* Modifies the way the resources are loading to fix a conflict with the "Fast Velocity Minify" plugin.

= 1.0.51 =

* Modifies some styles to allow redefine the logo size with CSS.

= 1.0.52 =

* Modifies the language files and plugin headers.

= 1.0.53 =

* Fixes an issue in the js code.

= 1.0.54 =

* Satisfying the users' requests, the Loading Page settings can be modified now only by the website's administrators. As the loading screen can affect all website's pages the responsability should be of the administrator.

= 1.0.55 =

* Fixes a minor issue in the loading screen: logo.

= 1.0.56 =

* Modifies the settings page of the plugin.

= 1.0.57 =

* Increases the plugin's security.

= 1.0.58 =

* Includes a new option in the settings for excluding the loading screen by post types.
* Includes new attributes in the troubleshoot area.

= 1.0.59 =

* Fixes an issue when the loading screen is being displayed or not depending on the screen size.

= 1.0.60 =

* Fixes an issue with the close loading screen button if have been assigned additional seconds to the loading screen.

= 1.0.61 =

* Fixes an issue when javascript is disabled on browsers.

= 1.0.62 =

* Includes a new section to allow disable the loading screen from pages based on their URLs. This allows to disable the loading screen on the ipn scripts, or any other URL used by services in background.

= 1.0.63 =

* Modifies the access to the demos.

= 1.0.64 =

* Fixes an issue calculating the loading percentage.

== Upgrade Notice ==

= 1.0.64 =

Important note: If you are using the Professional version don't update via the WP dashboard but using your personal update link. Contact us if you need further information: http://wordpress.dwbooster.com/support