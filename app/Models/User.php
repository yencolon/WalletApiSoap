<?php

namespace App\Models;
use Doctrine\ORM\Mapping AS ORM;
use Illuminate\Contracts\Auth\Authenticatable;
use  \LaravelDoctrine\ORM\Auth\Authenticatable AS Auth;
use LaravelDoctrine\ORM\Facades\EntityManager;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements Authenticatable
{
    use Auth;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

      /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $lastname;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $phone;

    /**
     * @ORM\OneToOne(targetEntity="Wallet", mappedBy="user", cascade={"persist"})
     * @var Wallet
     */
    protected $wallet;

    /**
     * User constructor
     * @param string $name
     * @param string $lastame
     * @param string $email
     * @param string $password
     * @param string $phone
     * @param string $lastame
     */
    public function __construct($name, $lastame, $phone, $email, $password)
    {
        $this->name = $name;
        $this->lastname = $lastame;
        $this->phone = $phone;
        $this->email = $email;
        $this->password = $password;
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setWallet(Wallet $wallet)
    {
        $this->wallet = $wallet;
        $wallet->setUser($this);
    }
    
    public function getWallet()
    {
        return $this->wallet;
    }

}
