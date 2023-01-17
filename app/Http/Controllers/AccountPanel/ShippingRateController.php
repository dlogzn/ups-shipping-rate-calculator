<?php

namespace App\Http\Controllers\AccountPanel;

use App\Http\Controllers\Controller;
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

    public function calculatePrice(Request $request): JsonResponse
    {
        try {

            $country = Country::where('id', $request->get('country_id'))->first();
//            return response()->json(['message' => $country], Response::HTTP_OK);

            $zipPopotamUrl = 'http://api.zippopotam.us';
            $shipFromStateCh = curl_init();
//            curl_setopt($shipFromStateCh, CURLOPT_URL, $zipPopotamUrl . '/' . $country->code . '/' . $request->get('origin_zip_code'));
            curl_setopt($shipFromStateCh, CURLOPT_URL, 'http://api.zippopotam.us/' . $country->code . '/' . $request->get('origin_zip_code'));
            curl_setopt($shipFromStateCh, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($shipFromStateCh, CURLOPT_HEADER, 0);
            curl_setopt($shipFromStateCh, CURLOPT_POST, 0);
            curl_setopt($shipFromStateCh, CURLOPT_TIMEOUT, 3600);
            $shipFromStateResponse = curl_exec($shipFromStateCh);
            curl_close($shipFromStateCh);
            $shipFromStateResponse = json_decode($shipFromStateResponse, true);
//            return response()->json(['message' => $shipFromStateResponse], Response::HTTP_OK);
            if (count($shipFromStateResponse) === 0) {
                return response()->json(['message' => 'The Origin ZIP Code is Invalid.'], Response::HTTP_BAD_REQUEST);

            }

            $shipToStateCh = curl_init();
            curl_setopt($shipToStateCh, CURLOPT_URL, $zipPopotamUrl . '/' . $country->code . '/' . $request->get('destination_zip_code'));
            curl_setopt($shipToStateCh, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($shipToStateCh, CURLOPT_HEADER, 0);
            curl_setopt($shipToStateCh, CURLOPT_POST, 0);
            curl_setopt($shipToStateCh, CURLOPT_TIMEOUT, 3600);
            $shipToStateResponse = curl_exec($shipToStateCh);
            curl_close($shipToStateCh);
            $shipToStateResponse = json_decode($shipToStateResponse, true);

            if (count($shipToStateResponse) === 0) {
                return response()->json(['message' => 'The Destination ZIP Code is Invalid.'], Response::HTTP_BAD_REQUEST);
            }



            $rateApiData = '

                <AccessRequest xml:lang="en-US">
                    <AccessLicenseNumber>' . env('UPS_ACCESS_KEY') . '</AccessLicenseNumber>
                    <UserId>' . env('UPS_USERNAME') . '</UserId>
                    <Password>' . env('UPS_PASSWORD') . '</Password>
                </AccessRequest>
                <?xml version="1.0"?>
                <RatingServiceSelectionRequest xml:lang="en-US">
                    <Request>
                        <TransactionReference>
                            <CustomerContext>GoodGross</CustomerContext>
                        </TransactionReference>
                        <RequestAction>Rate</RequestAction>
                        <RequestOption>Ratetimeintransit</RequestOption>
                    </Request>
                <Shipment>
                    <DeliveryTimeInformation>
                        <PackageBillType>03</PackageBillType>
                        <Pickup>
                            <Date>' . date_format(date_create($request->get('shipping_date')), 'Ymd') . '</Date>
                            <Date>' . explode(':', $request->get('shipping_time'))[0] . explode(':', $request->get('shipping_time'))[1] . '</Date>
                        </Pickup>
                    </DeliveryTimeInformation>
                    <RateInformation>
                        <NegotiatedRatesIndicator/>
                    </RateInformation>
                    <Shipper>
                        <Name>GoodGross</Name>
                        <AttentionName>GoodGross</AttentionName>
                        <PhoneNumber></PhoneNumber>
                        <FaxNumber></FaxNumber>
                        <ShipperNumber>A38H49</ShipperNumber>
                        <Address>
                            <AddressLine1></AddressLine1>
                            <City>' . $request->get('origin_city') . '</City>
                            <StateProvinceCode>' . $shipFromStateResponse['places'][0]['state abbreviation'] . '</StateProvinceCode>
                            <PostalCode>' . $request->get('origin_zip_code') . '</PostalCode>
                            <CountryCode>' . $country->code . '</CountryCode>
                        </Address>
                    </Shipper>
                    <ShipTo>
                        <CompanyName></CompanyName>
                        <AttentionName></AttentionName>
                        <PhoneNumber></PhoneNumber>
                        <FaxNumber></FaxNumber>
                        <Address>
                            <AddressLine1></AddressLine1>
                            <City>' . $request->get('destination_city') . '</City>
                            <StateProvinceCode>' . $shipToStateResponse['places'][0]['state abbreviation'] . '</StateProvinceCode>
                            <PostalCode>' . $request->get('destination_zip_code') . '</PostalCode>
                            <CountryCode>' . $country->code . '</CountryCode>
                        </Address>
                    </ShipTo>
                    <ShipFrom>
                        <CompanyName></CompanyName>
                        <AttentionName></AttentionName>
                        <PhoneNumber></PhoneNumber>
                        <FaxNumber></FaxNumber>
                        <Address>
                            <AddressLine1></AddressLine1>
                            <City>' . $request->get('origin_city') . '</City>
                            <StateProvinceCode>' . $shipFromStateResponse['places'][0]['state abbreviation'] . '</StateProvinceCode>
                            <PostalCode>' . $request->get('origin_zip_code') . '</PostalCode>
                            <CountryCode>' . $country->code . '</CountryCode>
                        </Address>
                    </ShipFrom>
                    <Service>
                        <Code>' . $request->get('service_code') . '</Code>
                        <Description></Description>
                    </Service>
                    <Package>
                        <PackagingType>
                            <Code>02</Code>
                            <Description>UPS Package</Description>
                        </PackagingType>
                        <PackageWeight>
                            <UnitOfMeasurement>
                                <Code>LBS</Code>
                                </UnitOfMeasurement>
                            <Weight>' . $request->get('package_weight') . '</Weight>
                        </PackageWeight>
                        <Dimensions>
                            <UnitOfMeasurement>IN</UnitOfMeasurement>
                            <Length>' . $request->get('package_length') . '</Length>
                            <Width>' . $request->get('package_width') . '</Width>
                            <Height>' . $request->get('package_height') . '</Height>
                        </Dimensions>
                    </Package>
                </Shipment>
                </RatingServiceSelectionRequest>
                ';





//            $rateApiUrl = 'https://wwwcie.ups.com/ups.app/xml/Rate';
//            $rateCh = curl_init();
//            curl_setopt($rateCh, CURLOPT_URL, $rateApiUrl);
//            curl_setopt($rateCh, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($rateCh, CURLOPT_HEADER, 0);
//            curl_setopt($rateCh, CURLOPT_POST, 1);
//            curl_setopt($rateCh, CURLOPT_POSTFIELDS, $rateApiData);
//            curl_setopt($rateCh, CURLOPT_TIMEOUT, 3600);
//            $rateApiResponse = curl_exec($rateCh);
//            curl_close($rateCh);
//
//            $rateApiResponse = json_encode(simplexml_load_string($rateApiResponse));
//            $rateApiResponse = str_replace('&lt;', '<', $rateApiResponse);
//            $rateApiResponse = str_replace('&gt;', '>', $rateApiResponse);
//            $rateApiResponse = json_decode($rateApiResponse, true);
//
//            return response()->json(['payload' => $rateApiResponse], Response::HTTP_OK);





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
                            'ShipperNumber' => 'A38H49',
                            'Address' => [
                                'AddressLine' => '',
                                'City' => $request->get('origin_city'),
                                'StateProvinceCode' => $shipFromStateResponse['places'][0]['state abbreviation'],
                                'PostalCode' => $request->get('origin_zip_code'),
                                'CountryCode' => $country->code,
                            ]
                        ],
                        'ShipTo' => [
                            'Name' => '',
                            'Address' => [
                                'AddressLine' => '',
                                'City' => $request->get('destination_city'),
                                'StateProvinceCode' => $shipToStateResponse['places'][0]['state abbreviation'],
                                'PostalCode' => $request->get('destination_zip_code'),
                                'CountryCode' => $country->code,
                            ],

                        ],
                        'ShipFrom' => [
                            'Name' => '',
                            'Address' => [
                                'AddressLine' => '',
                                'City' => $request->get('origin_city'),
                                'StateProvinceCode' => $shipFromStateResponse['places'][0]['state abbreviation'],
                                'PostalCode' => $request->get('origin_zip_code'),
                                'CountryCode' => $country->code,
                            ]
                        ],
                        'ShipmentTotalWeight' => [
                            'UnitOfMeasurement' => [
                                'Code' => 'LBS',
                                'Description' => 'Pounds'
                            ],
                            'Weight' => $request->get('package_weight')
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
                                'Length' => $request->get('package_length'),
                                'Width' => $request->get('package_width'),
                                'Height' => $request->get('package_height')
                            ],
                            'PackageWeight' => [
                                'UnitOfMeasurement' => [
                                    'Code' => 'LBS',
                                    'Description' => 'Pounds'
                                ],
                                'Weight' => $request->get('package_weight'),
                            ]
                        ]
                    ]
                ]
            ];
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
            return response()->json(['payload' => $rateResponse], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
