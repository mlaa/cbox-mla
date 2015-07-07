#!/bin/bash

# Script for rolling out this theme.

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

# Get a copy of BuddyPress Global Search
cd $P
git clone https://github.com/mlaa/buddypress-global-search.git

# Activate it
wp plugin activate buddypress-global-search

# Get a copy of Blog Avatar
git clone https://github.com/buddydev/blog-avatar

wp plugin activate --network blog-avatar

# Download a copy of Buddypress Profile Progression,
# unzip it, and remove the zip file:
wget https://downloads.wordpress.org/plugin/buddypress-profile-progression.zip && unzip buddypress-profile-progression.zip && rm buddypress-profile-progression.zip

# Now activate!
wp plugin activate buddypress-profile-progression
