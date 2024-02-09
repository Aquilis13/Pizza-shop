<?php

namespace pizzashop\auth\api\domain\dto;

class TokenDTO extends \pizzashop\auth\api\domain\dto\DTO
{
    public ?string $access_token;
    public ?string $refresh_token;

    function __construct(?string $access_token, ?string $refresh_token)
    {
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
    }
}