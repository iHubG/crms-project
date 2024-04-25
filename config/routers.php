<?php 

    // Define your routes
    $routes = [
        '/crms-project/' => 'index',
        '/crms-project/view/' => 'login',
        '/crms-project/admin-login' => 'admin-login',
        '/crms-project/instructor-login' => 'instructor-login',
        '/crms-project/student-login' => 'student-login',
        '/tutorial/login_process' => 'login_process',
        '/tutorial/register_validation' => 'register_validation',
    ];

    // Get the current URL
    $url = $_SERVER['REQUEST_URI'];

    // Remove query string from URL
    $url = strtok($url, '?');

    // Check if the requested route exists
    if (array_key_exists($url, $routes)) {
        // If the route exists, call the associated function or include the corresponding file
        $route = $routes[$url];
        switch ($route) {
            case 'index':
                require ('./view/login.view.php'); // Include the file for the home page
                break;
            case 'login':
                require ('./view/login.view.php'); // Include the file for the home page
                break;
            case 'admin-login':
                require ('./view/admin.view.php'); // Include the file for the home page
                break;
            case 'instructor-login':
                require ('./view/instructor.view.php'); // Include the file for the home page
                break;
            case 'student-login':
                require ('./view/student.view.php'); // Include the file for the home page
                break;
            case 'register_validation':
                require ('./view/admin.view.php'); // Include the file for the home page
                break;
            // Add more routes as needed
            default:
                // Handle 404 error
                echo "<h1 class='text-center text-muted my-5'>404 Page Not Found</h1>";
                break;
        }
    } else {
        // Handle 404 error
        echo "<h1 class='text-center text-muted my-5'>404 Page Not Found</h1>";

    }
