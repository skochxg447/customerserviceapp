<?php
session_start(); // start the PHP session

// check if the user is logged in
if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit();
}

$success = null;

// If the form was submitted, update the client record
if (isset($_POST['save'])) {
    // get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $time_before_greeting = $_POST['time_before_greeting'];
    $server_formality = $_POST['server_formality'];
    $jokes = $_POST['jokes'];
    $server_frequency = $_POST['server_frequency'];

    $db = new SQLite3('../db/client_user.db');

    // Prepare SQL statement to update client record
    $stmt = $db->prepare("UPDATE client_users SET phone = :phone, time_before_greeting = :time_before_greeting, server_formality = :server_formality, jokes = :jokes, server_frequency = :server_frequency WHERE id = :id");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':phone', $phone, SQLITE3_TEXT);
    $stmt->bindValue(':time_before_greeting', $time_before_greeting, SQLITE3_INTEGER);
    $stmt->bindValue(':server_formality', $server_formality, SQLITE3_INTEGER);
    $stmt->bindValue(':jokes', $jokes, SQLITE3_INTEGER);
    $stmt->bindValue(':server_frequency', $server_frequency, SQLITE3_INTEGER);
    $stmt->bindValue(':id', $_SESSION['client_id'], SQLITE3_INTEGER);
    
    // Execute SQL statement
    $result = $stmt->execute();
    $success = 'Update successful';

}


// If the user requested logout go back to ../../index.php
if (isset($_POST['logout'])) {
    
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    header('Location: ../../index.php');
    return;
}

// connect to the SQLite database
$db = new SQLite3('../db/client_user.db');

// prepare a SQL statement to select the user with the given id
$stmt = $db->prepare('SELECT * FROM client_users WHERE id = :id');

// bind the parameters to the statement
$stmt->bindParam(':id', $_SESSION['client_id']);

// execute the statement
$result = $stmt->execute();

// fetch the user's information
$user = $result->fetchArray(SQLITE3_ASSOC);

// assign user data to variables
$name = isset($user['name']) ? $user['name'] : '';
$email = isset($user['email']) ? $user['email'] : '';
$phone = isset($user['phone']) ? $user['phone'] : '';
$time_before_greeting = isset($user['time_before_greeting']) ? $user['time_before_greeting'] : '';
$server_formality = isset($user['server_formality']) ? $user['server_formality'] : '';
$jokes = isset($user['jokes']) ? $user['jokes'] : '';
$server_frequency = isset($user['server_frequency']) ? $user['server_frequency'] : '';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSA Dashboard</title>
    <link href="../bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
   <div class="container">
    <div class="row">
       <div class="page-header">
          <h1>Welcome <?=ucwords($name)?></h1>
       </div>
     </div>
     <?php if ($success != null): ?>
       <div><?= $success?></div>
     <?php endif; ?>
   <div class="row">
    <div class="container col-12 col-lg-3">
      <h3>Schedule</h3>
      <iframe src="https://calendar.google.com/calendar/embed?src=grantwkoch%40gmail.com&ctz=America%2FLos_Angeles" style="border: 0" width="100%" height="75%" frameborder="0" scrolling="yes"></iframe>
      <p>Google Calendar API Quickstart</p>

      <!--Add buttons to initiate auth sequence and sign out-->
      <button class="btn btn-primary"
       id="authorize_button" onclick="handleAuthClick()">Authorize</button>
           <script>
             function signOut() {
               var auth2 = gapi.auth2.getAuthInstance();
               auth2.signOut().then(function () {
                 console.log('User signed out.');
               });
             }
           </script>
      <button class="btn btn-primary" id="signout_button" onclick="handleSignoutClick()">Sign Out</button>

      <pre id="content" style="white-space: pre-wrap;"></pre>
    </div>
    <div class="container col-12 col-lg-5 text-center">
      <h3>Your Information</h3>
      <p>
      <form method="post">
         <input type="hidden" name="client_id" value="<?php echo $_SESSION['client_id']; ?>">
         <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control input-small" id="name" name="name" value="<?= isset($user['name']) ? $user['name'] : '' ?>" required>
         </div>
         <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" class="form-control input-small" id="email" name="email" value="<?= isset($user['email']) ? $user['email'] : '' ?>" required>
         </div>
         <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control input-small" id="phone" name="phone" value="<?= isset($user['phone']) ? $user['phone'] : '' ?>" required>
         </div>
         <div class="form-group">
            <label for="time_before_greeting">Time before greeting (in minutes):</label>
            <input type="number" class="form-control input-small" id="time_before_greeting" name="time_before_greeting" min="0" max="10" value="<?php echo isset($time_before_greeting) ? $time_before_greeting : ''; ?>">
         </div>
         <div class="form-group">
            <label for="server_formality">Server formality:</label>
            <select class="form-control input-small" id="server_formality" name="server_formality">
               <option value="0" <?php if (isset($user['server_formality']) == 0) echo "selected"; ?>>--Please Select--</option>
               <option value="1" <?php if (isset($user['server_formality']) == 1) echo "selected"; ?>>Very Casual</option>
               <option value="2" <?php if (isset($user['server_formality']) == 2) echo "selected"; ?>>Casual</option>
               <option value="3" <?php if (isset($user['server_formality']) == 3) echo "selected"; ?>>Formal</option>
               <option value="4" <?php if (isset($user['server_formality']) == 4) echo "selected"; ?>>Very Formal</option>
            </select>
         </div>
         <div class="form-group">
            <label for="jokes">Jokes:</label>
            <div class="radio">
               <label>
               <input type="radio" name="jokes" value="0" <?php if (isset($user['jokes']) && $user['jokes'] == 0) echo "checked"; ?>> No
               </label>
            </div>
            <div class="radio">
               <label>
               <input type="radio" name="jokes" value="1" <?php if (isset($user['jokes']) && $user['jokes'] == 1) echo "checked"; ?>> Yes
               </label>
            </div>
         </div>
         <div class="form-group">
            <label for="server_frequency">Server frequency: how often the server should stop by</label>
            <input type="range" class="form-control-range input-small" id="server_frequency" name="server_frequency" min="0" max="200" value="<?php echo $user['server_frequency']; ?>">
         </div>
         <input type="submit" name="save" value="Save" class="btn btn-primary">
         <input type="submit" name="logout" value="Logout" class="btn btn-primary">
         <div id="demo-mobile-month-view"></div>
      </form>
    </p>
    </div>
    <div class="container col-12 col-lg-3">
      <h3>Share Preferences</h3>
      <p>
        <label for="search">Enter a location:</label>
        <input id="search" type="text">
        <div id="map"></div>
        <ul>
          <li><img src="#"></li>
          <li><img src="#"></li>
          <li><img src="#"></li>
          <li><img src="#"></li>
          <li><img src="#"></li>
          <li><img src="#"></li>
          <li><img src="#"></li>
          <li><img src="#"></li>
          <li><img src="#"></li>
          <li><img src="#"></li>
          <li><img src="#"></li>
        </ul>
      </p>
    </div>
   </div>
  <script type="text/javascript">
    /* exported gapiLoaded */
    /* exported gisLoaded */
    /* exported handleAuthClick */
    /* exported handleSignoutClick */

    // TODO(developer): Set to client ID and API key from the Developer Console
    const CLIENT_ID = '101571387133-al6k570eiq3c0q9ostvgkphdlb9b8nhn.apps.googleusercontent.com';
    const API_KEY = 'AIzaSyCC08blSX9P-B7rdAaa4XgAvbySVgO2QAw';

    // Discovery doc URL for APIs used by the quickstart
    const DISCOVERY_DOC = 'https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest';

    // Authorization scopes required by the API; multiple scopes can be
    // included, separated by spaces.
    const SCOPES = 'https://www.googleapis.com/auth/calendar.readonly';

    let tokenClient;
    let gapiInited = false;
    let gisInited = false;

    document.getElementById('authorize_button').style.visibility = 'hidden';
    document.getElementById('signout_button').style.visibility = 'hidden';

    /**
     * Callback after api.js is loaded.
     */
    function gapiLoaded() {
      gapi.load('client', initializeGapiClient);
    }

    /**
     * Callback after the API client is loaded. Loads the
     * discovery doc to initialize the API.
     */
    async function initializeGapiClient() {
      await gapi.client.init({
        apiKey: API_KEY,
        discoveryDocs: [DISCOVERY_DOC],
      });
      gapiInited = true;
      maybeEnableButtons();
    }

    /**
     * Callback after Google Identity Services are loaded.
     */
    function gisLoaded() {
      tokenClient = google.accounts.oauth2.initTokenClient({
        client_id: CLIENT_ID,
        scope: SCOPES,
        callback: '', // defined later
      });
      gisInited = true;
      maybeEnableButtons();
    }

    /**
     * Enables user interaction after all libraries are loaded.
     */
    function maybeEnableButtons() {
      if (gapiInited && gisInited) {
        document.getElementById('authorize_button').style.visibility = 'visible';
      }
    }

    /**
     *  Sign in the user upon button click.
     */
    function handleAuthClick() {
      tokenClient.callback = async (resp) => {
        if (resp.error !== undefined) {
          throw (resp);
        }
        document.getElementById('signout_button').style.visibility = 'visible';
        document.getElementById('authorize_button').innerText = 'Refresh';
        await listUpcomingEvents();
      };

      if (gapi.client.getToken() === null) {
        // Prompt the user to select a Google Account and ask for consent to share their data
        // when establishing a new session.
        tokenClient.requestAccessToken({prompt: 'consent'});
      } else {
        // Skip display of account chooser and consent dialog for an existing session.
        tokenClient.requestAccessToken({prompt: ''});
      }
    }

    /**
     *  Sign out the user upon button click.
     */
    function handleSignoutClick() {
      const token = gapi.client.getToken();
      if (token !== null) {
        google.accounts.oauth2.revoke(token.access_token);
        gapi.client.setToken('');
        document.getElementById('content').innerText = '';
        document.getElementById('authorize_button').innerText = 'Authorize';
        document.getElementById('signout_button').style.visibility = 'hidden';
      }
    }

    /**
     * Print the summary and start datetime/date of the next ten events in
     * the authorized user's calendar. If no events are found an
     * appropriate message is printed.
     */
    async function listUpcomingEvents() {
      let response;
      try {
        const request = {
          'calendarId': 'primary',
          'timeMin': (new Date()).toISOString(),
          'showDeleted': false,
          'singleEvents': true,
          'maxResults': 10,
          'orderBy': 'startTime',
        };
        response = await gapi.client.calendar.events.list(request);
      } catch (err) {
        document.getElementById('content').innerText = err.message;
        return;
      }

      const events = response.result.items;
      if (!events || events.length == 0) {
        document.getElementById('content').innerText = 'No events found.';
        return;
      }
      // Flatten to string to display
      const output = events.reduce(
          (str, event) => `${str}${event.summary} (${event.start.dateTime || event.start.date})\n`,
          'Events:\n');
      document.getElementById('content').innerText = output;
    }
  </script>
  <script type="text/javascript">
    var inst = mobiscroll.eventcalendar('#demo-mobile-month-view', {
        theme: 'ios',
        themeVariant: 'light',
        clickToCreate: false,
        dragToCreate: false,
        dragToMove: false,
        dragToResize: false,
        eventDelete: false,
        view: {
            calendar: { type: 'month' },
            agenda: { type: 'month' }
        },
        onEventClick: function (event, inst) {
            mobiscroll.toast({
                message: event.event.title
            });
        }
    });

    mobiscroll.util.http.getJson('https://trial.mobiscroll.com/events/?vers=5', function (events) {
        inst.setEvents(events);
    }, 'jsonp');
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCC08blSX9P-B7rdAaa4XgAvbySVgO2QAw&libraries=places"></script>
  <script src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
  <script src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
</body>
</html>