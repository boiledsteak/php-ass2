<!-- components functions -->
<?php
date_default_timezone_set("Asia/Singapore");

function normalComponent()
{
    // Get the name of the currently logged-in user
    $currentUserName = $_SESSION['user']->getName();
        
    echo '
        <div class="canvas"><div class="mainpagefn">
            <div class="title">Welcome, ' . $currentUserName . '!</div>
                <div class="adminpage">
                    <a class="abilities" href="/userploc">Parking locations</a>
                    <a class="abilities" href="/usercheck">Check in/out</a>
                </div>
    

    
            </div>
        </div>
    ';
}

function adminComponent()
{
    // Check if the user is logged in
    if (isset($_SESSION['user'])) 
    {
        // Get the name of the currently logged-in user
        $currentUserName = $_SESSION['user']->getName();
        
        echo '
            <div class="canvas"><div class="mainpagefn">
                <div class="title">Welcome, ' . $currentUserName . '!</div>
                    <div class="adminpage">
                        <a class="abilities" href="/users">Users</a>
                        <a class="abilities" href="/ploc">Parking locations</a>
                        <a class="abilities" href="/check">Check in/out</a>
                    </div>
        

        
                </div>
            </div>
        ';
    } 
    else 
    {
        echo '<p>No user logged in.</p>';
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

?>