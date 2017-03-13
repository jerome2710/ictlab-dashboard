<?php

namespace ApiBundle\Service;

use GuzzleHttp\Client;
use JSend\JSendResponse;

class ApiService
{
	const API_URL = 'https://api.chibb-box.nl';
	const API_ENDPOINT_AUTHENTICATE = '/authenticate';
	const API_ENDPOINT_SENSORS_LIST = '/sensors';

	/** @var string $apiUsername */
	private $apiUsername;

	/** @var string $apiPassword */
	private $apiPassword;

	/** @var string $accessToken */
	private $accessToken;

	/**
	 * ApiService constructor.
	 *
	 * @param $apiUsername
	 * @param $apiPassword
	 */
	public function __construct($apiUsername, $apiPassword)
	{
		$this->apiUsername = $apiUsername;
		$this->apiPassword = $apiPassword;
	}

	/**
	 * Request an access token
	 *
	 * @return string|bool
	 */
	private function getAccessToken()
	{
		$oClient = new Client();
		$oResponse = $oClient->request(
			'POST', static::API_URL . static::API_ENDPOINT_AUTHENTICATE, array(
				'headers' => array(
					'Content-Type' => 'application/x-www-form-urlencoded'
				),
				'form_params' => array(
					'username' => $this->apiUsername,
					'password' => $this->apiPassword
				)
			)
		);

		$oResponse = JSendResponse::decode($oResponse->getBody());

		if ($oResponse->isSuccess()) {
			return $oResponse->getData()['token'];
		}

		return false;
	}

	/**
	 * Perform a request using the given endpoint
	 *
	 * @param $endpoint
	 * @return array|bool
	 */
	private function request($endpoint)
	{
		if (is_null($this->accessToken)) {
			$this->accessToken = $this->getAccessToken();
		}

		$oClient = new Client();
		$oResponse = $oClient->request(
			'GET', static::API_URL . $endpoint, array(
				'headers' => array(
					'x-access-token' => $this->accessToken
				)
			)
		);

		$oResponse = JSendResponse::decode($oResponse->getBody());

		if ($oResponse->isSuccess()) {
			return $oResponse->getData();
		}

		return false;
	}

	/**
	 * Get the sensors
	 *
	 * @return array
	 */
	public function getSensors()
	{
		$mResponse = $this->request(static::API_ENDPOINT_SENSORS_LIST);

		if ($mResponse && !empty($mResponse['sensors'])) {
			return $mResponse['sensors'];
		}

		return array();
	}
}