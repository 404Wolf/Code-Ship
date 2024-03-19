<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Page metadata -->
    <title>Send it!</title>

    <style>
        .bg-light-gray {
            background-color: #f8f9fa;
        }
    </style>

    <?php
    echo '<script> const ADDRESS = "' . $_ENV['ADDRESS'] . '"; </script>';
    ?>

    <!-- Setup webhook on load -->
    <script>
        var doNotUpdateTextArea = false;

        function setInputAreaText(text) {
            document.getElementById("text-to-send").value = text;
        }

        function getInputAreaText() {
            return document.getElementById("text-to-send").value;
        }

        function shipNewContents() {
            const newContents = document.getElementById("text-to-send").value;
            console.log("Shipping the contents of the text area. New contents: " + newContents);
            fetch(`${ADDRESS}/update.php`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        text: newContents.slice(0, 100),
                        date: (new Date()).getTime()
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

        function beginPolling() {
            setInterval(() => {
                console.log("Attempting poll @" + (new Date()))
                fetch(`${ADDRESS}/contents.json`)
                    .then(response => response.json())
                    .then(data => {
                        console.log("Polling for new updates @" + (new Date()).toDateString())
                        if (!doNotUpdateTextArea) {
                            setInputAreaText(data.text);
                            document.getElementById("last-update").innerText = "Updated at " + formatTime(new Date());
                        }
                    })
            }, 2000);
        }

        function beginAutoship() {
            const textArea = document.getElementById("text-to-send")
            textArea.addEventListener("input", () => {
                shipNewContents();
                doNotUpdateTextArea = true;
                setTimeout(() => {
                    if (doNotUpdateTextArea) {
                        doNotUpdateTextArea = false;
                    }
                }, 1000);
            })
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
            beginAutoship();
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
$file = file_get_contents("contents.json");
echo json_decode($file, true)['text'];
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