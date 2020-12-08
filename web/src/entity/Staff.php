<?php

/**
 * Created by PhpStorm.
 * User: saifkhichi96
 * Date: 27/05/2017
 * Time: 12:17 PM
 */
class Staff
{
    /**
     * @var string
     */
    private $nic;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $email;
    /**
     * @var int
     */
    private $admin;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getNic()
    {
        return $this->nic;
    }

    /**
     * @param string $nic
     */
    public function setNic($nic)
    {
        $this->nic = $nic;
    }

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

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->admin != 0;
    }

    /**
     * @return int
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->isAdmin() ? "Manager" : "Cashier";
    }

    /**
     * @param int $admin
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }

    function getJsonData()
    {
        $var = get_object_vars($this);
        foreach ($var as &$value) {
            if (is_object($value) && method_exists($value, 'getJsonData')) {
                $value = $value->getJsonData();
            }
        }
        return $var;
    }

}