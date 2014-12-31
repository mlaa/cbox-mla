# Commons-in-a-Box MLA Child Theme

This theme was developed for [_MLA Commons_][1]. It functions as a child 
theme of the [official Commons-in-a-Box theme][2].

The source code of this theme is released under the GPLv2 (see LICENSE.txt). 
The images are included for reference but remain the property of the Modern 
Language Association.

[1]: http://commons.mla.org
[2]: https://github.com/cuny-academic-commons/cbox-theme

# Changes planned for 2.3.0 "Sentimental" 
 * [ ] upgrade to 2.0 version of cbox-mla-blog 
 * [ ] complete switch to new API endpoints

# 2.2.2 "Epistolary" rollout todo

 * [ ] Pull in changes from the following repos: 
  - [ ] commons
  - [ ] cbox-mla
  - [ ] cac-advanced-profiles 
  - [ ] mla-admin-bar

# Content Changes Needed for 2.2.0 Rollout 

The changes in the admin bar require some updates to the how-to blog: 
 * [ ] http://howtouse.commons.mla.org/2013/05/31/creating-an-account-on-mla-commons/  
  - [ ] "Click where it says Log in in the top left side of the page" -- this is now the top right. 
  - [ ] the screenshot that directly follows the above line should be updated 
  - [ ] the last screenshot should be updated 
 * [ ] http://howtouse.commons.mla.org/2014/11/05/editing-your-portfolio/ 
  - [ ] all screenshots containing the black admin bar should be updated
 * [ ] http://faq.commons.mla.org/2014/08/12/profile-update/ 
  - [ ] "the 'Activity' page" is now called "My Commons," and is accessible from the top right of each page (the right of the admin bar) 
 * [ ] http://howtouse.commons.mla.org/2014/03/21/controlling-group-notifications/
  - [ ] the first and second screenshots should be updated 
 * [ ] http://howtouse.commons.mla.org/2013/04/04/how-to-find-things-on-mla-commons/  
  - [ ] the first screenshot needs updating

# Changes in Version 2.1.1 Bugfix Release

 * Member directory alphabetical listing now sorts by last name instead of first. 
 * Activity comments now have a button for canceling them, replacing the message "or press esc to cancel." 
 * The "tour" plugin that introduces the Activities section to first-time users has been disabled, fixing some issues with Internet Explorer. 
 * Changes you make to your portfolio will now show up in your activity stream. 

# Changes in Version 2.1.0 "Picaresque" 

 * User profiles are now highly configurable "portfolios," thanks to a plugin initially developed for the CUNY Academic Commons. Members can now add their positions, publications, educational data, and custom fields to their profiles, and rearrange their order. This portfolio is displayed in a clean, clutter-free interface that prints nicely to paper or PDF.  
 * Buddypress-docs has a cleaned-up interface. It no longer gives users the arguably unnecessary options of changing the associated group of a doc, or of changing its access rights or parent doc.  
 * WordPress has been updated to 4.0. Additional version upgrades include: 
  - Akismet -> 3.0.2
  - BP Group Documents -> 1.6
  - CommentPress Code -> 3.5.9
  - WP Accessibility -> 1.3.7

# Changes in Version 2.0 "Courtship" 

 * The site has been given a new, flat theme. Gradients, textures, and 3D buttons have been replaced by simple, minimalistic elements. The colorscheme has been also simplified, favoring desaturated colors and grayscale over the bright reds and oranges of the previous theme. 
 * Many redundant UI elements have been removed. Some subscription links have been removed in favor of the "Email Subscriptions" group tab. Pagination buttons that previously appeared on the top and bottom of any content area now only appear at the bottom. Forums titles that bear the same titles as their groups have been removed. 
 * The default homepage for groups is now "Forum." The group tab previously called "Home" is now more accurately termed "Activity." 
 * The sitewide wiki has been removed. However, documents created there are still available in user profiles, and may still be associated with groups. 
 * Help-related items on the homepage have been moved to a dropdown item revealed by clicking the homepage menu item "help." 
 * Group announcements have been removed. Group administrators who wish to make announcements may do so by making forum posts.  
 * The "Docs" plugin has been redesigned in a new, minimal style that transforms formerly tabular data into easier-to-read prose.  
