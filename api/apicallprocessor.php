<?php
    class CallManager {
        private $server;
        private $request;
        private $callProcessors;

        public function __construct($server, $request) {
            $this->server = $server;
            $this->request = $request;
            $this->callProcessors = array();
        }

        public function addCallProcessor($callProcessor) {
            array_push($this->callProcessors, $callProcessor);
        }

        private function makeResponse($callResponse) {
            http_response_code($callResponse->getCode());
            if(floor($callResponse->getCode() / 100) == 2) {
                if(isset($this->server['HTTP_ACCEPT']) && explode(',', explode(';', $this->server['HTTP_ACCEPT'])[0])[0] === "text/csv") {
                    header('Content-Type: text/csv');
                    echo $this->arrayToCSV($callResponse->getData());
                } else {
                    header('Content-Type: application/json');
                    echo json_encode($callResponse->getData(), JSON_UNESCAPED_UNICODE);
                }
            } else {
                header('Content-Type: text/plain');
                echo $callResponse->getData()['error'];
            }
        }

        private function arrayToCSV($array) {
            $result = "";
            $keys = array_keys($array[0]);
            $result .= implode(',', $keys) . "\n";
            foreach($array as $row) {
                $result .= "\"" . implode('","', $row) . "\"\n";
            }
            return $result;
        }

        public function run() {
            foreach($this->callProcessors as $callProcessor) {
                if(($this->server['REQUEST_METHOD'] === $callProcessor->getMethod()) && ($this->request['API_ROUTE'] === $callProcessor->getRoute())) {
                    $this->makeResponse($callProcessor->execute($this->request));
                }
            }
        }
    }

    class CallResponse {
        private $responseCode;
        private $responseData;

        public function __construct($responseCode, $responseData) {
            $this->responseCode = $responseCode;
            $this->responseData = $responseData;
        }

        public function getCode() {
            return $this->responseCode;
        }

        public function getData() {
            return $this->responseData;
        }
    }

    class CallProcessor {
        private $method;
        private $route;
        private $responseFunction;

        public function __construct($method, $route, $responseFunction) {
            $this->method = $method;
            $this->route = $route;
            $this->responseFunction = $responseFunction;
        }

        public function getMethod() {
            return $this->method;
        }

        public function getRoute() {
            return $this->route;
        }

        public function execute($request) {
            return ($this->responseFunction)($request);
        }
    }
?>