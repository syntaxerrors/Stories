<?php

class Ersatz extends aware
{
    private $serviceAddress;
    private $queue;

    public function __construct($purpose)
    {
        // TODO: replace with actual configuration
        $this->serviceAddress = "localhost:12301";
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
        require_once(path('app') .'models/Zend/Http_Client.php');
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
        $contentType = preg_replace("/;.*/", "", $response->getHeader("Content-Type"));
        if ($statusCode != 200) {
            $errorMessage = $response->getMessage();
            if ($contentType == "application/json") {
                $jsonResponse = json_decode($response->getBody());
                if ($jsonResponse) {
                    $errorMessage = $jsonResponse->message;
                }
            }
            print_pre("Failed sending events to Ersatz service at {$this->serviceAddress}}: HTTP {$statusCode}: {$errorMessage}");
        }
    }
}
