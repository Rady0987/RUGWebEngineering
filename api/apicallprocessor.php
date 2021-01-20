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
			http_response_code(json_encode($callResponse->getCode()));
			header("Access-Control-Allow-Origin: *");
			if(isset($this->server['HTTP_ACCEPT']) && explode(',', explode(';', $this->server['HTTP_ACCEPT'])[0])[0] === "text/csv") {
				header('Content-Type: text/csv');
				$this->outputCSV($callResponse->getData(), $this->isNestedArray($callResponse->getData()));
			} else {
				header('Content-Type: application/json');
				echo json_encode($callResponse->getData(), JSON_UNESCAPED_UNICODE);
			}
		}
		
		private function isNestedArray($data) {
			$f = array_filter($data, 'is_array');
			if(count($f) > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		private function outputCSV($data, $multiple) {
			if(!$multiple) {
				$data = array($data);
			}
			foreach($data as $da) {
				$out = fopen('php://output', 'w');
				fputcsv($out, $da);
				fclose($out);
			}
		}

		public function run() {
			$executed = false;
			foreach($this->callProcessors as $callProcessor) {
				if(($this->server['REQUEST_METHOD'] === $callProcessor->getMethod()) && (preg_match($callProcessor->getRoutePattern(), $this->request['route']))) {
					$this->makeResponse($callProcessor->execute($this->request));
					$executed = true;
				}
			}
			if(!$executed) {
				$this->makeResponse(new CallResponse(404, array()));
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
		private $routePattern;
		private $responseFunction;

		public function __construct($method, $routePattern, $responseFunction) {
			$this->method = $method;
			$this->routePattern = $routePattern;
			$this->responseFunction = $responseFunction;
		}

		public function getMethod() {
			return $this->method;
		}

		public function getRoutePattern() {
			return $this->routePattern;
		}

		public function execute($request) {
			return ($this->responseFunction)($request);
		}
	}
?>
