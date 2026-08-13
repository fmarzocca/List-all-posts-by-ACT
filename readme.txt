=== List all posts by Authors, nested Categories and Titles  ===
Contributors: fmarzocca
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4EH3N5XZJZCRQ
Tags: nested categories, posts, authors, titles
Requires at least: 4.0.1
Tested up to: 7.1
Stable tag: 2.10.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin lists all posts by Author, nested Categories and Title, allowing to place the lists in any page.

== Description ==

Particularly suitable to all multi-nested categories and multi-authors website, with lots of posts and complex category layout (i.e.: academic papers, newpapers articles, etc), as weel as for single-user websites (read below). This plugin allows the user to place a shortcode into any page and get rid of a long and nested menu/submenu to show all site's posts (including custom post types assigned to a standard category). A customizable selector in the page will allow the reader to select grouping by Category/Author/Title.

Shortcode's options include:

* excluding any category from the list
* excluding/including admin users from the list
* single-user website usage
* select what list(s) to display
* limit number of posts in list output 
* Show (optional) post date
* Show in reverse date order

Output grouped by Category will look like:

<pre><code>CAT1
	post1						AUTHOR
	SUBCAT1
		post2					AUTHOR
		post3					AUTHOR
		SUBCAT2
			post4				AUTHOR
			...
			...
</code></pre>

**Shortcode generator**

The plugin installs a new menu *ACT List Shortcodes* in Admin->Tools. The tool is a helper to automatically generate the required shortcode. It will parse the options and display the string to be copied and pasted into any page. Please refer to the plugin admin page for a full list of options. 

**Default manual usage:**

[ACT-list]

all categories and subcategories post, excluding administrator's posts, grouped by (upon selection) Category/Author/Title
	
**Exclude categories:**

[ACT-list exclude="cat1-slug, cat2-slug, ..."]

listed categories will be excluded. Categories must be listed with their *slugnames*.
	
**Include admin's posts:**

[ACT-list admin=1]

this option will also include all admin's posts in the list.
	
**Single-user website:**

[ACT-list singleuser=1]

this option is suited for websites with a single author (or when you don't want to show the authors). It removes grouping by Authors and any author name. This option includes the *admin=1* option, so that it will list any post in the website. You can still apply "excluding categories" option.

**Select what list(s) to display**

The parameter "show" will allow the admin to select what lists will be shown and if the dropdown selector is needed or not. i.e.:

[ACT-list show="Category, Author"]

or

[ACT-list show="Title, Category"]

Allowed terms for the "show" parameter are: *Author, Title, Category*.

**Split the lists into separate pages**

By selecting only one variable in the "show" parameter, you will be able to show only one list without the dropdown selector. This will enable you to put the 3 lists into separate pages, or separate tabs of the same page.

Page#1 (or tab#1)
[ACT-list show="Category"]

Page#2 (or tab#2)
[ACT-list show="Author"]

Page#3 (or tab#3)
[ACT-list show="Title"]

**Limit the number of posts in the lists**

If you have a large numbers of posts (>2,000), it could be convenient to limit the number of posts in the lists, including only a certain amount of the most recent posts. This can be achieved separately for the 3 lists using the following parameters: postspercategory, postsperauthor, totalpoststitle. i.e.:

[ACT-list show="Category" postspercategory="20"] will show only the 20 most recent posts for each category.

**Show posts list in reverse date order**

By default, the posts will be listed from newest to oldest. To change this behaviour, use the *reverse-date=1* parameter.

**Show posts date (v.>=2.7.0)**

The date of the post could be printed out before the post name, optionally

> If you like the plugin, feel free to rate it (on the right side of this page) or [donate via PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4EH3N5XZJZCRQ). Thanks a lot! :)

== Installation ==

1. Upload `list-all-posts-by-ACT` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the *Admin->Tools->ACT List Shortcodes* form to generate the shortcode.

== Frequently Asked Questions ==

= Can I customize the style in the lists? =
   Yes, all lists are included in *ATC-wrapper* div. You can override it in your child theme style.css
   
= Can I remove the selector, so that the plugin will display only one list? =
Yes. Just use the *show* option to display only one list, as explained in description

= What happened to the language folder? =
As of version 2.7.1, this plugin no longer ships with translation files. Translations will be available at http://translate.wordpress.org and they will be updated through WordPress process.

== Screenshots ==

1. The Shortcode generator in the Tools admin backend
2. A frontend example (single user, no other authors)
3. A frontend example (multiple users)



== Changelog ==
= 2.10.1
* Improved AJAX authorization and input validation.
* Fixed category exclusion handling and exact category slug matching.
* Fixed post ordering, URL escaping, category checkbox markup, and admin form CSS compatibility.
* Confirmed compatibility with WordPress 7.1.

= 2.10.0
* Code cleaning for WP 7.0

= 2.9.0
* security update

= 2.8.4 / 2.8.5
* sanitization

= 2.8.3
* security update

= 2.8.2
* code cleaning - removing deprecated

= 2.8.1
* bug fix for php 8

= 2.8.0
* security update

= 2.7.10
* bug fix for php 8

= 2.7.9
* wptexturize output, and change some vars name (contributed by A. Calligaris)

= 2.7.8 =
* Minor bug fixed.

= 2.7.7 =
* Added link to each Category or Author page.

= 2.7.6 =
* Minor bug fixed.

= 2.7.5 =
* Fixed bug: hide an authors name if he has no posts because of an excluded category.

= 2.7.4 =
* Fixed bug on postdate

= 2.7.3 =
* Bug fix

= 2.7.2 =
* Bug fix

= 2.7.1 =
* Removed languages folder, switched translations to translate.wordpress.org

= 2.7.0 =
* Introducing optional post-date printing in the list

= 2.6.6 =
* Minor changes. PSR2 code compliance

= 2.6.5 =
* Removed few unwanted characters

= 2.6 =
* Bug fix

= 2.5 =
* Introducing a new switch to change the posts lists in reverse date order

= 2.4 =
* removing old unwanted files

= 2.3 =
* Implemented proper localization support

= 2.2 =
* Minor code enhancements

= 2.1 =
* Now the plugin supports also custom post types, if assigned to any standard category

= 2.0 =
* Introduced new admin backend tool: shortcode automatic generator form
* Added Dutch translation (credits to Rolf van Gelder)

= 1.8.1 =
* Added Norwegian translation.

= 1.8 =
* Introduced new parameters to limit output for huge lists.

= 1.7 =
* Now all user's roles will be listed in the Author page, not only *author* roles.

= 1.6 =
* Added German localization

= 1.5 =
* Introducing the *show* option, to select what list to display
* Changing the option *single* to *singleuser* for better understanding 
* Updated description to include the new option


= 1.0 =
* Introducing the *single* option, for single-user websites
* Updated description to include the new option

= 0.5.4 =
* readme.txt improvements

= 0.5.3 =
* Added a missing <pre><code><ul></code></pre> in byTitle list

= 0.5.2 =
* few styling adjustments

= 0.5.1 =
* minor styling fixes

= 0.5 =
* First working version
