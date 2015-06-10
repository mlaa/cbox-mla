#!/bin/bash

# Script for rolling out this theme.

# Add CBOX menu to main nav area
wp menu location assign cbox-sub-menu primary_navigation

# Get a copy of BuddyPress Global Search
cd $P
git clone https://github.com/mlaa/buddypress-global-search.git

# Activate it
wp plugin activate buddypress-global-search

# Get a copy of Blog Avatar
git clone https://github.com/buddydev/blog-avatar

wp plugin activate --network blog-avatar
