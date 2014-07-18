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
    - disable and remove “Sitewide Wiki” plugin
    - Dashboard → Appearance → Menus: remove “wiki” from “CBOX submenu” and “Inside Header Navigation” 
 * change "blogs" to "sites" 
    - Dashboard → Appearance → Menus → "Inside Header Navigation": change "blogs" to "sites"   
 * deactivate and remove BP-Group-Announcements plugin
 * remove UserVoice plugin
 * get [forked version of BP-Docs](https://github.com/mlaa/buddypress-docs) and switch it to minimal theme
 * “Homepage Left” widget: use “(BuddyPress) MLA Groups” widget 
    - uncheck “link widget title”
    - max groups: 8
 * “Homepage Center” widget: use “(BuddyPress) Recently Active Members” widget
    - max members: 40
 * “Homepage Right” widget: use “Recent Networkwide Blog Posts” 
    - uncheck “link widget title”
    - max posts to show: 3
 * “Groups Sidebar,” “Member Sidebar,” “Activity Sidebar,” “Sitewide Sidebar” widgets: use blank “Text” widget
 * “Help Dropdown” widget: use Katina’s HTML text
 * install Typekit
    - install Typekit plugin network-wide
    - enter embed code in Dashboard -> Settings -> Typekit: http://commons.mla.org/wp-admin/options-general.php?page=typekit-admin.php
    - do the same for subdomains news.commons, faq.commons

# Commons-in-a-Box MLA Child Theme

This theme was developed for [_MLA Commons_][1]. It functions as a child 
theme of the [official Commons-in-a-Box theme][2].

The source code of this theme is released under the GPLv2 (see LICENSE.txt). 
The images are included for reference but remain the property of the Modern 
Language Association.

[1]: http://commons.mla.org
[2]: https://github.com/cuny-academic-commons/cbox-theme
