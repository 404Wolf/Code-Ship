<!doctype html>
<html lang="en">

<?php
require_once __DIR__ . '/vendor/autoload.php';
require 'vendor/autoload.php';
Predis\Autoloader::register();

$client = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => 'redis',  // Use the service name as the hostname                                                                                                                
    'port'   => 6379,  // Default port for Redis                                                                                                                                 
]);

function injectVariable($name, $value)
{
    echo '<script> const ' . $name . ' = "' . $value . '"; </script>';
}
?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Favicon -->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Page metadata -->
    <title>Send it!</title>

    <style>
        .bg-light-gray {
            background-color: #f8f9fa;
        }
    </style>

    <?php
    injectVariable('MAX_LENGTH', $_ENV['MAX_LENGTH']);
    injectVariable('ADDRESS', $_ENV['ADDRESS']);
    injectVariable('PORT', $_ENV['PORT']);
    injectVariable('REFRESH_RATE', $_ENV['REFRESH_RATE'])
    ?>

    <!-- Setup webhook on load -->
    <script>
        let priorContents = false;

        function setInputAreaText(text) {
            document.getElementById("text-to-send").value = text;
        }

        function getInputAreaText() {
            return document.getElementById("text-to-send").value;
        }

        function shipNewContents() {
            const newContents = document.getElementById("text-to-send").value;
            console.log("Shipping the contents of the text area. New contents: " + newContents);
            fetch(`${ADDRESS}:${PORT}/state.php`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        text: newContents.slice(0, MAX_LENGTH),
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.log(error);
                })
        }

        function justUpdated() {
            document.getElementById("last-update").innerText = "Updated at " + formatTime(new Date());
        }

        function beginPolling() {
            setInterval(() => {

                console.log("Attempting poll @" + (new Date()))
                const textArea = document.getElementById("text-to-send");
                if (textArea.value !== priorContents) {
                    console.log("New contents detected. Shipping new contents.");
                    shipNewContents();
                    priorContents = textArea.value;
                    justUpdated();
                    return
                }

                fetch(`${ADDRESS}:${PORT}/state.php`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.text !== priorContents) {
                            console.log("Received new text contents. Updating text area.");
                            setInputAreaText(data.text);
                        }
                        justUpdated();
                    })
            }, REFRESH_RATE);
        }

        function formatTime() {
            const date = new Date();

            let hours = date.getHours();
            const minutes = date.getMinutes();
            const seconds = date.getSeconds();
            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'                        
            const strHours = hours < 10 ? '0' + hours : hours;
            const strMinutes = minutes < 10 ? '0' + minutes : minutes;
            const strSeconds = seconds < 10 ? '0' + seconds : seconds;

            return strHours + ':' + strMinutes + ':' + strSeconds + ' ' + ampm;
        }

        addEventListener("DOMContentLoaded", () => {
            beginPolling();
        })
    </script>
</head>

</script>
</head>

<body>
    <!-- Main container for body -->
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="my-4 p-3 pb-0 border col-10 bg-light-gray">
                <h1 class="w-75 text-center mx-auto mb-2">Sender</h1>
                <p>
                    Share your code! Whatever you enter below becomes what everyone visiting this site sees,
                    and the current contents on the page will be lost.
                </p>

                <!-- Area for user input -->
                <code id="code-text-area">
                    <textarea class="form-control font-monospace text-sm" style="min-height: 32rem; font-size: 12px" id="text-to-send" rows="3">
<?php
echo $client->get('text');
?>
</textarea>
                </code>

                <!-- Last update timestamp -->
                <p class="text-right -mb-2" id="last-update">
                    Updated at <?php echo date("h:i:s A"); ?>
                </p>
            </div>
        </div>
    </div>

    <footer style="background-color: #f2f2f2; padding: 10px; position: fixed; bottom: 0; width: 100%;" class="container-fluid text-right border">
        Made by <span><a href="http://404wolf.com" target="_blank">Wolf</a></span>
    </footer>
</body>

</html>