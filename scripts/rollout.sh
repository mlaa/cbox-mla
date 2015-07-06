#!/bin/bash

# Script for rolling out this theme.

# Add CBOX menu to main nav area
wp menu location assign inside-header-navigation primary_navigation

# Get ID of "Activity" menu item
ACTIVITY_ID=`wp menu item list inside-header-navigation | grep Activity | cut -f1`

# Rename "Activity" to "Home" and put it at the beginning of the menu
wp menu item update $ACTIVITY_ID --position=1 --title=Home

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

