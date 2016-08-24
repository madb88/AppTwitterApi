<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Twitts
 *
 * @ORM\Table(name="twitts")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TwittsRepository")
 */
class Twitts
{
    

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="twitt_text", type="text")
     */
    private $twitt_text;

    /**
     * @var string
     *
     * @ORM\Column(name="user_twitt", type="string", length=255)
     */
    private $user_twitt;

    
    const CONSUMER_KEY = 'axtyuAjBWFohsS8htEE7c81eB';

    const CONSUMER_SECRET = 'QCKGmQp5pcHfnJZ15AJNmFqG9eTO30IDsjU7CF9qk0xnbtGXwE';
    
    const OAUTH_CALLBACK = 'http://127.0.0.1:8000/callBack';

    static function connectionToApi()
    {
       
        $connection = new TwitterOAuth(Twitts::CONSUMER_KEY, Twitts::CONSUMER_SECRET);
            
        return $connection;
        
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set twittText
     *
     * @param string $twittText
     *
     * @return Twitts
     */
    public function setTwittText($twitt_text)
    {
        $this->twitt_text = $twitt_text;

        return $this;
    }

    /**
     * Get twittText
     *
     * @return string
     */
    public function getTwittText()
    {
        return $this->twitt_text;
    }

    /**
     * Set userTwitt
     *
     * @param string $userTwitt
     *
     * @return Twitts
     */
    public function setUserTwitt($user_twitt)
    {
        $this->user_twitt = $user_twitt;

        return $this;
    }

    /**
     * Get userTwitt
     *
     * @return string
     */
    public function getUserTwitt()
    {
        return $this->user_twitt;
    }

    /**
     * Set imgTwitt
     *
     * @param string $imgTwitt
     *
     * @return Twitts
     */
    public function setImgTwitt($imgTwitt)
    {
        $this->imgTwitt = $imgTwitt;

        return $this;
    }

    /**
     * Get imgTwitt
     *
     * @return string
     */
    public function getImgTwitt()
    {
        return $this->imgTwitt;
    }


}

