<?php
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

class UserDataPersister implements DataPersisterInterface {

    public function supports($data): bool
    {
        return $data instanceof User;
    }
}