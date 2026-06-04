@extends('layouts.app')

@push('page-styles')
<style>
    /* ========== MY HIERARCHY — 3-TIER PROMINENT VIEW ========== */
    .my-hierarchy-container {
        max-width: 920px;
        margin: 0 auto;
    }

    /* ── Header ── */
    .chain-header {
        text-align: center;
        margin-bottom: 32px;
    }
    .chain-header h3 { font-size: 22px; font-weight: 700; color: #1e293b; }
    .chain-header p { color: #94a3b8; font-size: 14px; margin-top: 4px; }

    /* ── Section Title ── */
    .tier-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        padding: 0 2px;
    }
    .tier-section-title .tier-dot {
        width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
    }
    .tier-section-title .tier-label {
        font-size: 11px; font-weight: 600; text-transform: uppercase;
        letter-spacing: .6px; color: #64748b;
    }
    .tier-section-title .tier-count {
        font-size: 10px; padding: 1px 8px; border-radius: 20px;
        background: #f1f5f9; color: #94a3b8; font-weight: 600;
    }

    /* ── CONNECTOR BETWEEN SECTIONS ── */
    .tier-connector {
        display: flex;
        justify-content: center;
        padding: 6px 0;
    }
    .tier-connector .connector-line {
        width: 2px;
        height: 24px;
        background: #cbd5e1;
        border-radius: 2px;
    }

    /* ── LEVEL UP SECTION ── */
    .level-up-section { margin-bottom: 8px; }

    .level-up-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 14px;
    }

    /* ── CENTER (SELF) — HERO CARD ── */
    .self-section {
        margin: 12px 0;
    }

    .self-hero-card {
        background: linear-gradient(135deg, var(--primary, #ff6b35), #ff9a5c);
        border-radius: 16px;
        padding: 28px;
        color: #fff;
        box-shadow: 0 8px 32px rgba(255, 107, 53, 0.25);
        position: relative;
        overflow: hidden;
    }

    .self-hero-card::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 160px;
        height: 160px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .self-hero-card::after {
        content: '';
        position: absolute;
        bottom: -30px;
        left: -30px;
        width: 120px;
        height: 120px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }

    .self-hero-inner {
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        z-index: 1;
    }

    .self-hero-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255,255,255,0.3);
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .self-hero-info { flex: 1; min-width: 0; }

    .self-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .8px;
        background: rgba(255,255,255,0.2);
        padding: 3px 10px;
        border-radius: 20px;
        margin-bottom: 8px;
    }

    .self-hero-name {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 4px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .self-hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        font-size: 13px;
        opacity: 0.9;
    }

    .self-hero-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .self-hero-roles {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 10px;
    }

    .self-hero-role-tag {
        font-size: 10px;
        padding: 3px 9px;
        border-radius: 20px;
        background: rgba(255,255,255,0.2);
        font-weight: 500;
    }

    /* ── LEVEL DOWN SECTION ── */
    .level-down-section { margin-top: 8px; }

    .level-down-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 14px;
    }

    /* ── PERSON CARD (for up & down tiers) ── */
    .person-card {
        background: #fff;
        border-radius: 12px;
        padding: 14px 16px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        border: 1.5px solid #f1f5f9;
        transition: all .2s;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .person-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        transform: translateY(-1px);
        border-color: #e2e8f0;
    }

    .person-card.rm-card { border-left: 3px solid #4f6df5; }
    .person-card.sub-rm-card { border-left: 3px solid #2ecc71; }

    .person-card-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e8ecf1;
        flex-shrink: 0;
    }

    .person-card-info { flex: 1; min-width: 0; }
    .person-card-name { font-size: 14px; font-weight: 600; color: #1e293b; }

    .person-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        align-items: center;
        margin-top: 3px;
    }

    .person-card-desg { font-size: 11px; color: #64748b; }
    .person-card-dept {
        font-size: 9px;
        padding: 1px 6px;
        background: #f0f4ff;
        color: #4f6df5;
        border-radius: 20px;
    }

    .person-card-email {
        font-size: 10px;
        color: #94a3b8;
        margin-top: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .person-card-roles {
        display: flex;
        flex-wrap: wrap;
        gap: 3px;
        margin-top: 4px;
    }

    .person-card-role-tag {
        font-size: 9px;
        padding: 1px 6px;
        border-radius: 20px;
        background: #f1f5f9;
        color: #475569;
    }

    /* Rel badge on card */
    .person-card-rel {
        position: absolute;
        top: -8px;
        left: 12px;
        font-size: 9px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .6px;
        padding: 2px 8px;
        border-radius: 20px;
        color: #fff;
    }

    /* ── EMPTY STATE ── */
    .tier-empty {
        text-align: center;
        padding: 24px 16px;
        color: #94a3b8;
        background: #f8fafc;
        border-radius: 10px;
        border: 1.5px dashed #e2e8f0;
    }

    .tier-empty i { font-size: 24px; margin-bottom: 6px; display: block; }
    .tier-empty p { font-size: 13px; margin: 0; }

    /* ── SUMMARY BAR ── */
    .hierarchy-summary-bar {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 28px;
    }

    .summary-bar-item {
        background: #fff;
        border-radius: 10px;
        padding: 14px 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .summary-bar-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: #fff;
        flex-shrink: 0;
    }

    .summary-bar-icon.up { background: linear-gradient(135deg, #4f6df5, #7c93f7); }
    .summary-bar-icon.self { background: linear-gradient(135deg, #ff6b35, #ff9a5c); }
    .summary-bar-icon.down { background: linear-gradient(135deg, #2ecc71, #55e89a); }

    .summary-bar-value { font-size: 20px; font-weight: 700; color: #1e293b; line-height: 1; }
    .summary-bar-label { font-size: 11px; color: #94a3b8; margin-top: 2px; }

    /* ── Responsive ── */
    @media (max-width: 640px) {
        .self-hero-inner { flex-direction: column; text-align: center; }
        .self-hero-meta { justify-content: center; }
        .self-hero-roles { justify-content: center; }
        .self-hero-card { padding: 20px; }
        .level-up-grid, .level-down-grid { grid-template-columns: 1fr; }
        .hierarchy-summary-bar { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('page-content')
<div class="content container-fluid">

    <x-breadcrumb class="col">
        <x-slot name="title">{{ __('My Reporting Hierarchy') }}</x-slot>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item active">{{ __('My Hierarchy') }}</li>
        </ul>
        <x-slot name="right">
            <div class="col-auto ms-auto">
                @activeCan('view-hierarchy')
                    <a href="{{ route('hierarchy.org-tree') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fa-solid fa-sitemap me-1"></i> Org Tree
                    </a>
                @endactiveCan
            </div>
        </x-slot>
    </x-breadcrumb>

    <div class="my-hierarchy-container">
        <div class="chain-header">
            <h3>My Organization View</h3>
            <p>Your position in the reporting structure</p>
        </div>

        @php
            // Separate chain into "above me" (skip self) and identify self
            $aboveMe = array_filter($chain, fn($p) => $p['relationship'] !== 'self');
            $selfNode = collect($chain)->firstWhere('relationship', 'self');
            $reportingManager = collect($chain)->firstWhere('relationship', 'reporting_manager');
            $subReportingManager = collect($chain)->firstWhere('relationship', 'sub_reporting_manager');
            $aboveCount = count($aboveMe);
            $downCount = count($directReports);
        @endphp

        <!-- ══════════════ TIER 1: LEVEL UP ══════════════ -->
        <div class="level-up-section">
            <div class="tier-section-title">
                <span class="tier-dot" style="background:#4f6df5"></span>
                <span class="tier-label">1 Level Up</span>
                @if($aboveCount > 0)
                    <span class="tier-count">{{ $aboveCount }}</span>
                @endif
            </div>

            @if($aboveCount > 0)
                <div class="level-up-grid">
                    @foreach($aboveMe as $person)
                        @php
                            $cardClass = match($person['relationship']) {
                                'reporting_manager' => 'rm-card',
                                'sub_reporting_manager' => 'sub-rm-card',
                                'hr' => 'rm-card',
                                'admin' => 'rm-card',
                                default => 'rm-card',
                            };
                            $relLabel = match($person['relationship']) {
                                'reporting_manager' => 'Reporting Manager',
                                'sub_reporting_manager' => 'Sub RM',
                                'hr' => 'HR',
                                'admin' => 'Admin',
                                default => ucfirst(str_replace('_', ' ', $person['relationship'])),
                            };
                            $relColor = match($person['relationship']) {
                                'reporting_manager' => '#4f6df5',
                                'sub_reporting_manager' => '#2ecc71',
                                'hr' => '#e74c3c',
                                'admin' => '#9b59b6',
                                default => '#64748b',
                            };
                        @endphp
                        <div class="person-card {{ $cardClass }}" style="position:relative">
                            <span class="person-card-rel" style="background:{{ $relColor }}">{{ $relLabel }}</span>
                            <img class="person-card-avatar" src="{{ $person['avatar'] }}"
                                 onerror="this.src='{{ asset('images/user.jpg') }}'"
                                 alt="{{ $person['name'] }}">
                            <div class="person-card-info">
                                <div class="person-card-name">{{ $person['name'] }}</div>
                                <div class="person-card-meta">
                                    <span class="person-card-desg">{{ $person['designation'] ?? 'N/A' }}</span>
                                    @if($person['department'])
                                        <span class="person-card-dept">{{ $person['department'] }}</span>
                                    @endif
                                </div>
                                @if($person['email'])
                                    <div class="person-card-email">
                                        <i class="fa-solid fa-envelope" style="font-size:9px;margin-right:3px"></i>
                                        {{ $person['email'] }}
                                    </div>
                                @endif
                                @if(!empty($person['role_names']))
                                    <div class="person-card-roles">
                                        @foreach($person['role_names'] as $r)
                                            <span class="person-card-role-tag">{{ $r }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="tier-empty">
                    <i class="fa-solid fa-arrow-up"></i>
                    <p>No reporting manager assigned yet</p>
                </div>
            @endif
        </div>

        <!-- Connector -->
        @if($aboveCount > 0 || $selfNode)
        <div class="tier-connector"><div class="connector-line"></div></div>
        @endif

        <!-- ══════════════ TIER 2: YOU (CENTER) ══════════════ -->
        @if($selfNode)
        <div class="self-section">
            <div class="tier-section-title">
                <span class="tier-dot" style="background:var(--primary, #ff6b35)"></span>
                <span class="tier-label">You</span>
            </div>
            <div class="self-hero-card">
                <div class="self-hero-inner">
                    <img class="self-hero-avatar" src="{{ $selfNode['avatar'] }}"
                         onerror="this.src='{{ asset('images/user.jpg') }}'"
                         alt="{{ $selfNode['name'] }}">
                    <div class="self-hero-info">
                        <!-- <div class="self-hero-badge">
                            <i class="fa-solid fa-user"></i> This Is You
                        </div> -->
                        <div class="self-hero-name">{{ $selfNode['name'] }}</div>
                        <div class="self-hero-meta">
                            <span><i class="fa-solid fa-briefcase"></i> {{ $selfNode['designation'] ?? 'N/A' }}</span>
                            @if($selfNode['department'])
                                <span><i class="fa-solid fa-building"></i> {{ $selfNode['department'] }}</span>
                            @endif
                            @if($selfNode['email'])
                                <span><i class="fa-solid fa-envelope"></i> {{ $selfNode['email'] }}</span>
                            @endif
                        </div>
                        @if(!empty($selfNode['role_names']))
                            <div class="self-hero-roles">
                                @foreach($selfNode['role_names'] as $r)
                                    <span class="self-hero-role-tag">{{ $r }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Connector -->
        @if($downCount > 0)
        <div class="tier-connector"><div class="connector-line"></div></div>
        @endif

        <!-- ══════════════ TIER 3: LEVEL DOWN ══════════════ -->
        <div class="level-down-section">
            <div class="tier-section-title">
                <span class="tier-dot" style="background:#2ecc71"></span>
                <span class="tier-label">1 Level Down</span>
                @if($downCount > 0)
                    <span class="tier-count">{{ $downCount }}</span>
                @endif
            </div>

            @if($downCount > 0)
                <div class="level-down-grid">
                    @foreach($directReports as $person)
                        @php
                            $relLabel = $person['rel_type'] === 'reporting_manager'
                                ? 'Reports to You'
                                : 'Sub-Reports to You';
                            $cardClass = $person['rel_type'] === 'reporting_manager'
                                ? 'rm-card'
                                : 'sub-rm-card';
                            $relColor = $person['rel_type'] === 'reporting_manager'
                                ? '#4f6df5'
                                : '#2ecc71';
                        @endphp
                        <div class="person-card {{ $cardClass }}" style="position:relative">
                            <span class="person-card-rel" style="background:{{ $relColor }}">{{ $relLabel }}</span>
                            <img class="person-card-avatar" src="{{ $person['avatar'] }}"
                                 onerror="this.src='{{ asset('images/user.jpg') }}'"
                                 alt="{{ $person['name'] }}">
                            <div class="person-card-info">
                                <div class="person-card-name">{{ $person['name'] }}</div>
                                <div class="person-card-meta">
                                    <span class="person-card-desg">{{ $person['designation'] ?? 'N/A' }}</span>
                                    @if($person['department'])
                                        <span class="person-card-dept">{{ $person['department'] }}</span>
                                    @endif
                                </div>
                                @if($person['email'])
                                    <div class="person-card-email">
                                        <i class="fa-solid fa-envelope" style="font-size:9px;margin-right:3px"></i>
                                        {{ $person['email'] }}
                                    </div>
                                @endif
                                @if(!empty($person['role_names']))
                                    <div class="person-card-roles">
                                        @foreach($person['role_names'] as $r)
                                            <span class="person-card-role-tag">{{ $r }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="tier-empty">
                    <i class="fa-solid fa-users"></i>
                    <p>No one reports to you yet</p>
                </div>
            @endif
        </div>

        <!-- ══════════════ SUMMARY BAR ══════════════ -->
        <div class="hierarchy-summary-bar">
            <div class="summary-bar-item">
                <div class="summary-bar-icon up"><i class="fa-solid fa-arrow-up"></i></div>
                <div>
                    <div class="summary-bar-value">{{ $aboveCount }}</div>
                    <div class="summary-bar-label">Above You</div>
                </div>
            </div>
            <div class="summary-bar-item">
                <div class="summary-bar-icon self"><i class="fa-solid fa-user"></i></div>
                <div>
                    <div class="summary-bar-value">1</div>
                    <div class="summary-bar-label">You</div>
                </div>
            </div>
            <div class="summary-bar-item">
                <div class="summary-bar-icon down"><i class="fa-solid fa-arrow-down"></i></div>
                <div>
                    <div class="summary-bar-value">{{ $downCount }}</div>
                    <div class="summary-bar-label">Below You</div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
