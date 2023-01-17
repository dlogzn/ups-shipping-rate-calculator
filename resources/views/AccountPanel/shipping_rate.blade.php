@extends('layouts.account_panel')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mx-auto">
                <h3>Calculate Time and Cost</h3>
                <div class="text_color_a">
                    Quickly get estimated shipping quotes for our global package delivery services. Provide the origin, destination, and weight of your shipment to compare service details then sort your results by time or cost to find the most cost-effective shipping service.
                </div>

                <div class="row mt-3">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-4 mb-4 mb-xl-0">
                        <div id="shipping_rate_form_message" class="text-danger text-center" style="height: 30px;"></div>
                        <form id="shipping_rate_form">

                            <select class="form-select mb-3" name="country_id" id="country_id">
                                <option value="">Select a Country</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}" data-country_code="{{ $country->code }}">{{ $country->country }}</option>
                                @endforeach
                            </select>
                            <select class="form-select" name="service_code" id="service_code">
                                <option value="">Select Service</option>
                            </select>
                            <hr>
                            <div class="alert alert-info py-2">Where and When</div>
                            <div class="text_color_7 fw-bold">Ship From:</div>
                            <div class="row">
                                <div class="col-12 col-sm-6 mb-3 mb-sm-0">
                                    <div>
                                        <label for="origin_city" class="form-label">City</label>
                                        <input type="text" class="form-control" name="origin_city" id="origin_city">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label for="origin_zip_code" class="form-label">ZIP Code</label>
                                        <input type="text" class="form-control" name="origin_zip_code" id="origin_zip_code">
                                    </div>
                                </div>
                            </div>
{{--                            <div class="mt-3 text_color_7 fw-bold">Origin Type:</div>--}}
{{--                            <div class="form-check form-check-inline">--}}
{{--                                <input class="form-check-input" type="radio" name="origin_type" id="origin_type_commercial">--}}
{{--                                <label class="form-check-label" for="origin_type_commercial">--}}
{{--                                    Commercial--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="form-check form-check-inline">--}}
{{--                                <input class="form-check-input" type="radio" name="origin_type" id="origin_type_residential">--}}
{{--                                <label class="form-check-label" for="origin_type_residential">--}}
{{--                                    Residential--}}
{{--                                </label>--}}
{{--                            </div>--}}
                            <hr>
                            <div class="text_color_7 fw-bold">Ship To:</div>
                            <div class="row">
                                <div class="col-12 col-sm-6 mb-3 mb-sm-0">
                                    <div>
                                        <label for="destination_city" class="form-label">City</label>
                                        <input type="text" class="form-control" name="destination_city" id="destination_city">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label for="destination_zip_code" class="form-label">ZIP Code</label>
                                        <input type="text" class="form-control" name="destination_zip_code" id="destination_zip_code">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 text_color_7 fw-bold">Destination Type:</div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="destination_type" id="destination_type_commercial">
                                <label class="form-check-label" for="destination_type_commercial">
                                    Commercial
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="destination_type" id="destination_type_residential">
                                <label class="form-check-label" for="destination_type_residential">
                                    Residential
                                </label>
                            </div>
                            <div class="mt-3 text_color_7 fw-bold">When are you shipping?</div>
                            <div class="row">
                                <div class="col">
                                    <label for="shipping_date" class="form-label">Date</label>
                                    <input type="text" class="form-control" name="shipping_date" id="shipping_date">
                                </div>
                                <div class="col">
                                    <label for="shipping_time" class="form-label">Time</label>
                                    <input type="text" class="form-control" name="shipping_time" id="shipping_time">
                                </div>
                            </div>
                            <div class="mt-3 alert alert-info py-2">Package Information</div>
                            <div class="text_color_7 fw-bold">Package Dimension:</div>
                            <div class="row">
                                <div class="col-3">
                                    <div>
                                        <label for="package_length" class="form-label">Length</label>
                                        <input type="text" class="form-control" name="package_length" id="package_length">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div>
                                        <label for="package_width" class="form-label">Width</label>
                                        <input type="text" class="form-control" name="package_width" id="package_width">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div>
                                        <label for="package_height" class="form-label">Height</label>
                                        <input type="text" class="form-control" name="package_height" id="package_height">
                                    </div>
                                </div>
                                <div class="col-3 ps-0">
                                    <div>
                                        <label class="form-label invisible">Inches</label>
                                        <div class="form-control border_color_default bg-transparent">Inches</div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 text_color_7 fw-bold">Package Weight:</div>
                            <div class="row">
                                <div class="col-3">
                                    <div>
                                        <input type="text" class="form-control" name="package_weight" id="package_weight">
                                    </div>
                                </div>
                                <div class="col-3 ps-0">
                                    <div>
                                        <div class="form-control border_color_default bg-transparent">LBS</div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col d-grid">
                                        <button type="button" class="btn btn-warning" id="shipping_rate_form_clear">Clear Form</button>
                                    </div>
                                    <div class="col d-grid">
                                        <button type="submit" class="btn btn-primary" id="shipping_rate_form_submit">
                                            <span id="shipping_rate_form_submit_text">Calculate Cost</span>
                                            <div id="shipping_rate_form_submit_processing" class="d-flex align-items-center sr-only">
                                                <span>Processing...</span>
                                                <div class="spinner-border spinner-border-sm ms-auto" role="status" aria-hidden="true"></div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-8">
                        <div style="height: 30px;"></div>
                        <div class="p-3 bg-white">
{{--                            <div class="d-flex justify-content-between">--}}
{{--                                <div class="text_color_7">Rate Result</div>--}}
{{--                                <div class="text_color_a" id="rate_result_time">{{ date('Y-m-d H:i:s') }}</div>--}}
{{--                            </div>--}}
                            <div class="card border-0">

                                <div class="card-body">
                                    <div class="text-info">General Shipment Information</div>
                                    <div class="d-flex flex-row">
                                        <div class="pe-3">
                                            <div class="text_color_a">Method</div>
                                            <div class="text_color_a">Est. Delivery Date</div>
                                            <div class="text_color_a">Est. Delivery Time</div>
                                        </div>
                                        <div class="ps-3">
                                            <div class="text_color_7" id="method">---</div>
                                            <div class="text_color_7" id="delivery_date">---</div>
                                            <div class="text_color_7" id="delivery_time">---</div>
                                        </div>
                                    </div>

                                    <div class="text-info mt-4">Shipping Cost</div>
                                    <div class="d-flex flex-row">
                                        <div class="pe-3">
                                            <div class="text_color_a">Total Cost</div>
                                            <div class="text_color_a">Handling Fees</div>
                                            <div class="text_color_a">Total Price</div>
                                        </div>
                                        <div class="ps-3">
                                            <div class="text_color_7" id="total_cost">---</div>
                                            <div class="text_color_7" id="handling_fees">---</div>
                                            <div class="text_color_7" id="total_price">---</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        $(document).ready(function () {
            $('#shipping_date').datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $('#shipping_time').timepicker({
                timeFormat: 'HH:mm',
                interval: 15,
                minTime: '10',
                maxTime: '22:00',
                defaultTime: '15',
                startTime: '10:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
        });

        $(document).on('change', '#country_id', function () {
            if ($(this).find(':selected').val() === '') {
                $('#service_code').empty().append(`
                    <option value="">Select Service</option>
                `);
            } else {
                const countryCode = $(this).find(':selected').data('country_code');
                if (countryCode === 'CA') {
                    $('#service_code').empty().append(`
                        <option value="11">UPS Standard</option>
                    `);
                    $('#method').empty().text('UPS Standard');
                }
                if (countryCode === 'US') {
                    $('#service_code').empty().append(`
                        <option value="03">UPS Ground</option>
                    `);
                    $('#method').empty().text('UPS Ground');
                }
            }
        });

        $(document).on('submit', '#shipping_rate_form', function(event) {
            event.preventDefault();




            $('#shipping_rate_form_submit').addClass('disabled');
            $('#shipping_rate_form_submit_processing').removeClass('sr-only');
            $('#shipping_rate_form_submit_text').addClass('sr-only');
            $('#shipping_rate_form_message').empty();

            let formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');


            $.ajax({
                method: 'post',
                url: '{{ url('/account/panel/shipping-rate/calculate/price') }}',
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                global: false,
                success: function (result) {
                    console.log(result);
                    $('#shipping_rate_form_submit').removeClass('disabled');
                    $('#shipping_rate_form_submit_text').removeClass('sr-only');
                    $('#shipping_rate_form_submit_processing').addClass('sr-only');
                    if (result.payload.hasOwnProperty('response')) {
                        $('#shipping_rate_form_message').append(result.payload.response.errors[0].message);
                    } else {
                        let deliveryDate = result.payload.RateResponse.RatedShipment.TimeInTransit.ServiceSummary.EstimatedArrival.Arrival.Date;
                        let deliveryTime = result.payload.RateResponse.RatedShipment.TimeInTransit.ServiceSummary.EstimatedArrival.Arrival.Time;
                        let totalCharges = result.payload.RateResponse.RatedShipment.TotalCharges.CurrencyCode + ' ' + result.payload.RateResponse.RatedShipment.TotalCharges.MonetaryValue;
                        $('#delivery_date').empty().text(deliveryDate.replace(/(\d{4})(\d{2})(\d{2})/, '$1-$2-$3'));
                        $('#delivery_time').empty().text(deliveryTime.replace(/(\d{2})(\d{2})(\d{2})/, '$1:$2:$3'));
                        $('#total_cost').empty().text(totalCharges);
                        $('#total_price').empty().text(totalCharges);
                        $('#handling_fees').empty().text(result.payload.RateResponse.RatedShipment.TotalCharges.CurrencyCode + ' 0');
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    $('#shipping_rate_form_submit').removeClass('disabled');
                    $('#shipping_rate_form_submit_text').removeClass('sr-only');
                    $('#shipping_rate_form_submit_processing').addClass('sr-only');
                    if (xhr.status === 500) {
                        $('#shipping_rate_form_message').text('Internal Server Error!');
                    }

                    if (xhr.status === 400) {
                        $('#shipping_rate_form_message').text(xhr.responseJSON.message);
                    }

                }
            });
        });

    </script>

@endsection
