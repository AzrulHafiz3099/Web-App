<?php
// vRegister.php
require_once "./pRegisterProcess.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Supply Manager | Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #fdfcfb, #f3c623);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200vh;
            color: #000;
        }

        .registration_form {
            background: #ffffff;
            color: #000;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 800px;
            text-align: left;
        }

        .registration_form .title {
            font-size: 30px;
            font-weight: bold;
            color: #f83600;
            margin-bottom: 20px;
            text-align: center;
        }

        .registration_form .form-control {
            border-radius: 20px;
            padding: 10px 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }

        .registration_form .btn {
            background: linear-gradient(135deg, #f83600, #ff9a3c);
            border: none;
            border-radius: 30px;
            color: white;
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        .registration_form .btn:hover {
            background: linear-gradient(135deg, #ff9a3c, #f83600);
        }

        .registration_form .invalid-feedback {
            font-size: 0.9em;
        }

        .registration_form label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        #map {
            width: 100%;
            height: 300px;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 15px;
        }
    </style>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Validation rules
        const validationRules = {
            fullname: value => value.trim() !== "" || "Vendor Name is required.",
            username: value => /^[a-zA-Z0-9]{4,}$/.test(value) || "Username must be at least 4 characters, alphanumeric.",
            password: value => value.length >= 6 || "Password must be at least 6 characters.",
            contact_num: value => /^\d{10,}$/.test(value) || "Contact Number must contain at least 10 digits.",
            email: value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) || "Please enter a valid email address.",
        };

        const form = document.querySelector("form");
        const inputs = form.querySelectorAll("input");

        inputs.forEach(input => {
            const rule = validationRules[input.name];

            if (rule) {
                input.addEventListener("blur", () => validateField(input, rule));
                input.addEventListener("input", () => clearError(input));
            }
        });

        function validateField(input, rule) {
            const error = rule(input.value);
            if (error !== true) {
                showError(input, error);
            } else {
                clearError(input);
            }
        }

        function showError(input, message) {
            input.classList.add("is-invalid");

            let feedback = input.nextElementSibling;
            if (!feedback || !feedback.classList.contains("invalid-feedback")) {
                feedback = document.createElement("div");
                feedback.classList.add("invalid-feedback");
                input.parentNode.appendChild(feedback);
            }
            feedback.textContent = message;
        }

        function clearError(input) {
            input.classList.remove("is-invalid");
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains("invalid-feedback")) {
                feedback.textContent = "";
            }
        }
    });
</script>

</head>
<body>
    <div class="registration_form">
        <div class="title">Create an Account</div>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php
                    echo $_SESSION['message'] . "<br>";
                    unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <form action="./pRegisterProcess.php" method="post">
    <div class="row">
        <div class="col-md-6 form-group">
            <label>Vendor Name</label>
            <input class="form-control <?php echo isset($error['fullname']) ? 'is-invalid' : ''; ?>" 
                   type="text" name="fullname" value="<?php echo isset($vfullname) ? $vfullname : ''; ?>">
            <?php if (isset($error['fullname'])): ?>
                <div class="invalid-feedback"><?php echo $error['fullname']; ?></div>
            <?php endif; ?>
        </div>

        <div class="col-md-6 form-group">
            <label>Username</label>
            <input class="form-control <?php echo isset($error['username']) ? 'is-invalid' : ''; ?>" 
                   type="text" name="username" value="<?php echo isset($vusername) ? $vusername : ''; ?>">
            <?php if (isset($error['username'])): ?>
                <div class="invalid-feedback"><?php echo $error['username']; ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label>Password</label>
            <input class="form-control <?php echo isset($error['password']) ? 'is-invalid' : ''; ?>" 
                   type="password" name="password">
            <?php if (isset($error['password'])): ?>
                <div class="invalid-feedback"><?php echo $error['password']; ?></div>
            <?php endif; ?>
        </div>

        <div class="col-md-6 form-group">
            <label>Contact Number</label>
            <input class="form-control <?php echo isset($error['contact_num']) ? 'is-invalid' : ''; ?>" 
                   type="text" name="contact_num" value="<?php echo isset($vcontact) ? $vcontact : ''; ?>">
            <?php if (isset($error['contact_num'])): ?>
                <div class="invalid-feedback"><?php echo $error['contact_num']; ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label>Email</label>
            <input class="form-control <?php echo isset($error['email']) ? 'is-invalid' : ''; ?>" 
                   type="email" name="email" value="<?php echo isset($vemail) ? $vemail : ''; ?>">
            <?php if (isset($error['email'])): ?>
                <div class="invalid-feedback"><?php echo $error['email']; ?></div>
            <?php endif; ?>
        </div>
    </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Pick Location</label>
                    <div id="map"></div>
                </div>

                <div class="col-md-6 form-group">
                    <label>Address</label>
                    <input class="form-control" type="text" id="address" name="address" readonly value="<?php echo isset($vaddress) ? $vaddress : ''; ?>">
                </div>

                <div class="col-md-6 form-group">
                    <label>Latitude</label>
                    <input class="form-control" type="text" id="latitude" name="latitude" readonly value="<?php echo isset($vlatitude) ? $vlatitude : ''; ?>">
                </div>

                <div class="col-md-6 form-group">
                    <label>Longitude</label>
                    <input class="form-control" type="text" id="longitude" name="longitude" readonly value="<?php echo isset($vlongitude) ? $vlongitude : ''; ?>">
                </div>
            </div>

            <button type="submit" name="create" class="btn">Create My Profile</button>
            <a href="./vLogin.php" style="text-decoration: none;"> 
                <p style="text-align: center; clear: both; margin-top: 25px; color: #f83600; text-decoration: underline;">Already have an account? Log in!</p>
            </a>
        </form>
    </div>

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBI-Bqpyxk6CgSTeB5ImREwm9762vUjnu8&libraries=places"></script>
    <script>
        let map, marker;

        function initMap() {
            const initialLocation = { lat: 3.1390, lng: 101.6869 }; // Default to Kuala Lumpur
            map = new google.maps.Map(document.getElementById("map"), {
                center: initialLocation,
                zoom: 13,
            });

            marker = new google.maps.Marker({
                position: initialLocation,
                map: map,
                draggable: true,
            });

            const geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(marker, 'dragend', function () {
                const position = marker.getPosition();
                document.getElementById("latitude").value = position.lat();
                document.getElementById("longitude").value = position.lng();

                geocoder.geocode({ location: position }, function (results, status) {
                    if (status === 'OK' && results[0]) {
                        document.getElementById("address").value = results[0].formatted_address;
                    }
                });
            });
        }

        window.onload = initMap;
    </script>
</body>
</html>
