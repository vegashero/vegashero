<?php
declare(strict_types=1);

namespace VegasHero\ShortCodes;

final class SingleGame
{

    /**
     * @param int $game_id
     * @return array
     */
    static public function getIframeSrcForGameId(int $game_id) 
    {
        $post = self::_getPost($game_id);
    }

    static private function _getPost(int $game_id) {
				$query = new WP_Query( 
						array(
								'post_type' => 'vegashero_games',
								'meta_query' => array(
										array(
												'key' => 'game_id',
												'value' => $game_id,
										)
								)
						)
				);
        return $query->get_posts()[0];
    }

}
