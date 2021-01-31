<?php
	class API {
		private $server;
		private $request;
		private $apiRoutes;

		public function __construct($server, $request) {
			$this->server = $server;
			$this->request = $request;
			$this->apiRoutes = array();
		}

		public function addAPIRoute($apiRoute) {
			array_push($this->apiRoutes, $apiRoute);
		}

		private function makeResponse($apiResponse) {
			http_response_code(json_encode($apiResponse->getCode()));
			header("Access-Control-Allow-Origin: *");
			if(isset($this->server['HTTP_ACCEPT']) && explode(',', explode(';', $this->server['HTTP_ACCEPT'])[0])[0] === "text/csv") {
				header('Content-Type: text/csv');
				$this->outputCSV($apiResponse->getData(), $this->isNestedArray($apiResponse->getData()));
			} else {
				header('Content-Type: application/json');
				echo json_encode($apiResponse->getData(), JSON_UNESCAPED_UNICODE);
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
			foreach($this->apiRoutes as $apiRoute) {
				if(($this->server['REQUEST_METHOD'] === $apiRoute->getMethod()) && (preg_match($apiRoute->getRoutePattern(), $this->request['route']))) {
					$this->makeResponse($apiRoute->execute($this->request));
					$executed = true;
				}
			}
			if(!$executed) {
				$this->makeResponse(new APIResponse(400, array()));
			}
		}
	}

	class APIResponse {
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

	class APIRoute {
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
