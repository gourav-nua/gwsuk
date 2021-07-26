<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Trunking\V1\Trunk;

use Twilio\Exceptions\TwilioException;
use Twilio\ListResource;
use Twilio\Values;
use Twilio\Version;

class TerminatingSipDomainList extends ListResource {
    /**
     * Construct the TerminatingSipDomainList
     *
     * @param Version $version Version that contains the resource
     * @param string $trunkSid The SID of the Trunk to which we should route calls
     * @return \Twilio\Rest\Trunking\V1\Trunk\TerminatingSipDomainList
     */
    public function __construct(Version $version, $trunkSid) {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('trunkSid' => $trunkSid, );

        $this->uri = '/Trunks/' . \rawurlencode($trunkSid) . '/TerminatingSipDomains';
    }

    /**
     * Create a new TerminatingSipDomainInstance
     *
     * @param string $sipDomainSid The SID of the SIP Domain to associate with the
     *                             trunk
     * @return TerminatingSipDomainInstance Newly created
     *                                      TerminatingSipDomainInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create($sipDomainSid) {
        $data = Values::of(array('SipDomainSid' => $sipDomainSid, ));

        $payload = $this->version->create(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new TerminatingSipDomainInstance($this->version, $payload, $this->solution['trunkSid']);
    }

    /**
     * Streams TerminatingSipDomainInstance records from the API as a generator
     * stream.
     * This operation lazily loads records as efficiently as possible until the
     * limit
     * is reached.
     * The results are returned as a generator, so this operation is memory
     * efficient.
     *
     * @param int $limit Upper limit for the number of records to return. stream()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, stream()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return \Twilio\Stream stream of results
     */
    public function stream($limit = null, $pageSize = null) {
        $limits = $this->version->readLimits($limit, $pageSize);

        $page = $this->page($limits['pageSize']);

        return $this->version->stream($page, $limits['limit'], $limits['pageLimit']);
    }

    /**
     * Reads TerminatingSipDomainInstance records from the API as a list.
     * Unlike stream(), this operation is eager and will load `limit` records into
     * memory before returning.
     *
     * @param int $limit Upper limit for the number of records to return. read()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, read()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return TerminatingSipDomainInstance[] Array of results
     */
    public function read($limit = null, $pageSize = null) {
        return \iterator_to_array($this->stream($limit, $pageSize), false);
    }

    /**
     * Retrieve a single page of TerminatingSipDomainInstance records from the API.
     * Request is executed immediately
     *
     * @param mixed $pageSize Number of records to return, defaults to 50
     * @param string $pageToken PageToken provided by the API
     * @param mixed $pageNumber Page Number, this value is simply for client state
     * @return \Twilio\Page Page of TerminatingSipDomainInstance
     */
    public function page($pageSize = Values::NONE, $pageToken = Values::NONE, $pageNumber = Values::NONE) {
        $params = Values::of(array(
            'PageToken' => $pageToken,
            'Page' => $pageNumber,
            'PageSize' => $pageSize,
        ));

        $response = $this->version->page(
            'GET',
            $this->uri,
            $params
        );

        return new TerminatingSipDomainPage($this->version, $response, $this->solution);
    }

    /**
     * Retrieve a specific page of TerminatingSipDomainInstance records from the
     * API.
     * Request is executed immediately
     *
     * @param string $targetUrl API-generated URL for the requested results page
     * @return \Twilio\Page Page of TerminatingSipDomainInstance
     */
    public function getPage($targetUrl) {
        $response = $this->version->getDomain()->getClient()->request(
            'GET',
            $targetUrl
        );

        return new TerminatingSipDomainPage($this->version, $response, $this->solution);
    }

    /**
     * Constructs a TerminatingSipDomainContext
     *
     * @param string $sid The unique string that identifies the resource
     * @return \Twilio\Rest\Trunking\V1\Trunk\TerminatingSipDomainContext
     */
    public function getContext($sid) {
        return new TerminatingSipDomainContext($this->version, $this->solution['trunkSid'], $sid);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() {
        return '[Twilio.Trunking.V1.TerminatingSipDomainList]';
    }
}