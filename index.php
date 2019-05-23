<?php
include 'vendor/autoload.php';

$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '35202', // change to The client ID
    'clientSecret'            => getenv('CLIENT_SECRET'),
    'redirectUri'             => 'http://127.0.0.1/index.php',
    'urlAuthorize'            => 'https://www.strava.com/oauth/authorize',
    'urlAccessToken'          => 'https://www.strava.com/oauth/token?client_id=35202', // change to The client ID
    'urlResourceOwnerDetails' => 'https://www.strava.com/api/v3/athlete'
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
    $authorizationUrl = $provider->getAuthorizationUrl();

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl . '&approval_prompt=auto&scope=activity:read_all');
    exit;

// Check given state against previously stored one to mitigate CSRF attack
// FIXME: CSRF チェックが通らない orz
// } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
//
//     unset($_SESSION['oauth2state']);
//     exit('Invalid state');
//
} else {

    try {

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo $accessToken->getToken() . "\n";
        echo $accessToken->getRefreshToken() . "\n";
        echo $accessToken->getExpires() . "\n";
        echo ($accessToken->hasExpired() ? 'expired' : 'not expired') . "\n";

        // Using the access token, we may look up details about the
        // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);

        var_export($resourceOwner->toArray());

        // The provider provides a way to get an authenticated API request for
        // the service, using the access token; it returns an object conforming
        // to Psr\Http\Message\RequestInterface.
        // $request = $provider->getAuthenticatedRequest(
        //     'GET',
        //     'https://example.com/oauth2/lockdin/resource',
        //     $accessToken
        // );

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());

    }

}
