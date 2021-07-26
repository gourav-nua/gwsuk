<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\TwiML\Voice;

use Twilio\TwiML\TwiML;

class Prompt extends TwiML {
    /**
     * Prompt constructor.
     *
     * @param array $attributes Optional attributes
     */
    public function __construct($attributes = array()) {
        parent::__construct('Prompt', null, $attributes);
    }

    /**
     * Add Say child.
     *
     * @param string $message Message to say
     * @param array $attributes Optional attributes
     * @return Say Child element.
     */
    public function say($message, $attributes = array()) {
        return $this->nest(new Say($message, $attributes));
    }

    /**
     * Add Play child.
     *
     * @param string $url Media URL
     * @param array $attributes Optional attributes
     * @return Play Child element.
     */
    public function play($url = null, $attributes = array()) {
        return $this->nest(new Play($url, $attributes));
    }

    /**
     * Add Pause child.
     *
     * @param array $attributes Optional attributes
     * @return Pause Child element.
     */
    public function pause($attributes = array()) {
        return $this->nest(new Pause($attributes));
    }

    /**
     * Add For_ attribute.
     *
     * @param string $for_ Name of the payment source data element
     * @return static $this.
     */
    public function setFor_($for_) {
        return $this->setAttribute('for_', $for_);
    }

    /**
     * Add ErrorType attribute.
     *
     * @param string $errorType Type of error
     * @return static $this.
     */
    public function setErrorType($errorType) {
        return $this->setAttribute('errorType', $errorType);
    }

    /**
     * Add CardType attribute.
     *
     * @param string $cardType Type of the credit card
     * @return static $this.
     */
    public function setCardType($cardType) {
        return $this->setAttribute('cardType', $cardType);
    }

    /**
     * Add Attempt attribute.
     *
     * @param int $attempt Current attempt count
     * @return static $this.
     */
    public function setAttempt($attempt) {
        return $this->setAttribute('attempt', $attempt);
    }
}