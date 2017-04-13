<?php

namespace AppBundle\Controller;

use ApiBundle\Service\ApiService;
use JSend\JSendResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class XhrController
 * @package AppBundle\Controller
 *
 * @Route("/xhr")
 */
class XhrController extends Controller
{
	/**
	 * @Route("/readings", name="xhr_readings")
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function readingsAction(Request $request)
	{
		/** @var ApiService $apiService */
		$apiService = $this->get('api.service');

		// format date properly for Javascript
		$dateFrom = \DateTime::createFromFormat('d-m-Y', $request->get('dateFrom'))->getTimestamp();
		$dateTo = \DateTime::createFromFormat('d-m-Y', $request->get('dateTo'))->getTimestamp();

		$sensors = array();
		foreach ($request->get('sensors') as $sensor) {
			$sensors[] = array(
				'label' => $sensor['sensor'],
				'readings' => $apiService->getReadings(
					$dateFrom,
					$dateTo,
					$request->get('interval'),
					$sensor['sensor'],
					$sensor['type']
				)
			);
		}

		// rip the labels from the first sensor, the rest should be the same
		$labels = array();
		if (!empty($sensors[0])) {
			foreach ($sensors[0]['readings'] as $reading) {
				$datetime = new \DateTime($reading['time']);
				$labels[] = $datetime->format('d-m-Y H:i');
			}
		}

		// strip the labels from all readings to get clean datasets
		$dataset = array();
		foreach ($sensors as $sensor) {

			$readings = array();
			foreach ($sensor['readings'] as $sensorReading) {
				$readings[] = $sensorReading['mean'] ? number_format((float) $sensorReading['mean'], 2) : null;
			}

			$dataset[] = array(
				'label' => $this->get('app.service.sensor')->getReadableName($sensor['label']),
				'readings' => $readings
			);
		}

		return new JsonResponse(new JSendResponse(JSendResponse::SUCCESS, array(
			'labels' => $labels,
			'dataset' => $dataset
		)));
	}
}