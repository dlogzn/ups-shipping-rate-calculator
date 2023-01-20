<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/storage/link', function () {
    exec(symlink('/home/goodgros/businessautomata.com/application/storage/app/public', '/home/goodgros/businessautomata.com/storage'));
});


Route::get('/clear/all', function() {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return response()->json('all cache cleared');
});
Route::get('/', function () {
    return redirect()->to('/login');
});
Route::group([
    'middleware' => 'allow.front.panel.access'
], __DIR__ . '/front_panel.php');
Route::group([
    'prefix' => '/account/panel',
    'middleware' => 'allow.account.panel.access'
], __DIR__ . '/account_panel.php');



Route::get('/test-rate', function () {
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
                            <CustomerContext></CustomerContext>
                        </TransactionReference>
                        <RequestAction>Rate</RequestAction>
                        <RequestOption>Ratetimeintransit</RequestOption>
                    </Request>

                <Shipment>
                    <DeliveryTimeInformation>
                        <PackageBillType>03</PackageBillType>
                        <Pickup>
                            <Date>' . date('Ymd') . '</Date>
                        </Pickup>
                    </DeliveryTimeInformation>
                    <RateInformation>
                        <NegotiatedRatesIndicator/>
                    </RateInformation>
                    <Shipper>
                        <Name>' . env('UPS_SHIPPER_NAME') . '</Name>
                        <AttentionName>' . env('UPS_SHIPPER_NAME') . '</AttentionName>
                        <PhoneNumber></PhoneNumber>
                        <FaxNumber></FaxNumber>
                        <ShipperNumber>' . env('UPS_SHIPPER_NUMBER') . '</ShipperNumber>
                        <Address>
                            <AddressLine1></AddressLine1>
                            <City>BROOKLYN</City>
                            <StateProvinceCode>NY</StateProvinceCode>
                            <PostalCode>11204</PostalCode>
                            <CountryCode>US</CountryCode>
                        </Address>
                    </Shipper>
                    <ShipTo>
                        <CompanyName></CompanyName>
                        <AttentionName></AttentionName>
                        <PhoneNumber></PhoneNumber>
                        <FaxNumber></FaxNumber>
                        <Address>
                            <AddressLine1></AddressLine1>
                            <City>BROOKLYN</City>
                            <StateProvinceCode>NY</StateProvinceCode>
                            <PostalCode>11204</PostalCode>
                            <CountryCode>US</CountryCode>
                        </Address>
                    </ShipTo>
                    <ShipFrom>
                        <CompanyName></CompanyName>
                        <AttentionName></AttentionName>
                        <PhoneNumber></PhoneNumber>
                        <FaxNumber></FaxNumber>
                        <Address>
                            <AddressLine1></AddressLine1>
                            <City>BROOKLYN</City>
                            <StateProvinceCode>NY</StateProvinceCode>
                            <PostalCode>11204</PostalCode>
                            <CountryCode>US</CountryCode>
                        </Address>
                    </ShipFrom>
                    <Service>
                        <Code>03</Code>
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
                            <Weight>10</Weight>
                        </PackageWeight>
                        <Dimensions>
                            <UnitOfMeasurement>IN</UnitOfMeasurement>
                            <Length>10</Length>
                            <Width>10</Width>
                            <Height>10</Height>
                        </Dimensions>
                    </Package>
                </Shipment>
                </RatingServiceSelectionRequest>
                ';


    $rateApiUrl = 'https://wwwcie.ups.com/ups.app/xml/Rate';
    $rateCh = curl_init();
    curl_setopt($rateCh, CURLOPT_URL, $rateApiUrl);
    curl_setopt($rateCh, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($rateCh, CURLOPT_HEADER, 0);
    curl_setopt($rateCh, CURLOPT_POST, 1);
    curl_setopt($rateCh, CURLOPT_POSTFIELDS, $rateApiData);
    curl_setopt($rateCh, CURLOPT_TIMEOUT, 3600);
    $rateApiResponse = curl_exec($rateCh);
    curl_close($rateCh);

    $rateApiResponse = json_encode(simplexml_load_string($rateApiResponse));
    $rateApiResponse = str_replace('&lt;', '<', $rateApiResponse);
    $rateApiResponse = str_replace('&gt;', '>', $rateApiResponse);
    $rateApiResponse = json_decode($rateApiResponse, true);

    dd($rateApiResponse);
    return response()->json(['payload' => $rateApiResponse], Response::HTTP_OK);
});
