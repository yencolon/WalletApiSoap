<?php

namespace App\Models;

use Doctrine\ORM\Mapping AS ORM;

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
     * @ORM\Column(type="integer")
     */
    protected $type;

    /**
    * @ORM\ManyToOne(targetEntity="Wallet", inversedBy="records")
    * @var Wallet
    */
    protected $wallet;

    public function __construct($amount, $status, $type)
    {
        $this->amount = $amount;
        $this->status = $status;
        $this->type = $type;
    }

    public function setWallet(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }
    
    public function getWallet()
    {
        return $this->wallet;
    }

}
