<?php

namespace App\Traits;
use App\Models\EmployeeDetail;

trait GenerateEmployeeCode 
{
	protected function generateEmployeeCode($tenant)
    {
        $prefix = $this->generatePrefix($tenant->name);

        $lastEmployee = EmployeeDetail::where('tenant_id', $tenant->id)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastEmployee && $lastEmployee->emp_id) {
            $lastNumber = (int) str_replace($prefix . '-', '', $lastEmployee->emp_id);
        } else {
            $lastNumber = 0;
        }

        $nextNumber = $lastNumber + 1;

        return $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    protected function generatePrefix($name)
    {
        // Normalize name
        $name = trim($name);

        // Split by space into words
        $words = preg_split('/\s+/', $name);

        $prefix = '';

        // Step 1: Take first letter of each word
        foreach ($words as $word) {
            $firstChar = strtoupper(substr($word, 0, 1));
            if (ctype_alpha($firstChar)) {
                $prefix .= $firstChar;
            }
        }

        // Step 2: If prefix has 4+ letters → trim to 4
        if (strlen($prefix) >= 4) {
            return substr($prefix, 0, 4);
        }

        // Step 3: If less than 4, fill from full name letters
        $cleanName = strtoupper(preg_replace('/[^A-Za-z]/', '', $name));

        $i = 0;
        while (strlen($prefix) < 4 && $i < strlen($cleanName)) {
            $char = $cleanName[$i];
            if (ctype_alpha($char)) {
                $prefix .= $char;
            }
            $i++;
        }

        // Ensure exactly 4 characters
        return substr($prefix, 0, 4);
    }
}