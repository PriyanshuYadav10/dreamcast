<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="container mt-5">
        <h3>Create User</h3>
        <div id="response-message" style="display:none;" class="alert"></div>
        <form id="user-form" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" >
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" >
                @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" >{{ old('description') }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="role_id">User Role</label>
                <select name="role_id" class="form-control @error('role_id') is-invalid @enderror">
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                    @endforeach
                </select>
                @error('role_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="profile_image">Profile Image</label>
                <input type="file" name="profile_image" id="profile_image" class="form-control @error('profile_image') is-invalid @enderror" accept="image/*">
                @error('profile_image')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="button" id="submit" class="btn btn-primary">Submit</button>
        </form>

        <div class="table-data">
            <h3>User List</h3>
            <table>
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>

                </tr>
                </thead>
                <tbody id="tbody">
                @forelse($users as $k => $user)
                    <tr>
                        <td>{{ ++$k }}</td>
                        <td>
                            @if($user->profile_image)
                                <img src="{{ asset('images/profiles/' . $user->profile_image) }}" alt="Image" width="50">
                            @else
                                <p>No Image</p>
                            @endif
                        </td>
                        <td>{{ $user->name ?? '' }}</td>
                        <td>{{ $user->email ?? '' }}</td>
                        <td>{{ $user->phone ?? '' }}</td>
                        <td>{{ $user->role->display_name ?? '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No data found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script>
        $(document).ready(function() {
            jQuery.validator.addMethod("extension", function(value, element, param) {
                if (this.optional(element)) {
                    return true;
                }
                var fileName = value.split('.').pop().toLowerCase();
                return param.indexOf(fileName) !== -1;
            });

            jQuery.validator.addMethod("filesize", function(value, element, param) {
                if (this.optional(element)) {
                    return true;
                }
                return element.files[0].size <= param;
            }, "File size must be less than {0} bytes.");

            jQuery.validator.addMethod("indianPhone", function(value, element) {
                return this.optional(element) || /^[7-9]\d{9}$/.test(value);
            }, "Please enter a valid Indian phone number starting with 7, 8, or 9.");


            $("#user-form").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10,
                        indianPhone: true
                    },
                    description: {
                        required: true,
                        minlength: 10
                    },
                    profile_image: {
                        required: true,
                        extension: "jpeg|jpg|png|gif",
                        filesize: 2048000
                    },
                    role_id: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Full Name is required.",
                        maxlength: "Full Name cannot exceed 255 characters."
                    },
                    email: {
                        required: "Email Address is required.",
                        email: "Please enter a valid Email Address."
                    },
                    phone: {
                        required: "Phone Number is required.",
                        digits: "Phone Number must be exactly 10 digits.",
                        minlength: "Phone Number must be exactly 10 digits.",
                        maxlength: "Phone Number must be exactly 10 digits."
                    },
                    description: {
                        required: "Description is required.",
                        minlength: "Description should be at least 10 characters."
                    },
                    profile_image: {
                        required: "Profile Picture is required.",
                        extension: "Only jpeg, jpg, png, and gif formats are allowed.",
                        filesize: "Profile Picture size must be less than 2MB."
                    },
                    role_id: {
                        required: "User Role is required."
                    }
                },
                errorElement: "div",
                errorPlacement: function(error, element) {
                    error.addClass("invalid-feedback");
                    element.closest('.form-group').append(error);
                },
                highlight: function(element) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element) {
                    $(element).removeClass("is-invalid");
                }
            });
            
            $('#submit').on('click', function(e) {
                e.preventDefault();
                if ($("#user-form").valid()) {
                    let formData = new FormData($('#user-form')[0]);
                    $.ajax({
                        url: "{{ route('user.store') }}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#tbody').html(response.html);
                            $('#response-message')
                                .removeClass('alert-danger')
                                .addClass('alert-success')
                                .html('Form submitted successfully!')
                                .show();
                            // Reset form after success
                            $('#user-form')[0].reset();
                            setTimeout(function() {
                                $('#response-message').fadeOut();
                            }, 2000);
                        },
                        error: function(xhr) {
                            $('.invalid-feedback').remove();
                            $('.form-control').removeClass('is-invalid');
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    let inputElement = $('#' + key);
                                    inputElement.addClass('is-invalid');
                                    let fieldName = inputElement.closest('.form-group').find('label').text();
                                    inputElement.closest('.form-group').append('<div class="invalid-feedback">' + ' ' + value[0] + '</div>');
                                });
                            } else {
                                $('#response-message')
                                    .removeClass('alert-success')
                                    .addClass('alert-danger')
                                    .html('An unexpected error occurred. Please try again later.')
                                    .show();
                            }
                        }
                    });
                } else {
                    console.log("Form validation failed");
                }
            });
        });
    </script>
</body>
</html>