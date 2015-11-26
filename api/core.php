<?php

require "/vendor/autoload.php";															// loads our frameworks

require "/database.php";																// loads our database handling code


class tokenAuth extends \Slim\Middleware
{
	public function __construct()
	{
        // Get reference to application
        $app = $this->app;

        // Run inner middleware and application
        $this->next->call();

        // Capitalize response body
        $res = $app->response;
        $body = $res->getBody();
        $res->setBody(strtoupper($body));
	}
}


class apiHandler
{
	public function __construct()
	{
		$app = new \Slim\Slim();														// slim framework
		$app->add(new tokenAuth())
		/*
		Defining all the routes below.
		See https://github.com/intheon/rest-backend#allowed-methods for a full list.
		*/

		$app->get("/", array($this, "readHomeRoute"));	

		/*							// for the root
		$app->get("/user", array($this, "tokenCheck"), array($this, "readAllUsers"));							// get all users
		$app->get("/widget", array($this, "tokenCheck"), array($this, "readAllWidgets")); 						// get all widgets
		$app->get("/user/:id", array($this, "tokenCheck"), array($this, "readOneUser"));						// get specific user
		$app->get("/widget/:id", array($this, "tokenCheck"), array($this, "readOneWidget"));					// get specific widget
		$app->get("/state/:id", array($this, "tokenCheck"), array($this, "readOneState"));						// get specific state
		$app->post("/login/:username/:password", array($this, "tokenCheck"), array($this, "loginUser"));		// create new user
		$app->post("/user", array($this, "tokenCheck"), array($this, "createUser"));							// create new user
		$app->post("/widget", array($this, "tokenCheck"), array($this, "createWidget"));						// create new widget
		$app->post("/state", array($this, "tokenCheck"), array($this, "createState"));							// create new state
		$app->put("/user/:id", array($this, "tokenCheck"), array($this, "updateUser"));							// update user
		$app->put("/widget/:id", array($this, "tokenCheck"), array($this, "updateWidget"));						// update widget
		$app->put("/state/:id", array($this, "tokenCheck"), array($this, "updateState"));						// update state
		$app->delete("/user/:id", array($this, "tokenCheck"), array($this, "deleteUser"));						// delete user
		$app->delete("/widget/:id", array($this, "tokenCheck"), array($this, "deleteWidget"));					// delete widget
		$app->delete("/state/:id", array($this, "tokenCheck"), array($this, "deleteState"));					// delete state
		*/

		$app->run();																	// start this mofo
	}

	public function tokenCheck()
	{
		echo "HAI!";
	}

	public function readHomeRoute()														// messages for the home screen
	{

		echo "root mofo";
	}

	public function readAllUsers()														// block of users
	{
		// this needs restricting to be admin only, or removing!
		$db = new database();
		$data = $db->connectToDB()->select("user","*");
		echo json_encode($data);
	}

	public function readAllWidgets()													// get block of widgets
	{
		echo "all widgets...";
	}

	public function readOneUser($id)													// get users profile
	{
		//$db = new database();
		echo "a specific user... " . $id;
	}

	public function readOneWidget($id)													// get widget info
	{
		echo "a specific widget... " . $id;
	}

	public function readOneState($id)													// get specific state details
	{
		echo "a specific state... " . $id;
	}

	public function createUser()														// create a new user
	{
		echo "user created";
	}

	public function createWidget()														// create new widget
	{
		echo "widget created";
	}

	public function createState()														// create new state
	{
		echo "state created";
	}

	public function loginUser($username, $password)										// log user in
	{
		$doesUserExist = $this->checkUsernameExists($username);

		if ($doesUserExist)
		{
			$isPasswordCorrect = $this->checkPassword($username, $password);
			if ($isPasswordCorrect) $this->generateAuthToken($username);
			else if ($isPasswordCorrect == false) $this->responseBuilder("message", "incorrectpwd");
		}
		else $this->responseBuilder("message", "nonexistent");
	}

	public function updateUser()														// update existing user
	{
		echo "user updated";
	}

	public function updateWidget()														// update existing widget
	{
		echo "widget updated";
	}

	public function updateState()														// update existing state
	{
		echo "state updated";
	}

	public function deleteUser()														// delete existing user
	{
		echo "user deleted";
	}

	public function deleteWidget()														// delete existing widget
	{
		echo "widget deleted";
	}

	public function deleteState()														// delete existing state
	{
		echo "state deleted";
	}

	private function hashPassword($plaintext_password)									// helper functions for log in and auth stuff
	{
		return password_hash($plaintext_password,PASSWORD_DEFAULT);
	}

	private function checkUsernameExists($username)
	{
		$db = new database();
		$data = $db->connectToDB()->select("auth", "username", ["username" => $username]);

		if (count($data) === 0) return false;
		else if (count($data) === 1) return true;
	}

	private function checkPassword($username, $password)
	{
		$db = new database();
		$data = $db->connectToDB()->select("auth", "password", ["username" => $username]);

		foreach ($data as $row)
			if (!password_verify($password,$row)) return false;
			else if (password_verify($password,$row)) return true;
	}

	private function generateAuthToken($username)
	{
		/*
		see https://www.webstreaming.com.ar/articles/php-slim-token-authentication/
		*/
		$credentials["username"] = $username;
		$credentials["token"] = bin2hex(openssl_random_pseudo_bytes(16));
		$credentials["tokenExpiration"] = date('Y-m-d H:i:s', strtotime('+1 hour'));

		$db = new database();
		$data = $db->connectToDB()->update("auth", [
				"token" => $credentials["token"],
				"token_expiry" => $credentials["tokenExpiration"]
			],[
				"username" => $username
			]);

		echo $this->responseBuilder("token", json_encode($credentials));
	}

	private function responseBuilder($messageType, $messageBody)							// a helper function to build a json response to the client
	{
		$response["messageType"] = $messageType;
		$response["messageBody"] = $messageBody;
		echo json_encode($response);
	}
}

$api = new apiHandler();

?>