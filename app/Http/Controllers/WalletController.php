<?php

namespace App\Http\Controllers;

use App\Enum\RecordStatus;
use App\Enum\RecordType;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletRecord;
use App\Utils\CommonResponse;
use LaravelDoctrine\ORM\Facades\EntityManager;

class WalletController
{
    /**
     * Create Wallet is succesful return true otherwise throws an exception
     *
     * @param int $userId
     * @param float $credit
     * @return \App\Utils\CommonResponse
     */
    public function createWallet($userId, $credit)
    {
        $existingUser = EntityManager::find(User::class, $userId);

        if (!$existingUser || $existingUser->getWallet()) {
            header("Status: 404");
            return new CommonResponse(404, 'User does not exists or has not a wallet');
        }

        $existingUser->setWallet(new Wallet($credit));
        EntityManager::flush($existingUser);
        return new CommonResponse(200, 'Wallet Create');
    }

    /**
     * Get Wallet is succesful return wallet and its records otherwise throws an exception
     *
     * @param int $userId
     * @return \App\Utils\CommonResponse
     */
    public function getWallet($userId)
    {
        $existingUser = EntityManager::find(User::class, $userId);

        if (!$existingUser || !$existingUser->getWallet()) {
            header("Status: 404");
            return new CommonResponse(404, 'User does not has wallet');
        }

        return new CommonResponse(200, 'Success', $existingUser->getWallet()->getFormattedWallet());
    }

    /**
     * Create a Purchase that need to be verified
     *
     * @param int $userId
     * @return \App\Utils\CommonResponse
     */
    public function createPurchase($userId, $amount, $token)
    {
        
        $existingUser = EntityManager::find(User::class, $userId);
        $existingWallet = null;
        
        if (!$existingUser ) {
            header("Status: 404");
            return new CommonResponse(404, 'Wallet does not exists');
        }
        
        $existingWallet = $existingUser->getWallet();

        if($existingWallet->getCredit() < $amount) {
            header("Status: 403");
            return new CommonResponse(403, 'Wallet does not have enough credits');
        }

        $record = new WalletRecord($amount, RecordStatus::PENDING, RecordType::PURCHASE, $token);
        $existingWallet->addRecord($record);

        EntityManager::persist($record);
        EntityManager::persist($existingWallet);
        EntityManager::flush();

        return new CommonResponse(200, 'Success', $record->getFormattedRecord());
    }

    /**
     * Get Wallet is succesful return wallet and records otherwise throws an exception
     *
     * @param int $userId
     * @param int $recordId
     * @param string $token
     * @return \App\Utils\CommonResponse
     */
    public function verifyPurchase($userId, $recordId, $token)
    {
        $existingUser = EntityManager::find(User::class, $userId);
        $existingWallet = null;

        if (!$existingUser) {
            header("Status: 404");
            return new CommonResponse(404, 'Wallet does not exists');
        }

        $existingWallet = $existingUser->getWallet();
        $record = $existingWallet->getRecords()->filter(function ($record) use ($recordId) {
            return $record->getId() == $recordId;
        })->first();

        if (!$record && $record->getToken() != $token && $record->getStatus() != RecordStatus::APPROVED) {
            return new CommonResponse(403, 'Invalid token');
        }

        $record->setStatus(RecordStatus::APPROVED);
        EntityManager::persist($record);
        EntityManager::flush($record);
        return new CommonResponse(200, 'Success', $record->getFormattedRecord());
    }

    /**
     * Add credit to wallet if document and phone match with wallet's user
     * 
     * @param int $userId
     * @param string $document
     * @param string $phone
     * @param float $amount
     * @return \App\Utils\CommonResponse
     */
    public function addCreditToWallet($userId, $document, $phone, $amount)
    {
        $existingUser = EntityManager::find(User::class, $userId);
        $existingWallet = null;
      
        if (!$existingUser) {
            header("Status: 404");
            return new CommonResponse(404, 'User does not exists');
        }
        $existingWallet = $existingUser->getWallet();

        if ($existingUser->getDocument() == $document && $existingUser->getPhone() == $phone) {
            $currentCredit = $existingWallet->getCredit();
            $existingWallet->setCredit($currentCredit + $amount);
            EntityManager::persist($existingWallet);
            EntityManager::flush($existingWallet);
            return new CommonResponse(200, 'Success', $existingWallet->getFormattedWallet());
        }

        return new CommonResponse(403, 'Invalid params');
    }

     /**
     * Get Wallet credit succesful return wallet credit
     *
     * @param int $userId
     * @return \App\Utils\CommonResponse
     */
    public function getWalletCredit($userId)
    {
        $existingUser = EntityManager::find(User::class, $userId);

        if (!$existingUser || !$existingUser->getWallet()) {
            header("Status: 404");
            return new CommonResponse(404, 'User does not has wallet');
        }

        return new CommonResponse(200, 'Success', $existingUser->getWallet()->getCredit());
    }
}
