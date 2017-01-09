<?php

namespace App\Helpers;

use DB;

/*
|--------------------------------------------------------
|   Nonce
|--------------------------------------------------------
|   Creates cyrptographically secure nonces
|
|
 */

class Nonce {

	/**
	 * Retrieve a nonce
	 * @param  integer $length
	 * @return string
	 */
	public static function getNonce($length = 250) {
		return self::generateNonce($length);
	}

	/**
	 * Check nonce for authenticity
	 * @param  string $nonce
	 * @return boolean
	 */
	public static function checkNonce($nonce) {
		return self::verifyNonce($nonce);
	}

	/**
	 * Mark a nonce as used
	 * @param  string $nonce
	 * @return boolean
	 */
	public static function markNonceInactive($nonce) {
		return DB::table('nonces')
			->where('nonce', $nonce)
			->update(['active' => false]);
	}

	/**
	 * Generates the crypto nonce
	 * @param  int $length
	 * @return string
	 */
	private static function generateNonce($length) {
		$nonce = self::random_str($length);

		$nonce = self::uniqueNonce($nonce);
		DB::table('nonces')->insert(
			[
				'nonce' => $nonce,
				'active' => true,
				'created_at' => date("Y-m-d H:i:s"),
			]
		);

		return $nonce;
	}

	/**
	 * Recursively checks if the nonce is unique
	 * @param  string $nonce
	 * @return string
	 */
	private static function uniqueNonce($nonce) {
		$results = DB::table('nonces')->where('nonce', $nonce)->first();

		if (!empty($results)) {
			return self::uniqueNonce($nonce);
		}

		return $nonce;
	}

	/**
	 * Verifies if a nonce is fresh and active
	 * @param  string $nonce
	 * @return boolean
	 */
	private static function verifyNonce($nonce) {

		$result = DB::table('nonces')
			->where('nonce', $nonce)
			->where('created_at', '>', DB::raw('NOW() - INTERVAL 10 MINUTE'))
			->first();

		if (!empty($result)) {
			self::markNonceInactive($nonce);
			return true;
		}

		return false;
	}

	/**
	 * Generate a random string, using a cryptographically secure
	 * pseudorandom number generator (random_int)
	 *
	 * For PHP 7, random_int is a PHP core function
	 *
	 * @param int $length      How many characters do we want?
	 * @param string $keyspace A string of all possible characters
	 *                         to select from
	 * @return string
	 */
	private static function random_str(
		$length,
		$keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
	) {
		$str = '';
		$max = mb_strlen($keyspace, '8bit') - 1;
		if ($max < 1) {
			throw new Exception('$keyspace must be at least two characters long');
		}
		for ($i = 0; $i < $length; ++$i) {
			$str .= $keyspace[random_int(0, $max)];
		}
		return $str;
	}
}