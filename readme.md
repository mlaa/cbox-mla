# Commons-in-a-Box MLA Child Theme

This theme was developed for [_MLA Commons_][1]. It functions as a child
theme of the [official Commons-in-a-Box theme][2].

The source code of this theme is released under the GPLv2 (see LICENSE.txt).
The images are included for reference but remain the property of the Modern
Language Association.

[1]: http://commons.mla.org
[2]: https://github.com/cuny-academic-commons/cbox-theme

# Current and Past Releases

## Changes in Version 2.3.1 Bugfix Release
 * Minor bug fixes to the behavior of the Invite Anyone plugin, the BP Group Email Subscription plugin, and [the official blog theme](https://github.com/mlaa/cbox-mla-blog/issues?q=milestone:"2.3.1+Bugfix+Release"+is:closed).
 * Search boxes were added to all official blogs.
 * A more complete list may be found in the [closed issues for this milestone](https://github.com/mlaa/cbox-mla/issues?q=milestone:"2.3.1+Bugfix+Release"+is:closed).

## Changes in Version 2.3.0 "Sentimental"

 * Member names, email addresses, and institutional affiliations are now dynamically updated from the MLA member database. These fields are no longer editable in portfolios, but remain editable on mla.org.
 * Group membership data is also dynamically synced with the MLA member database.
 * Members may now invite to their groups not only their contacts, but anyone else, as well.

## Changes in Version 2.2.0 "Epistolary"

 * The black bar at the top of the page has been simplified. Clicking on your avatar now takes you to your portfolio, and clicking "My Commons" takes you to your activity page.
 * WordPress has been updated to version 4.1. Commons-in-a-Box has been updated to [version 1.0.9](https://wordpress.org/plugins/commons-in-a-box/changelog/), which brings with it [BuddyPress version 2.1.1](https://codex.buddypress.org/releases/version-2-1-1/), BuddyPress Docs 1.8.5, and many others. Among BuddyPress 2.1 features is autosuggestion for @-mentions.
 * To avoid naming collisions with MLA forums, group forums have been renamed to "discussions."

## Changes in Version 2.1.1 Bugfix Release

 * Member directory alphabetical listing now sorts by last name instead of first.
 * Activity comments now have a button for canceling them, replacing the message "or press esc to cancel."
 * The "tour" plugin that introduces the Activities section to first-time users has been disabled, fixing some issues with Internet Explorer.
 * Changes you make to your portfolio will now show up in your activity stream.

## Changes in Version 2.1.0 "Picaresque"

 * User profiles are now highly configurable "portfolios," thanks to a plugin initially developed for the CUNY Academic Commons. Members can now add their positions, publications, educational data, and custom fields to their profiles, and rearrange their order. This portfolio is displayed in a clean, clutter-free interface that prints nicely to paper or PDF.
 * Buddypress-docs has a cleaned-up interface. It no longer gives users the arguably unnecessary options of changing the associated group of a doc, or of changing its access rights or parent doc.
 * WordPress has been updated to 4.0. Additional version upgrades include:
  - Akismet -> 3.0.2
  - BP Group Documents -> 1.6
  - CommentPress Code -> 3.5.9
  - WP Accessibility -> 1.3.7

## Changes in Version 2.0 "Courtship"

 * The site has been given a new, flat theme. Gradients, textures, and 3D buttons have been replaced by simple, minimalistic elements. The colorscheme has been also simplified, favoring desaturated colors and grayscale over the bright reds and oranges of the previous theme.
 * Many redundant UI elements have been removed. Some subscription links have been removed in favor of the "Email Subscriptions" group tab. Pagination buttons that previously appeared on the top and bottom of any content area now only appear at the bottom. Forums titles that bear the same titles as their groups have been removed.
 * The default homepage for groups is now "Forum." The group tab previously called "Home" is now more accurately termed "Activity."
 * The sitewide wiki has been removed. However, documents created there are still available in user profiles, and may still be associated with groups.
 * Help-related items on the homepage have been moved to a dropdown item revealed by clicking the homepage menu item "help."
 * Group announcements have been removed. Group administrators who wish to make announcements may do so by making forum posts.
 * The "Docs" plugin has been redesigned in a new, minimal style that transforms formerly tabular data into easier-to-read prose.

# Rollout Todo for 2.3.1

 * [ ] run the add-search-bars.sh script from cbox-mla-blog on each official blog
 * [ ] replace buddypress-group-email-subscription with our fork, as a temporary measure. (Our changes have been merged into the project, but the changes haven't made their way downstream to CBOX yet.)
