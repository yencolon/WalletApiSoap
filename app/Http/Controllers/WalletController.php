<?php

namespace App\Http\Controllers;

use App\Enum\RecordStatus;
use App\Enum\RecordType;
use App\Mail\ConfirmationCode;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletRecord;
use App\Utils\CommonResponse;
use Exception;
use Illuminate\Support\Facades\Mail;
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
            return new CommonResponse(404, 'El Usuario No Existe');
        }

        $existingUser->setWallet(new Wallet($credit));
        EntityManager::flush($existingUser);
        return new CommonResponse(200, 'Billetera Creada');
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
            return new CommonResponse(404, 'Usuario No Posee Billetera');
        }

        return new CommonResponse(200, 'Hecho', $existingUser->getWallet()->getFormattedWallet());
    }

    /**
     * Create a Purchase that need to be verified
     *
     * @param string $document
     * @param float $amount
     * @param string $token
     * 
     * @return \App\Utils\CommonResponse
     */
    public function createPurchase($document, $amount, $token)
    {
        $existingUser = EntityManager::createQuery("SELECT u FROM \App\Models\User u Where u.document = $document")->getSingleResult();
        $existingWallet = null;

        if (!$existingUser) {
            header("Status: 404");
            return new CommonResponse(404, 'Billetera No Existe');
        }

        $existingWallet = $existingUser->getWallet();

        if ($existingWallet->getCredit() < floatval($amount)) {
            header("Status: 403");
            return new CommonResponse(403, 'Billetera No Posee Suficientes Fondos');
        }

        $record = new WalletRecord($amount, RecordStatus::PENDING, RecordType::PURCHASE, $token);
        $existingWallet->addRecord($record);

        EntityManager::persist($record);
        EntityManager::persist($existingWallet);
        EntityManager::flush();

        try {
            Mail::to($existingUser->getEmail())->send(new ConfirmationCode($token));
        } catch (Exception $e) {
           
        }
        return new CommonResponse(
            200,
            'Se ha enviado un correo al usuario',
            $record->getFormattedRecord()
        );
    }

    /**
     * Get Wallet is succesful return wallet and records otherwise throws an exception
     *
     * @param int $recordId
     * @param string $token
     * @return \App\Utils\CommonResponse
     */
    public function verifyPurchase($recordId, $token)
    {
        $existingRecord = EntityManager::find(WalletRecord::class, $recordId);

        if (!$existingRecord || $existingRecord->getType() != RecordType::PURCHASE || $existingRecord->getStatus() != RecordStatus::PENDING ) {
            header("Status: 404");
            return new CommonResponse(404, 'Record No Existe');
        }

        $existingWallet = $existingRecord->getWallet();
      
        if ($existingRecord->getToken() != $token && $existingRecord->getStatus() != RecordStatus::APPROVED) {
            return new CommonResponse(403, 'Token De Verificacion Invalido');
        }

        $existingWallet->setCredit($existingWallet->getCredit() - $existingRecord->getAmount());
        $existingRecord->setStatus(RecordStatus::APPROVED);

        EntityManager::persist($existingRecord);
        EntityManager::persist($existingWallet);
        EntityManager::flush($existingRecord);

        return new CommonResponse(200, 'Hecho', $existingRecord->getFormattedRecord());
    }

    /**
     * Add credit to wallet if document and phone match with wallet's user
     * 
     * @param string $document
     * @param string $phone
     * @param string $amount
     * @return \App\Utils\CommonResponse
     */
    public function addCreditToWallet($document, $phone, $amount)
    {
        $existingUser = EntityManager::createQuery("SELECT u FROM \App\Models\User u Where u.document = $document AND u.phone = $phone")->getSingleResult();
        $existingWallet = null;

        if (!$existingUser) {
            header("Status: 404");
            return new CommonResponse(404, 'No Hay Usuario Asociado A Documento Dado');
        }

        $existingWallet = $existingUser->getWallet();
        $currentCredit = $existingWallet->getCredit();

        $record = new WalletRecord($amount, RecordStatus::APPROVED, RecordType::RECHARGE, '');

        $existingWallet->addRecord($record);
        $existingWallet->setCredit($currentCredit + $amount);

        EntityManager::persist($existingWallet);
        EntityManager::flush($existingWallet);

        return new CommonResponse(200, 'Hecho', $existingWallet->getFormattedWallet());
    }

    /**
     * Get Wallet credit succesful return wallet credit
     *
     * @param string $document
     * @param string $phone
     * 
     * @return \App\Utils\CommonResponse
     */
    public function getWalletCredit($document, $phone)
    {
        $existingUser = EntityManager::createQuery("SELECT u FROM \App\Models\User u Where u.document = $document AND u.phone = $phone")->getSingleResult();
        
        if (!$existingUser || !$existingUser->getWallet()) {
            header("Status: 404");
            return new CommonResponse(404, 'No Hay Usuario Asociado A Documento Dado');
        }

        return new CommonResponse(200, 'Hecho', (object)['credit' => $existingUser->getWallet()->getCredit()]);
    }
}
