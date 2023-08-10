<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- PAGE settings -->
    <title><?= isset($pageTitle) ? "$pageTitle - " : ""; ?>HFESTS</title>
    <!-- CSS dependencies -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
          type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/style2.css" type="text/css">
</head>

<body>
<div class="py-5 text-center filter-dark">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="display-3 text-capitalize">HFESTS</h1>
                <p class="lead text-white">Health Facility Employee Status Tracking System</p>
            </div>
        </div>
    </div>
</div>
<nav class="navbar navbar-expand-md navbar-dark bg-secondary mb-3">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <b>HFESTS</b>
        </a>

        <button class="navbar-toggler navbar-toggler-right justify-content-end" type="button" data-toggle="collapse"
                data-target="#navbarSecondarySupportedContent" aria-controls="navbar2SupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="navbarSecondarySupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Data sheets
                    </a>
                    <div class="dropdown-menu " aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="facility.php">Facilities</a>
                        <a class="dropdown-item" href="employee.php">Employees</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="employment.php">Employment</a>
                        <a class="dropdown-item" href="infection.php">Infections</a>
                        <a class="dropdown-item" href="vaccination.php">Vaccinations</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Basic Information
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="facility-type.php">Facility Types</a>
                        <a class="dropdown-item" href="infection-type.php">Infection Types</a>
                        <a class="dropdown-item" href="employee-role.php">Employee Roles</a>
                        <a class="dropdown-item" href="vaccination-type.php">Vaccination Types</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Reports
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="report6.php">6 - Facility Details</a>
                        <a class="dropdown-item" href="report7.php">7 - Employee By Facility</a>
                        <a class="dropdown-item" href="report8.php">8 - Employee Schedule</a>
                        <a class="dropdown-item" href="report9.php">9 - Covid in Last 2 Weeks </a>
                        <a class="dropdown-item" href="report10.php">10 - Emails Sent</a>
                        <a class="dropdown-item" href="report11.php">11 - By Facility in Last Two Weeks</a>
                        <a class="dropdown-item" href="report12.php">12 - Facility Scheduled Hours</a>
                        <a class="dropdown-item" href="report13.php">13 - Number Of Infected For Facility</a>
                        <a class="dropdown-item" href="report14.php">14 - Number of Facilities</a>
                        <a class="dropdown-item" href="report15.php">15 - Nurse Summary</a>
                        <a class="dropdown-item" href="report16.php">16 - Times Infected</a>
                        <a class="dropdown-item" href="report17.php">17 - Lack of Covid</a>
                    </div>
                </li>

            </ul>
        </div>



    </div>
</nav>
<div class="pb-5">
    <div class="container">