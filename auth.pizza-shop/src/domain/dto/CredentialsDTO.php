<?php

namespace pizzashop\auth\api\domain\dto;

class CredentialsDTO extends \pizzashop\auth\api\domain\dto\DTO
{
    public ?string $email;
    public ?string $password;
    public ?string $username;
    public ?string $refresh_token;

    function __construct(?string $email, ?string $password, ?string $username, ?string $refresh_token)
    {
        $this->email = $email;
        $this->password = $password;
        $this->username = $username;
        $this->refresh_token = $refresh_token;
    }
}
