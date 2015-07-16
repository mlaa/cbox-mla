#!/bin/bash -u

# Script for rolling out this theme.
# Requires: wp-cli (should be already installed)

# Check to make sure either the P env variable is set, or there's a plugins
# directory at /srv/www/commons/current/web/app/plugins.
if [ -z "$P" ] || [ ! -d /srv/www/commons/current/web/app/plugins ]
then
	echo "You need to set the location of your plugins directory before running this script."
	exit 1
fi

if [ -z "$1" ]
then
	echo "Please specify the server address for your WP install, i.e. ./rollout.sh commons.mla.org"
fi

SERVER=$1

# Temporarily move widgets to "wp_inactive_widgets" area so we can save their content while
# we change the theme.
# Dashboard Widgets: 1. rss-5: "News from the MLA"; 2. text-15: "MLA Sites"; 3. links-2: "Member Resources"
# Footer Widgets: 1. text-6: "Contact Us"; 2. rss-3: "FAQ"; 3. text-10: "Get Help"
for widget in rss-5 text-15 links-2 text-6 rss-3 text-10
do
	wp widget move $widget --sidebar-id=wp_inactive_widgets
done

# Delete "More Resources" widget
wp widget delete text-13

# Activate this theme.
wp theme activate tuileries --url=$SERVER

# Add profile area to dashboard sidebar
wp widget add mla_bp_profile_area sidebar-primary

# Add CBOX menu to main nav area
wp menu location assign inside-header-navigation primary_navigation

# Get ID of "Activity" menu item
ACTIVITY_ID=`wp menu item list inside-header-navigation | grep Activity | cut -f1`

# Remove "Activity" since we'll be effectively replacing it with the dashboard
wp menu item delete $ACTIVITY_ID

# Make a new page called "Dashboard"
DASHBOARD_ID=`wp post create --post_type=page --post_title=Dashboard --post_status=publish --porcelain`

# Make a menu item that corresponds with our newly-created page
wp menu item add-post inside-header-navigation $DASHBOARD_ID --title=Dashboard --position=0

# --------- Plugins ----------

# Get the BuddyPress Global Search plugin and activate it
cd $P #plugins
git clone https://github.com/mlaa/buddypress-global-search.git
wp plugin activate buddypress-global-search

# Get and activate the Blog Avatar plugin
#git clone https://github.com/buddydev/blog-avatar
#wp plugin activate --network blog-avatar

# Download a copy of Buddypress Profile Progression,
# unzip it, and remove the zip file:
wget https://downloads.wordpress.org/plugin/buddypress-profile-progression.zip && unzip buddypress-profile-progression.zip && rm buddypress-profile-progression.zip

# Now activate!
wp plugin activate buddypress-profile-progression
