=== Eventbrite Pro ===
Contributors: yguez
Donate link: PayPal - info@trestian.com
Tags: eventbrite, calendar, events, widget, api
Requires at least: 3.0
Tested up to: 3.6
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Shortcode to display a list and calendar of Eventbrite events

== Description ==

This WordPress shortcode, [eventbrite_list], uses the Eventbrite API to display your upcoming Eventbrite events in a list as well as an
optional calendar.

It will cache your event data to increase site performance and to prevent API abuse.  You can choose the cache duration
and manually clear the cache as needed.  You can also easily override the template used to list your events.  See the
FAQ for more details.

A few notes about the sections above:

*   Stable tag should indicate the Subversion "tag" of the latest stable version, or "trunk," if you use `/trunk/` for
stable.

    Note that the `readme.txt` of the stable tag is the one that is considered the defining one for the plugin, so
if the `/trunk/readme.txt` file says that the stable tag is `4.3`, then it is `/tags/4.3/readme.txt` that'll be used
for displaying information about the plugin.  In this situation, the only thing considered from the trunk `readme.txt`
is the stable tag pointer.  Thus, if you develop in trunk, you can update the trunk `readme.txt` to reflect changes in
your in-development version, without having that information incorrectly disclosed about the current stable version
that lacks those changes -- as long as the trunk's `readme.txt` points to the correct stable tag.

    If no stable tag is provided, it is assumed that trunk is stable, but you should specify "trunk" if that's where
you put the stable version, in order to eliminate any doubt.

==== Usage ====
On first use, visit the plugin settings page and set your Eventbrite API key, email and cache duration.
You can get your API key at: http://www.eventbrite.com/api/key/

Events loaded via the API are cached (see the FAQ).  You can change the cache duration in the settings page.
You can also manually clear the cache at any point by clicking "Clear Page" from the settings page.

To display a list of your events, simply use the shortcode [eventbrite_list].  If you don't wish to display the calendar
simply add the attribute calendar="false", i.e. [eventbrite_list calendar="false"].

If you'd like to override the template used to list your events in order to add your own markup and CSS class names,
simply copy template-eventbrite_list.php to your theme directory.  Then you may make any changes you wish to the copied
template file without breaking future upgrades.  Do NOT modify the original template file.

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'eventbrite-pro'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `eventbrite-pro.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `eventbrite-pro.zip`
2. Extract the `eventbrite-pro-` directory to your computer
3. Upload the `eventbrite-pro` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard


== Frequently Asked Questions ==

= This doesn't work! =

That's not a question.  No, but seriously, this is the first plugin I've written from scratch so if any bugs slipped in
then I'm really sorry about that.  Please let me know and I'll fix it asap.

= Where's the widget? =
I haven't gotten around to adding a native widget yet.  But it's really easy to insert a shortcode in a widget.  Just
use a widget like Black Studio TinyMCE Widget and add the shortcode there.

= Can I change the way my events are displayed? =
Absolutely!  I used a lot of CSS class names which you can style away with your own stylesheet.  If that's not enough,
then take a look in the public/views folder of the plugin.  There's a file in there called template-eventbrite_list.php.
Do NOT modify this file.  If you do you'll lose all your changes when you upgrade the plugin.  Instead, just COPY
it to your theme directory.  The plugin will automatically check there first and use that template instead. Smart, huh?

Feel free to modify the markup as you want, add classes...go wild!

= What is cache duration all about? =

This plugin uses the Eventbrite API to load your events from the Eventbrite website.  Every time you call the API it has
to contact the Eventbrite server.  This adds lag to page load.  If you call the API too many times then Eventbrite might
cut you off.  Fortunately, events don't change very often so it's not necessary to contact Eventbrite every time someone
views your events page.

The first time someone visits a page or post with this shortcode, the plugin will contact Eventbrite using the API, load
any upcoming events and store it in your database.  The next time someone visits this page, the plugin will display
 the events that are already in the database.  It will load much faster and not require another call to Eventbrite.

 After a few hours or days, depending on what you set in the plugin settings, the cache will expire and the next visit to
 this shortcode will load the latest events from Eventbrite.

 = What if I create a new event and want it to show up right away? =
 Simply click the "clear cache" button in the settings page.  Easy, huh?

 = Is the calendar cached as well? =
 Unfortunately, no.  The calendar is displayed with an IFRAME so caching it wouldn't work very well.  A
 caching plugin like W3 Total Cache probably caches this...I think.

= Why doesn't the plugin do X ? =
Because it's my first plugin and I wanted to get something up and running first.  I'll add more functionality and API
integration as I have the time.  I do have a day job though....http://www.trestian.com

= What custom hooks and filters do you use? =
See previous question.

= But I NEED it to do X! =
http://www.trestian.com

= You're the man!  How can I buy you a drink? =
Why, thank you very much! Feel free to PayPal donations to info---at---trestian---dot---com

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 0.1.1 =
* We are live baby!


== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`
