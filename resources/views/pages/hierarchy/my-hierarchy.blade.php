@extends('layouts.app')

@push('page-styles')
<style>
    /* ================================================================
       MY HIERARCHY — ENTERPRISE REDESIGN (v2)
       Clean • Fast • Scalable (1000+ reports)
       ================================================================ */

    /* ── PAGE HEADER ── */
    .mh-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .mh-header-left h3 {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .mh-header-left p {
        font-size: 13px;
        color: #94a3b8;
        margin: 2px 0 0;
    }

    /* ── SECTION BLOCKS ── */
    .mh-section {
        margin-bottom: 28px;
    }
    .mh-section-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #475569;
    }
    .mh-section-label .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .mh-section-label .badge {
        font-size: 10px;
        padding: 1px 8px;
        border-radius: 20px;
        background: #f1f5f9;
        color: #94a3b8;
        font-weight: 600;
    }

    /* ══════════════════════════════════════════
       SELF CARD — Prominent Hero
       ══════════════════════════════════════════ */
    .mh-self-card {
        background: linear-gradient(135deg, var(--primary, #ff6b35), #ff9a5c);
        border-radius: 14px;
        padding: 24px 28px;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 20px;
        min-height: 120px;
    }
    .mh-self-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,0.3);
        flex-shrink: 0;
        display: block;
    }
    .mh-self-body {
        flex: 1;
        min-width: 0;
    }
    .mh-self-tag {
        display: inline-block;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        background: rgba(255,255,255,0.2);
        padding: 2px 10px;
        border-radius: 20px;
        margin-bottom: 6px;
    }
    .mh-self-name {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .mh-self-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 6px 14px;
        font-size: 13px;
        opacity: .9;
    }
    .mh-self-meta span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .mh-self-meta i { font-size: 11px; }
    .mh-self-roles {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-top: 8px;
    }
    .mh-self-role {
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 20px;
        background: rgba(255,255,255,0.18);
    }

    /* ══════════════════════════════════════════
       PERSON ROW — Compact horizontal card
       ══════════════════════════════════════════ */
    .mh-person {
        background: #fff;
        border-radius: 10px;
        padding: 12px 14px;
        border: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 64px;
    }
    .mh-person:hover {
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border-color: #e2e8f0;
    }
    /* Left accent border */
    .mh-person.direct { border-left: 3px solid #4f6df5; }
    .mh-person.sub    { border-left: 3px dashed #2ecc71; }

    .mh-person-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e8ecf1;
        flex-shrink: 0;
        display: block;
    }
    .mh-person-body {
        flex: 1;
        min-width: 0;
    }
    .mh-person-name {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .mh-person-sub {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 2px;
        font-size: 11px;
        color: #64748b;
    }
    .mh-person-dept {
        font-size: 9px;
        padding: 1px 6px;
        border-radius: 20px;
        background: #f0f4ff;
        color: #4f6df5;
        font-weight: 500;
    }
    .mh-person-dept.green {
        background: #eafaf1;
        color: #2ecc71;
    }
    .mh-person-email {
        font-size: 10px;
        color: #94a3b8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 1px;
    }
    .mh-person-rel {
        font-size: 9px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 20px;
        color: #fff;
        flex-shrink: 0;
        white-space: nowrap;
    }
    .mh-person-rel.rm   { background: #4f6df5; }
    .mh-person-rel.srm  { background: #2ecc71; }
    .mh-person-rel.hr   { background: #e74c3c; }
    .mh-person-rel.admin { background: #9b59b6; }

    /* ── LEVEL UP GRID ── */
    .mh-up-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 10px;
    }

    /* ══════════════════════════════════════════
       LEVEL DOWN — Toolbar + Cards/Table + Pagination
       ══════════════════════════════════════════ */
    .mh-toolbar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    .mh-search {
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 320px;
    }
    .mh-search input {
        width: 100%;
        padding: 8px 12px 8px 34px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        color: #334155;
        background: #fff;
    }
    .mh-search input:focus {
        outline: none;
        border-color: #4f6df5;
        box-shadow: 0 0 0 3px rgba(79,109,245,0.1);
    }
    .mh-search input::placeholder {
        color: #cbd5e1;
    }
    .mh-search i {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12px;
        color: #cbd5e1;
    }

    .mh-filter-tabs {
        display: flex;
        gap: 4px;
    }
    .mh-filter-tabs button {
        padding: 6px 14px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #fff;
        font-size: 11px;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
    }
    .mh-filter-tabs button.active {
        background: #4f6df5;
        color: #fff;
        border-color: #4f6df5;
    }
    .mh-filter-tabs button:hover:not(.active) {
        background: #f8fafc;
    }

    /* ── VIEW TOGGLE ── */
    .mh-view-toggle {
        display: inline-flex;
        align-items: center;
        gap: 2px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        flex-shrink: 0;
    }
    .mh-view-toggle button {
        padding: 6px 10px;
        border: none;
        background: transparent;
        color: #94a3b8;
        cursor: pointer;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .mh-view-toggle button:not(:last-child) {
        border-right: 1px solid #e2e8f0;
    }
    .mh-view-toggle button.active {
        background: #4f6df5;
        color: #fff;
    }
    .mh-view-toggle button:hover:not(.active) {
        background: #f1f5f9;
        color: #475569;
    }

    /* ── TABLE ── */
    .mh-table-wrap {
        border: 1px solid #e8ecf1;
        border-radius: 10px;
        overflow: hidden;
    }
    .mh-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .mh-table thead th {
        background: #f8fafc;
        padding: 10px 14px;
        text-align: left;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #475569;
        border-bottom: 1.5px solid #e2e8f0;
    }
    .mh-table tbody tr {
    }
    .mh-table tbody tr:hover {
        background: #f8fafc;
    }
    .mh-table tbody td {
        padding: 10px 14px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }
    .mh-table .avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid #e8ecf1;
        display: block;
    }
    .mh-table .name {
        font-weight: 600;
        color: #1e293b;
    }
    .mh-table .dept-badge {
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 20px;
        display: inline-block;
    }
    .mh-table .dept-badge.blue {
        background: #f0f4ff;
        color: #4f6df5;
    }
    .mh-table .dept-badge.green {
        background: #eafaf1;
        color: #2ecc71;
    }
    .mh-table .rel-badge {
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 600;
        color: #fff;
        display: inline-block;
    }
    .mh-table .rel-badge.direct { background: #4f6df5; }
    .mh-table .rel-badge.sub    { background: #2ecc71; }

    /* ── PAGINATION ── */
    .mh-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 8px;
    }
    /* Standalone card pagination (outside table wrapper) */
    #mhCardPagination {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-top: 8px;
    }
    .mh-pagination .info {
        font-size: 12px;
        color: #64748b;
    }
    .mh-pagination .pages {
        display: flex;
        gap: 4px;
    }
    .mh-pagination .pages button {
        padding: 5px 10px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #fff;
        font-size: 12px;
        color: #475569;
        cursor: pointer;
    }
    .mh-pagination .pages button:hover:not(:disabled):not(.active) {
        background: #f1f5f9;
    }
    .mh-pagination .pages button.active {
        background: #4f6df5;
        color: #fff;
        border-color: #4f6df5;
    }
    .mh-pagination .pages button:disabled {
        opacity: .4;
        cursor: not-allowed;
    }

    /* ── CARD GRID ── */
    .mh-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 10px;
    }

    /* ── EMPTY STATE ── */
    .mh-empty {
        text-align: center;
        padding: 28px 16px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1.5px dashed #e2e8f0;
    }
    .mh-empty i {
        font-size: 22px;
        color: #cbd5e1;
        margin-bottom: 6px;
        display: block;
    }
    .mh-empty p {
        font-size: 13px;
        color: #94a3b8;
        margin: 0;
    }

    /* ── NO RESULTS ── */
    .mh-no-results {
        text-align: center;
        padding: 28px;
        color: #94a3b8;
        font-size: 13px;
    }

    /* ── LOADER ── */
    .mh-loader {
        text-align: center;
        padding: 30px;
        color: #94a3b8;
        font-size: 13px;
    }
    .mh-loader i { font-size: 20px; }
    .mh-loader p { margin-top: 8px; }

    /* ══════════════════════════════════════════
       PROFILE + STATS — ONE ROW (left + right)
       ══════════════════════════════════════════ */
    .mh-hero-row {
        display: flex;
        align-items: stretch;
        gap: 16px;
        margin-bottom: 28px;
    }
    .mh-hero-row .mh-self-card {
        flex: 1;
        min-width: 0;
    }
    .mh-hero-stats {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex-shrink: 0;
        width: 200px;
    }
    .mh-stat-card {
        background: #fff;
        border-radius: 10px;
        padding: 12px 14px;
        border: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }
    .mh-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .mh-stat-icon.up  { background: #eef2ff; color: #4f6df5; }
    .mh-stat-icon.me  { background: #fff4ed; color: #ff6b35; }
    .mh-stat-icon.dn  { background: #eafaf1; color: #2ecc71; }
    .mh-stat-num {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.1;
    }
    .mh-stat-label {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 1px;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
        .mh-hero-row { flex-direction: column; }
        .mh-hero-stats { width: auto; flex-direction: row; }
        .mh-up-grid { grid-template-columns: 1fr; }

        .mh-self-card { flex-direction: column; text-align: center; padding: 20px; }
        .mh-self-meta { justify-content: center; }
        .mh-self-roles { justify-content: center; }
        .mh-cards-grid { grid-template-columns: 1fr; }
        .mh-table-wrap { overflow-x: auto; }
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

    @php
        $aboveMe  = array_filter($chain, fn($p) => $p['relationship'] !== 'self');
        $selfNode = collect($chain)->firstWhere('relationship', 'self');
        $aboveCount = count($aboveMe);
        $aboveMe    = array_values($aboveMe);
        $defaultAvatar = asset('images/user.jpg');
        $ajaxUrl   = route('hierarchy.my-reports-paginated');
    @endphp

    <!-- ══════════════════════════════════════
         PROFILE (LEFT) + STATS (RIGHT) — ONE ROW
         ══════════════════════════════════════ -->
    <div class="mh-hero-row">
        @if($selfNode)
            <div class="mh-self-card">
                <img class="mh-self-avatar"
                     src="{{ $selfNode['avatar'] ?? $defaultAvatar }}"
                     onerror="this.src='{{ $defaultAvatar }}'"
                     alt="{{ $selfNode['name'] }}">
                <div class="mh-self-body">
                    <div class="mh-self-tag"><i class="fa-solid fa-user" style="margin-right:3px;font-size:8px"></i>Profile</div>
                    <div class="mh-self-name">{{ $selfNode['name'] }}</div>
                    <div class="mh-self-meta">
                        @if($selfNode['designation'])
                            <span><i class="fa-solid fa-briefcase"></i> {{ $selfNode['designation'] }}</span>
                        @endif
                        @if($selfNode['department'])
                            <span><i class="fa-solid fa-building"></i> {{ $selfNode['department'] }}</span>
                        @endif
                        @if($selfNode['email'])
                            <span><i class="fa-solid fa-envelope"></i> {{ $selfNode['email'] }}</span>
                        @endif
                    </div>
                    @if(!empty($selfNode['role_names']))
                        <div class="mh-self-roles">
                            @foreach($selfNode['role_names'] as $r)
                                <span class="mh-self-role">{{ $r }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif
        <div class="mh-hero-stats">
            <div class="mh-stat-card">
                <div class="mh-stat-icon up"><i class="fa-solid fa-arrow-up"></i></div>
                <div>
                    <div class="mh-stat-num">{{ $aboveCount }}</div>
                    <div class="mh-stat-label">Above You</div>
                </div>
            </div>
            <div class="mh-stat-card">
                <div class="mh-stat-icon me"><i class="fa-solid fa-user"></i></div>
                <div>
                    <div class="mh-stat-num">1</div>
                    <div class="mh-stat-label">You</div>
                </div>
            </div>
            <div class="mh-stat-card">
                <div class="mh-stat-icon dn"><i class="fa-solid fa-arrow-down"></i></div>
                <div>
                    <div class="mh-stat-num">{{ $downCount }}</div>
                    <div class="mh-stat-label">Reporting to You</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════
         LEVEL UP — Managers & HR & Admin
         ══════════════════════════════════════ -->
    <div class="mh-section">
        <div class="mh-section-label">
            <span class="dot" style="background:#4f6df5"></span>
            Reporting Chain
            @if($aboveCount > 0)
                <span class="badge">{{ $aboveCount }}</span>
            @endif
        </div>

        @if($aboveCount > 0)
            <div class="mh-up-grid">
                @foreach($aboveMe as $person)
                    @php
                        $relClass = match($person['relationship']) {
                            'reporting_manager'      => 'rm',
                            'sub_reporting_manager'  => 'srm',
                            'hr'                     => 'hr',
                            'admin'                  => 'admin',
                            default                  => 'rm',
                        };
                        $relText = match($person['relationship']) {
                            'reporting_manager'      => 'RM',
                            'sub_reporting_manager'  => 'Sub RM',
                            'hr'                     => 'HR',
                            'admin'                  => 'Admin',
                            default                  => '',
                        };
                        $cardBorder = ($person['relationship'] === 'sub_reporting_manager') ? 'sub' : 'direct';
                    @endphp
                    <div class="mh-person {{ $cardBorder }}">
                        <img class="mh-person-avatar"
                             src="{{ $person['avatar'] ?? $defaultAvatar }}"
                             onerror="this.src='{{ $defaultAvatar }}'"
                             alt="{{ $person['name'] }}">
                        <div class="mh-person-body">
                            <div class="mh-person-name">{{ $person['name'] }}</div>
                            <div class="mh-person-sub">
                                @if($person['designation'])
                                    <span>{{ $person['designation'] }}</span>
                                @endif
                                @if($person['department'])
                                    <span class="mh-person-dept">{{ $person['department'] }}</span>
                                @endif
                            </div>
                            @if($person['email'])
                                <div class="mh-person-email">{{ $person['email'] }}</div>
                            @endif
                        </div>
                        @if($relText)
                            <span class="mh-person-rel {{ $relClass }}">{{ $relText }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="mh-empty">
                <i class="fa-solid fa-arrow-up"></i>
                <p>No reporting manager assigned yet</p>
            </div>
        @endif
    </div>

    <!-- ══════════════════════════════════════
         LEVEL DOWN — Reports (AJAX Paginated)
         ══════════════════════════════════════ -->
    <div class="mh-section">
        <div class="mh-section-label">
            <span class="dot" style="background:#2ecc71"></span>
            Direct Reports & Sub-Reports
            @if($downCount > 0)
                <span class="badge" id="mhDownBadge">{{ $downCount }}</span>
            @endif
        </div>

        @if($downCount > 0)

            {{-- TOOLBAR: Search + Filter + View Toggle --}}
            <div class="mh-toolbar">
                <div class="mh-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text"
                           id="mhSearchInput"
                           placeholder="Search by name, designation, or department..."
                           autocomplete="off">
                </div>
                <div class="mh-filter-tabs" id="mhFilterTabs">
                    <button class="active" data-filter="all" onclick="mhFilter('all', this)">
                        All ({{ $downCount }})
                    </button>
                    @if($directCount > 0)
                        <button data-filter="direct" onclick="mhFilter('direct', this)">
                            Direct ({{ $directCount }})
                        </button>
                    @endif
                    @if($subCount > 0)
                        <button data-filter="sub" onclick="mhFilter('sub', this)">
                            Sub ({{ $subCount }})
                        </button>
                    @endif
                </div>
                {{-- VIEW TOGGLE: Cards / Table --}}
                <div class="mh-view-toggle" id="mhViewToggle">
                    <button class="active" data-view="cards" onclick="mhSwitchView('cards', this)" title="Card View">
                        <i class="fa-solid fa-grip"></i>
                    </button>
                    <button data-view="table" onclick="mhSwitchView('table', this)" title="Table View">
                        <i class="fa-solid fa-list"></i>
                    </button>
                </div>
            </div>

            {{-- LOADING SPINNER --}}
            <div id="mhLoader" class="mh-loader">
                <i class="fa-solid fa-spinner fa-spin"></i>
                <p>Loading reports...</p>
            </div>

            {{-- CARD GRID (populated by AJAX) --}}
            <div class="mh-cards-grid" id="mhCardsGrid" style="display:none"></div>

            {{-- TABLE VIEW (populated by AJAX, hidden by default) --}}
            <div class="mh-table-wrap" id="mhTableWrap" style="display:none">
                <table class="mh-table">
                    <thead>
                        <tr>
                            <th style="width:48px">Photo</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Email</th>
                            <th style="width:80px;text-align:center">Type</th>
                        </tr>
                    </thead>
                    <tbody id="mhTableBody"></tbody>
                </table>
                <div class="mh-pagination" id="mhTablePagination"></div>
            </div>

            {{-- CARD PAGINATION (outside table) --}}
            <div class="mh-pagination" id="mhCardPagination" style="display:none"></div>

            {{-- NO RESULTS (shown by JS when search has 0 matches) --}}
            <div id="mhNoResults" class="mh-no-results" style="display:none">
                <i class="fa-solid fa-magnifying-glass" style="margin-right:6px"></i> No matching results found
            </div>

        @else
            <div class="mh-empty">
                <i class="fa-solid fa-users"></i>
                <p>No one reports to you yet</p>
            </div>
        @endif
    </div>

</div>
@endsection

@push('page-scripts')
<script>
(function() {
    'use strict';

    /* ════════════════════════════════════════════
       SERVER-SIDE PAGINATION VIA AJAX
       - Loads 10 items per page from server
       - Search, filter, page nav — all via AJAX
       - View toggle re-renders cached data (no extra call)
       - Counts: PHP COUNT() on page load for tabs
       - Counts: AJAX response.total for search results
       ════════════════════════════════════════════ */

    var PAGE_SIZE     = 10;
    var currentPage  = 1;
    var currentFilter = 'all';
    var currentView   = 'cards';
    var searchTerm    = '';
    var isLoading     = false;
    var lastData      = [];

    var AJAX_URL       = '{{ $ajaxUrl }}';
    var DEFAULT_AVATAR = '{{ $defaultAvatar }}';

    var cardsGrid   = document.getElementById('mhCardsGrid');
    var tableWrap   = document.getElementById('mhTableWrap');
    var tableBody   = document.getElementById('mhTableBody');
    var cardPagEl   = document.getElementById('mhCardPagination');
    var tablePagEl  = document.getElementById('mhTablePagination');
    var loaderEl    = document.getElementById('mhLoader');
    var noResultsEl = document.getElementById('mhNoResults');
    var badgeEl     = document.getElementById('mhDownBadge');
    var searchInput = document.getElementById('mhSearchInput');

    function esc(str) {
        if (!str) return '';
        var d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    function cardHtml(p) {
        var isDir = (p.rel_type === 'reporting_manager');
        return '<div class="mh-person ' + (isDir ? 'direct' : 'sub') + '">' +
            '<img class="mh-person-avatar" src="' + esc(p.avatar) + '" onerror="this.src=\'' + DEFAULT_AVATAR + '\'" alt="' + esc(p.name) + '">' +
            '<div class="mh-person-body">' +
                '<div class="mh-person-name">' + esc(p.name) + '</div>' +
                '<div class="mh-person-sub">' +
                    (p.designation ? '<span>' + esc(p.designation) + '</span>' : '') +
                    (p.department ? '<span class="mh-person-dept' + (isDir ? '' : ' green') + '">' + esc(p.department) + '</span>' : '') +
                '</div>' +
                (p.email ? '<div class="mh-person-email">' + esc(p.email) + '</div>' : '') +
            '</div>' +
            '<span class="mh-person-rel ' + (isDir ? 'rm' : 'srm') + '">' + (isDir ? 'Direct' : 'Sub') + '</span>' +
        '</div>';
    }

    function rowHtml(p) {
        var isDir = (p.rel_type === 'reporting_manager');
        return '<tr>' +
            '<td><img class="avatar" src="' + esc(p.avatar) + '" onerror="this.src=\'' + DEFAULT_AVATAR + '\'" alt="' + esc(p.name) + '"></td>' +
            '<td class="name">' + esc(p.name) + '</td>' +
            '<td>' + esc(p.designation || '-') + '</td>' +
            '<td><span class="dept-badge ' + (isDir ? 'blue' : 'green') + '">' + esc(p.department || '-') + '</span></td>' +
            '<td style="font-size:12px;color:#64748b">' + esc(p.email || '-') + '</td>' +
            '<td style="text-align:center"><span class="rel-badge ' + (isDir ? 'direct' : 'sub') + '">' + (isDir ? 'Direct' : 'Sub') + '</span></td>' +
        '</tr>';
    }

    function renderData(data) {
        lastData = data;
        cardsGrid.innerHTML = data.map(cardHtml).join('');
        tableBody.innerHTML = data.map(rowHtml).join('');
        applyViewVisibility();
    }

    function applyViewVisibility() {
        cardsGrid.style.display  = (currentView === 'cards') ? '' : 'none';
        tableWrap.style.display  = (currentView === 'table') ? '' : 'none';
        cardPagEl.style.display  = (currentView === 'cards') ? '' : 'none';
        tablePagEl.style.display = (currentView === 'table') ? '' : 'none';
    }

    function hideAllContent() {
        cardsGrid.style.display  = 'none';
        tableWrap.style.display  = 'none';
        cardPagEl.style.display  = 'none';
        tablePagEl.style.display = 'none';
        noResultsEl.style.display = 'none';
    }

    function fetchData() {
        if (isLoading) return;
        isLoading = true;
        hideAllContent();
        if (loaderEl) loaderEl.style.display = '';

        var params = 'page=' + currentPage + '&per_page=' + PAGE_SIZE + '&type=' + currentFilter;
        if (searchTerm) params += '&search=' + encodeURIComponent(searchTerm);

        fetch(AJAX_URL + '?' + params, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(r) { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(function(resp) {
            isLoading = false;
            if (loaderEl) loaderEl.style.display = 'none';
            if (resp.data) {
                var total = resp.total || 0;
                var lastPg = resp.last_page || 1;
                if (badgeEl) badgeEl.textContent = total;
                if (total === 0) { hideAllContent(); if (noResultsEl) noResultsEl.style.display = ''; return; }
                renderData(resp.data);
                renderPagination((currentView === 'cards') ? cardPagEl : tablePagEl, lastPg, total);
            }
        })
        .catch(function(err) {
            isLoading = false;
            if (loaderEl) loaderEl.style.display = 'none';
            console.error('Failed to load reports:', err);
        });
    }

    function renderPagination(el, totalPages, totalVisible) {
        if (!el) return;
        var start = (currentPage - 1) * PAGE_SIZE + 1;
        var end   = Math.min(currentPage * PAGE_SIZE, totalVisible);
        var html = '<span class="info">Showing ' + start + '\u2013' + end + ' of ' + totalVisible + '</span>';
        html += '<div class="pages">';
        html += '<button ' + (currentPage <= 1 ? 'disabled' : '') + ' onclick="mhGoPage(' + (currentPage - 1) + ')"><i class="fa-solid fa-chevron-left" style="font-size:10px"></i></button>';
        var sp = Math.max(1, currentPage - 2), ep = Math.min(totalPages, sp + 4);
        if (ep - sp < 4) sp = Math.max(1, ep - 4);
        for (var i = sp; i <= ep; i++) html += '<button class="' + (i === currentPage ? 'active' : '') + '" onclick="mhGoPage(' + i + ')">' + i + '</button>';
        html += '<button ' + (currentPage >= totalPages ? 'disabled' : '') + ' onclick="mhGoPage(' + (currentPage + 1) + ')"><i class="fa-solid fa-chevron-right" style="font-size:10px"></i></button>';
        html += '</div>';
        el.innerHTML = html;
    }

    var searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() { searchTerm = searchInput.value; currentPage = 1; fetchData(); }, 300);
        });
    }

    fetchData();

    window.mhFilter = function(filter, btn) {
        currentFilter = filter; currentPage = 1; searchTerm = '';
        if (searchInput) searchInput.value = '';
        document.querySelectorAll('#mhFilterTabs button').forEach(function(b) { b.classList.remove('active'); });
        btn.classList.add('active');
        fetchData();
    };
    window.mhSwitchView = function(view, btn) {
        currentView = view;
        document.querySelectorAll('#mhViewToggle button').forEach(function(b) { b.classList.remove('active'); });
        btn.classList.add('active');
        if (lastData.length > 0) applyViewVisibility();
    };
    window.mhGoPage = function(page) { currentPage = page; fetchData(); };

})();
</script>
@endpush
