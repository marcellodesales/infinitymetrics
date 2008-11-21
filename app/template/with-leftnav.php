<?php
    include 'header-left-nav.php';
?>
    <div id="content-wrap">
        <div id="inside">
            <div id="sidebar-left">
                <ul id="rootcandy-menu">
                    <li class="menu-123 first"><a href="http://infinitymetrics.local.net/admin/content/book" title="Manage your site's book outlines.">Project Owners</a></li>
                    <li class="menu-27"><a href="http://infinitymetrics.local.net/admin/content/comment" title="List and edit site comments and the comment moderation queue.">Instructors</a></li>
                    <li class="menu-28"><a href="http://infinitymetrics.local.net/admin/content/node" title="View, edit, and delete your site's content.">Team Leaders</a></li>
                    <li class="menu-29 active"><a href="http://infinitymetrics.local.net/admin/content/types" title="Manage posts by content type, including default status, front page promotion, etc." class="active-trail active">Regular Members</a></li>
                </ul>
            </div><!-- end sidebar-left -->
            <<div id="sidebar-right">
                <div id="block-user-3" class="block block-user">
                    <h2>Who's online</h2>

                    <div class="content">
                        There are currently <em>1 user</em> and <em>0 guests</em> online.

                        <div class="item-list"><h3>Online users</h3>
                            <ul>
                                <li class="first last">demo</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- end sidebar-right -->
            <div id="content">
                <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
                    <div class="content-in">
                        <div id="tabs-primary">
                            <ul class="tabs primary">
                                <li class="active"><a href="http://infinitymetrics.local.net/admin/content/comment" class="active">Viewing List</a></li>
                                <li><a href="http://infinitymetrics.local.net/admin/content/comment/approval">Invite Instructors</a></li>
                            </ul>
                        </div>
                        <div class="level-1">
                            <div class="help">
                                <p>
                                    Below is a list of the latest comments posted to your site. Click on a
                                    subject to see the comment, the author's name to edit the author's user
                                    information, 'edit' to modify the text, and 'delete' to remove their
                                    submission.
                                </p>
                                <div class="more-help-link">
                                    [<a href="http://infinitymetrics.local.net/admin/help/comment">more help...</a>]
                                </div>
                            </div>

                            <form action="/rootcandy/admin/content/comment" accept-charset="UTF-8" method="post" id="comment-admin-overview">
                                <div>
                                    <div class="container-inline">
                                        <fieldset>
                                            <legend>Update options</legend>
                                                <div class="form-item" id="edit-operation-wrapper">
                                                    <select name="operation" class="form-select" id="edit-operation">
                                                        <option value="unpublish">Unpublish the selected comments</option>
                                                        <option value="delete">Delete the selected comments</option>
                                                    </select>
                                                </div>
                                                <input name="op" id="edit-submit" value="Update" class="form-submit" type="submit">
                                        </fieldset>
                                    </div>
                                    <table style="position: fixed; top: 0px; width: 800px; left: 306px; visibility: hidden;" class="sticky-header">
                                        <thead>
                                            <tr>
                                                <th style="width: 23.4167px;" class="select-all">
                                                    <input title="Select all rows in this table" class="form-checkbox" type="checkbox">
                                                </th>
                                                <th style="width: 185.15px;">
                                                    <a href="http://infinitymetrics.local.net/admin/content/comment?sort=asc&amp;order=Subject" title="sort by Subject" class="active">Subject</a>
                                                </th>
                                                <th style="width: 54.95px;">
                                                    <a href="http://infinitymetrics.local.net/admin/content/comment?sort=asc&amp;order=Author" title="sort by Author" class="active">Author</a>
                                                </th>
                                                <th style="width: 197.117px;">
                                                    <a href="http://infinitymetrics.local.net/admin/content/comment?sort=asc&amp;order=Posted+in" title="sort by Posted in" class="active">Posted in</a>
                                                </th>
                                                <th style="width: 137.3px;" class="active">
                                                    <a href="http://infinitymetrics.local.net/admin/content/comment?sort=asc&amp;order=Time" title="sort by Time" class="active">
                                                        Time
                                                            <img src="left-navigation-centraldata_files/arrow-asc.png" alt="sort icon" title="sort ascending" width="13" height="13">
                                                    </a>
                                                </th>
                                                <th style="width: 88.6667px;">Operations</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <table class="sticky-enabled tableSelect-processed sticky-table">
                                        <thead class="tableHeader-processed">
                                            <tr>
                                                <th class="select-all">
                                                    <input title="Select all rows in this table" class="form-checkbox" type="checkbox">
                                                </th>
                                                <th>
                                                    <a href="http://infinitymetrics.local.net/admin/content/comment?sort=asc&amp;order=Subject" title="sort by Subject" class="active">Subject</a>
                                                </th>
                                                <th><a href="http://infinitymetrics.local.net/admin/content/comment?sort=asc&amp;order=Author" title="sort by Author" class="active">Author</a>
                                                </th>
                                                <th><a href="http://infinitymetrics.local.net/admin/content/comment?sort=asc&amp;order=Posted+in" title="sort by Posted in" class="active">Posted in</a>
                                                </th>
                                                <th class="active">
                                                    <a href="http://infinitymetrics.local.net/admin/content/comment?sort=asc&amp;order=Time" title="sort by Time" class="active">
                                                        Time
                                                        <img src="left-navigation-centraldata_files/arrow-asc.png" alt="sort icon" title="sort ascending" width="13" height="13">
                                                    </a>
                                                </th>
                                                <th>Operations</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td>
                                                    <div class="form-item" id="edit-comments-2-wrapper">
                                                    <label class="option">
                                                        <input name="comments[2]" id="edit-comments-2" value="2" class="form-checkbox" type="checkbox">
                                                    </label>
                                                    </div>
                                                </td>
                                                <td><a href="http://infinitymetrics.local.net/node/1#comment-2" title="wow that's pretty cool.. it would be nice to have that as drupal's default admin page">wow that's pretty cool.. it</a></td>
                                                <td>demo</td>
                                                <td><a href="http://infinitymetrics.local.net/node/1">Welcome to RootCandy test</a></td>
                                                <td class="active">10/14/2008 - 07:12</td>
                                                <td><a href="http://infinitymetrics.local.net/comment/edit/2?destination=admin%2Fcontent%2Fcomment">edit</a></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="form-checkboxes">
                                    </div>
                                    <input name="form_build_id" id="form-7594fee705bb8702a53c6137ede48631" value="form-7594fee705bb8702a53c6137ede48631" type="hidden">
                                    <input name="form_token" id="edit-comment-admin-overview-form-token" value="a1bf621067700aa278af979623266ddb" type="hidden">
                                    <input name="form_id" id="edit-comment-admin-overview" value="comment_admin_overview" type="hidden">
                                </div>
                            </form>
                        </div>
                    </div>
                    <br class="clear">
                </div></div></div></div></div></div></div></div>
            </div><!-- end content -->
        </div><!-- end inside -->
    </div><!-- end content-wrap -->
<?php
    include 'footer.php';
?>