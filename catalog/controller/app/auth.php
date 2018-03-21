<?php
class ControllerAppAuth extends Controller {
	public function index(){
		$username = 'root';
		$password = 'root';
		$dsn = 'mysql:host=localhost;dbname=db_mycncart_new';
		require_once('./system/OAuth2/Autoloader.php');
		OAuth2\Autoloader::register();
		$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
		$server = new OAuth2\Server($storage);
		$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage)); // or any grant type you like!
		//$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
	}

	public function auth(){
		$username = 'root';
		$password = 'root';
		$dsn = 'mysql:host=localhost;dbname=db_mycncart_new';
		require_once('./system/OAuth2/Autoloader.php');
		OAuth2\Autoloader::register();
		$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
		$server = new OAuth2\Server($storage);
		$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage)); // or any grant type you like!
		$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
	}

	public function authorize()
    {
        
        $app = $this->setup($app);

        $server = $app['oauth_server'];

         // get the oauth response (configured in src/OAuth2Demo/Server/Server.php)
        $response = $app['oauth_response'];

        // validate the authorize request.  if it is invalid, redirect back to the client with the errors in tow
        if (!$server->validateAuthorizeRequest($app['request'], $response)) {
            return $server->getResponse();
        }

        // display the "do you want to authorize?" form
        return $app['twig']->render('server/authorize.twig', array(
            'client_id' => $app['request']->query->get('client_id'),
            'response_type' => $app['request']->query->get('response_type')
        ));
    }

    public function setup(Application $app)
    {
        $username = 'root';
		$password = 'root';
		$dsn = 'mysql:host=localhost;dbname=db_mycncart_new';
		require_once('./system/OAuth2/Autoloader.php');
		OAuth2\Autoloader::register();
		$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));

        //$storage = new Pdo(array('dsn' => 'sqlite:'.$sqliteFile));

        // create array of supported grant types
        $grantTypes = array(
            'authorization_code' => new AuthorizationCode($storage),
            'user_credentials'   => new UserCredentials($storage),
            'refresh_token'      => new RefreshToken($storage, array(
                'always_issue_new_refresh_token' => true,
            )),
        );

        // instantiate the oauth server
        $server = new OAuth2Server($storage, array(
            'enforce_state' => true,
            'allow_implicit' => true,
            'use_openid_connect' => true,
            'issuer' => $_SERVER['HTTP_HOST'],
        ),$grantTypes);

        $server->addStorage($storage);

        // add the server to the silex "container" so we can use it in our controllers (see src/OAuth2Demo/Server/Controllers/.*)
        $app['oauth_server'] = $server;

        $app['oauth_response'] = new BridgeResponse();
    }

}