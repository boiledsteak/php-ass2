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
            <div class="menoptions">
                <div class="menoption">
                    <a href="/register">Register</a>
                </div>
                <div class="menoption">
                    <a href="/login">Login</a>
                </div>
            </div>
        </div>
    </div>
    ';
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
        require __DIR__ . $viewDir . 'mainpage.php';
        headerComponent();
        
        btmComponent();      
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

                echo "New record created successfully";
            }
        } 
        catch (PDOException $e) 
        {
            // put into error_log when ready
            echo "Connection failed: " . $e->getMessage();
        }

        break;
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