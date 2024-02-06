// Search user form
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



function insertPloc()
{
    echo '
            <div class="abilitybox">
                <div class="abilities">
                    Add Parking Location
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
}

function displayCheckedInUsersForCheckOut()
{
    global $servername, $username, $password, $dbname;

    try 
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Select users with RealCheckOutTime not filled (still checked in)
        $stmt = $conn->query("SELECT * FROM parkingrecords WHERE RealCheckOutTime IS NULL");
        $checkedInUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($checkedInUsers) 
        {
            echo '<div class="checked-in-users"><div class="title">Checked-In Users</div>';
            echo '<table border="1">';
            echo '<tr><th>ParkingID</th><th>UserID</th><th>CheckInTime</th><th>ExpectedCheckOutTime</th><th>RealCheckOutTime</th></tr>';

            foreach ($checkedInUsers as $user) 
            {
                echo '<tr>';
                echo '<td>' . $user['ParkingID'] . '</td>';
                echo '<td>' . $user['UserID'] . '</td>';
                echo '<td>' . $user['CheckInTime'] . '</td>';
                echo '<td>' . $user['CheckOutTime'] . '</td>';
                echo '<td>' . $user['RealCheckOutTime'] . '</td>';
                echo '<td><form method="post" action="/">
                        <input type="hidden" name="parkingID" value="' . $user['ParkingID'] . '">
                        <input type="hidden" name="userID" value="' . $user['UserID'] . '">
                        <button class="nicebtn" type="submit" name="checkOutUser">Check Out</button>
                    </form></td>';
                echo '</tr>';
            }
            
            echo '</table></div>';
        } 
        else 
        {
            echo '<p>No users currently checked in.</p>';
        }
    } 
    catch (PDOException $e) 
    {
        echo "Connection failed: " . $e->getMessage();
    }
}

function checkOutUser($parkingID, $userID)
{
    global $servername, $username, $password, $dbname;

    try 
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update the RealCheckOutTime for the specified user
        $stmt = $conn->prepare("UPDATE parkingrecords SET RealCheckOutTime = NOW() WHERE ParkingID = :parkingID AND UserID = :userID AND RealCheckOutTime IS NULL");
        $stmt->bindParam(':parkingID', $parkingID);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
    } 
    catch (PDOException $e) 
    {
        echo "Connection failed: " . $e->getMessage();
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



function displayAllPlocs()
{
    global $servername, $username, $password, $dbname;

    try 
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

function searchForParkingLocation($searchLocationName)
{
    global $servername, $username, $password, $dbname;

    try 
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL statement to search for a specific parking location
        $sql = "SELECT * FROM parkinglocs WHERE Location = :searchLocationName";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':searchLocationName', $searchLocationName);
        $stmt->execute();

        $location = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($location) 
        {
            echo '<div class="abilitybox"><div class="abilities">'.$location['Location'].'</div>';
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>Location</th><th>Description</th><th>Capacity</th><th>Cost Per Hour</th><th>Cost Per Hour Late Check Out</th></tr>';
            echo '<tr>';
            echo '<td>' . $location['ParkingID'] . '</td>';
            echo '<td>' . $location['Location'] . '</td>';
            echo '<td>' . $location['Description'] . '</td>';
            echo '<td>' . $location['Capacity'] . '</td>';
            echo '<td>' . $location['CostPerHour'] . '</td>';
            echo '<td>' . $location['CostPerHourLateCheckOut'] . '</td>';
            echo '</tr>';
            echo '</table></div>';
        } 
        else 
        {
            echo '<p>No parking location found with the name ' . $searchLocationName . '.</p>';
        }
    } 
    catch (PDOException $e) 
    {
        echo "Connection failed: " . $e->getMessage();
    }
}

function adminCheck()
{
    // Check if the form is submitted for check-in
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkInUser'])) 
    {
        // Assuming you have the ParkingID and UserID from the form
        $parkingID = intval($_POST['parkingID']);
        $userID = intval($_POST['userID']);

        // Prompt the user for expected CheckOutTime
        $expectedCheckOutTime = $_POST['CheckOutTime'];

        // Create a connection to the database
        global $servername, $username, $password, $dbname;
        try 
        {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check the available capacity
            $capacityCheckStmt = $conn->prepare("SELECT Capacity FROM parkinglocs WHERE ParkingID = :parkingID");
            $capacityCheckStmt->bindParam(':parkingID', $parkingID);
            $capacityCheckStmt->execute();

            $availableCapacity = $capacityCheckStmt->fetchColumn();

            if ($availableCapacity > 0) 
            {
                // Insert check-in record with expected CheckOutTime
                $checkInStmt = $conn->prepare("INSERT INTO parkingrecords (ParkingID, UserID, CheckInTime, CheckOutTime) VALUES (:parkingID, :userID, NOW(), :CheckOutTime)");

                $checkInStmt->bindParam(':CheckOutTime', $expectedCheckOutTime);
                $checkInStmt->bindParam(':parkingID', $parkingID);
                $checkInStmt->bindParam(':userID', $userID);

                // Execute the check-in statement
                $checkInStmt->execute();

                // Deduct capacity
                $deductCapacityStmt = $conn->prepare("UPDATE parkinglocs SET Capacity = Capacity - 1 WHERE ParkingID = :parkingID");
                $deductCapacityStmt->bindParam(':parkingID', $parkingID);
                $deductCapacityStmt->execute();
            } 
            else 
            {
                echo "Parking is full. Cannot check in.";
            }
        } 
        catch (PDOException $e) 
        {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Check if the form is submitted for check-out
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkOutUser'])) 
    {
        // Assuming you have the ParkingID and UserID from the form
        $parkingID = intval($_POST['parkingID']);
        $userID = intval($_POST['userID']);

        // Create a connection to the database
        global $servername, $username, $password, $dbname;
        try 
        {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Update check-out record with RealCheckOutTime
            $checkOutStmt = $conn->prepare("UPDATE parkingrecords SET RealCheckOutTime = NOW() WHERE ParkingID = :parkingID AND UserID = :userID AND CheckOutTime IS NULL");

            $checkOutStmt->bindParam(':parkingID', $parkingID);
            $checkOutStmt->bindParam(':userID', $userID);

            // Execute the check-out statement
            $checkOutStmt->execute();

            // Add capacity
            $addCapacityStmt = $conn->prepare("UPDATE parkinglocs SET Capacity = Capacity + 1 WHERE ParkingID = :parkingID");
            $addCapacityStmt->bindParam(':parkingID', $parkingID);
            $addCapacityStmt->execute();
        } 
        catch (PDOException $e) 
        {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Display the form for admin to check in a user
    echo '
        <div class="check-form">
            <form method="post" action="/">
                <label for="parkingID">Parking Location ID:</label>
                <input type="text" id="parkingID" name="parkingID" required>
                
                <label for="userID">User ID:</label>
                <input type="text" id="userID" name="userID" required>

                <!-- Prompt user for expected CheckOutTime when checking in -->
                <label for="CheckOutTime">Expected Check Out Time:</label>
                <input type="datetime-local" id="CheckOutTime" name="CheckOutTime">

                <button class="nicebtn" type="submit" name="checkInUser">Check In</button>
            </form>
        </div>';

    // Display the table of currently checked-in users for check-out
    displayCheckedInUsersForCheckOut();
    // Process check-out form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkOutUser'])) 
    {
        // Ensure the required fields are present in the form
        if (isset($_POST['parkingID']) && isset($_POST['userID'])) 
        {
            // Process the check-out
            $parkingID = intval($_POST['parkingID']);
            $userID = intval($_POST['userID']);
            checkOutUser($parkingID, $userID);
        }
    }
    echo '</div></div>';
}
