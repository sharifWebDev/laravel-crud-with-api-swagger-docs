@extends('layouts.admin') 
@section('title', 'Admin | Students') 
@section('page-headder') 
@endsection 
@section('content')
    <div class="py-2 my-auto mb-3 bg-white border shadow-md row align-items-center">
        <div class="col-sm-6"> <span class="my-auto h6 page-headder">@yield('page-headder')</span>
            <ol class="bg-white breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="text-primary"><i class="fa fa-home"></i></a>
                </li>
                <li class="breadcrumb-item text-dark"><a href="{{ route('admin.students.index') }}">Students</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="p-0 bg-white card">
            <div class="py-3 card-header justify-content-between">
                <h4 class="float-left pt-2 card-title">Update Students</h4>
            </div>
            <div class="container p-4">

                @include('admin.students.partials.edit-form')

            </div>
        </div>
    </div> <!-- /.content -->

 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>
    $(document).ready(async function() {
        const id = window.location.pathname.split('/').slice(-2, -1)[0];

        if (!id) {
            console.error("Invalid ID.");
            return;
        }
        var baseUrl = `{{ url('api/v1/students') }}`;

        try {
            let response = await fetch(baseUrl + `/${id}`);
            if (!response.ok) {
                console.log(`HTTP error! Status: ${response.status}`);
                showErrorMessage(`HTTP error! Status: ${response.status}`);
            }
            let data = await response.json();

            if (data.data) {

                Object.entries(data.data).forEach(([key, value]) => {
                    let element = document.querySelector(`[name="${key}"]`);

                    if (!element) return;

                    switch (element.type) {
                        case "checkbox":
                            element.checked = Boolean(value);
                            break;

                        case "radio":
                            let radioElement = document.querySelector(
                                `[name="${key}"][value="${value}"]`);
                            if (radioElement) radioElement.checked = true;
                            break;

                        case "select":
                        case "select-one":
                        case "select-multiple":
                            [...element.options].forEach(option => {
                                option.selected = Array.isArray(value) ? value.includes(
                                    option.value) : option.value == value;
                            });
                            break;

                        case "file":
                            break;

                        default:
                            if (value !== null && value !== undefined) {
                                element.value = value;
                            }
                            break;
                    }
                });
            }

        } catch (error) {
            console.error("Error fetching data:", error);
            showErrorMessage("Error fetching data:", error);
        }



        function getFormId(form) {
            return $(form).attr('id') || 'createForm';
        }

        function findFormId(input) {
            return $(input).closest('form').attr('id');
        }

        function getCsrfToken() {
            return $('meta[name="csrf-token"]').attr('content');
        }

        $('form').on('submit', function(e) {

            var formId = getFormId(this);
            e.preventDefault();

            if (!validateForm(formId)) {
                return;
            }

            $.ajax({
                url: this.action,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                data: new FormData(this),
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#' + formId + ' #submitButton').prop('disabled', true).text(
                        'Submitting...');
                },
                success: function(response) {
                    $('#' + formId + ' #submitButton').prop('disabled', false).text(
                        'Submit');

                    if (response.success) {
                        $('#' + formId)[0].reset();
                        showSuccessMessage(response.message);
                    } else {
                        showErrorMessage(response.message);
                    }
                },
                error: function(xhr) {
                    $('#' + formId + ' #submitButton').prop('disabled', false).text(
                        'Submit');

                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        showValidationErrors(errors, formId);
                    } else {
                        showErrorMessage("An unexpected error occurred.");
                    }
                }
            });
        });


        function validateForm(formId) {
            let isValid = true;
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $('#' + formId).find('input[required], textarea[required], select[required]').each(function() {
                if (!$(this).val().trim()) {
                    showFieldError($(this), "This field is required.");
                    isValid = false;
                }
            });


            $('#' + formId + ' #submitButton').prop('disabled', !isValid);
            return isValid;
        }


        function showFieldError(input, message) {
            input.addClass('is-invalid');
            if (input.is(':radio')) {
                input.closest('.form-group').append('<div class="invalid-feedback d-block">' + message +
                    '</div>');
            } else {
                input.after('<div class="invalid-feedback">' + message + '</div>');
            }
        }


        $(document).on('input change', 'input, textarea, select', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();

            const formId = findFormId(this);
            validateForm(formId);

            const isValid = validateForm(formId);
            $('#' + formId + ' #submitButton').prop('disabled', !isValid);
            console.log("Form ID:", formId, "Valid:", isValid);
        });


        function showValidationErrors(errors, formId) {
            $.each(errors, function(field, messages) {
                var input = $('#' + formId + ' [name="' + field + '"]');
                showFieldError(input, messages[0]);
            });
        }

        function showSuccessMessage(message) {
            toastr.success(message);
            // history.back();
        }

        function showErrorMessage(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
            });
        }
    });
</script>
@endsection
