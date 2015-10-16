#!/bin/bash
#set -e
set -x

# Script for rolling out this theme.
# Requires: wp-cli (should be already installed)

start_dir="$PWD"
project_dir=/srv/www/commons/current/
plugins_dir="$project_dir"web/app/plugins
themes_dir="$project_dir"web/app/themes

# Check to make sure either the P env variable is set, or there's a plugins
# directory at /srv/www/commons/current/web/app/plugins.
if [ ! -d "$plugins_dir" ]
then
	echo "Can't find the plugins directory. Edit the script with the current path."
	exit 1
fi

if [ -z "$1" ]
then
	echo "Please specify the server address for your WP install, i.e. ./rollout.sh commons.mla.org"
	exit 2
fi

SERVER=$1

URL="--url=$SERVER"



# Temporarily move widgets to "wp_inactive_widgets" area so we can save their content while
# we change the theme.
# Dashboard Widgets: 1. rss-5: "News from the MLA"; 2. text-15: "MLA Sites"; 3. links-2: "Member Resources"
# 4. rss-6: "New from the MLA" (copy); 5. links-3: "Member Resources" (copy)
# Footer Widgets: 1. text-6: "Contact Us"; 2. rss-3: "FAQ"; 3. text-10: "Get Help"
for widget in rss-5 text-15 links-2 text-6 rss-3 text-10 rss-6 links-3
do
	wp widget move $widget --sidebar-id=wp_inactive_widgets
done

# Delete "More Resources" widget
wp widget delete text-13

# Activate this theme.
wp theme activate tuileries $URL

# Add profile area to dashboard sidebar
wp widget add mla_bp_profile_area sidebar-primary

# Add "News from the MLA" to logged-out dashboard main area.
wp widget move rss-5 --sidebar-id=mla-dashboard-main

# Add "MLA Resources" to the logged-out sidebar.
wp widget move links-2 --sidebar-id=mla-dashboard-logged-out

# Populate footer with footer widgets
for widget in text-6 rss-3 text-10
do
	wp widget move $widget --sidebar-id=sidebar-footer
done

# Move "News from the MLA" (copy), "MLA Sites," and "Member Resources" to the tabbed sidebar,
# visible to logged-in users only.
for widget in links-3 text-15 rss-6
do
	wp widget move $widget --sidebar-id=mla-dashboard-tabbed-sidebar
done

# Add CBOX menu to main nav area
wp menu location assign inside-header-navigation primary_navigation

# Get ID of "Activity" menu item
ACTIVITY_ID=`wp menu item list inside-header-navigation | grep Activity | cut -f1`

# Remove "Activity" since we'll be effectively replacing it with the dashboard
wp menu item delete $ACTIVITY_ID

# Make a new page called "Dashboard"
DASHBOARD_ID=`wp post create --post_type=page --post_title=Dashboard --post_status=publish --porcelain`

# Make a menu item that corresponds with our newly-created page
wp menu item add-post inside-header-navigation $DASHBOARD_ID --title=Dashboard --position=1

# --------- Plugins ----------

# Get the BuddyPress Global Search plugin and activate it
# this is now already present in commons on the feature/new-cbox-mla branch
#cd $plugins_dir
#git clone https://github.com/mlaa/buddypress-global-search.git
wp plugin activate buddypress-global-search

# Get and activate the Blog Avatar plugin
#git clone https://github.com/buddydev/blog-avatar
#wp plugin activate --network blog-avatar

# Download a copy of Buddypress Profile Progression,
# unzip it, and remove the zip file:
# this is now already present in commons on the feature/new-cbox-mla branch
#wget https://downloads.wordpress.org/plugin/buddypress-profile-progression.zip && unzip buddypress-profile-progression.zip && rm buddypress-profile-progression.zip

# Now activate!
wp plugin activate buddypress-profile-progression

# this is now already present in commons on the feature/new-cbox-mla branch
#cd $plugins_dir/mla-admin-bar
#git checkout -b develop origin/develop || git checkout develop #get the develop version of mla-admin-bar

# this is now already present in commons on the feature/new-cbox-mla branch
#cd $plugins_dir/cbox-auth
#git checkout -b develop origin/develop || git checkout develop #get the develop version of cbox-auth

# --------- Styles ------------

# including files instead of submodule
#cd $project_dir
#git submodule --init --recursive # Check out a copy of the Boilerplate repo, which lives at assets/styles
#
#cd $themes_dir/cbox-mla/assets/styles
#git fetch
#git checkout -b develop origin/develop || git checkout develop

echo "Unless you're seeing errors, everything seems to have worked. Now in order for the theme to be functional, you have to build it using `npm install`, `bower install`, and `gulp`. If you're installing to a VM, you might want to do all that on your host machine, but if you're rolling out to AWS, you might want to do that in the box itself."


# And you can do that on the box itself by uncommenting these lines:

#sudo apt-get install -y npm # npm is already installed
npm install #install node dependencies
#npm install bower # already installed
bower install
gulp


# back to where we got started
cd $start_dir
