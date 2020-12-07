<?php

namespace App\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="wallets")
 */
class Wallet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var double
     * @ORM\Column(type="float")
     */
    protected $credit;

    /**
    * @ORM\OneToMany(targetEntity="WalletRecord", mappedBy="wallet", cascade={"persist"})
    * @var ArrayCollection|WalletRecord[]
    */
    protected $records;

    /**
    * @ORM\OneToOne(targetEntity="User", inversedBy="wallet")
    * @var User
    */
    protected $user;
    

    public function __construct($credit)
    {
        $this->credit = $credit;
        $this->records = new ArrayCollection();
    }

    public function addRecord(WalletRecord $record)
    {
        if(!$this->records->contains($record)) {
            $record->setWallet($this);
            $this->records->add($record);
        }
    }

    public function setUser(User $user){
        $this->user = $user;
    }

    
}
