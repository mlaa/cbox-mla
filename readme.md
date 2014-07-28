# New Changes in Version 2.0 ("minimal" branch)

 * The site has been given a new, flat theme. Gradients, textures, and 3D buttons have been replaced by simple, minimalistic elements. The colorscheme has been also simplified, favoring desaturated colors and grayscale over the bright reds and oranges of the previous theme. 
 * Many redundant UI elements have been removed. Some subscription links have been removed in favor of the "Email Subscriptions" group tab. Pagination buttons that previously appeared on the top and bottom of any content area now only appear at the bottom. Forums titles that bear the same titles as their groups have been removed. 
 * The default homepage for groups is now "Forum." The group tab previously called "Home" is now more accurately termed "Activity." 
 * The sitewide wiki has been removed. However, documents created there are still available in user profiles, and may still be associated with groups. 
 * Help-related items on the homepage have been moved to a dropdown item revealed by clicking the homepage menu item "help." 
 * Group announcements have been removed. Group administrators who wish to make announcements may do so by making forum posts.  
 * The "Docs" plugin has been redesigned in a new, minimal style that transforms formerly tabular data into easier-to-read prose.  

# Content Changes for Minimal Theme Rollout

 * edit HTML on “Publications” page, remove `<b>` tags and `<strong>` tags. This makes font weights more consistent. 
 * remove wiki 
    - disable “Wiki” plugin
    - Dashboard → Appearance → Menus: remove “wiki” from “CBOX submenu” and “Inside Header Navigation” 
    - remove wiki page from Dashboard -> Pages
 * change "blogs" to "sites" 
    - Dashboard → Appearance → Menus → "Inside Header Navigation" and "CBOX Submenu": change "blogs" to "sites" 
    - MLA Commons -> Dashboard -> Pages -> edit "Blogs" page: change title and slug from "blogs" to "sites"
      - Create new page with title and slug "blogs" and use "Sites Redirect" template.
 * deactivate and remove BP-Group-Announcements plugin
 * network-deactive and remove TM-Replace-Howdy plugin
 * remove UserVoice plugin
 * get [forked version of BP-Docs](https://github.com/mlaa/buddypress-docs) and switch it to minimal theme
 * “Homepage Middle” widget: use “(BuddyPress) Recently Active Members” widget
    - max members: 40
 * “Homepage Right” widget: use “Recent Networkwide Blog Posts” 
    - uncheck “link widget title”
    - max posts to show: 3
 * “Groups Sidebar,” “Member Sidebar,” “Activity Sidebar,” “Blog Sidebar," "Sitewide Sidebar" widgets: use blank “Text” widget
 * change template for Publications page to Publications template
 * “Help Dropdown” widget: use Katina’s HTML text
 * make Forums the default group landing page: 
    - Network Dashboard -> Commons in a Box -> Settings -> Group Forum Default Tab: check 'On a group page, set the default tab to "Forum" instead of "Activity".' 
 * pull in changes from master branch of cbox-mla-blog repo to update blog theme for News and FAQ sites
 * Commons Dashboard -> Feature Slider -> edit slides -> wrap photo credits in `<span class="credits">` 
 * Delete caches! 

# Commons-in-a-Box MLA Child Theme

This theme was developed for [_MLA Commons_][1]. It functions as a child 
theme of the [official Commons-in-a-Box theme][2].

The source code of this theme is released under the GPLv2 (see LICENSE.txt). 
The images are included for reference but remain the property of the Modern 
Language Association.

[1]: http://commons.mla.org
[2]: https://github.com/cuny-academic-commons/cbox-theme

# Blog Writeup

##Short
The _MLA Commons_ sports a brand new theme this week. Many months in the making, it reimagines the user interface and modernizes the look with a breathable, flat design. The layout, typography, and color scheme have all been redesigned. Some features have been consolidated for simplicity and usability: the site-wide wiki has been replaced with group docs, for instance, and group announcements have been removed in favor of forum posts. The WordPress plugins that constitute the _Commons_ have all been updated to their latest versions, bringing with them new features, bug fixes, and other improvements. Best of all, all of this software is free and open-source, released under the GNU Public License, and available via the [MLA Github repository](https://github.com/mlaa). Read more about the new theme at (where?), and see it for yourself at http://commons.mla.org.  

##Long
The _MLA Commons_ has a fresh new theme this week, designed with the principles of simplicity, clarity, and ease-of-use. The boxes and lines of the previous design have been removed, giving the site a freer, more open feel. Gradients, textures, and 3-D elements have also been removed, in order to flatten the design and bring the focus back to the content. The bright red and orange color scheme has been replaced with a subtler and more legible palette of grays and blues, and the typography has been renovated, using typefaces from _Adobe Typekit_. We think you'll love the new look. Following is an in-depth description of the aesthetic and technical changes you'll notice in the new theme. 

Much of the _Commons_ user interface has been simplified. Where previously there had been up to three different buttons on a page which would take you to a group's Email Options section, or two areas for navigating through the pages of a forum, redundant elements have been removed, making the site's organizational hierarchy clearer and the interface less cluttered. Some functions of the _Commons_ have also been consolidated, streamlining the interface while maintaining the same functionality. The site-wide Wiki page, for instance, has been removed in favor of the existing Forum and Docs group functions. Those who have created Wiki pages will find their documents in their profile pages, under the Docs tab. Similarly, the group announcements function has been removed to promote greater use of the group forums. Group administrators that wish to leave an announcement for a group may create a forum post and mark the post as "sticky," which will make the post appear at the top of the topic list. Soon, file attachments to group docs will also be disabled in favor of the existing Files group function. While the consolidation of these functions may be surprising at first to seasoned visitors of the _Commons_, we believe that they will ultimately enhance the simplicity and usability of the site. 

With the release of this new theme, the WordPress plugins that constitute the _Commons_ software suite have all been updated to their latest versions. These changes bring fixes for a number of known bugs, and with the release of BuddyPress 2.0, page loads that are orders of magnitude faster than before. For a more complete list of the changes in these updates, see the changelogs for [Commons-in-a-Box 1.0.8](http://commonsinabox.org/archives/4830), [BuddyPress 2.0](http://codex.buddypress.org/developer/releases/version-2-0/), [BBPress 2.5.4](http://bbpress.org/blog/2014/06/bbpress-2-5-4-security-bugfix-release/), [BuddyPress Docs 1.7.0](https://wordpress.org/plugins/buddypress-docs/changelog/), [BP Group Documents 1.7](http://wordpress.org/plugins/bp-group-documents/changelog/), and [the other peripheral plugins packaged in Commons-in-a-Box](http://commonsinabox.org/documentation/plugins). 

Since the _MLA Commons_ runs almost entirely on free and open-source software, we've been able to contribute many of our improvements back to the greater community, so that institutions using the same software suite might benefit from our development. [Our simplified theme for BuddyPress Docs](https://github.com/mlaa/buddypress-docs), for instance, is awaiting review from its package maintainers, and should provide future users of the plugin the option to use our cleaned-up user interface. Bugfixes and improvements of ours can also be found in the most recent versions of the [Commons-in-a-Box theme](https://github.com/cuny-academic-commons/cbox-theme), [BuddyPress Group Documents](http://wordpress.org/plugins/bp-group-documents/changelog/), and the [WordPress CLI tool's BuddyPress plugin](https://github.com/boonebgorges/wp-cli-buddypress), among others. For a full list of our contributions, and to try out our latest code, visit the [MLA's repositories on GitHub](https://github.com/mlaa). 
