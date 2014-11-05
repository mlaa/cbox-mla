# Changes for 2.1.0 Rollout

 * [x] 1105 11:41 remove photo credits from slider posts (they're handled by the alt text now)
 * [x] 1105 11:41 upgrade WP to 4.0 and upgrade plugins
   - [x] 1105 11:41 may require changing permissions: `sudo chown -R www-data:www-data ~/app`
   - [x] 1105 11:41 change back afterwards with: `sudo chown -R admin:admin ~/app`
   - [x] 1105 11:41 but the uploads dir still needs to be writeable. 
 * [x] 1105 11:41 ensure that we're using the mlaa repo versions of buddypress-docs, bp-group-documents, bp-groupblog. 
 * [x] 1105 11:41 check out CACAP from our repo: `git clone https://github.com/mlaa/cac-advanced-profiles.git`
 * [x] 1105 11:41 ensure we're on the correct branches
   - [x] 1105 11:41 cbox-mla: master
   - [x] 1105 11:41 cac-advanced-profiles: master
   - [x] 1105 11:41 commons: master  
   - [x] 1105 11:41 bp-groupblog: master
   - [x] 1105 11:41 buddypress-docs: master
   - [x] 1105 11:41 bp-group-documents: master
   - [x] 1105 11:41 mla-admin-bar: master
 * [x] 1105 11:41 disable attachments in BP Docs. (Dashboard -> Buddypress Docs -> Settings -> Disable Attachments) 
 * [x] 1105 11:41 get CACAP dependencies via git submodules: `git submodule update --init --recursive` 
 * [x] 1105 11:41 network-enable CAC-Advanced-Profiles
 * [x] 1105 11:41 Network Dashboard -> Users -> CAC Advanced Profiles -> Tab: Profile Header (Public): drag the following fields to their places: 
   - Institutional or Other Affiliation -> Brief Descriptor
   - Title -> About You
 * [x] 1105 11:41 Network Dashboard -> Users -> CAC Advanced Profiles -> Tab: Profile Header (Edit Mode): make sure fields are in this order: 
   - Left column: Name, Institutional or Other Affiliation
   - Right column: Title
 * [x] 1105 11:41 Network Dashboard -> Users -> Portfolio Fields -> Title -> Edit: 
   - description: e.g., &quot;Adjunct Instructor&quot; 
 * [x] 1105 11:41 Network Dashboard -> Users -> Profile Fields -> Institutional or Other Affiliation -> Edit: 
   - description: e.g., &quot;College of Yoknapatawpha&quot; 
 * [x] 1105 11:41 make links work for things in the slider, updates blog post, and help blog post. 

##Optional Changes
 * [ ] apply [Buddypress patch #5858](https://buddypress.trac.wordpress.org/ticket/5858), which fixes #104 with site searches incorrectly reporting the number of results. 

# Commons-in-a-Box MLA Child Theme

This theme was developed for [_MLA Commons_][1]. It functions as a child 
theme of the [official Commons-in-a-Box theme][2].

The source code of this theme is released under the GPLv2 (see LICENSE.txt). 
The images are included for reference but remain the property of the Modern 
Language Association.

[1]: http://commons.mla.org
[2]: https://github.com/cuny-academic-commons/cbox-theme

# Changes in Version 2.1.0 

 * User profiles are now highly configurable "portfolios," thanks to a plugin initially developed for the CUNY Academic Commons. Members can now add their positions, publications, educational data, and custom fields to their profiles, and rearrange their order. This portfolio is displayed in a clean, clutter-free interface that prints nicely to paper or PDF.  
 * Buddypress-docs has a cleaned-up interface. It no longer gives users the arguably unnecessary options of changing the associated group of a doc, or of changing its access rights or parent doc.  
 * WordPress has been updated to 4.0. Additional version upgrades include: 
  - Akismet -> 3.0.2
  - BP Group Documents -> 1.6
  - CommentPress Code -> 3.5.9
  - WP Accessibility -> 1.3.7

# Changes in Version 2.0 

 * The site has been given a new, flat theme. Gradients, textures, and 3D buttons have been replaced by simple, minimalistic elements. The colorscheme has been also simplified, favoring desaturated colors and grayscale over the bright reds and oranges of the previous theme. 
 * Many redundant UI elements have been removed. Some subscription links have been removed in favor of the "Email Subscriptions" group tab. Pagination buttons that previously appeared on the top and bottom of any content area now only appear at the bottom. Forums titles that bear the same titles as their groups have been removed. 
 * The default homepage for groups is now "Forum." The group tab previously called "Home" is now more accurately termed "Activity." 
 * The sitewide wiki has been removed. However, documents created there are still available in user profiles, and may still be associated with groups. 
 * Help-related items on the homepage have been moved to a dropdown item revealed by clicking the homepage menu item "help." 
 * Group announcements have been removed. Group administrators who wish to make announcements may do so by making forum posts.  
 * The "Docs" plugin has been redesigned in a new, minimal style that transforms formerly tabular data into easier-to-read prose.  
