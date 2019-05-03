<form id="posts-filter" method="post">
    <input type="hidden" name="taxonomy" value="game_operator">
    <input type="hidden" name="post_type" value="vegashero_games">

    <input type="hidden" id="_wpnonce" name="_wpnonce" value="ca3ac191ca"><input type="hidden" name="_wp_http_referer" value="/wp-admin/edit-tags.php?taxonomy=<?=$config->gameOperatorTaxonomy?>&amp;post_type=<?=$config->customPostType?>">    <div class="tablenav top">

        <div class="alignleft actions bulkactions">
            <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label><select name="action" id="bulk-action-selector-top">
                <option value="-1">Bulk Actions</option>
                <option value="delete">Delete</option>
            </select>
            <input type="submit" id="doaction" class="button action" value="Apply">
        </div>
        <div class="tablenav-pages no-pages"><span class="displaying-num">0 items</span>
            <span class="pagination-links"><span class="tablenav-pages-navspan" aria-hidden="true">«</span>
                <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
                <span class="paging-input"><label for="current-page-selector" class="screen-reader-text">Current Page</label><input class="current-page" id="current-page-selector" type="text" name="paged" value="1" size="1" aria-describedby="table-paging"> of <span class="total-pages">0</span></span>
                <a class="next-page" href="http://localhost:8000/wp-admin/edit-tags.php?taxonomy=game_operator&amp;post_type=vegashero_games&amp;paged=0"><span class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>
                <a class="last-page" href="http://localhost:8000/wp-admin/edit-tags.php?taxonomy=game_operator&amp;post_type=vegashero_games&amp;paged=0"><span class="screen-reader-text">Last page</span><span aria-hidden="true">»</span></a></span></div>
        <br class="clear">
    </div>
    <h2 class="screen-reader-text">Tags list</h2><table class="wp-list-table widefat fixed striped tags">
        <thead>
            <tr>
                <td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td><th scope="col" id="name" class="manage-column column-name column-primary sortable desc"><a href="http://localhost:8000/wp-admin/edit-tags.php?taxonomy=game_operator&amp;post_type=vegashero_games&amp;orderby=name&amp;order=asc"><span>Name</span><span class="sorting-indicator"></span></a></th><th scope="col" id="description" class="manage-column column-description sortable desc"><a href="http://localhost:8000/wp-admin/edit-tags.php?taxonomy=game_operator&amp;post_type=vegashero_games&amp;orderby=description&amp;order=asc"><span>Description</span><span class="sorting-indicator"></span></a></th><th scope="col" id="slug" class="manage-column column-slug sortable desc"><a href="http://localhost:8000/wp-admin/edit-tags.php?taxonomy=game_operator&amp;post_type=vegashero_games&amp;orderby=slug&amp;order=asc"><span>Slug</span><span class="sorting-indicator"></span></a></th><th scope="col" id="posts" class="manage-column column-posts num sortable desc"><a href="http://localhost:8000/wp-admin/edit-tags.php?taxonomy=game_operator&amp;post_type=vegashero_games&amp;orderby=count&amp;order=asc"><span>Count</span><span class="sorting-indicator"></span></a></th> </tr>
        </thead>

        <tbody id="the-list" data-wp-lists="list:tag">
            <tr class="no-items"><td class="colspanchange" colspan="5">No tags found.</td></tr> </tbody>

        <tfoot>
            <tr>
                <td class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-2">Select All</label><input id="cb-select-all-2" type="checkbox"></td><th scope="col" class="manage-column column-name column-primary sortable desc"><a href="http://localhost:8000/wp-admin/edit-tags.php?taxonomy=game_operator&amp;post_type=vegashero_games&amp;orderby=name&amp;order=asc"><span>Name</span><span class="sorting-indicator"></span></a></th><th scope="col" class="manage-column column-description sortable desc"><a href="http://localhost:8000/wp-admin/edit-tags.php?taxonomy=game_operator&amp;post_type=vegashero_games&amp;orderby=description&amp;order=asc"><span>Description</span><span class="sorting-indicator"></span></a></th><th scope="col" class="manage-column column-slug sortable desc"><a href="http://localhost:8000/wp-admin/edit-tags.php?taxonomy=game_operator&amp;post_type=vegashero_games&amp;orderby=slug&amp;order=asc"><span>Slug</span><span class="sorting-indicator"></span></a></th><th scope="col" class="manage-column column-posts num sortable desc"><a href="http://localhost:8000/wp-admin/edit-tags.php?taxonomy=game_operator&amp;post_type=vegashero_games&amp;orderby=count&amp;order=asc"><span>Count</span><span class="sorting-indicator"></span></a></th> </tr>
        </tfoot>

    </table>
    <div class="tablenav bottom">

        <div class="alignleft actions bulkactions">
            <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label><select name="action2" id="bulk-action-selector-bottom">
                <option value="-1">Bulk Actions</option>
                <option value="delete">Delete</option>
            </select>
            <input type="submit" id="doaction2" class="button action" value="Apply">
        </div>
        <div class="tablenav-pages no-pages"><span class="displaying-num">0 items</span>
            <span class="pagination-links"><span class="tablenav-pages-navspan" aria-hidden="true">«</span>
                <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
                <span class="screen-reader-text">Current Page</span><span id="table-paging" class="paging-input">1 of <span class="total-pages">0</span></span>
                <a class="next-page" href="http://localhost:8000/wp-admin/edit-tags.php?taxonomy=game_operator&amp;post_type=vegashero_games&amp;paged=0"><span class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>
                <a class="last-page" href="http://localhost:8000/wp-admin/edit-tags.php?taxonomy=game_operator&amp;post_type=vegashero_games&amp;paged=0"><span class="screen-reader-text">Last page</span><span aria-hidden="true">»</span></a></span></div>
        <br class="clear">
    </div>

    <br class="clear">
</form>
