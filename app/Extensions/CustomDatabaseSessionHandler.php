<?php

namespace App\Extensions;

use Illuminate\Session\DatabaseSessionHandler;

class CustomDatabaseSessionHandler extends DatabaseSessionHandler
{
    public function write($sessionId, $data)
    {
        dd(app('tenant'));
        
        $payload = [
            'id' => $sessionId,
            'user_id' => $this->userId(),
            'ip_address' => $this->ipAddress(),
            'user_agent' => $this->userAgent(),
            'payload' => $data,
            'last_activity' => time(),
            'tenant_id' => app('tenant')->id ?? null,
        ];

        $query = $this->getQuery()->where('id', $sessionId);

        if ($query->exists()) {
            $query->update($payload);
        } else {
            $this->getQuery()->insert($payload);
        }
    }
}
