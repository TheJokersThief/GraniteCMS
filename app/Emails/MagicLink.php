<?php

namespace App\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MagicLink extends Mailable {
	use Queueable, SerializesModels;

	private $nonce;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($nonce) {
		$this->nonce = $nonce;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {

		$url = route('magic-link-verification', ['code' => $this->nonce]);
		return $this->view('auth.emails.magic-link')->with('url', $url);
	}
}