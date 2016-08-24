<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Abraham\TwitterOAuth\TwitterOAuth;
use AppBundle\Entity\Twitts;


class ConnectionController extends Controller 
{

	/**
    *@Route("/main", name="homepage")
    */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();

        $url = $this->generateUrl('callBack');
        $connection = Twitts::connectionToApi();

        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => Twitts::OAUTH_CALLBACK));
        $session->set('oauth_token', $request_token['oauth_token']);
        $session->set('oauth_token_secret', $request_token['oauth_token_secret']);
        $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

        return $this->redirect($url);
    }

    /**
    *@Route("/callBack", name="callBack");
    */
    public function callBackAction(Request $request)
    {
	    $session = $request->getSession();

        $request_token = [];
        $request_token['oauth_token'] = $session->get('oauth_token');
        $request_token['oauth_token_secret'] = $session->get('oauth_token_secret');

        $connection = new TwitterOAuth(Twitts::CONSUMER_KEY, Twitts::CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
        $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_REQUEST['oauth_verifier']]);
		
        $session->set('access_token', $access_token);
        $session->set('connection', $connection);

        return $this->redirectToRoute('finish');
	}

    /**
    *@Route ("/finish", name="finish");
    *@Template("AppBundle::user.html.twig");
    */
    public function authorizationFinishAction(Request $request)
    {
    	$session = $request->getSession();
    	$access_token = $session->get('access_token');

    	$connection = new TwitterOAuth(Twitts::CONSUMER_KEY, Twitts::CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $session->set('connection', $connection);

    	$user = $connection->get("account/verify_credentials");
        $session->set('userTwitt', $user);

    	return array('user'=>$user);
    }
}