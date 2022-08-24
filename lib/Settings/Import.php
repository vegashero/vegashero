<?php

namespace VegasHero\Settings;

abstract class Import {

    /**
     * TODO: improve
     */
    public function enqueueAjaxScripts() {
        wp_enqueue_script('vegashero-import', plugins_url( '/js/vegashero-import.js', __FILE__ ), array('jquery'), null, true);
    }

    /**
     * Fetch list of providers from cache
     * @param string $cache_id
     * @return array Cached array of providers or operators
     */
    protected function _getCachedList($cache_id) {
        if($items = get_transient($cache_id)) {
            return $items;
        }
        return Array();
    }

    protected function _getPostStatusDropdown() {
        $markup = "<select name='post_status'>";
        $markup .= sprintf("<option selected='selected' value='publish'>%s</option>", __('Published', 'vegashero'));
        $markup .= sprintf("<option value='draft'>%s</option>", __('Draft', 'vegashero'));
        $markup .= '</select>';
        return $markup;
    }

    protected function _getGameTypeCheckboxes($slug, $html5_total, $flash_total) {
        $html5_text = wp_strip_all_tags(__('Import HTML5 games', 'vegashero'));
        $flash_text = wp_strip_all_tags(__('Import Flash games', 'vegashero'));
        $checkboxes = '<div class="vh_game_type_checkbox_wrapper">';
        $checkboxes .= wp_kses(sprintf('<div class="vh_game_type_checkbox"><input type="checkbox" id="%1$s_html5" name="vh-import-html5" checked><label for="%1$s_html5">%2$s (%3$d)</label></div>', $slug, $html5_text, $html5_total), ["div" =>["class" => true], "input" => ["type" => true, "id" => true, "name" => true, "checked" => true], "label" => ["for" => true]]);
        $checkboxes .= wp_kses(sprintf('<div class="vh_game_type_checkbox"><input type="checkbox" id="%1$s_flash" name="vh-import-flash"><label for="%1$s_flash">%2$s (%3$d)</label></div>', $slug, $flash_text, $flash_total), ["div" => ["class" => true], "input" => ["type" => true, "id" => true, "name" => true, "checked" => true], "label" => ["for" => true]]);
        $checkboxes .= '</div>';
        return $checkboxes;
    }

    protected function _getGameCount($slug, $total) {
        $title = esc_attr(__('Purchase a license key to unlock access to all the games', 'vegashero'));
        $text = wp_strip_all_tags(__('Games available', 'vegashero'));
        return "<p class='description gamecount' title='$title'>$text: <strong>2</strong> / $total<span class='dashicons dashicons-lock'></span></p>";
    }


    /**
     * @param string $cache_id
     * @param array $items Array of providers or operators
     * @return array Cached array of providers or operators
     */
    protected function _cacheList($cache_id, $items) {
        set_transient( $cache_id, $items, HOUR_IN_SECONDS);
    }

    /**
     * @param string $cache_id
     * @return boolean
     */
    protected function _clearCache($cache_id) {
        return delete_transient($cache_id);
    }

    /**
     * Fetch list of operators or providers from cache or remote server
     * @param string $endpoint
     * @return array|false Array of providers or operators
     */
    protected function _fetchList($endpoint) {
        //NB: $this->_clearCache($endpoint);
        $items = $this->_getCachedList($endpoint);
        if(empty($items)) {
            $response = wp_remote_get($endpoint);
            if( ! is_wp_error($response)) { //TODO: do something about error scenario
                $body = wp_remote_retrieve_body($response);
                if( ! is_wp_error($body)) { //TODO: do something about error scenario
                    $items = json_decode($body, true);
                    if(count($items)) {
                        $this->_cacheList($endpoint, $items);
                    }
                }
            }
        }
        return $items;
    }

}
