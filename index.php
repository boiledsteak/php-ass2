<!-- no need password -->
<!-- minimum do check in check out -->
<!-- due 19 feb -->
<!-- extra features no extra marks -->
<!-- no delete feature -->
<!-- SQL statement should create database if no exist. add to db if yes exist -->
<!-- prepared statement not needed -->

<?php
session_start();

$request = strtolower($_SERVER['REQUEST_URI']);
$viewDir = '/pages/';
$musicDir = '/pics/';

$servername = "localhost";  
$username = "root";      
$password = "";      
$dbname = "php-ass2";

function normalComponent()
{
    echo'
    this is normal
    testomg
    ';
}

function adminComponent()
{
    // Check if the user is logged in
    if (isset($_SESSION['user'])) 
    {
        // Get the name of the currently logged-in user
        $currentUserName = $_SESSION['user']['name'];
        
        echo '<div class="canvas"><div class="mainpagefn">';
        echo '<div class="title">Welcome, ' . $currentUserName . '!</div>';

        // Search form
        echo '<div class="searchcon"><form method="post" action="/">';
        echo '<label for="searchUser">Search for user:</label>';
        echo '<div><input type="text" id="searchUser" name="searchUser">';
        echo '<button class="nicebtn" type="submit">Go</button></div>';
        echo '</form></div>';

        // Display search results or all users if form not submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchUser'])) 
        {
            $searchUserName = strtolower(htmlspecialchars($_POST['searchUser']));
            // If search input is empty, display all users
            if (empty($searchUserName)) 
            {
                displayAllUsers();
            } 
            else 
            {
                searchForUser($searchUserName);
            }
        } 
        else 
        {
            displayAllUsers();
        }

        

        echo '
            <div class="abilitybox">
                <div class="abilities">
                    Parking Locations
                </div>
                <form class="plocform" method="post" action="/insertparkingloc">
                <div class="form-row">
                    <label for="locName">Location Name:</label>
                    <input type="text" id="locName" name="locName" required>
                </div>

                <div class="form-row">
                    <label for="locDescription">Description:</label>
                    <input type="text" id="locDescription" name="locDescription">
                </div>

                <div class="form-row">
                    <label for="locCapacity">Capacity:</label>
                    <input type="number" id="locCapacity" name="locCapacity" required>
                </div>

                <div class="form-row">
                    <label for="locCostPerHour">Cost Per Hour:</label>
                    <input type="text" id="locCostPerHour" name="locCostPerHour">
                </div>

                <div class="form-row">
                    <label for="locCostPerHourLateCheckOut">Cost Per Hour Late Check Out:</label>
                    <input type="text" id="locCostPerHourLateCheckOut" name="locCostPerHourLateCheckOut">
                </div>

                    <button class="nicebtn" type="submit">Add Parking Location</button>
                </form>

            </div>
        ';
        echo '</div></div>';
    } 
    else 
    {
        echo '<p>No user logged in.</p>';
    }
}

function displayAllUsers()
{
    global $servername, $username, $password, $dbname;

    try 
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->query("SELECT * FROM usertable");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($users) 
        {
            echo '<div class="abilitybox"><div class="abilities" >All users</div>';
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>Name</th><th>Role</th><th>Surname</th><th>Email</th><th>Phone</th></tr>';
            
            foreach ($users as $user) 
            {
                echo '<tr>';
                echo '<td>' . $user['id'] . '</td>';
                echo '<td>' . $user['name'] . '</td>';
                echo '<td>' . $user['role'] . '</td>';
                echo '<td>' . $user['surname'] . '</td>';
                echo '<td>' . $user['email'] . '</td>';
                echo '<td>' . $user['phone'] . '</td>';
                echo '</tr>';
            }
            
            echo '</table></div>';
        } 
        else 
        {
            echo '<p>No users found.</p>';
        }
    } 
    catch (PDOException $e) 
    {
        echo "Connection failed: " . $e->getMessage();
    }
}

function searchForUser($searchUserName)
{
    global $servername, $username, $password, $dbname;

    try 
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL statement to search for a specific user
        $sql = "SELECT * FROM usertable WHERE name = :searchUserName";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':searchUserName', $searchUserName);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) 
        {
            echo '<div class="abilitybox"><div class="abilities" >'.$user['name'].'</div>';
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>Name</th><th>Role</th><th>Surname</th><th>Email</th><th>Phone</th></tr>';
            echo '<tr>';
            echo '<td>' . $user['id'] . '</td>';
            echo '<td>' . $user['name'] . '</td>';
            echo '<td>' . $user['role'] . '</td>';
            echo '<td>' . $user['surname'] . '</td>';
            echo '<td>' . $user['email'] . '</td>';
            echo '<td>' . $user['phone'] . '</td>';
            echo '</tr>';
            echo '</table></div>';
        } 
        else 
        {
            echo '<p>No user found with the name ' . $searchUserName . '.</p>';
        }
    } 
    catch (PDOException $e) 
    {
        echo "Connection failed: " . $e->getMessage();
    }
}


function loginComponent()
{
    echo '
        <div class="canvas">
            <div class="mainpagefn">
                <div class="title">
                    Login with your username!
                </div>
                <form class="nameform" method="post" action="/loginuser">
                    <div class="namebox">
                        <div class="thename">
                            Username: 
                        </div>
                        <input type="text" id="fname" name="fname" required>
                    </div>
                    <div class="optionsbox">
                        <button type="submit" class="options">
                        Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    ';
}

function registerComponent()
{
    echo '
        <div class="canvas">
            <div class="mainpagefn">
                <div class="title">
                Please enter account details
                </div>
                <form class="nameform" method="post" action="/registernew">
                    <div class="namebox">
                        <div class="thename">
                            Username: 
                        </div>
                        <input type="text" id="fname" name="fname" required>
                    </div>
                    <div class="namebox">
                        <div class="thename">
                            Surname: 
                        </div>
                        <input type="text" id="fname" name="fsname" required>
                    </div>
                    <div class="namebox">
                        <div class="thename">
                            Role: 
                        </div>
                        <input type="text" id="fname" name="frole" required>
                    </div>
                    <div class="namebox">
                        <div class="thename">
                            Email: 
                        </div>
                        <input type="text" id="fname" name="femail" required>
                    </div>
                    <div class="namebox">
                        <div class="thename">
                            Phone number: 
                        </div>
                        <input type="text" id="fname" name="fphone" required>
                    </div>
                    <div class="optionsbox">
                        <button type="submit" class="options">
                        Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    ';
}

function headerComponent()
{
    echo '
    <div class="outerhead">
        <div class="thehead">
            <a class="thelogo" href="/">
                Easy Parking
            </a>
            <div class="menoptions">';

    if (isset($_SESSION['user'])) 
    {
        // If user is logged in, display Logout button
        echo '
            <div class="menoption">
                <a href="/logout">Logout</a>
            </div>';
    } 
    else 
    {
        // If user is not logged in, display Login button
        echo '
            <div class="menoption">
                <a href="/login">Login</a>
            </div>';
    }

    echo '
                <div class="menoption">
                    <a href="/register">Register</a>
                </div>
            </div>
        </div>
    </div>';
}


function btmComponent()
{
    echo '
    <div class="btm"></div>
    ';
}

// Router API
switch ($request) {

    case '/':
    {
        // Check if the user is logged in
        if (isset($_SESSION['user'])) 
        {
            // Get the user's role from the session
            $userRole = $_SESSION['user']['role'];
            require __DIR__ . $viewDir . 'mainpage.php';
            headerComponent();
            // Call the appropriate component based on the user role
            if ($userRole === 'admin') 
            {
                adminComponent();
            } 
            elseif ($userRole === 'normal') 
            {
                normalComponent();
            }
        }
        else 
        {
            // Redirect to /login if not logged in
            header("Location: /login");
            exit;
        }
    
        btmComponent();      
        break;
    }

    case '/insertparkingloc':
    {
        // Create a connection to the database
        try 
        {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // Set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") 
            {
                // Get data from the form
                $locName = strtolower(htmlspecialchars($_POST['locName']));
                $locDescription = htmlspecialchars($_POST['locDescription']);
                $locCapacity = intval($_POST['locCapacity']);
                $locCostPerHour = isset($_POST['locCostPerHour']) ? floatval($_POST['locCostPerHour']) : null;
                $locCostPerHourLateCheckOut = isset($_POST['locCostPerHourLateCheckOut']) ? floatval($_POST['locCostPerHourLateCheckOut']) : null;

                // SQL statement to insert data into the parkinglocs table
                $sql = "INSERT INTO parkinglocs (Location, Description, Capacity, CostPerHour, CostPerHourLateCheckOut) VALUES (:locName, :locDescription, :locCapacity, :locCostPerHour, :locCostPerHourLateCheckOut)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':locName', $locName);
                $stmt->bindParam(':locDescription', $locDescription);
                $stmt->bindParam(':locCapacity', $locCapacity);
                $stmt->bindParam(':locCostPerHour', $locCostPerHour);
                $stmt->bindParam(':locCostPerHourLateCheckOut', $locCostPerHourLateCheckOut);

                // Execute the statement
                $stmt->execute();

                // Redirect back to admin component or any desired destination
                header('Location: /');
            }
        } 
        catch (PDOException $e) 
        {
            // Handle database connection error
            echo "Connection failed: " . $e->getMessage();
        }

        break;
    }
        
    case '/css':
    {
        header('Content-Type: text/css');
        require __DIR__ . '/main.css';
        break;
    }

    case '/register':
    {
        require __DIR__ . $viewDir . 'mainpage.php';
        headerComponent();
        registerComponent();
        btmComponent();      
        break;
    }

    case '/registernew':
    {
        // Create a connection to the database
        try 
        {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // Set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") 
            {

                // Get data from the form
                $name = strtolower(htmlspecialchars($_POST['fname']));
                $sname = strtolower(htmlspecialchars($_POST['fsname']));
                $role = strtolower(htmlspecialchars($_POST['frole']));
                $email = strtolower(htmlspecialchars($_POST['femail']));
                $phone = intval($_POST['fphone']);  

                // SQL statement to insert data into the usertable
                $sql = "INSERT INTO usertable (name, role, surname, email, phone) VALUES (:name, :role, :sname, :email, :phone)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':role', $role);
                $stmt->bindParam(':sname', $sname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone', $phone);

                // Execute the statement
                $stmt->execute();

                // Redirect back
                header('Location: /');
            }
        } 
        catch (PDOException $e) 
        {
            // put into error_log when ready
            echo "Connection failed: " . $e->getMessage();
        }

        break;
    }

    case '/login':
    {
        require __DIR__ . $viewDir . 'mainpage.php';
        headerComponent();
        loginComponent();
        btmComponent();      
        break;
    }

    case '/loginuser':
    {
        // Create a connection to the database
        try 
        {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // Set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") 
            {
                // Get data from the form
                $username = strtolower(htmlspecialchars($_POST['fname']));
    
                // SQL statement to check if the username exists
                $sql = "SELECT * FROM usertable WHERE name = :username";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->execute();
    
                // Fetch the result
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($user) 
                {
                    // Store all user attributes in the session
                    $_SESSION['user'] = $user;
                    // Redirect to / 
                    header("Location: /");
                    exit;
                } 
                else 
                {
                    // Redirect back to /login if the username doesn't exist
                    header("Location: /login");
                    exit;
                }
            }
        } 
        catch (PDOException $e) 
        {
            // Handle database connection error
            echo "Connection failed: " . $e->getMessage();
        }
        break;
    }
    
    case '/logout':
    {
        // Check if the user is logged in
        if (isset($_SESSION['user'])) 
        {
            // Unset the session variable
            unset($_SESSION['user']);
        }
        
        // Redirect to the home page or any desired destination
        header("Location: /");
        exit;
    }
        
        
    default:
    {
        http_response_code(404);
        $errorPageUrl = "https://http.cat/404";
        header("Location: $errorPageUrl");
        exit;
    }
}
// Close the connection
$conn = null;
?>