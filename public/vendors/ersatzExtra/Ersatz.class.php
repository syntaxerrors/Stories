<?php

/**
 * Client interface for the Ersatz daemon, which manages a collection of COMET
 * connections to the browser.
 *
 * @category   SoftLayer_Utility_Ersatz
 * @package    SoftLayer Framework
 * @author     Chris Evans <cevans@softlayer.com>
 * @copyright  2013 Softlayer Technologies, Inc
 * @since      Class available since Release 1.0.0
 */
class SoftLayer_Utility_Ersatz extends SoftLayer_Core
{
    private $serviceAddress;
    private $queue;

    public function __construct($purpose)
    {
        $config = SoftLayer_Utility::getConfiguration()->ersatz->{$purpose};
        if (!isset($config->host)) {
            throw new SoftLayer_Exception("No Ersatz configuration for '{$purpose}'.");
        }

        // TODO: replace with actual configuration
        $this->serviceAddress = "{$config->host}:{$config->port}";
        $this->queue = array();
    }

    public function send($objectId, $eventType, $keys, $data)
    {
        $this->queue[] = array(
            "id" => $objectId,
            "type" => $eventType,
            "keys" => $keys,
            "data" => $data);
    }

    public function flush()
    {
        $jsonBody = json_encode($this->queue);
        $this->requestOnce("http://{$this->serviceAddress}/post", $jsonBody);
        $this->queue = array();
    }

    private function requestOnce($url, $body)
    {
        $client = new Zend_Http_Client($url, array(
            'timeout' => 1.0,
            'keepalive' => true,
        ));
        $client->setHeaders("Accept", "application/json");
        $client->setHeaders("Accept-Encoding", "identity");
        $client->setHeaders("Content-Type", "application/json");
        $client->setRawData($body);

        $response = $client->request("POST");

        $statusCode = $response->getStatus();
        $contentType = ereg_replace(";.*", "", $response->getHeader("Content-Type"));
        if ($statusCode != 200) {
            $errorMessage = $response->getMessage();
            if ($contentType == "application/json") {
                $jsonResponse = json_decode($response->getBody());
                if ($jsonResponse) {
                    $errorMessage = $jsonResponse->message;
                }
            }
            self::getLogger()->error("Failed sending events to Ersatz service at {$this->serviceAddress}}: HTTP {$statusCode}: {$errorMessage}");
        }
    }
}
