<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\JoinColumn;

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
     * @var float
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
     * 
     * @var User
     */
    protected $user;

    /**
     * Wallet constructor
     * 
     * @param float $credit
     */
    public function __construct($credit)
    {
        $this->credit = $credit;
        $this->records = new ArrayCollection();
    }

    public function addRecord(WalletRecord $record)
    {
        if (!$this->records->contains($record)) {
            $record->setWallet($this);
            $this->records->add($record);
        }
    }

    public function getRecords()
    {
        return $this->records;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
    public function getUser()
    {
        return $this->user;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCredit()
    {
        return $this->credit;
    }

    public function setCredit($credit)
    {
        $this->credit = $credit;
    }

    public function getFormattedWallet()
    {
        return (object) [
            'id' => $this->id,
            'credit' => $this->credit,
            'records' => $this->getRecords()->map(function ($record) {
                return $record->getFormattedRecord();
            })
        ];
    }
}
