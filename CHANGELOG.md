### 1.4.6
- Fix registering rewrite rules ([#261])
- Fix missing type=button on toggle all link ([#259])
- Bring back post row actions ([#260])

### 1.4.5
- Flush Rewrite Rules later ([#257])

### 1.4.4
- Add support for internal DMs ([#253])
- Improve Debugger ([#256])

### 1.4.3
- Fix double creation of reply posts ([#245])
- Enable OPTIONS and api/apps endpoint even with rest_login_required ([#242])
- Convert urls to links when posting ([#243])

### 1.4.2
- Update search endpoints ([#238])
- Fix the EMA announcements appearing publically when debug on ([#237])

### 1.4.1
- Allow filtering whether the user is a member of the blog ([#234])

### 1.4.0
- Implement Direct Messages ([#233])
- Add following endpoint ([#228])
- Submit Post as Standard if create post format is empty ([#227])
- Fix comments context API response ([#225])
- Fix title in standard posts when HTML is submitted ([#226])

### 1.3.0
- Fix small errors on app page ([#224])
- Show internal CPTs if debug is turned on ([#223])
- Add setting for a default post format ([#221])
- Improve New Post Setting and explicit set a New post format ([#220], [#222])
- Assign all post formats to EMA announcements ([#218])

### 1.2.1
- Fixed Boost, Like and Comment notifications ([#216])
- Announce Initial and Changed App Settings ([#207], [#214])
- Added a Welcome message to your feed and explain the EMA plugin ([#210])
- Added missing types to notifications endpoint ([#215])
- Don't set any post formats as default ([#211])
- Updated Mastodon API Tester ([#209])
- Added a setting to disable status updates to be added to the feed ([#208])
- Added support for the OAuth2 Out of Band flow ([#206])

### 1.1.0
- Add an Announcement CPT so that we can inform about the changed app settings ([#204])
- Add support for viewing bookmarks and favourites ([#203])
- Fix a wrong settings link, thanks @jeherve! ([#202])
- Fix problems with user language when authorizing ([#201])

### 1.0.0
- Post through Mastodon Apps to a new post type by default ([#192])
- Explain the new hide posts setting better ([#198])
- Don't enforce parameters on non-EMA REST requests ([#197])
- Add missing CPT supports ([#196])
- Don't show reblog mapping posts ([#193])
- Update PHPCompatibility and restrict PHPUnit ([#195])
- Add missing svn in Github Actions ([#194])
- Improve REST Authentication Error Debugging ([#191])
- Use title instead of post_content when there is no line break ([#185])
- Fix wp:image block created for attachments ([#184])

### 0.9.9
- Improve targeting of CORS headers ([#181])
- Fix fatal when deleting an app ([#182])

### 0.9.8
- Fix replying on received comments via the ActivityPub plugin ([#176])

### 0.9.7
- Fixed bug where the create post type was ignored ([#175])
- Automatically convert post content to blocks, can be disabled ([#174])

### 0.9.6
- Adds steaming_api to instance_v1, props @mediaformat ([#171])
- PATCH routes: support field_attributes, props @mattwiebe ([#167])
- Make token storage taxonomies private, props @mattwiebe ([#165])
- Updated tester.html from upstream
- Introduce a Never Used label to the registered apps screen.

### 0.9.5
- Add a details link to the apps page ([#163])
- Show all comments by others as notifications ([#164])
- Update NodeInfo endpoint by @pfefferle ([#162])
- Multisite: ensure that user_ids only work for users of this site by @mattwiebe ([#158])
- Increase phpcs rules and fix them by @mattwiebe ([#160], [#155])
- Add `api/v1/accounts/update_credentials` route by @mattwiebe ([#157])

### 0.9.4
- Added a dedicated page per app in the settings. There you can set which post types should be shown in the app. Also which post type should be created for new posts. ([#154])
- Fixed authenticating Jetpack so that you can connect WordPress.com to this plugin ([#152])

### 0.9.3
- Bring back the upgrade code.

### 0.9.2
- Quick fix to disable the upgrade script to avoid errors.

### 0.9.1
- Allow an empty search type, to search in all categories ([#150]) props @pfefferle
- Don't reactivate the Link Manager ([#148])
- Avoid errors when dividing strings ([#147]) props @mattwiebe
- Don't include spam comments in the feed ([#149])
- Ensure no spaces in URLs ([#144])
- Fix some typos on the Welcome Screen ([#143])

### 0.9.0
- Complete Rewrite, started at the Cloudfest Hackathon! Props @pfefferle, @drivingralle, @kittmedia, @obenland
- Thus: all ActivityPub related tasks are handled by the ActivityPub plugin, all following-related tasks by the Friends plugin. Please make sure you have the latest version of those plugins if you want to use such features
- Reorganized settings, added a way to tester the local api ([#138], [#140])
- Allow Editing a submitted status ([#137])
- Improves to Attachments ([#132], [#136])
- Fix OAuth rewrite path ([#130])

### 0.6.6
- Implement Autoloader ([#73])
- Add scope adherence ([#76])

### 0.6.5
- Fix missing image attachments for WordPress posts, props @thatguygriff ([#72])

### 0.6.4
- Address an incompatibility with the IndieAuth plugin ([#65])

### 0.6.3
- Thanks @toolstack for lots of PRs with small fixes and enhancements!
- Fixed compatibility with version 2.0.0 of the ActivityPub plugin ([#60]) thanks @toolstack!
- Strip the protocol from the home_url ([#52]) props @toolstack
- Add additional warning about changing the default post format ([#53]) props @toolstack
- Make sure to decode html_entities for blog_info() ([#51]) props @toolstack
- Enable local/public timeline ([#62]) props @toolstack
- Check to make sure the current user has edit_post posting ([#61]) props @toolstack
- Fix duplicate line generation in whitespace code ([#55]) props @toolstack

### 0.6.2
- Add a setting to implicitly re-register the next unknown client ([#48])
- Add Instance-Endpoint Filters ([#45]) props @pfefferle

### 0.6.1
- Communicate the current settings inside Mastodon using an Announcement ([#44])
- Bring back library adaptations which hopefully solves the "No client id supplied" problem

### 0.6.0
- Use a Custom Post Type to for mapping post ids ([#42])
- Improve response time after transients expired ([#41])

### 0.5.0
- Major fixes for Ivory, now we're fullfilling (most of?) their assumptions ([#37])

### 0.4.2
-  Fix media upload in Ice Cubes and potentially other clients ([#35])

### 0.4.1
-  Fix boost attribution ([#33])

### 0.4.0
- Improve notification pagination ([#29])
- Compatibility fixes for Friends 2.6.0 ([#31])

### 0.3.6
- Improve debug logging.

### 0.3.5
- Fix little inconsistencies with min_id and max_id.
- Add a debug mode ([#23]).

### 0.3.4
- Implement min_id to avoid double postings in the IceCubes app.

### 0.3.3
- Fixes for Mastodon for iOS and Mammoth.
- Fix deleting of toots.

### 0.3.2
- Ivory should work now.
- Posting: wrap mentions and links in HTML tags
- Attachments: try harder to deduplicate them, identify attachment types
- Admin: More tools for cleaning out stale apps and tokens
- Search: You can now search for URLs so that you can reply to them

### 0.3.1
- Implemented: Attachment descriptions (and updates for it)
- Use the first line of a post as the post title if we're using a standard post format

### 0.3.0
- Implemented: search for a specific remote account to follow it
- Implemented: Notifications for mentions
- Added: Option whether replies should be posted as comments on the messages or as new posts
- Fixed: Ivory should now be able to connect

### 0.2.1
- Improve compatibility of Swift based apps
- Fix fatal error on admin page

### 0.2.0
- Post replies as comments ([#3])
- Fix a fatal when saving the default post format

[#224]: https://github.com/akirk/enable-mastodon-apps/pull/224
[#223]: https://github.com/akirk/enable-mastodon-apps/pull/223
[#222]: https://github.com/akirk/enable-mastodon-apps/pull/222
[#221]: https://github.com/akirk/enable-mastodon-apps/pull/221
[#220]: https://github.com/akirk/enable-mastodon-apps/pull/220
[#218]: https://github.com/akirk/enable-mastodon-apps/pull/218
[#216]: https://github.com/akirk/enable-mastodon-apps/pull/216
[#214]: https://github.com/akirk/enable-mastodon-apps/pull/214
[#215]: https://github.com/akirk/enable-mastodon-apps/pull/215
[#211]: https://github.com/akirk/enable-mastodon-apps/pull/211
[#210]: https://github.com/akirk/enable-mastodon-apps/pull/210
[#209]: https://github.com/akirk/enable-mastodon-apps/pull/209
[#208]: https://github.com/akirk/enable-mastodon-apps/pull/208
[#207]: https://github.com/akirk/enable-mastodon-apps/pull/207
[#206]: https://github.com/akirk/enable-mastodon-apps/pull/206
[#204]: https://github.com/akirk/enable-mastodon-apps/pull/204
[#203]: https://github.com/akirk/enable-mastodon-apps/pull/203
[#202]: https://github.com/akirk/enable-mastodon-apps/pull/202
[#201]: https://github.com/akirk/enable-mastodon-apps/pull/201
[#198]: https://github.com/akirk/enable-mastodon-apps/pull/198
[#197]: https://github.com/akirk/enable-mastodon-apps/pull/197
[#196]: https://github.com/akirk/enable-mastodon-apps/pull/196
[#192]: https://github.com/akirk/enable-mastodon-apps/pull/192
[#193]: https://github.com/akirk/enable-mastodon-apps/pull/193
[#195]: https://github.com/akirk/enable-mastodon-apps/pull/195
[#194]: https://github.com/akirk/enable-mastodon-apps/pull/194
[#191]: https://github.com/akirk/enable-mastodon-apps/pull/191
[#185]: https://github.com/akirk/enable-mastodon-apps/pull/185
[#184]: https://github.com/akirk/enable-mastodon-apps/pull/184
[#182]: https://github.com/akirk/enable-mastodon-apps/pull/182
[#181]: https://github.com/akirk/enable-mastodon-apps/pull/181
[#176]: https://github.com/akirk/enable-mastodon-apps/pull/176
[#175]: https://github.com/akirk/enable-mastodon-apps/pull/175
[#174]: https://github.com/akirk/enable-mastodon-apps/pull/174
[#173]: https://github.com/akirk/enable-mastodon-apps/pull/173
[#171]: https://github.com/akirk/enable-mastodon-apps/pull/171
[#167]: https://github.com/akirk/enable-mastodon-apps/pull/167
[#165]: https://github.com/akirk/enable-mastodon-apps/pull/165
[#163]: https://github.com/akirk/enable-mastodon-apps/pull/163
[#164]: https://github.com/akirk/enable-mastodon-apps/pull/164
[#162]: https://github.com/akirk/enable-mastodon-apps/pull/162
[#160]: https://github.com/akirk/enable-mastodon-apps/pull/160
[#158]: https://github.com/akirk/enable-mastodon-apps/pull/158
[#157]: https://github.com/akirk/enable-mastodon-apps/pull/157
[#155]: https://github.com/akirk/enable-mastodon-apps/pull/155
[#154]: https://github.com/akirk/enable-mastodon-apps/pull/154
[#152]: https://github.com/akirk/enable-mastodon-apps/pull/152
[#150]: https://github.com/akirk/enable-mastodon-apps/pull/150
[#148]: https://github.com/akirk/enable-mastodon-apps/pull/148
[#147]: https://github.com/akirk/enable-mastodon-apps/pull/147
[#149]: https://github.com/akirk/enable-mastodon-apps/pull/149
[#144]: https://github.com/akirk/enable-mastodon-apps/pull/144
[#143]: https://github.com/akirk/enable-mastodon-apps/pull/143
[#140]: https://github.com/akirk/enable-mastodon-apps/pull/140
[#138]: https://github.com/akirk/enable-mastodon-apps/pull/138
[#137]: https://github.com/akirk/enable-mastodon-apps/pull/137
[#136]: https://github.com/akirk/enable-mastodon-apps/pull/136
[#132]: https://github.com/akirk/enable-mastodon-apps/pull/132
[#130]: https://github.com/akirk/enable-mastodon-apps/pull/130
[#73]: https://github.com/akirk/enable-mastodon-apps/pull/73
[#76]: https://github.com/akirk/enable-mastodon-apps/pull/76
[#72]: https://github.com/akirk/enable-mastodon-apps/pull/72
[#65]: https://github.com/akirk/enable-mastodon-apps/pull/65
[#60]: https://github.com/akirk/enable-mastodon-apps/pull/60
[#52]: https://github.com/akirk/enable-mastodon-apps/pull/52
[#53]: https://github.com/akirk/enable-mastodon-apps/pull/53
[#51]: https://github.com/akirk/enable-mastodon-apps/pull/51
[#62]: https://github.com/akirk/enable-mastodon-apps/pull/62
[#61]: https://github.com/akirk/enable-mastodon-apps/pull/61
[#55]: https://github.com/akirk/enable-mastodon-apps/pull/55
[#48]: https://github.com/akirk/enable-mastodon-apps/pull/48
[#45]: https://github.com/akirk/enable-mastodon-apps/pull/45
[#44]: https://github.com/akirk/enable-mastodon-apps/pull/44
[#42]: https://github.com/akirk/enable-mastodon-apps/pull/42
[#41]: https://github.com/akirk/enable-mastodon-apps/pull/41
[#37]: https://github.com/akirk/enable-mastodon-apps/pull/37
[#35]: https://github.com/akirk/enable-mastodon-apps/pull/35
[#33]: https://github.com/akirk/enable-mastodon-apps/pull/33
[#31]: https://github.com/akirk/enable-mastodon-apps/pull/31
[#29]: https://github.com/akirk/enable-mastodon-apps/pull/29
[#23]: https://github.com/akirk/enable-mastodon-apps/pull/23
[#3]: https://github.com/akirk/enable-mastodon-apps/pull/3




[#225]: https://github.com/akirk/friends/pull/225
[#226]: https://github.com/akirk/friends/pull/226
[#227]: https://github.com/akirk/friends/pull/227
[#228]: https://github.com/akirk/friends/pull/228
[#233]: https://github.com/akirk/friends/pull/233

[#234]: https://github.com/akirk/enable-mastodon-apps/pull/234

[#237]: https://github.com/akirk/enable-mastodon-apps/pull/237
[#238]: https://github.com/akirk/enable-mastodon-apps/pull/238

[#242]: https://github.com/akirk/enable-mastodon-apps/pull/242
[#243]: https://github.com/akirk/enable-mastodon-apps/pull/243
[#245]: https://github.com/akirk/enable-mastodon-apps/pull/245

[#253]: https://github.com/akirk/enable-mastodon-apps/pull/253
[#256]: https://github.com/akirk/enable-mastodon-apps/pull/256

[#257]: https://github.com/akirk/enable-mastodon-apps/pull/257

[#259]: https://github.com/akirk/enable-mastodon-apps/pull/259
[#260]: https://github.com/akirk/enable-mastodon-apps/pull/260
[#261]: https://github.com/akirk/enable-mastodon-apps/pull/261
