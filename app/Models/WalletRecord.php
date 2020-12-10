<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="wallet_records")
 */
class WalletRecord
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    protected $amount;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $token;


    /**
     * @ORM\ManyToOne(targetEntity="Wallet", inversedBy="records")
     * @var Wallet
     */
    protected $wallet;

    public function __construct($amount, $status, $type, $token)
    {
        $this->amount = $amount;
        $this->status = $status;
        $this->type = $type;
        $this->token = $token;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setWallet(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    public function getWallet()
    {
        return $this->wallet;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getAmount()
    {
        return $this->amount;
    }
    
    public function getType()
    {
        return $this->type;
    }

    public function getFormattedRecord()
    {
        return (object) [
            'id' => $this->id,
            'amount' => $this->amount,
            'type' => $this->type,
            'status' => $this->status
        ];
    }
}
