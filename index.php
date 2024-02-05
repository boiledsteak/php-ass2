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

function headerComponent()
{
    echo '
    <div class="outerhead">
        <div class="thehead">
            <a class="thelogo" href="/">
                Funny facts
            </a>
            <div class="menoptions">
                <div class="menoption">
                    <a href="/leaderboard">Leaderboard</a>
                </div>
                <div class="menoption">
                    <a href="/exit">Exit</a>
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

    default:
    {
        http_response_code(404);
        $errorPageUrl = "https://http.cat/404";
        header("Location: $errorPageUrl");
        exit;
    }
}

?>