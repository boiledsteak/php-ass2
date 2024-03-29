<!-- due 19 feb -->
<?php
require_once './components/components.php';
require_once './db/db.php';
session_start();
date_default_timezone_set("Asia/Singapore");
$request = strtolower($_SERVER['REQUEST_URI']);
$viewDir = '/pages/';
$musicDir = '/pics/';


class User 
{
    private $id;
    private $name;
    private $role;
    private $surname;
    private $email;
    private $phone;

    public function __construct($id, $name, $role, $surname, $email, $phone) {
        $this->id = $id;
        $this->name = $name;
        $this->role = $role;
        $this->surname = $surname;
        $this->email = $email;
        $this->phone = $phone;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getRole() {
        return $this->role;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

   
}

function displayAllUsers()
{
    try 
    {
        global $conn;
        $stmt = $conn->query("SELECT * FROM usertable");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($users) 
        {
            echo '
                <div class="abilitybox">
                    <div class="abilities" >
                        All users
                    </div>
                    <table border="1">
                        <tr><th>ID</th><th>Name</th><th>Role</th><th>Surname</th><th>Email</th><th>Phone</th></tr>';
            
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
            
            echo '
                    </table>
                </div>
            ';
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

function searchForUser($searchTerm)
{
    try 
    {
        global $conn;
        // SQL statement to search for a user by name or ID
        $sql = "SELECT * FROM usertable WHERE name LIKE :searchTerm 
                OR id LIKE :searchTerm";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($users) 
        {
            echo '<div class="abilitybox"><div class="abilities">Search Results</div>';
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
            echo '<p>No users found matching the search term "' . $searchTerm . '".</p>';
        }
    } 
    catch (PDOException $e) 
    {
        echo "Connection failed: " . $e->getMessage();
    }
}

function displayAllPlocs()
{
    try 
    {
        global $conn;
        $stmt = $conn->query("SELECT * FROM parkinglocs");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($users) 
        {
            echo '<div class="abilitybox"><div class="abilities" >All Parking Locations</div>';
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>Location</th><th>Description</th><th>Capacity</th><th>CostPerHour</th><th>Late rate</th></tr>';
            
            foreach ($users as $user) 
            {
                echo '<tr>';
                echo '<td>' . $user['ParkingID'] . '</td>';
                echo '<td>' . $user['Location'] . '</td>';
                echo '<td>' . $user['Description'] . '</td>';
                echo '<td>' . $user['Capacity'] . '</td>';
                echo '<td>' . $user['CostPerHour'] . '</td>';
                echo '<td>' . $user['CostPerHourLateCheckOut'] . '</td>';
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

function searchploc($searchLocationName)
{
    try 
    {
        global $conn;
        // SQL statement to search for a specific parking location
        $sql = "SELECT * FROM parkinglocs WHERE Location LIKE :searchLocationName
                OR Description LIKE :searchLocationName
                OR ParkingID LIKE :searchLocationName";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':searchLocationName', '%' . $searchLocationName . '%');
        $stmt->execute();

        $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($locations) 
        {
            echo '<div class="abilitybox"><div class="abilities">Available Parking Locations</div>';
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>Location</th><th>Description</th><th>Capacity</th><th>Cost Per Hour</th><th>Cost Per Hour Late Check Out</th></tr>';
            
            foreach ($locations as $location) 
            {
                echo '<tr>';
                echo '<td>' . $location['ParkingID'] . '</td>';
                echo '<td>' . $location['Location'] . '</td>';
                echo '<td>' . $location['Description'] . '</td>';
                echo '<td>' . $location['Capacity'] . '</td>';
                echo '<td>' . $location['CostPerHour'] . '</td>';
                echo '<td>' . $location['CostPerHourLateCheckOut'] . '</td>';
                echo '</tr>';
            }
            
            echo '</table></div>';
        } 
        else 
        {
            echo '<p>No available parking locations found matching the search term "' . $searchLocationName . '".</p>';
        }
    } 
    catch (PDOException $e) 
    {
        echo "Connection failed: " . $e->getMessage();
    }
}

function displayPlocsFull()
{
    try 
    {
        global $conn;   
        $stmt = $conn->prepare("SELECT * FROM parkinglocs WHERE Capacity = 0");
        $stmt->execute();
        $plocs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($plocs) {
            echo '<div class="abilitybox"><div class="abilities">Full Parking Locations</div>';
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>Location</th><th>Description</th><th>Capacity</th><th>CostPerHour</th><th>Late rate</th></tr>';

            foreach ($plocs as $ploc) {
                echo '<tr>';
                echo '<td>' . $ploc['ParkingID'] . '</td>';
                echo '<td>' . $ploc['Location'] . '</td>';
                echo '<td>' . $ploc['Description'] . '</td>';
                echo '<td>' . $ploc['Capacity'] . '</td>';
                echo '<td>' . $ploc['CostPerHour'] . '</td>';
                echo '<td>' . $ploc['CostPerHourLateCheckOut'] . '</td>';
                echo '</tr>';
            }

            echo '</table></div>';
        } else {
            echo '<p>No full parking locations found.</p>';
        }
    } 
    catch (PDOException $e) 
    {
        echo "Connection failed: " . $e->getMessage();
    }
}

function displayPlocsNotFull()
{
    try {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM parkinglocs WHERE Capacity > 0");
        $stmt->execute();
        $plocs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($plocs) {
            echo '<div class="abilitybox"><div class="abilities">Available Parking Locations</div>';
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>Location</th><th>Description</th><th>Capacity</th><th>CostPerHour</th><th>Late rate</th></tr>';

            foreach ($plocs as $ploc) {
                echo '<tr>';
                echo '<td>' . $ploc['ParkingID'] . '</td>';
                echo '<td>' . $ploc['Location'] . '</td>';
                echo '<td>' . $ploc['Description'] . '</td>';
                echo '<td>' . $ploc['Capacity'] . '</td>';
                echo '<td>' . $ploc['CostPerHour'] . '</td>';
                echo '<td>' . $ploc['CostPerHourLateCheckOut'] . '</td>';
                echo '</tr>';
            }

            echo '</table></div>';
        } else {
            echo '<p>No available parking locations found.</p>';
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

function plocnotfullsearch($searchTerm)
{
    try 
    {
        global $conn;
        // SQL statement to search for parking locations with capacity > 0
        $sql = "SELECT * FROM parkinglocs WHERE ParkingID LIKE :searchTerm 
                OR Location LIKE :searchTerm 
                OR Description LIKE :searchTerm 
                AND Capacity > 0";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
        $stmt->execute();

        $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($locations) 
        {
            echo '<div class="abilitybox"><div class="abilities">Available Parking Locations</div>';
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>Location</th><th>Description</th><th>Capacity</th><th>Cost Per Hour</th><th>Cost Per Hour Late Check Out</th></tr>';
            
            foreach ($locations as $location) 
            {
                echo '<tr>';
                echo '<td>' . $location['ParkingID'] . '</td>';
                echo '<td>' . $location['Location'] . '</td>';
                echo '<td>' . $location['Description'] . '</td>';
                echo '<td>' . $location['Capacity'] . '</td>';
                echo '<td>' . $location['CostPerHour'] . '</td>';
                echo '<td>' . $location['CostPerHourLateCheckOut'] . '</td>';
                echo '</tr>';
            }
            
            echo '</table></div>';
        } 
        else 
        {
            echo '<p>No available parking locations found matching the search term "' . $searchTerm . '".</p>';
        }
    } 
    catch (PDOException $e) 
    {
        echo "Connection failed: " . $e->getMessage();
    }
}

function plocfullsearch($searchLocation)
{
    try 
    {
        global $conn;
        // SQL statement to search for full parking locations by name
        $sql = "SELECT * FROM parkinglocs WHERE Location LIKE :searchLocation 
                OR ParkingID LIKE :searchLocation
                AND Capacity = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':searchLocation', '%' . $searchLocation . '%');
        $stmt->execute();

        $fullLocations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($fullLocations) 
        {
            echo '<div class="abilitybox"><div class="abilities">Full Parking Locations</div>';
            echo '<table border="1">';
            echo '<tr><th>Parking ID</th><th>Location</th><th>Description</th><th>Capacity</th><th>CostPerHour</th><th>Late rate</th></tr>';
            
            foreach ($fullLocations as $location) 
            {
                echo '<tr>';
                echo '<td>' . $location['ParkingID'] . '</td>';
                echo '<td>' . $location['Location'] . '</td>';
                echo '<td>' . $location['Description'] . '</td>';
                echo '<td>' . $location['Capacity'] . '</td>';
                echo '<td>' . $location['CostPerHour'] . '</td>';
                echo '<td>' . $location['CostPerHourLateCheckOut'] . '</td>';
                echo '</tr>';
            }
            
            echo '</table></div>';
        } 
        else 
        {
            echo '<p>No full parking locations found with the name ' . $searchLocation . '.</p>';
        }
    } 
    catch (PDOException $e) 
    {
        echo "Connection failed: " . $e->getMessage();
    }
}


function printCheckIn()
{
    try 
    {
        global $conn;
        $stmt = $conn->query("SELECT parkingrecords.RecordID, parkinglocs.Location, usertable.name AS UserName, parkingrecords.CheckInTime, parkingrecords.CheckOutTime
                              FROM parkingrecords
                              LEFT JOIN parkinglocs ON parkingrecords.ParkingID = parkinglocs.ParkingID
                              LEFT JOIN usertable ON parkingrecords.UserID = usertable.id
                              WHERE parkingrecords.RealCheckOutTime IS NULL");
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($records) {
            echo '
            <div class="abilitybox">
            <div class="abilities">All checked-in locations</div>
            <div class="checkin-table">
            ';
            echo '<table border="1">';
            echo '<tr><th>Record ID</th><th>Location</th><th>User Name</th><th>Check In Time</th><th>Check Out Time</th><th></th></tr>';
            
            foreach ($records as $record) 
            {
                echo '<tr>';
                echo '<td>' . $record['RecordID'] . '</td>';
                echo '<td>' . $record['Location'] . '</td>';
                echo '<td>' . $record['UserName'] . '</td>';
                echo '<td>' . $record['CheckInTime'] . '</td>';
                echo '<td>' . $record['CheckOutTime'] . '</td>';
                echo '<td><form method="post" action="/checkout"><input type="hidden" name="recordID" value="' . $record['RecordID'] . '"><button class="nicebtn" type="submit">Checkout</button></form></td>';
                echo '</tr>';
            }
            
            echo '</table></div></div>';
        } else {
            echo '<p>No checked-in users found.</p>';
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

function searchCheckedInLocation($searchName)
{
    try 
    {
        global $conn;
        // SQL statement to search for a specific checked-in location by name
        $sql = "SELECT parkingrecords.RecordID, parkinglocs.Location, parkinglocs.Description, usertable.name AS UserName, parkingrecords.CheckInTime, parkingrecords.CheckOutTime
                FROM parkingrecords 
                INNER JOIN parkinglocs ON parkingrecords.ParkingID = parkinglocs.ParkingID 
                LEFT JOIN usertable ON parkingrecords.UserID = usertable.id
                WHERE parkinglocs.Location LIKE :searchName";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':searchName', '%' . $searchName . '%');
        $stmt->execute();

        $checkedInLocations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($checkedInLocations) 
        {
            if (empty($searchName)) 
            {
                echo '<div class="abilitybox"><div class="abilities">All Checked-In Locations</div>';
            } 
            else 
            {
                echo '<div class="abilitybox"><div class="abilities">Checked-In at ' . htmlspecialchars($searchName) . '</div>';
            }
            echo '<table border="1">';
            echo '<tr><th>Record ID</th><th>Location</th><th>Description</th><th>User Name</th><th>Check-In Time</th><th>Check Out Time</th><th></th></tr>';
            
            foreach ($checkedInLocations as $location) 
            {
                echo '<tr>';
                echo '<td>' . $location['RecordID'] . '</td>';
                echo '<td>' . $location['Location'] . '</td>';
                echo '<td>' . $location['Description'] . '</td>';
                echo '<td>' . $location['UserName'] . '</td>';
                echo '<td>' . $location['CheckInTime'] . '</td>';
                echo '<td>' . $location['CheckOutTime'] . '</td>';
                echo '<td><form method="post" action="/checkout"><input type="hidden" name="recordID" value="' . $location['RecordID'] . '"><button class="nicebtn" type="submit">Checkout</button></form></td>';
                echo '</tr>';
            }
            
            echo '</table></div>';
        } 
        else 
        {
            echo '<p>No checked-in locations found with the name ' . $searchName . '.</p>';
        }
    } 
    catch (PDOException $e) 
    {
        echo "Connection failed: " . $e->getMessage();
    }
}

function searchCheckedInLocationByName($searchName)
{
    try 
    {
        global $conn;
        // SQL statement to search for a specific checked-in location by name
        $sql = "SELECT parkingrecords.RecordID, parkinglocs.Location, parkinglocs.Description, usertable.name AS UserName, parkingrecords.CheckInTime, parkingrecords.CheckOutTime, parkingrecords.RealCheckOutTime
                FROM parkingrecords 
                INNER JOIN parkinglocs ON parkingrecords.ParkingID = parkinglocs.ParkingID 
                LEFT JOIN usertable ON parkingrecords.UserID = usertable.id
                WHERE usertable.name LIKE :searchName AND parkingrecords.RealCheckOutTime IS NOT NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':searchName', '%' . $searchName . '%');
        $stmt->execute();

        $checkedInLocations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($checkedInLocations) 
        {
            if (empty($searchName)) {
                echo '<div class="abilitybox"><div class="abilities">All Checked-In Locations</div>';
            } else {
                echo '<div class="abilitybox"><div class="abilities">Checked-Out by ' . htmlspecialchars($searchName) . '</div>';
            }
            echo '<table border="1">';
            echo '<tr><th>Record ID</th><th>Location</th><th>Description</th><th>User Name</th><th>Check-In Time</th><th>Check Out Time</th><th>Real Check Out Time</th></tr>';
            
            foreach ($checkedInLocations as $location) 
            {
                echo '<tr>';
                echo '<td>' . $location['RecordID'] . '</td>';
                echo '<td>' . $location['Location'] . '</td>';
                echo '<td>' . $location['Description'] . '</td>';
                echo '<td>' . $location['UserName'] . '</td>';
                echo '<td>' . $location['CheckInTime'] . '</td>';
                echo '<td>' . $location['CheckOutTime'] . '</td>';
                echo '<td>' . $location['RealCheckOutTime'] . '</td>';
                echo '</tr>';
            }
            
            echo '</table></div>';
        } 
        else 
        {
            echo '<p>No checked-in locations found by the user ' . $searchName . '.</p>';
        }
    } 
    catch (PDOException $e) 
    {
        echo "Connection failed: " . $e->getMessage();
    }
}

function activeparkingsbyname($searchName)
{
    try 
    {
        global $conn;
        // SQL statement to search for a specific checked-in location by name
        $sql = "SELECT parkingrecords.RecordID, parkinglocs.Location, parkinglocs.Description, usertable.name AS UserName, parkingrecords.CheckInTime, parkingrecords.CheckOutTime
                FROM parkingrecords 
                INNER JOIN parkinglocs ON parkingrecords.ParkingID = parkinglocs.ParkingID 
                LEFT JOIN usertable ON parkingrecords.UserID = usertable.id
                WHERE usertable.name LIKE :searchName AND parkingrecords.RealCheckOutTime IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':searchName', '%' . $searchName . '%');
        $stmt->execute();

        $checkedInLocations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($checkedInLocations) 
        {
            if (empty($searchName)) {
                echo '<div class="abilitybox"><div class="abilities">All Checked-In Locations</div>';
            } else {
                echo '<div class="abilitybox"><div class="abilities">Checked-In by ' . htmlspecialchars($searchName) . '</div>';
            }
            echo '<table border="1">';
            echo '<tr><th>Record ID</th><th>Location</th><th>Description</th><th>User Name</th><th>Check-In Time</th><th>Check Out Time</th><th></th></tr>';
            
            foreach ($checkedInLocations as $location) 
            {
                echo '<tr>';
                echo '<td>' . $location['RecordID'] . '</td>';
                echo '<td>' . $location['Location'] . '</td>';
                echo '<td>' . $location['Description'] . '</td>';
                echo '<td>' . $location['UserName'] . '</td>';
                echo '<td>' . $location['CheckInTime'] . '</td>';
                echo '<td>' . $location['CheckOutTime'] . '</td>';
                echo '<td><form method="post" action="/usercheckout"><input type="hidden" name="recordID" value="' . $location['RecordID'] . '"><button class="nicebtn" type="submit">Checkout</button></form></td>';
                echo '</tr>';
            }
            
            echo '</table></div>';
        } 
        else 
        {
            echo '<p>No checked-in locations found by the user ' . $searchName . '.</p>';
        }
    } 
    catch (PDOException $e) 
    {
        echo "Connection failed: " . $e->getMessage();
    }
}

function loginUser($username) 
{
    try 
    {
        global $conn;
        // SQL statement to check if the username exists
        $sql = "SELECT * FROM usertable WHERE name = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch the result
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            // Create a User object from database data
            $user = new User($userData['id'], $userData['name'], $userData['role'], $userData['surname'], $userData['email'], $userData['phone']);
            return $user;
        } else {
            return null;
        }
    } catch (PDOException $e) {
        // Handle database connection error
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}

// Router API
switch ($request) 
{

    case '/':
    {
        // Check if the user is logged in
        if (isset($_SESSION['user'])) 
        {
            // Get the user's role from the session
            $userRole = $_SESSION['user']->getRole();
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

    case '/users':
    {
        require __DIR__ . $viewDir . 'mainpage.php';
        headerComponent();
        // Get the name of the currently logged-in user
        $currentUserName = $_SESSION['user']->getName();

        
        echo '
            <div class="canvas">
                <div class="mainpagefn">
                    <div class="title">
                        Welcome, ' . $currentUserName . '!
                    </div>
                    <div class="adminpage">
                        <a class="abilities" id="adminone" href="/users">Users</a>
                        <a class="abilities" href="/ploc">Parking locations</a>
                        <a class="abilities" href="/check">Check in/out</a>
                    </div>
        ';
        // Search user form
        echo '
                    <div class="searchcon">
                        <form method="post" action="/users">
                            <label for="searchUser">Search for user:</label>
                            <div>
                                <input type="text" id="searchUser" name="searchUser">
                                <button class="nicebtn" type="submit">Go</button>
                            </div>
                        </form>
                    </div>
            ';
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
                </div>
            </div>
        ';
        btmComponent();
        break;
    }

    case '/ploc':
    {
        // Check if the user is logged in
        if (isset($_SESSION['user']))
        {
            require __DIR__ . $viewDir . 'mainpage.php';
            headerComponent();
            // Get the name of the currently logged-in user
            $currentUserName = $_SESSION['user']->getName();
            echo '
                <div class="canvas">
                    <div class="mainpagefn">
                        <div class="title">
                            Welcome, ' . $currentUserName . '!
                        </div>
                        <div class="adminpage">
                            <a class="abilities" href="/users">Users</a>
                            <a class="abilities" id="adminone" href="/ploc">Parking locations</a>
                            <a class="abilities" href="/check">Check in/out</a>
                        </div>
            ';

            echo '
                        <div class="adminpage">
                            <a class="abilities" id="adminone" href="/ploc">All parking locations</a>
                            <a class="abilities" href="/plocfull">Full lots</a>
                            <a class="abilities" href="/plocnotfull">Available lots</a>
                        </div>
            ';
            // Search user form
            echo '
            <div class="searchcon">
                <form method="post" action="/ploc">
                    <label for="searchploc">Search for Parking Location:</label>
                    <div>
                        <input type="text" id="searchploc" name="searchploc">
                        <button class="nicebtn" type="submit">Go</button>
                    </div>
                </form>
            </div>
             ';
            // Display search results or all users if form not submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchploc'])) 
            {
                $searchploc = strtolower(htmlspecialchars($_POST['searchploc']));
                // If search input is empty, display all users
                if (empty($searchploc)) 
                {
                    displayAllPlocs();
                } 
                else 
                {
                    searchploc($searchploc);
                }
            } 
            else 
            {
                displayAllPlocs();
            }
            printinsertploc();
            echo '
                        </div>
                    </div>            
            ';
        }
        else 
        {
            // Redirect to /login if not logged in
            header("Location: /login");
            exit;
        }
        break;
    }

    case '/userploc':
    {
        require __DIR__ . $viewDir . 'mainpage.php';
        headerComponent();
        // Get the name of the currently logged-in user
        $currentUserName = $_SESSION['user']->getName();
        echo '
            <div class="canvas">
                <div class="mainpagefn">
                    <div class="title">
                        Welcome, ' . $currentUserName . '!
                    </div>
                    <div class="adminpage">
                        <a class="abilities" id="adminone" href="/userploc">Parking locations</a>
                        <a class="abilities" href="/usercheck">Check in/out</a>
                    </div>
        ';

        // Search parking locations form
        echo '
        <div class="searchcon">
            <form method="post" action="/userploc">
                <label for="searchploc">Search for Parking Location:</label>
                <div>
                    <input type="text" id="searchploc" name="searchploc">
                    <button class="nicebtn" type="submit">Go</button>
                </div>
            </form>
        </div>';

        // Check if search input is provided
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchploc'])) 
        {
            $searchLocation = strtolower(htmlspecialchars($_POST['searchploc']));
            if (empty($searchLocation)) 
            {
                // Call displayPlocsNotFull if search input is empty
                displayPlocsNotFull();
            } 
            else 
            {
                // Call plocnotfullsearch if search input is provided
                plocnotfullsearch($searchLocation);
            }
        } 
        else 
        {
            // Call displayPlocsNotFull if search input is not provided
            displayPlocsNotFull();
        }


        echo '
                    </div>
                </div>            
        ';
        break;
    }

    case '/plocfull':
    {
        // Check if the user is logged in
        if (isset($_SESSION['user'])) 
        {
            require __DIR__ . $viewDir . 'mainpage.php';
            headerComponent();
            // Get the name of the currently logged-in user
            $currentUserName = $_SESSION['user']->getName();
            echo '
                <div class="canvas">
                    <div class="mainpagefn">
                        <div class="title">
                            Welcome, ' . $currentUserName . '!
                        </div>
                        <div class="adminpage">
                            <a class="abilities" href="/users">Users</a>
                            <a class="abilities" id="adminone" href="/ploc">Parking locations</a>
                            <a class="abilities" href="/check">Check in/out</a>
                        </div>';
            echo '
                        <div class="adminpage">
                            <a class="abilities" href="/ploc">All parking locations</a>
                            <a class="abilities" id="adminone" href="/plocfull">Full lots</a>
                            <a class="abilities" href="/plocnotfull">Available lots</a>
                        </div>
            ';
            // Search parking locations form
            echo '
            <div class="searchcon">
                <form method="post" action="/plocfull">
                    <label for="searchploc">Search for Parking Location:</label>
                    <div>
                        <input type="text" id="searchploc" name="searchploc">
                        <button class="nicebtn" type="submit">Go</button>
                    </div>
                </form>
            </div>';
            
            // Check if search input is provided
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchploc'])) 
            {
                $searchLocation = strtolower(htmlspecialchars($_POST['searchploc']));
                // If search input is provided, call plocfullsearch
                plocfullsearch($searchLocation);
            } 
            else 
            {
                // If search input is not provided, call displayPlocsFull
                displayPlocsFull();
            }
            
            echo '</div></div>';
        } 
        else 
        {
            // Redirect to /login if not logged in
            header("Location: /login");
            break;
        }
        break;
    }
    

    case '/plocnotfull':
    {
        // Check if the user is logged in
        if (isset($_SESSION['user'])) 
        {
            require __DIR__ . $viewDir . 'mainpage.php';
            headerComponent();
            // Get the name of the currently logged-in user
            $currentUserName = $_SESSION['user']->getName();
            echo '
                <div class="canvas">
                    <div class="mainpagefn">
                        <div class="title">
                            Welcome, ' . $currentUserName . '!
                        </div>
                        <div class="adminpage">
                            <a class="abilities" href="/users">Users</a>
                            <a class="abilities" id="adminone" href="/ploc">Parking locations</a>
                            <a class="abilities" href="/check">Check in/out</a>
                        </div>';
            echo '
                        <div class="adminpage">
                            <a class="abilities" href="/ploc">All parking locations</a>
                            <a class="abilities" href="/plocfull">Full lots</a>
                            <a class="abilities" id="adminone" href="/plocnotfull">Available lots</a>
                        </div>
            ';
    
            // Search parking locations form
            echo '
            <div class="searchcon">
                <form method="post" action="/plocnotfull">
                    <label for="searchploc">Search for Parking Location:</label>
                    <div>
                        <input type="text" id="searchploc" name="searchploc">
                        <button class="nicebtn" type="submit">Go</button>
                    </div>
                </form>
            </div>';
    
            // Check if search input is provided
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchploc'])) 
            {
                $searchLocation = strtolower(htmlspecialchars($_POST['searchploc']));
                if (empty($searchLocation)) 
                {
                    // Call displayPlocsNotFull if search input is empty
                    displayPlocsNotFull();
                } 
                else 
                {
                    // Call plocnotfullsearch if search input is provided
                    plocnotfullsearch($searchLocation);
                }
            } 
            else 
            {
                // Call displayPlocsNotFull if search input is not provided
                displayPlocsNotFull();
            }
    
            echo '</div></div>';
        } 
        else 
        {
            // Redirect to /login if not logged in
            header("Location: /login");
            break;
        }
        break;
    }

    case '/insertparkingloc':
    {
        // Create a connection to the database
        try 
        {
            global $conn;

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
                header('Location: /ploc');
            }
        } 
        catch (PDOException $e) 
        {
            // Handle database connection error
            echo "Connection failed: " . $e->getMessage();
        }

        break;
    }

    case '/check':
    {
        // Check if the user is logged in
        if (isset($_SESSION['user'])) 
        {
            require __DIR__ . $viewDir . 'mainpage.php';
            headerComponent();
            // Get the name of the currently logged-in user
            $currentUserName = $_SESSION['user']->getName();
            
            echo '
                <div class="canvas">
                    <div class="mainpagefn">
                        <div class="title">
                            Welcome, ' . $currentUserName . '!
                        </div>
                        <div class="adminpage">
                            <a class="abilities" href="/users">Users</a>
                            <a class="abilities" href="/ploc">Parking locations</a>
                            <a class="abilities" id="adminone" href="/check">Check in/out</a>
                        </div>
            ';

            // Search form for checked-in users
            echo '
                <div class="searchcon">
                    <form method="post" action="/check">
                        <label for="searchName">Search for Checked-In Location:</label>
                        <div>
                            <input type="text" id="searchName" name="searchName" placeholder="Enter name...">
                            <button class="nicebtn" type="submit">Go</button>
                        </div>
                    </form>
                </div>
            ';
            
            // Check if the form is submitted for searching
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchName'])) 
            {
                $searchName = htmlspecialchars($_POST['searchName']);
                // If search input is empty, display all checked-in users
                if (empty($searchName)) 
                {
                    printCheckIn();
                } 
                else 
                {
                    searchCheckedInLocation($searchName);
                }
            } 
            else 
            {
                // Display all checked-in users if no search performed
                printCheckIn();
            }

            // Form to prompt for check-in and checkout time
            echo '
                <div class="checkform">
                    <form class="checkin-form" method="post" action="/checkin">
                        <div>
                            <label for="parkingID">Parking ID:</label>
                            <input type="text" id="parkingID" name="parkingID" required>
                        </div>
                        <div>
                            <label for="userID">User ID:</label>
                            <input type="text" id="userID" name="userID" required>
                        </div>
                        <div>
                            <label for="checkoutTime">Checkout Time:</label>
                            <input type="datetime-local" id="checkoutTime" name="checkoutTime" required>
                        </div>
                        <button class="nicebtn" type="submit">Check In</button>
                    </form>
                </div>
            ';
            btmComponent();
        } 
        else 
        {
            // Redirect to /login if not logged in
            header("Location: /login");
            exit;
        }
        
        break;
    }

    case '/usercheck':
    {
        require __DIR__ . $viewDir . 'mainpage.php';
        headerComponent();
        // Get the name of the currently logged-in user
        $currentUserName = $_SESSION['user']->getName();
        echo '
            <div class="canvas">
                <div class="mainpagefn">
                    <div class="title">
                        Welcome, ' . $currentUserName . '!
                    </div>
                    <div class="adminpage">
                        <a class="abilities" href="/userploc">Parking locations</a>
                        <a class="abilities" id="adminone" href="/usercheck">Check in/out</a>
                    </div>
        ';
        echo '
                        <div class="adminpage">
                            <a class="abilities" id="adminone" href="/usercheck">Check in</a>
                            <a class="abilities" href="/history">History</a>
                            <a class="abilities" href="/current">Current parkings</a>
                            <a class="abilities"  href="/payment">Check out</a>
                        </div>
            ';
        // Form to prompt for check-in and checkout time
        echo '
            <div class="checkform">
                <form class="checkin-form" method="post" action="/checkin">
                    <div>
                        <label for="parkingID">Parking ID:</label>
                        <input type="text" id="parkingID" name="parkingID" required>
                    </div>
                    
                        <input type="hidden" id="userID" name="userID" value="'. $_SESSION['user']->getId() .'">
                    
                    <div>
                        No of hours:
                        <input type="text">
                    </div>
                    <div>
                        <label for="checkoutTime">Checkout Time:</label>
                        <input type="datetime-local" id="checkoutTime" name="checkoutTime" required>
                    </div>
                    <button class="nicebtn" type="submit">Check In</button>
                </form>
            </div>
        ';
        // Check if the user is already checked in
        if (isset($_SESSION['checkoutTime'])) 
        {
            // Calculate the number of hours between checkout time and current time
            $checkoutTime = $_SESSION['checkoutTime'];
            $currentTimestamp = time();
            // Calculate the number of hours difference
            $hoursDifference = ceil((strtotime($checkoutTime) - $currentTimestamp) / 3600);

            
            // Retrieve the parking ID from the session
            $parkingID = $_SESSION['parkingID'];

            try
            {
                global $conn;
                                
                // Retrieve the cost per hour and cost per hour for late checkout from the database
                $sql = "SELECT CostPerHour, CostPerHourLateCheckOut FROM parkinglocs WHERE ParkingID = :parkingID";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':parkingID', $parkingID);
                $stmt->execute();
                $parkingDetails = $stmt->fetch(PDO::FETCH_ASSOC);

                echo '
                    <div class="notif">
                        <p>Checkout time: ' . date("d F Y H:i", strtotime($checkoutTime)) . '</p>
                        <p>Number of hours untill checkout: ' . $hoursDifference . '</p>
                        <p>Cost per hour: ' . $parkingDetails['CostPerHour'] . '</p>
                        <p>Cost per hour for late checkout: ' . $parkingDetails['CostPerHourLateCheckOut'] . '</p>
                    </div>
                ';
            }
            catch (PDOException $e) 
            {
                // Handle database connection error
                echo "Connection failed: " . $e->getMessage();
            }
        }


            
        echo '
                    </div>
                </div>            
        ';
        break;
    }

    case '/history':
    {
        require __DIR__ . $viewDir . 'mainpage.php';
        headerComponent();
        // Get the name of the currently logged-in user
        $currentUserName = $_SESSION['user']->getName();
        echo '
            <div class="canvas">
                <div class="mainpagefn">
                    <div class="title">
                        Welcome, ' . $currentUserName . '!
                    </div>
                    <div class="adminpage">
                        <a class="abilities" href="/userploc">Parking locations</a>
                        <a class="abilities" id="adminone" href="/usercheck">Check in/out</a>
                    </div>
        ';
        echo '
                        <div class="adminpage">
                            <a class="abilities"  href="/usercheck">Check in</a>
                            <a class="abilities" id="adminone" href="/history">History</a>
                            <a class="abilities" href="/current">Current parkings</a>
                            <a class="abilities"  href="/payment">Check out</a>
                        </div>
            ';
            searchCheckedInLocationByName($currentUserName);
            echo '
                    </div>
                </div>            
        ';
        break;
    }

    case '/current':
    {
        require __DIR__ . $viewDir . 'mainpage.php';
        headerComponent();
        // Get the name of the currently logged-in user
        $currentUserName = $_SESSION['user']->getName();
        echo '
            <div class="canvas">
                <div class="mainpagefn">
                    <div class="title">
                        Welcome, ' . $currentUserName . '!
                    </div>
                    <div class="adminpage">
                        <a class="abilities" href="/userploc">Parking locations</a>
                        <a class="abilities" id="adminone" href="/usercheck">Check in/out</a>
                    </div>
        ';
        echo '
                        <div class="adminpage">
                            <a class="abilities"  href="/usercheck">Check in</a>
                            <a class="abilities"  href="/history">History</a>
                            <a class="abilities" id="adminone" href="/current">Current parkings</a>
                            <a class="abilities"  href="/payment">Check out</a>
                        </div>
            ';

            activeparkingsbyname($currentUserName);
            echo '
                </div>
            </div>            
    ';
        break;
    }
        
    case '/checkin':
    {
        // Create a connection to the database
        try 
        {
            global $conn;
    
            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") 
            {
                // Get data from the form
                $parkingID = $_POST['parkingID'];
                $userID = $_POST['userID'];
                $checkoutTime = $_POST['checkoutTime'];
    
                // Prepare the SQL statement to insert data into the parkingrecords table
                $sql = "INSERT INTO parkingrecords (ParkingID, UserID, CheckInTime, CheckOutTime) VALUES (:parkingID, :userID, CURRENT_TIMESTAMP, :checkoutTime)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':parkingID', $parkingID);
                $stmt->bindParam(':userID', $userID);
                $stmt->bindParam(':checkoutTime', $checkoutTime);
    
                // Execute the statement
                $stmt->execute();
    
                // Update the capacity of the corresponding parking location
                $updateSql = "UPDATE parkinglocs SET Capacity = Capacity - 1 WHERE ParkingID = :parkingID";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bindParam(':parkingID', $parkingID);
                $updateStmt->execute();
                
                $_SESSION['checkoutTime'] = $checkoutTime;
                $_SESSION['parkingID'] = $parkingID;
                // Redirect based on user role
                if ($_SESSION['user']->getRole()=="admin") 
                {
                    header('Location: /check');
                } 
                else 
                {
                    header('Location: /usercheck');
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
        
    case '/payment':
    {
        require __DIR__ . $viewDir . 'mainpage.php';
            headerComponent();
            // Get the name of the currently logged-in user
            $currentUserName = $_SESSION['user']->getName();
            echo '
                <div class="canvas">
                    <div class="mainpagefn">
                        <div class="title">
                            Welcome, ' . $currentUserName . '!
                        </div>
                        <div class="adminpage">
                            <a class="abilities" href="/userploc">Parking locations</a>
                            <a class="abilities" id="adminone" href="/usercheck">Check in/out</a>
                        </div>
            ';
            echo '
                            <div class="adminpage">
                                <a class="abilities"  href="/usercheck">Check in</a>
                                <a class="abilities"  href="/history">History</a>
                                <a class="abilities"  href="/current">Current parkings</a>
                                <a class="abilities" id="adminone" href="/payment">Check out</a>
                            </div>
                ';
            // Check if the total payment is set in the session
            if (isset($_SESSION['totalPayment'])) 
            {
                // Retrieve total payment from session
                $totalPayment = $_SESSION['totalPayment'];
                $totalHours = $_SESSION['allhours'];
                $lateHours = $_SESSION['latehours'];

                // Display payment information
                error_log("this is the total fee->". $totalPayment);
                $totalPayment = str_replace(',', '', $totalPayment); // Remove the comma from the string
                $totalPayment = (float) $totalPayment; // Convert the string to a float
                $formatted_number = number_format($totalPayment, 2); // Format the number with two decimal places


                echo '
                    
                            <div class="notif">
                                Payment Details
                                <p>On time Hours: ' . $totalHours . '</p>
                                <p>Late Hours: ' . $lateHours . '</p>
                                <p>Total Fee: $' . $formatted_number . '</p>
                            </div>
                    ';
            }
        
                
            echo '
                </div>
            </div>            
            ';
        break;
    }

    case '/checkout':
    {
        // Create a connection to the database
        try 
        {
            global $conn;
    
            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Get data from the form
                $recordID = $_POST['recordID'];
    
                // Prepare the SQL statement to update the RealCheckOutTime column
                $updateSql = "UPDATE parkingrecords SET RealCheckOutTime = CURRENT_TIMESTAMP WHERE RecordID = :recordID";
                $stmt = $conn->prepare($updateSql);
                $stmt->bindParam(':recordID', $recordID);
                $stmt->execute();
    
                // Retrieve the parking ID for the corresponding record
                $sql = "SELECT ParkingID FROM parkingrecords WHERE RecordID = :recordID";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':recordID', $recordID);
                $stmt->execute();
                $parkingID = $stmt->fetchColumn();
    
                // Update the capacity of the corresponding parking location
                $updateSql = "UPDATE parkinglocs SET Capacity = Capacity + 1 WHERE ParkingID = :parkingID";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bindParam(':parkingID', $parkingID);
                $updateStmt->execute();
    
                // Redirect back to /check or any desired destination
                header('Location: /check');
            }
        } 
        catch (PDOException $e) 
        {
            // Handle database connection error
            echo "Connection failed: " . $e->getMessage();
        }
    
        break;
    }
                
    case '/usercheckout':
    {
        // Check if the user is logged in
        if (isset($_SESSION['user'])) 
        {
            // Create a connection to the database
            try 
            {
                global $conn;
        
                // Check if the form is submitted
                if ($_SERVER["REQUEST_METHOD"] == "POST") 
                {
                    // Get data from the form
                    $recordID = $_POST['recordID'];
        
                    // Prepare the SQL statement to update the RealCheckOutTime column
                    $updateSql = "UPDATE parkingrecords SET RealCheckOutTime = CURRENT_TIMESTAMP WHERE RecordID = :recordID";
                    $stmt = $conn->prepare($updateSql);
                    $stmt->bindParam(':recordID', $recordID);
                    $stmt->execute();
        
                    // Retrieve the parking ID for the corresponding record
                    $sql = "SELECT CheckInTime, CheckOutTime, CostPerHour, CostPerHourLateCheckOut FROM parkingrecords INNER JOIN parkinglocs ON parkingrecords.ParkingID = parkinglocs.ParkingID WHERE RecordID = :recordID";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':recordID', $recordID);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
                    // Calculate the total hours
                    $checkInTime = strtotime($result['CheckInTime']);
                    $checkOutTime = strtotime($result['CheckOutTime']);
                    $totalHours = ceil(($checkOutTime - $checkInTime) / 3600); // Round up to the nearest hour
    
                    // Calculate the total payment
                    $totalPayment = $totalHours * $result['CostPerHour'];
    
                    // Calculate the late fee
                    $realCheckOutTime = time(); // Assuming the current time is the checkout time
                    $lateHours = max(0, ceil(($realCheckOutTime - $checkOutTime) / 3600)); // Round up to the nearest hour
                    $lateFee = $lateHours * $result['CostPerHourLateCheckOut'];
    
                    // Add late fee to total payment
                    $totalPayment += $lateFee;
                    
                    
    
                    // Store necessary data in session
                    $_SESSION['totalPayment'] = number_format($totalPayment, 2); // Truncate total payment to 2 decimal places
                    $_SESSION['recordID'] = $recordID;
                    $_SESSION['allhours'] = $totalHours;
                    $_SESSION['latehours'] = $lateHours;
                    $_SESSION['lateFee'] = number_format($lateFee, 2); // Truncate late fee to 2 decimal places
    
                    // Update the capacity of the corresponding parking location
                    $updateSql = "UPDATE parkinglocs SET Capacity = Capacity + 1 WHERE ParkingID = :parkingID";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bindParam(':parkingID', $result['ParkingID']);
                    $updateStmt->execute();
                    
                    unset($_SESSION['checkoutTime']);
                    // Redirect to payment page
                    header('Location: /payment');
                    exit;
                }
            } 
            catch (PDOException $e) 
            {
                // Handle database connection error
                echo "Connection failed: " . $e->getMessage();
            }
        }
        else 
        {
            // Redirect to login if not logged in
            header("Location: /login");
            exit;
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
            global $conn;

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
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            // Get data from the form
            $username = strtolower(htmlspecialchars($_POST['fname']));

            // Attempt to login the user
            $user = loginUser($username);

            if ($user) 
            {
                // Store user object in the session
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
        break;
    }
    
    case '/logout':
    {
        // Check if the user is logged in
        if (isset($_SESSION['user'])) 
        {
            // Unset the session variable
            unset($_SESSION['user']);
            unset($_SESSION['totalPayment']);
            unset($_SESSION['checkoutTime']);
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