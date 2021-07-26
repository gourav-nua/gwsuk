<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Verify\V2;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Options;
use Twilio\Rest\Verify\V2\Service\MessagingConfigurationList;
use Twilio\Rest\Verify\V2\Service\RateLimitList;
use Twilio\Rest\Verify\V2\Service\VerificationCheckList;
use Twilio\Rest\Verify\V2\Service\VerificationList;
use Twilio\Serialize;
use Twilio\Values;
use Twilio\Version;

/**
 * @property \Twilio\Rest\Verify\V2\Service\VerificationList $verifications
 * @property \Twilio\Rest\Verify\V2\Service\VerificationCheckList $verificationChecks
 * @property \Twilio\Rest\Verify\V2\Service\RateLimitList $rateLimits
 * @property \Twilio\Rest\Verify\V2\Service\MessagingConfigurationList $messagingConfigurations
 * @method \Twilio\Rest\Verify\V2\Service\VerificationContext verifications(string $sid)
 * @method \Twilio\Rest\Verify\V2\Service\RateLimitContext rateLimits(string $sid)
 * @method \Twilio\Rest\Verify\V2\Service\MessagingConfigurationContext messagingConfigurations(string $country)
 */
class ServiceContext extends InstanceContext {
    protected $_verifications = null;
    protected $_verificationChecks = null;
    protected $_rateLimits = null;
    protected $_messagingConfigurations = null;

    /**
     * Initialize the ServiceContext
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $sid The unique string that identifies the resource
     * @return \Twilio\Rest\Verify\V2\ServiceContext
     */
    public function __construct(Version $version, $sid) {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('sid' => $sid, );

        $this->uri = '/Services/' . \rawurlencode($sid) . '';
    }

    /**
     * Fetch a ServiceInstance
     *
     * @return ServiceInstance Fetched ServiceInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() {
        $params = Values::of(array());

        $payload = $this->version->fetch(
            'GET',
            $this->uri,
            $params
        );

        return new ServiceInstance($this->version, $payload, $this->solution['sid']);
    }

    /**
     * Deletes the ServiceInstance
     *
     * @return boolean True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() {
        return $this->version->delete('delete', $this->uri);
    }

    /**
     * Update the ServiceInstance
     *
     * @param array|Options $options Optional Arguments
     * @return ServiceInstance Updated ServiceInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update($options = array()) {
        $options = new Values($options);

        $data = Values::of(array(
            'FriendlyName' => $options['friendlyName'],
            'CodeLength' => $options['codeLength'],
            'LookupEnabled' => Serialize::booleanToString($options['lookupEnabled']),
            'SkipSmsToLandlines' => Serialize::booleanToString($options['skipSmsToLandlines']),
            'DtmfInputRequired' => Serialize::booleanToString($options['dtmfInputRequired']),
            'TtsName' => $options['ttsName'],
            'Psd2Enabled' => Serialize::booleanToString($options['psd2Enabled']),
        ));

        $payload = $this->version->update(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new ServiceInstance($this->version, $payload, $this->solution['sid']);
    }

    /**
     * Access the verifications
     *
     * @return \Twilio\Rest\Verify\V2\Service\VerificationList
     */
    protected function getVerifications() {
        if (!$this->_verifications) {
            $this->_verifications = new VerificationList($this->version, $this->solution['sid']);
        }

        return $this->_verifications;
    }

    /**
     * Access the verificationChecks
     *
     * @return \Twilio\Rest\Verify\V2\Service\VerificationCheckList
     */
    protected function getVerificationChecks() {
        if (!$this->_verificationChecks) {
            $this->_verificationChecks = new VerificationCheckList($this->version, $this->solution['sid']);
        }

        return $this->_verificationChecks;
    }

    /**
     * Access the rateLimits
     *
     * @return \Twilio\Rest\Verify\V2\Service\RateLimitList
     */
    protected function getRateLimits() {
        if (!$this->_rateLimits) {
            $this->_rateLimits = new RateLimitList($this->version, $this->solution['sid']);
        }

        return $this->_rateLimits;
    }

    /**
     * Access the messagingConfigurations
     *
     * @return \Twilio\Rest\Verify\V2\Service\MessagingConfigurationList
     */
    protected function getMessagingConfigurations() {
        if (!$this->_messagingConfigurations) {
            $this->_messagingConfigurations = new MessagingConfigurationList(
                $this->version,
                $this->solution['sid']
            );
        }

        return $this->_messagingConfigurations;
    }

    /**
     * Magic getter to lazy load subresources
     *
     * @param string $name Subresource to return
     * @return \Twilio\ListResource The requested subresource
     * @throws TwilioException For unknown subresources
     */
    public function __get($name) {
        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown subresource ' . $name);
    }

    /**
     * Magic caller to get resource contexts
     *
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     * @return \Twilio\InstanceContext The requested resource context
     * @throws TwilioException For unknown resource
     */
    public function __call($name, $arguments) {
        $property = $this->$name;
        if (\method_exists($property, 'getContext')) {
            return \call_user_func_array(array($property, 'getContext'), $arguments);
        }

        throw new TwilioException('Resource does not have a context');
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() {
        $context = array();
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Verify.V2.ServiceContext ' . \implode(' ', $context) . ']';
    }
}