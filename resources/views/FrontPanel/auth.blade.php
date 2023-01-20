@extends('layouts.front_panel')
@section('content')
    <div class="container-fluid bg-white">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mx-auto">
                <div class="text-center mt-4 mb-3">
                    <img src="{{ asset('storage/images/default/icons8-sign-in-100.png') }}" style="height: 100px; width: 100px;">
                </div>
                <div class="text-center border-bottom pb-3" style="border-color: #e8f3ed !important;">
                    <div class="h4 text_color_7">Log in to Account Panel</div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-7 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
                        <div id="sign_in_form_message" class="text-center text-danger my-3" style="height: 30px;"></div>
                        <form id="sign_in_form">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" name="email" id="email" placeholder="Email" autocomplete="off">
                                <label for="email">Email</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off">
                                <label for="password">Password</label>
                            </div>
                            <div class="row mb-4">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="remember_me">
                                        <label class="form-check-label" for="remember_me">
                                            Remember me
                                        </label>
                                    </div>
                                </div>
                                <div class="col text-end" style="font-size: 14px;">
                                    <a href="javascript:void(0)" class="text-decoration-none" style="color: #636363;">Forgot Password?</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col d-grid">
                                    <button type="submit" class="btn btn-primary" id="sign_in_form_submit">
                                        <span id="sign_in_form_submit_text">Sign in</span>
                                        <div id="sign_in_form_submit_processing" class="d-flex align-items-center sr-only">
                                            <span>Processing...</span>
                                            <div class="spinner-border spinner-border-sm ms-auto" role="status" aria-hidden="true"></div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div style="height: 50px;"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).on('submit', '#sign_in_form', function(event) {
            event.preventDefault();
            $('#sign_in_form_submit').addClass('disabled');
            $('#sign_in_form_submit_processing').removeClass('sr-only');
            $('#sign_in_form_submit_text').addClass('sr-only');
            $('#sign_in_form_message').empty();

            let formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('remember_me', $('#remember_me').prop('checked') ? 1 : 0);

            $.ajax({
                method: 'post',
                url: '{{ url('/login/authenticate') }}',
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                global: false,
                success: function (result) {
                    console.log(result);
                    $('#sign_in_form_submit').removeClass('disabled');
                    $('#sign_in_form_submit_text').removeClass('sr-only');
                    $('#sign_in_form_submit_processing').addClass('sr-only');
                    location = '{{ url('/account/panel/shipping-rate') }}';
                },
                error: function (xhr) {
                    console.log(xhr);
                    $('#sign_in_form_submit').removeClass('disabled');
                    $('#sign_in_form_submit_text').removeClass('sr-only');
                    $('#sign_in_form_submit_processing').addClass('sr-only');
                    if (xhr.status === 500) {
                        $('#sign_in_form_message').text('Internal Server Error!');
                    }
                    if (xhr.status === 422 || xhr.status === 401) {
                        $('#sign_in_form_message').text('Unauthorized Access!');
                    }

                }
            });
        });
    </script>
@endsection
