<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class TenantDefaultSettings
{
    /**
     * Create all default settings for a new tenant
     */
    public static function createDefaults(int $tenantId): void
    {
        self::insertThemeSettings($tenantId);
        self::insertLocalizationSettings($tenantId);
        self::insertCompanySettings($tenantId);
        self::insertEmailSettings($tenantId);
        self::insertInvoiceSettings($tenantId);
        self::insertSalarySettings($tenantId);
    }

    /* ============================================
     * THEME SETTINGS DEFAULTS
     * ============================================ */
    private static function insertThemeSettings(int $tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        $defaults = [
            'name'            => $tenant->name ?? 'Default Theme',
            'logo_light'      => '',
            'logo_dark'       => '',
            'favicon'         => '',
            'theme'           => 'light',
            'layout'          => 'vertical',
            'color_scheme'    => 'light',
            'layout_width'    => 'fluid',
            'layout_position' => 'fixed',
            'topbar_color'    => 'light',
            'sidebar_size'    => 'default',
            'sidebar_view'    => 'default',
            'sidebar_img'     => '',
            'sidebar_color'   => 'light',
        ];

        self::insertGroup('theme', $defaults, $tenantId);
    }


    /* ============================================
     * LOCALIZATION SETTINGS DEFAULTS
     * ============================================ */
    private static function insertLocalizationSettings(int $tenantId)
    {
        $defaults = [
            'country'         => 'India',
            'date_format'     => 'd-m-Y',
            'timezone'        => 'Asia/Kolkata',
            'lang'            => 'en',
            'currency_symbol' => '₹',
            'currency_code'   => 'INR',
        ];

        self::insertGroup('localization', $defaults, $tenantId);
    }


    /* ============================================
     * COMPANY SETTINGS DEFAULTS
     * ============================================ */
    private static function insertCompanySettings(int $tenantId)
    {
        $defaults = [
            'name'              => 'My Company',
            'contact_person'    => 'Administrator',
            'address'           => 'Office Address',
            'country'           => 'India',
            'city'              => 'Delhi',
            'province'          => 'Delhi',
            'postal_code'       => '110001',
            'email'             => 'example@company.com',
            'phone'             => '',
            'probation_period'  => '90',
            'mobile'            => '',
            'fax'               => '',
            'website_url'       => '',
            'term_conditions'   => '',
            'about'             => '',
        ];

        self::insertGroup('company', $defaults, $tenantId);
    }


    /* ============================================
     * EMAIL SETTINGS DEFAULTS
     * ============================================ */
    private static function insertEmailSettings(int $tenantId)
    {
        $defaults = [
            'mailer'       => 'smtp',
            'from_address' => 'no-reply@example.com',
            'from_name'    => 'My App',
            'host'         => 'smtp.gmail.com',
            'port'         => '587',
            'enc'          => 'tls',
            'domain'       => null,
            'user'         => '',
            'password'     => '',
        ];

        self::insertGroup('email', $defaults, $tenantId);
    }


    /* ============================================
     * INVOICE SETTINGS DEFAULTS
     * ============================================ */
    private static function insertInvoiceSettings(int $tenantId)
    {
        $defaults = [
            'prefix' => 'INV-',
            'logo'   => '',
        ];

        self::insertGroup('invoice', $defaults, $tenantId);
    }


    /* ============================================
     * SALARY SETTINGS DEFAULTS
     * ============================================ */
    private static function insertSalarySettings(int $tenantId)
    {
        $defaults = [
            'enable_da_hra'            => true,
            'enable_provident_fund'    => true,
            'enable_esi_fund'          => false,

            'da_percent'               => '10',
            'hra_percent'              => '20',

            'emp_pf_percentage'        => '12',
            'company_pf_percentage'    => '12',

            'emp_esi_percentage'       => '0.75',
            'company_esi_percentage'   => '3.25',
        ];

        self::insertGroup('general_salary', $defaults, $tenantId);
    }


    /* ============================================
     * SHARED HELPER
     * ============================================ */
    private static function insertGroup(string $group, array $data, int $tenantId)
    {
        foreach ($data as $name => $value) {
            DB::table('settings')->updateOrInsert(
                [
                    'group'     => $group,
                    'name'      => $name,
                    'tenant_id' => $tenantId,
                ],
                [
                    'payload' => json_encode($value),
                ]
            );
        }
    }
}
