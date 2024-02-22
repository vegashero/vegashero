<?php

namespace VegasHero\Import;

class Utils {


	/**
	 * @param int $type
	 * @return string
	 * @throw InvalidArgumentException
	 */
	public static function translateGameType( int $type ): string {
		switch ( $type ) {
			case 0:
				return 'flash';
			break;
			case 1:
				return 'html5';
			break;
			default:
				throw new InvalidArgumentException( "Unknown game type: $type" );
		}
	}
}
