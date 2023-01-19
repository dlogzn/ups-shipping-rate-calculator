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
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-7 mb-4 mb-xl-0">
                        <div id="shipping_rate_form_message" class="text-danger text-center" style="height: 30px;"></div>
                        <form id="shipping_rate_form">




                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 mb-3 mb-xl-0">
                                    <div class="mt-3 alert alert-info py-2">Country and Service</div>
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
                                        <div class="col-12 col-sm-12 col-md-6 mb-3 mb-md-0">
                                            <div>
                                                <label for="origin_city" class="form-label">City</label>
                                                <input type="text" class="form-control" name="origin_city" id="origin_city">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6">
                                            <div>
                                                <label for="origin_zip_code" class="form-label">ZIP Code</label>
                                                <input type="text" class="form-control" name="origin_zip_code" id="origin_zip_code">
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="text_color_7 fw-bold">Ship To:</div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-6 mb-3 mb-md-0">
                                            <div>
                                                <label for="destination_city" class="form-label">City</label>
                                                <input type="text" class="form-control" name="destination_city" id="destination_city">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6">
                                            <div>
                                                <label for="destination_zip_code" class="form-label">ZIP Code</label>
                                                <input type="text" class="form-control" name="destination_zip_code" id="destination_zip_code">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-12 col-md-6 mb-3 mb-md-0">
                                            <div class="text_color_7 fw-bold">Destination Type</div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input destination_type" type="radio" name="destination_type" value="Commercial" id="destination_type_commercial" checked>
                                                <label class="form-check-label" for="destination_type_commercial">
                                                    Commercial
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input destination_type" type="radio" name="destination_type" value="Residential" id="destination_type_residential">
                                                <label class="form-check-label" for="destination_type_residential">
                                                    Residential
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6">
                                            <div class="text_color_7 fw-bold">Shipping Date</div>
                                            <div>
                                                <input type="text" class="form-control" name="shipping_date" id="shipping_date">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                    <div class="mt-3 alert alert-info py-2">Package Information</div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-6 mb-3 mb-md-0">
                                            <div>
                                                <div class="text_color_7 fw-bold">Number of Boxes</div>
                                                <input type="text" class="form-control" name="number_of_boxes" id="number_of_boxes" value="1">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6">
                                            <div class="text_color_7 fw-bold">Profit Margin</div>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="profit_margin" id="profit_margin">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="boxes_container" class="mt-4">
                                        <div class="card border-0">
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger">1</span>
                                            <div class="card-body">
                                                <div class="text_color_7 fw-bold">Package Dimension:</div>
                                                <div class="row">
                                                    <div class="col-3">
                                                        <div>
                                                            <label for="package_length_0" class="form-label">Length</label>
                                                            <input type="text" class="form-control" name="package_length[]" id="package_length_0">
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div>
                                                            <label for="package_width_0" class="form-label">Width</label>
                                                            <input type="text" class="form-control" name="package_width[]" id="package_width_0">
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div>
                                                            <label for="package_height_0" class="form-label">Height</label>
                                                            <input type="text" class="form-control" name="package_height[]" id="package_height_0">
                                                        </div>
                                                    </div>
                                                    <div class="col-3 ps-0">
                                                        <div>
                                                            <label class="form-label invisible">Inches</label>
                                                            <div class="form-control border-white bg-transparent">Inches</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-12 col-sm-12 col-md-6 mb-3 mb-md-0">
                                                        <div class="text_color_7 fw-bold">Package Weight</div>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="package_weight[]" id="package_weight_0">
                                                            <span class="input-group-text">LBS</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="d-flex flex-row-reverse mt-4">
                                <button type="submit" class="btn btn-primary" id="shipping_rate_form_submit">
                                    <span id="shipping_rate_form_submit_text">Calculate Cost</span>
                                    <div id="shipping_rate_form_submit_processing" class="d-flex align-items-center sr-only">
                                        <span>Processing...</span>
                                        <div class="spinner-border spinner-border-sm ms-auto" role="status" aria-hidden="true"></div>
                                    </div>
                                </button>
                                <button type="button" class="btn btn-warning me-4" id="shipping_rate_form_clear">Clear Form</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-5">
                        <div style="height: 30px;"></div>
                        <div id="rate_result">
                            <div class="card border-0">
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger">1</span>
                                <div class="card-body">
                                    <div class="text-info">General Shipment Information</div>
                                    <div class="d-flex flex-row">
                                        <div class="pe-3">
                                            <div class="text_color_a">Service</div>
                                            <div class="text_color_a">Pickup Date and Time</div>
                                            <div class="text_color_a">Business Days In Transit</div>
                                            <div class="text_color_a">Est. Delivery Date and Time</div>

                                        </div>
                                        <div class="ps-3">
                                            <div class="text_color_7">---</div>
                                            <div class="text_color_7">---</div>
                                            <div class="text_color_7">---</div>
                                            <div class="text_color_7">---</div>

                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col">
                                            <div class="text-info">Package Information</div>
                                            <div class="d-flex flex-row">
                                                <div class="pe-3">
                                                    <div class="text_color_a">Weight</div>
                                                    <div class="text_color_a">Dimension</div>
                                                </div>
                                                <div class="ps-3">
                                                    <div class="text_color_7">---</div>
                                                    <div class="text_color_7">---</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="text-info">Shipping Cost</div>
                                            <div class="d-flex flex-row">
                                                <div class="pe-3">
                                                    <div class="text_color_a">Negotiated Rate</div>
                                                    <div class="text_color_a">Commercial Rate</div>
                                                </div>
                                                <div class="ps-3">
                                                    <div class="text_color_7">---</div>
                                                    <div class="text_color_7">---</div>
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

        $(document).on('input', '#number_of_boxes', function () {
            let numberOfBoxes = $(this).val();
            if (numberOfBoxes !== '') {
                $('#boxes_container').empty();
                for (let i=0; i<numberOfBoxes; i++) {
                    $('#boxes_container').append(`
                        <div class="card border-0 mb-4">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger">` + (i+1) + `</span>
                            <div class="card-body">
                                <div class="text_color_7 fw-bold">Package Dimension:</div>
                                <div class="row">
                                    <div class="col-3">
                                        <div>
                                            <label for="package_length_` + i + `" class="form-label">Length</label>
                                            <input type="text" class="form-control" name="package_length[]" id="package_length_` + i + `">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div>
                                            <label for="package_width_` + i + `" class="form-label">Width</label>
                                            <input type="text" class="form-control" name="package_width[]" id="package_width_` + i + `">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div>
                                            <label for="package_height_` + i + `" class="form-label">Height</label>
                                            <input type="text" class="form-control" name="package_height[]" id="package_height_` + i + `">
                                        </div>
                                    </div>
                                    <div class="col-3 ps-0">
                                        <div>
                                            <label class="form-label invisible">Inches</label>
                                            <div class="form-control border-white bg-transparent">Inches</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12 col-sm-12 col-md-6 mb-3 mb-md-0">
                                        <div class="text_color_7 fw-bold">Package Weight</div>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="package_weight[]" id="package_weight_` + i + `">
                                            <span class="input-group-text">LBS</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                }
            }
        });

        $(document).on('change', '.destination_type', function () {
            if ($(this).val() === 'Commercial') {
                $('#regular_rate').parent().parent().children().eq(0).children().eq(1).text('Commercial Rate');
            }
            if ($(this).val() === 'Residential') {
                $('#regular_rate').parent().parent().children().eq(0).children().eq(1).text('Residential Rate');
            }
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
                }
                if (countryCode === 'US') {
                    $('#service_code').empty().append(`
                        <option value="03">UPS Ground</option>
                    `);
                }
            }
        });

        $(document).on('submit', '#shipping_rate_form', function(event) {
            event.preventDefault();

            $('#shipping_rate_form_submit').addClass('disabled');
            $('#shipping_rate_form_submit_processing').removeClass('sr-only');
            $('#shipping_rate_form_submit_text').addClass('sr-only');
            $('#shipping_rate_form').find('.invalid-feedback').remove();
            $('#shipping_rate_form').find('.has-validation').removeClass('has-validation');
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
                        $('#rate_result').empty();
                        let service = $('#service_code').val() === '03' ? 'UPS Ground' : 'UPS Standard';
                        let profitMargin = $('#profit_margin').val() === '' ? 100 : parseInt($('#profit_margin').val());

                        $.each(result.payload, function (key, value) {
                            let businessDaysInTransit = value.RateResponse.RatedShipment.TimeInTransit.ServiceSummary.EstimatedArrival.BusinessDaysInTransit + ' Days(s)';
                            let deliveryDate = value.RateResponse.RatedShipment.TimeInTransit.ServiceSummary.EstimatedArrival.Arrival.Date;
                            let pickupDate = value.RateResponse.RatedShipment.TimeInTransit.ServiceSummary.EstimatedArrival.Pickup.Date;
                            let deliveryTime = value.RateResponse.RatedShipment.TimeInTransit.ServiceSummary.EstimatedArrival.Arrival.Time;
                            let pickupTime = value.RateResponse.RatedShipment.TimeInTransit.ServiceSummary.EstimatedArrival.Pickup.Time;
                            let totalCharges = parseFloat(value.RateResponse.RatedShipment.TotalCharges.MonetaryValue);

                            if (profitMargin < 100) {
                                totalCharges = (totalCharges * profitMargin) / 100;
                            }

                            let negotiatedCharges;
                            if (value.RateResponse.RatedShipment.hasOwnProperty('NegotiatedRateCharges')) {
                                negotiatedCharges = parseFloat(value.RateResponse.RatedShipment.NegotiatedRateCharges.TotalCharge.MonetaryValue);
                                if (profitMargin < 100) {
                                    negotiatedCharges = (negotiatedCharges * profitMargin) / 100;
                                }
                                negotiatedCharges = value.RateResponse.RatedShipment.NegotiatedRateCharges.TotalCharge.CurrencyCode + ' ' + negotiatedCharges;
                            } else {
                                negotiatedCharges = 'Negotiated Rates Unavailable';
                            }
                            $('#rate_result').append(`
                                <div class="card border-0 mb-4">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger">` + (key+1) + `</span>
                                    <div class="card-body">
                                        <div class="text-info">General Shipment Information</div>
                                        <div class="d-flex flex-row">
                                            <div class="pe-3">
                                                <div class="text_color_a">Service</div>
                                                <div class="text_color_a">Pickup Date and Time</div>
                                                <div class="text_color_a">Business Days In Transit</div>
                                                <div class="text_color_a">Est. Delivery Date and Time</div>
                                            </div>
                                            <div class="ps-3">
                                                <div class="text_color_7">` + service + `</div>
                                                <div class="text_color_7">` + pickupDate.replace(/(\d{4})(\d{2})(\d{2})/, '$1-$2-$3') + ' ' + pickupTime.replace(/(\d{2})(\d{2})(\d{2})/, '$1:$2:$3') + `</div>
                                                <div class="text_color_7">` + businessDaysInTransit + `</div>
                                                <div class="text_color_7">` + deliveryDate.replace(/(\d{4})(\d{2})(\d{2})/, '$1-$2-$3') + ' ' + deliveryTime.replace(/(\d{2})(\d{2})(\d{2})/, '$1:$2:$3') + `</div>

                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col">
                                                <div class="text-info">Pacakge Information</div>
                                                <div class="d-flex flex-row">
                                                    <div class="pe-3">
                                                        <div class="text_color_a">Weight</div>
                                                        <div class="text_color_a">Dimension</div>
                                                    </div>
                                                    <div class="ps-3">
                                                        <div class="text_color_7">` + value.Package.Weight + ` LBS</div>
                                                        <div class="text_color_7">` + value.Package.Length + ` x ` + value.Package.Width + ` x ` + value.Package.Height + ` inch</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="text-info">Shipping Cost</div>
                                                <div class="d-flex flex-row">
                                                    <div class="pe-3">
                                                        <div class="text_color_a">Negotiated Rate</div>
                                                        <div class="text_color_a">` + $('input[name="destination_type"]:checked').val() + ` Rate</div>
                                                    </div>
                                                    <div class="ps-3">
                                                        <div class="text_color_7">` + negotiatedCharges + `</div>
                                                        <div class="text_color_7">` + value.RateResponse.RatedShipment.TotalCharges.CurrencyCode + ` ` + totalCharges + `</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);



                        });
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
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            if (key === 'country_id' || key === 'service_code' || key === 'origin_zip_code' || key === 'destination_zip_code' || key === 'origin_city' || key === 'destination_city' || key === 'shipping_date' || key === 'destination_type') {
                                $('#' + key).after('<div class="invalid-feedback d-block">' + value[0] + '</div>');
                            } else {
                                let splitKey = key.split('.')[0];
                                let originalKey = key.split('.')[0] + '_' + key.split('.')[1]
                                console.log(splitKey)
                                console.log(originalKey)
                                if (splitKey === 'package_weight') {
                                    $('#' + originalKey).parent().addClass('has-validation').append('<div class="invalid-feedback d-block">' + value[0] + '</div>');
                                } else {
                                    $('#' + originalKey).after('<div class="invalid-feedback d-block">' + value[0] + '</div>');
                                }
                            }
                        });
                    }
                }
            });
        });

    </script>

@endsection
