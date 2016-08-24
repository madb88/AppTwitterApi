<?php 

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Abraham\TwitterOAuth\TwitterOAuth;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Twitts;
use AppBundle\Form\TwittType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;

class TwitterController extends Controller
{
    
    /**
    *@Route ("/", name="mainPage")
    */
    public function mainPageAction()
    {
        return $this->render('AppBundle::mainPage.html.twig');
    }

    /**
    *@Route("/createTwitt", name="createTwitt")
    *@Template("AppBundle::newTwitt.html.twig")
    */
    public function createTwittAction()
    {
        $twitt = new Twitts();

        $form = $this->createForm(TwittType::class, $twitt, array(
            'action' => $this->generateUrl('postTweet')));
            
        return array(
            'form' => $form->createView(),
            
        );
    }

    //Saving new twitt to database (text and user)
    //Adding new Twitt to Twitter website

    /**
    * @Route("/postTweet", name="postTweet")
    * @Template ("AppBundle::addNewTwitt.html.twig")
    */
    public function postTwittAction(Request $request)
    {
        $twitt = new Twitts();

        //loading token and connection obj. from session
        $session = $request->getSession();
        $access_token = $session->get('access_token');
        $connection = $session->get('connection');

        //Getting user info from session 
        $user = $session->get('userTwitt');
        $twitt->setUserTwitt($user->name);

        $form = $this->createForm(TwittType::class, $twitt, array(
            'action' => $this->generateUrl('postTweet')));

        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($twitt);
            $em->flush();
        }

        //Getting the twitt text
        $twittText = $request->get("twitt")["twitt_text"];
        
        $newTweet = $connection->post("statuses/update", [
            'status' => $twittText]);
        
        return array('newTweet' => $newTweet, 'user'=>$user);
    }

    //Form for finding twitts for choosen user
    //Response in json. 
    
    /**
    * @Route("/showUserTwitts", name="showUserTwitts")
    * @Template("AppBundle::searchUserTwitts.html.twig")
    */
    public function showUserTwittsFormAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('showTwitts'))
            ->add('user_name', TextType::class, array(
                'label' => 'User name',
                'constraints'=>array(
                    new Assert\NotBlank(),
                    )))
            ->add('twitt_count', IntegerType::class, array(
                'label' => 'How many twitts? (200max)',
                'constraints' => array(
                    new Assert\Range (array(
                        'min' => 1,
                        'max' => 200,
                        'minMessage' => 'You must search at least {{ limit }} twiit',
                        'maxMessage' => 'You cannot search more than {{ limit }} twitt',
                        )))
                ))
            ->add('save', SubmitType::class, array(
                'label' => 'Show this user Twitts - JsonResponse'))
            ->getForm();
        $form->handleRequest($request);

        return array(
            'form' => $form->createView(),
        );
    }

    /**
    *@Route ("/showTwitts", name="showTwitts")
    */
    public function showUserTwittsJsonAction(Request $request)
    {
        $session = $request->getSession();
        $access_token = $session->get('access_token');
        $connection = $session->get('connection');

        $userTwitts = $request->get("form")["user_name"];
        $twittCount = $request->get("form")["twitt_count"];

        $tweets = $connection->get("statuses/user_timeline", [
            'count' => $twittCount, 
            'exclude_replies'=> true, 
            'screen_name'=> $userTwitts]);

        $response = new JsonResponse();

        return $response->setData(array(
            'tweets' => $tweets
        ));

    }
}
