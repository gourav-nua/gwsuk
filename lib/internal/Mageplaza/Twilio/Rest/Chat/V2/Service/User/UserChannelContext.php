<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Chat\V2\Service\User;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Options;
use Twilio\Serialize;
use Twilio\Values;
use Twilio\Version;

class UserChannelContext extends InstanceContext {
    /**
     * Initialize the UserChannelContext
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $serviceSid The SID of the Service to fetch the User Channel
     *                           resource from
     * @param string $userSid The SID of the User to fetch the User Channel
     *                        resource from
     * @param string $channelSid The SID of the Channel that has the User Channel
     *                           to fetch
     * @return \Twilio\Rest\Chat\V2\Service\User\UserChannelContext
     */
    public function __construct(Version $version, $serviceSid, $userSid, $channelSid) {
        parent::__construct($version);

        // Path Solution
        $this->solution = array(
            'serviceSid' => $serviceSid,
            'userSid' => $userSid,
            'channelSid' => $channelSid,
        );

        $this->uri = '/Services/' . \rawurlencode($serviceSid) . '/Users/' . \rawurlencode($userSid) . '/Channels/' . \rawurlencode($channelSid) . '';
    }

    /**
     * Fetch a UserChannelInstance
     *
     * @return UserChannelInstance Fetched UserChannelInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() {
        $params = Values::of(array());

        $payload = $this->version->fetch(
            'GET',
            $this->uri,
            $params
        );

        return new UserChannelInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['userSid'],
            $this->solution['channelSid']
        );
    }

    /**
     * Deletes the UserChannelInstance
     *
     * @return boolean True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() {
        return $this->version->delete('delete', $this->uri);
    }

    /**
     * Update the UserChannelInstance
     *
     * @param array|Options $options Optional Arguments
     * @return UserChannelInstance Updated UserChannelInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update($options = array()) {
        $options = new Values($options);

        $data = Values::of(array(
            'NotificationLevel' => $options['notificationLevel'],
            'LastConsumedMessageIndex' => $options['lastConsumedMessageIndex'],
            'LastConsumptionTimestamp' => Serialize::iso8601DateTime($options['lastConsumptionTimestamp']),
        ));

        $payload = $this->version->update(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new UserChannelInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['userSid'],
            $this->solution['channelSid']
        );
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
        return '[Twilio.Chat.V2.UserChannelContext ' . \implode(' ', $context) . ']';
    }
}