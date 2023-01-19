<?php

namespace App\Http\Controllers\AccountPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountPanel\ShippingRateRequest;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function PHPUnit\Framework\isEmpty;

class ShippingRateController extends Controller
{
    protected array $upsServiceCodes   = [
        '01'    => 'UPS Next Day Air',
        '02'    => 'UPS 2nd Day Air',
        '03'    => 'UPS Ground',
        '12'    => 'UPS 3 Day Select',
        '13'    => 'UPS Next Day Air Saver',
        '14'    => 'UPS Next Day Air Early A.M.',
        '59'    => 'UPS 2nd Day Air A.M.'
    ];

    public function index(): Response
    {
        $title = 'UPS Shipping Rate';
        $countries = Country::whereIn('code', ['US', 'CA'])->orderBy('id', 'desc')->get();
        return response()->view('AccountPanel.shipping_rate', compact('title', 'countries'), Response::HTTP_OK);
    }

    public function calculatePrice(ShippingRateRequest $request): JsonResponse
    {
        try {

            $country = Country::where('id', $request->get('country_id'))->first();
            $googleApiUrl = 'https://addressvalidation.googleapis.com/v1:validateAddress?key=AIzaSyBh7y76vE9SOafX22mdAmHOgvJlfPddmXM';
            $googleApiHeaders  = [
                'Content-Type: application/json'
            ];
            $shipFromPostFields = '{"address": {"regionCode": "' . $country->code . '","addressLines": ["' . $request->get('origin_zip_code') . '"]}}';
            $shipFromStateCh = curl_init();
            curl_setopt($shipFromStateCh, CURLOPT_URL,$googleApiUrl);
            curl_setopt($shipFromStateCh, CURLOPT_POST, 1);
            curl_setopt($shipFromStateCh, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($shipFromStateCh, CURLOPT_POSTFIELDS, $shipFromPostFields);
            curl_setopt($shipFromStateCh, CURLOPT_HTTPHEADER, $googleApiHeaders);
            $shipFromStateResponse = json_decode(curl_exec($shipFromStateCh), true);
            curl_close($shipFromStateCh);
            if (array_key_exists('result', $shipFromStateResponse) && array_key_exists('administrativeArea', $shipFromStateResponse['result']['address']['postalAddress'])) {
                $shipFromState = $shipFromStateResponse['result']['address']['postalAddress']['administrativeArea'];
            } else if (array_key_exists('error', $shipFromStateResponse)) {
                return response()->json(['message' => $shipFromStateResponse['error']['message']], Response::HTTP_BAD_REQUEST);
            } else {
                return response()->json(['message' => 'Invalid Ship From ZIP Code.'], Response::HTTP_BAD_REQUEST);
            }

            $shipToPostFields = '{"address": {"regionCode": "' . $country->code . '","addressLines": ["' . $request->get('destination_zip_code') . '"]}}';
            $shipToStateCh = curl_init();
            curl_setopt($shipToStateCh, CURLOPT_URL,$googleApiUrl);
            curl_setopt($shipToStateCh, CURLOPT_POST, 1);
            curl_setopt($shipToStateCh, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($shipToStateCh, CURLOPT_POSTFIELDS, $shipToPostFields);
            curl_setopt($shipToStateCh, CURLOPT_HTTPHEADER, $googleApiHeaders);
            $shipToStateResponse = json_decode(curl_exec($shipToStateCh), true);
            curl_close($shipToStateCh);
            if (array_key_exists('result', $shipToStateResponse) && array_key_exists('administrativeArea', $shipToStateResponse['result']['address']['postalAddress'])) {
                $shipToState = $shipToStateResponse['result']['address']['postalAddress']['administrativeArea'];
            } else if (array_key_exists('error', $shipToStateResponse)) {
                return response()->json(['message' => $shipToStateResponse['error']['message']], Response::HTTP_BAD_REQUEST);
            } else {
                return response()->json(['message' => 'Unknown error occurred.'], Response::HTTP_BAD_REQUEST);
            }
            foreach ($request->get('package_length') as $key => $packageLength) {
                $rateApiUrl = 'https://wwwcie.ups.com/ship/v1/rating/RateTimeInTransit';
                $rateApiData = [
                    'RateRequest' => [
                        'CustomerClassification' => [
                            'Code' => '00'
                        ],
                        'Shipment' => [
                            'DeliveryTimeInformation' => [
                                'PackageBillType' => '03',
                                'Pickup' => [
                                    'Date' => date_format(date_create($request->get('shipping_date')), 'Ymd')
                                ]
                            ],
                            'Service' => [
                                'Code' => $request->get('service_code')
                            ],
                            'ShipmentRatingOptions' => [
                                'NegotiatedRatesIndicator' => '1',
                            ],
                            'Shipper' => [
                                'Name' => 'GoodGross',
                                'ShipperNumber' => '1RX454',
                                'Address' => [
                                    'AddressLine' => '',
                                    'City' => $request->get('origin_city'),
                                    'StateProvinceCode' => $shipFromState,
                                    'PostalCode' => $request->get('origin_zip_code'),
                                    'CountryCode' => $country->code,
                                ]
                            ],
                            'ShipTo' => [
                                'Name' => '',
                                'Address' => [
                                    'AddressLine' => '',
                                    'City' => $request->get('destination_city'),
                                    'StateProvinceCode' => $shipToState,
                                    'PostalCode' => $request->get('destination_zip_code'),
                                    'CountryCode' => $country->code,
                                ],

                            ],
                            'ShipFrom' => [
                                'Name' => '',
                                'Address' => [
                                    'AddressLine' => '',
                                    'City' => $request->get('origin_city'),
                                    'StateProvinceCode' => $shipFromState,
                                    'PostalCode' => $request->get('origin_zip_code'),
                                    'CountryCode' => $country->code,
                                ]
                            ],
                            'ShipmentTotalWeight' => [
                                'UnitOfMeasurement' => [
                                    'Code' => 'LBS',
                                    'Description' => 'Pounds'
                                ],
                                'Weight' => $request->get('package_weight')[$key]
                            ],
                            'Package' => [
                                'PackagingType' => [
                                    'Code' => '02',
                                    'Description' => 'Package'
                                ],
                                'Dimensions' => [
                                    'UnitOfMeasurement' => [
                                        'Code' => 'IN',
                                        'Description' => 'Inch'
                                    ],
                                    'Length' => $request->get('package_length')[$key],
                                    'Width' => $request->get('package_width')[$key],
                                    'Height' => $request->get('package_height')[$key]
                                ],
                                'PackageWeight' => [
                                    'UnitOfMeasurement' => [
                                        'Code' => 'LBS',
                                        'Description' => 'Pounds'
                                    ],
                                    'Weight' => $request->get('package_weight')[$key],
                                ]
                            ]
                        ]
                    ]
                ];

                if ($request->has('destination_type') && $request->get('destination_type') === 'Residential') {
                    $rateApiData['RateRequest']['Shipment']['ShipTo']['Address']['ResidentialAddressIndicator'] = '';
                }
                $rateApiPostData = json_encode($rateApiData);
                $rateCh = curl_init($rateApiUrl);
                curl_setopt($rateCh, CURLOPT_POST, 1);
                curl_setopt($rateCh, CURLOPT_POSTFIELDS, $rateApiPostData);
                curl_setopt($rateCh, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($rateCh, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'AccessLicenseNumber: ' . env('UPS_ACCESS_KEY'),
                    'transId: ' . time(),
                    'transactionSrc: GoodGross',
                    'Username: '. env('UPS_USERNAME'),
                    'Password: '. env('UPS_PASSWORD'),
                ]);
                $rateApiResponse = curl_exec($rateCh);
                curl_close($rateCh);
                $rateResponse = json_decode($rateApiResponse, true);

                if ( ! array_key_exists('RateResponse', $rateResponse)) {
                    return response()->json(['payload' => $rateResponse], Response::HTTP_OK);
                } else {
                    $rateResponse['Package'] = [
                        'Weight' => $request->get('package_weight')[$key],
                        'Length' => $request->get('package_length')[$key],
                        'Width' => $request->get('package_width')[$key],
                        'Height' => $request->get('package_height')[$key]
                    ];
                    $response[] = $rateResponse;
                }
            }
            return response()->json(['payload' => $response], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
