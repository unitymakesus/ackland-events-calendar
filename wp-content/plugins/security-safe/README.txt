=== WP Security Safe ===
Contributors: sovstack, freemius
Tags: wp security plugin, privacy, security audit, file permissions, brute force login, firewall, disable XML-RPC, security
Requires at least: 3.5
Requires PHP: 5.3
Tested up to: 5.2.4
Stable tag: trunk

This WordPress security plugin helps you quickly audit, harden, and secure your website.

== Description ==

### WP FIREWALL
* Detects and Logs Threats
* Add Firewall Rules to Whitelist and Blacklist IP Addresses With Internal Notes
* Historical Log of Firewall Blocks With Visual Chart

### WP LOGIN SECURITY
* Disable XML-RPC.php
* Brute Force Protection
* **[Pro]** Import/Export Settings
* **[Pro]** Priority Support

### WP PRIVACY
* Hide WordPress CMS Version
* Hide Script Versions
* Make Website Anonymous During Updates
* **[Pro]** Make Theme Versions Private
* **[Pro]** Make Plugin Versions Private

### WP CORE, THEME, AND PLUGIN FILE SECURITY
* Enable Automatic Core, Plugin, and Theme Updates
* Disable Editing Theme Files
* Audit & Fix File Permission
* **[Pro]** Bulk Fix File Permissions
* **[Pro]** Automatically Fix Theme/Plugin File Permissions

### OTHER FEATURES
* 404 Error Logging
* Content Copyright Protection
* Audit Hosting Software Versions
* Various Logs and Charts
* Turn On/Off All Security Policies Easily

> Note: [Upgrade to WP Security Safe Pro](https://checkout.freemius.com/mode/dialog/plugin/2439/plan/3762/) to unlock advanced Pro features.

### WP Security Plugins Are Complicated

Every WordPress security plugin becomes more complicated and bloated as more features are added. As a plugin's code grows, it consumes more time to load, thus slowing down your website. WP Security Safe's purpose is to protect your website from the majority of threats with minimal impact on website load time. We constantly test our load performance to ensure our features to ensure it continues to run fast and lean.

### LANGUAGE SUPPORT

* English (default)
* Spanish
* [Translate this plugin in your language.](https://translate.wordpress.org/projects/wp-plugins/security-safe)

== Videos ==

https://player.vimeo.com/video/360060065

== Screenshots ==

1. File Permissions
2. Login Attempts
3. Firewall Blocks

### Follow Us: 

Twitter: [Follow WP Security Safe](https://twitter.com/wpSecuritySafe/)
Website: [WP Security Safe](https://wpsecuritysafe.com)

== Installation ==

1. Install WP Security Safe automatically or by uploading the ZIP file to your plugins folder. 
2. Activate the WP Security Safe on the 'Plugins' admin page. When activated the plugin settings are initially set minimum security values.
3. Navigate to the Plugin Settings by clicking on the WP Security Safe menu located on the left side admin panel.
4. On Plugin Settings page, you will notice an icon menu at the top of the page. Navigate through all of them and review and change settings as they pertain to your site's needs.
5. Test your site thoroughly. If you notice that your site is not functioning as expected, you can turn off each type of security policy (Privacy, Files, User Access, etc.) by navigating to each page and disabling the policy type. If necessary, you can disable all policy types at once on Plugin Settings page.
6. If you are having issues, reach out for help in the forum before leaving a review. 

== Changelog ==

= 2.2.3 (High Priority) =
*Release Date - 21 October 2019*

* Bug Fix: Local Login feature would not allow logins via front-end login forms created with wp_login_form(). Thank you @alfonsoborghi for the bug report.
* Bug Fix: An admin notice was not properly counting directories with OK permissions on the Files admin page.
* Bug Fix: Stats were attempting to record during system activities and thus throwing "WordPress database error Duplicate entry"
* Bug Fix: Search and bulk delete on the Firewall Allow/Deny admin page would trigger false flag admin errors regarding IP validation.
* Bug Fix: Sort filters on the Firewall admin page would trigger false flag admin notices.
* Bug Fix: Body class was being added to every page in the admin.
* Bug Fix: Duplicate policy disabled admin notices were appearing on admin pages using wp_list_table()
* Security: Added nonce to reset and save settings
* Security: Added nonce to add / remove Firewall rules
* Improvement: Renamed nonces to prevent conflicts with other plugins
* Improvement: Performance tuning to reduce function calls
* Improvement: Changed default settings to inlcude disabling XML-RPC and force Local Logins.
* Improvement: Fixed a PHP Warning.
* Improvement: Updated PHP version checks
* Tested up to: 5.2.4

= 2.2.2 (Medium Priority) =
*Release Date - 9 September 2019*

* Bug Fix: Cron cleanup scripts were failing.
* Improvement: Fixed two PHP errors.

= 2.2.1 (Medium Priority) = 
*Release Date - 5 September 2019*

* Updated Feature: The local login feature was improved to be more reliable.
* Bug Fix: The local login feature was causing server errors on Pantheon servers. Thanks FullSteam Labs for the bug report.
* Bug Fix: The blacklist check was not functioning properly.
* Bug Fix: The sidebar was appearing on tabs that were full width of the screen.
* Bug Fix: The charts would not load in a local development environment without an active internet connection.
* Bug Fix: Fixed minor styling anomalies when viewing admin in Spanish
* Pro Bug Fix: File corrupt error displayed if imported settings already matched the current settings.
* Improvement: Added more i18n language support.
* Improvement: The form that adds an IP to the firewall is more user-friendly
* Improvement: Added ability to make notes when manually adding IPs to the firewall
* Improvement: Fixed some minor PHP notices.
* Improvement: Added 'Status' column and filter to Firewall page.
* Improvement: Added additional information to the 'Details' column.
* Improvement: Converted the Firewall page to include all detected threats
* Improvement: Added Spanish Translations
* Improvement: Updated logo and minor styling
* Improvement: Updated PHP version checks
* Security: Added additional sanitization for logging
* Tested with ManageWP Version 4.9.1
* Tested up to: 5.2.3

= 2.1.1 (Medium Priority) = 
*Release Date - 15 July 2019*

* Bug Fix: Session handling conflicted with some admin features in oddball scenarios
* Improvement: Fixed a PHP Warning

= 2.1.0 (Medium Priority) = 
*Release Date - 15 July 2019*

* Bug Fix: WP Cron activities were not recording to activity log (Only visible in debug mode)
* Bug Fix: Charts do not display properly until an entry has been initially added to stats.
* Bug Fix: Styling issue with wp_table_list pagination
* Bug Fix: Search field not working on log tables
* Bug Fix: Admin notices would not display for policies that were disabled or if wp cron was disabled using DISABLE_WP_CRON.
* Bug Fix: The admin notices were not displaying bold properly
* Improvement: Fixed some PHP notices, thanks to Charles Suggs
* Improvement: Excluded user roles super admin, administrator, editor, and author from text highlighting and right-click content protection while logged in
* Improvement: Updated SDK
* Improvement: Implemented better session handling for increased load performance
* Improvement: Added more i18n language support.

= 2.0.2 (High Priority) = 
*Release Date - 10 June 2019*

* Improvement: In some outlying circumstances, the DB tables do not get created. A failsafe was added to create the tables if the insertion of a record failed.
* Bug Fix: The new DB tables get created if the plugin is disabled and then enabled, but not after an update process.

= 2.0.0 (Low Priority) = 
*Release Date - 10 June 2019*

* Bug Fix: Security Safe would unintentionally recommend a lower version of PHP if the user had a newer version higher than the known versions.
* Added Feature: Log 404 Errors
* Added Feature: Log Successful and Failed Logins
* Added Feature: Manage Blacklist / Whitelist IP Addresses
* Added Feature: Log Blocked Access Attempts
* Added Feature: Log Security Vulnerability Probing
* Added Feature: Statistics and Charts
* Improvement: Force Local Logins setting now records blocked attempts.
* Improvement: Cleaned up some PHP Notices in error log.
* Improvement: Updated namespacing to support future plugins
* Improvement: Updated directory structure for better scalability
* Improvement: Minor code standardization updates
* Improvement: Performance testing and optimization
* Improvement: Minor styling updates
* Improvement: Updated PHP version checks
* Security: Added additional security to prevent XSS
* Tested up to: 5.2.1

= 1.2.3 (High Priority) = 
*Release Date - 1 March 2019*

* Security: Updated SDK
* Improvement: Updated PHP version checks
* Tested up to: 5.1

= 1.2.2 (High Priority) = 
*Release Date - 9 December 2018*

* NOTE: PHP 5.6 and 7.0 are now identified as no longer supported due to end of life.
* Improvement: Converted plugin variables to constants for efficiency and updated all references
* Improvement: Updated PHP version checks
* Tested up to: 5.0

= 1.2.1 (Medium Priority) =
*Release Date - 22 September 2018*

* Bug Fix: WP-CLI does not properly set variables and causes fatal error when attempting to load the plugin. Thank you Brian Medlin.

= 1.2.0 (High Priority) =
*Release Date - 22 September 2018*

* Improvement: Automatically display file permission issues at the top of the list of files.
* Improvement: Removed Composer autoloading to increase efficiency
* Improvement: Reduced PHP memory usage to increase performance
* Improvement: Added Freemius integration
* Improvement: Updated PHP version checks
* Improvement: Minor UI styling
* Bug Fix: UI Styling issues in WP 3.5
* Bug Fix: Some WP-CLI commands return blank responses due to plugin killing PHP process. Thank you Brian Medlin for the discovery.
* Added Feature: Remove WP Version in wp-admin
* Pro: Added Feature: Import / Export Settings
* Pro: Added Feature: Automatic fix plugin permissions on plugin updates.
* Pro: Added Feature: Automatic fix theme permissions on theme updates.
* Pro: Added Feature: Automatically hide files with permissions that cannot be changed.
* Compatibility testing with WordPress version 3.5
* Tested up to: 4.9.8

= 1.1.13 (Low Priority) =
*Release Date - 17 August 2018*

* Bug Fix: Individual policy disabled notice was visible when all notices were disabled.
* Added Feature: Clear PHP Cache Before Updates
* Improvement: Updated descriptions of features in settings.
* Improvement: Updated PHP version checks.

= 1.1.12 (Low Priority) =
*Release Date - 4 July 2018*

* NOTICE: Update to this version if you are having issues with your settings.
* Improvement: Automatically detects if settings are corrupted and reset them to default values.
* Improvement: Updated the initial/default settings.
* Improvement: Updated PHP version checks.

= 1.1.11 (High Priority) =
*Release Date - 3 July 2018*

* Bug Fix: Cannot change file permissions. Bug introduced in version 1.1.10.
* Bug Fix: File Policy settings get cleared out when attempting to change file permissions. Bug introduced in version 1.1.10.
* Bug Fix: Initial settings were not properly being set. Bug introduced in version 1.1.10.
* Bug Fix: debug.log file does not remove itself when debugging is turned off.
* Improvement: Cleaned up some PHP Notices in error log.
* Improvement: Added additional logging for troubleshooting bugs.

= 1.1.10 (Low Priority) =
*Release Date - 26 June 2018*

* Bug Fix: After a group of policies is enabled, the disabled warning notice still appears immediately after saving, but goes away after navigating to another page.
* Bug Fix: When all security policies are disabled, the notice was incorrectly referring to "General Settings" which no longer exists.
* Bug Fix: When a group of policies is disabled, the warning notice would instruct the user to go to the relative settings page even if the user was already on that specific page.
* Bug Fix: Page would not go back to the top when a page anchor was used in the URL and settings were saved.
* Improvement: Improved usability by Adding color indicators within the settings tab to match the notices related to the specific setting.
* Improvement: Added Priorities to the changelog to indicate the urgency of an update.
* Thank you @df03472 for notifying us about the bugs above.

= 1.1.9 (Medium Priority) =
*Release Date - 14 June 2018*

* Bug Fix: Security Safe Admin page styling breaks when other plugins add classes to the body.

= 1.1.8 (High Priority) =
*Release Date - 12 June 2018*

* Bug Fix: Reference to wp-content was incorrect as a fallback default value when using custom plugin directory outside of wp-content directory.
* Security: Prevent Administrators of a multisite environment from modifying settings unless they are Super Admin.
* Added Support: Add support for backup logging.
* Tested Multi-site Compatibility
* Improvement: Increased plugin load efficiency

= 1.1.7 (High Priority) =
*Release Date - 06 June 2018*

* Added Feature: Hide password protected posts from public queries.
* Bug Fix: Changing permissions of the home directory has been reported to cause issues when loading the website. Use default permissions set by the host. 
* Bug Fix: Duplicate notices were being displayed in the Files section.
* Bug Fix: Fixed broken link in notice message.
* Improvement: Moved certain notices regarding features to the specific areas of each settings tab.
* Improvement: Updated PHP version checks
* Improvement: Minor grammatical corrections
* Tested up to version 4.9.6

= 1.1.6 (Low Priority) =
*Release Date - 08 May 2018*

* Bug Fix: If a child theme is used, only the parent theme files were appearing in the theme files permissions audit list.
* Improvement: Updated PHP version checks

= 1.1.5 (High Priority) =
*Release Date - 23 April 2018*

* Added Feature: Prevent Access to readme.html and license.txt core files.
* Added Feature: Notifications for file permissions displaying totals of vulnerable files.
* Improvement: Updated file permission status color scheme to match WP notifications.
* Improvement: Updated PHP version checks and added notifications.
* Security: Added additional security measures when handling $_POST variables.
* Bug Fix: Changed status of files from "good" to "secure" for all files that should only be 644 permissions.
* Bug Fix: When using the Hide Script Versions feature, CSS and JS files cache would not update for the browser until the next day after a plugin or theme was updated.
* Bug Fix: After the user pressed the Reset Settings button, the content on the page would not display.
* Added support for Security Safe Pro Add-on.
* Tested up to version 4.9.5

= 1.1.3 (Medium Priority) =
*Release Date - 25 February 2018*

* Added Feature: Hide WordPress Version from the RSS feed.
* Added Feature: Hide Script Versions from enqueued CSS and JS files
* Bug Fix: "Hide WordPress" feature stays enabled despite the setting's value
* Bug Fix: An error is displayed when saving settings if the settings are the same in the database.

= 1.1.2 (Medium Priority) =
*Release Date - 20 February 2018*

* Bug Fix: Icon CSS conflict with other icon plugins

= 1.1.1 (Low Priority) =
*Release Date - 20 February 2018*

* Added Feature: Disable text highlighting to deter copying content
* Added Feature: Disable right-clicking to deter copying content
* Added Feature: Fix file permissions
* Added Feature: Make website anonymous when checking for updates
* Added Feature: Plugin information tab for debugging purposes
* Bug Fix: Database was including nonce and referrer when saving settings
* Improvement: Update UI styling
* Thank you @epohs and @isabisa for file permissions UI testing and feedback
* Tested up to: 4.9.4

> NOTE: [View full WP Security Safe changelog](https://sovstack.com/wp-security-safe/changelog/).
