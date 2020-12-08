<?php

/**
 * Created by PhpStorm.
 * User: saifkhichi96
 * Date: 29/05/2017
 * Time: 1:46 AM
 */
class Credentials
{
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $password;

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}