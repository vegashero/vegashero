<?php

namespace VegasHero\Tests\Import;

use VegasHero\Helpers\Test as TestHelper;
use VegasHero\Config;
use VegasHero\Import\Operator;

use WP_UnitTestCase, Faker;

final class OperatorImportTest extends WP_UnitTestCase {


	public function set_up() {
		parent::set_up();
		$this->config            = Config::getInstance();
		$this->operator_importer = Operator::getInstance();
		$this->faker             = Faker\Factory::create();
		$this->operator          = 'energycasino';
	}

	/**
	 * given a game with status 1
	 * when the game is imported
	 * then the game is imported
	 * and meta data is added
	 * and taxonomies created
	 */
	public function testImportsAddNewGamesWhenStatusOne() {
		$games = TestHelper::generateRandomGames(
			$this->faker,
			array(
				'status'        => 1,
				$this->operator => 1,
				'mrgreen'       => 0,
				'slotsmillion'  => 0,
				'europa'        => 0,
				'slotslv'       => 0,
				'winner'        => 0,
				'bet365'        => 0,
				'williamhill'   => 0,
				'intercasino'   => 0,
				'videoslots'    => 0,
				'bellfruit'     => 0,
			)
		);
		$posts = TestHelper::importGames(
			json_encode( $games ),
			$this->operator_importer,
			$this->config,
			[
				'operator'    => $this->operator,
				'post_status' => 'publish',
			]
		);
		$this->assertSame( count( $games ), count( $posts ) );
		foreach ( $posts as $post ) {
			$this->assertSame( $post->post_status, 'publish' );
			$terms = get_the_terms( $post, $this->config->gameOperatorTaxonomy );
			$this->assertContains( $this->operator, array_column( $terms, 'slug' ) );
			$this->assertObjectHasAttribute( $this->config->postMetaGameId, $post->meta );
			$this->assertObjectHasAttribute( $this->config->postMetaGameSrc, $post->meta );
			$this->assertObjectHasAttribute( $this->config->postMetaGameTitle, $post->meta );
			$this->assertObjectHasAttribute( $this->config->postMetaGameImg, $post->meta );
		}
	}

	public function testImportAsDraft() {
		$games = TestHelper::generateRandomGames(
			$this->faker,
			array(
				'status'        => 1,
				$this->operator => 1,
				'mrgreen'       => 0,
				'slotsmillion'  => 0,
				'europa'        => 0,
				'slotslv'       => 0,
				'winner'        => 0,
				'bet365'        => 0,
				'williamhill'   => 0,
				'intercasino'   => 0,
				'videoslots'    => 0,
				'bellfruit'     => 0,
			)
		);
		$posts = TestHelper::importGames(
			json_encode( $games ),
			$this->operator_importer,
			$this->config,
			[
				'operator'    => $this->operator,
				'post_status' => 'draft',
			]
		);
		$this->assertSame( count( $games ), count( $posts ) );
		foreach ( $posts as $post ) {
			$this->assertSame( $post->post_status, 'draft' );
		}
	}

	/**
	 * given game has status of 0
	 * when the game is imported
	 * then the game is not imported
	 */
	public function testImportDontAddGamesWhenStatusZero() {
		$games = TestHelper::generateRandomGames(
			$this->faker,
			array(
				'status'        => 0,
				$this->operator => 1,
			)
		);
		$posts = TestHelper::importGames(
			json_encode( $games ),
			$this->operator_importer,
			$this->config,
			[
				'operator'    => $this->operator,
				'post_status' => 'publish',
			]
		);
		$this->assertSame( 0, count( $posts ) );
		foreach ( $posts as $post ) {
			$terms = get_the_terms( $post, $this->config->gameOperatorTaxonomy );
			$this->assertNotContains( $this->operator, array_column( $terms, 'slug' ) );
		}
	}

	/**
	 * @group import
	 * @group operator
	 *
	 * given game has already been imported
	 * and updated game has status 0
	 * when the game is updated
	 * then post status is left as is (do not change to draft)
	 */
	public function testImportDontUpdatePostStatusOfExistingGamesWhenStatusZero() {
		$games         = TestHelper::generateRandomGames(
			$this->faker,
			array(
				'status'        => 1,
				$this->operator => 1,
			)
		);
		$posts         = TestHelper::importGames(
			json_encode( $games ),
			$this->operator_importer,
			$this->config,
			[
				'operator'    => $this->operator,
				'post_status' => 'publish',
			]
		);
		$updated_games = array_map(
			function( $game ) {
				$game->status = 0;
				return $game;
			},
			$games
		);
		$updated_posts = TestHelper::importGames(
			json_encode( $updated_games ),
			$this->operator_importer,
			$this->config,
			[
				'operator'    => $this->operator,
				'post_status' => 'publish',
			]
		);
		$result        = ( array_search( 'draft', array_column( $updated_posts, 'post_status' ) ) !== false );
		$this->assertSame( $result, false );
		$this->assertEquals( $posts, $updated_posts );
		// no new games imported
	}

	/**
	 * given game has already been imported
	 * and updated game has status 0
	 * and the game meta changes
	 * when the game is updated
	 * then the game src, game title and game img meta data is updated
	 * and the post title, post name and game id meta data is left untouched
	 */
	public function testImportUpdateMetaOfExistingGame() {
		$games         = TestHelper::generateRandomGames(
			$this->faker,
			array(
				'status'        => 1,
				$this->operator => 1,
			)
		);
		$posts         = TestHelper::importGames(
			json_encode( $games ),
			$this->operator_importer,
			$this->config,
			[
				'operator'    => $this->operator,
				'post_status' => 'publish',
			]
		);
		$updated_games = array_map(
			function( $game ) {
				$game->src  = $this->faker->url;
				$game->name = $this->faker->firstname;
				return $game;
			},
			$games
		);
		$updated_posts = TestHelper::importGames(
			json_encode( $updated_games ),
			$this->operator_importer,
			$this->config,
			[
				'operator'    => $this->operator,
				'post_status' => 'publish',
			]
		);
		$this->assertNotEquals( $posts, $updated_posts );
		for ( $i = 0; $i < count( $updated_posts ); $i++ ) {
			$this->assertSame( $posts[ $i ]->post_title, $updated_posts[ $i ]->post_title );
			$this->assertSame( $posts[ $i ]->post_name, $updated_posts[ $i ]->post_name );
			$this->assertSame( $posts[ $i ]->meta->game_id, $updated_posts[ $i ]->meta->game_id );
			$this->assertNotSame( $posts[ $i ]->meta->game_src, $updated_posts[ $i ]->meta->game_src );
			$this->assertNotSame( $posts[ $i ]->meta->game_title, $updated_posts[ $i ]->meta->game_title );
			$this->assertNotSame( $posts[ $i ]->meta->game_img, $updated_posts[ $i ]->meta->game_img );
		}
	}

	/**
	 * given game has already been imported
	 * and a new operator is added to a game
	 * when the game is updated
	 * then the new operator category is created and added to the game
	 */
	public function testImportUpdateGameOperators() {
		$games         = TestHelper::generateRandomGames(
			$this->faker,
			array(
				'status'        => 1,
				$this->operator => 1,
			)
		);
		$posts         = TestHelper::importGames(
			json_encode( $games ),
			$this->operator_importer,
			$this->config,
			[
				'operator'    => $this->operator,
				'post_status' => 'publish',
			]
		);
		$new_operator  = strtolower( $this->faker->firstname );
		$updated_games = array_map(
			function( $game, $new_operator ) {
				$game->$new_operator = 1;
				return $game;
			},
			$games,
			array_fill( 0, count( $games ), $new_operator )
		);
		$updated_posts = TestHelper::importGames(
			json_encode( $updated_games ),
			$this->operator_importer,
			$this->config,
			[
				'operator'    => $new_operator,
				'post_status' => 'publish',
			]
		);
		$this->assertEquals( $posts, $updated_posts );
		foreach ( $updated_posts as $updated_post ) {
			$terms = get_the_terms( $updated_post, $this->config->gameOperatorTaxonomy );
			$this->assertContains( $new_operator, array_column( $terms, 'slug' ) );
		}
	}

}
