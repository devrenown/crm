@extends('layouts.app')

@push('page-styles')
<style>
    /* ══════════════════════════════════════════════════════════════
       ORG TREE — COMPACT v3.0
       Horizontal cards (avatar left, info right) · 6-7 per line
       Lazy-load AJAX · Server-side search for 10K+ employees
       ══════════════════════════════════════════════════════════════ */

    .hierarchy-container {
        font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    /* ── Controls Bar ── */
    .tree-controls {
        background: #fff;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .tree-search-box {
        flex: 1;
        min-width: 180px;
        max-width: 320px;
        position: relative;
    }

    .tree-search-box input {
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 7px 12px 7px 34px;
        width: 100%;
        font-size: 13px;
        transition: border-color .2s, box-shadow .2s;
        background: #f8fafc;
    }

    .tree-search-box input:focus {
        border-color: #4f6df5;
        outline: none;
        box-shadow: 0 0 0 3px rgba(79,109,245,0.12);
        background: #fff;
    }

    .tree-search-box i {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
    }

    .tree-filter-group { display: flex; gap: 6px; }

    .tree-filter-group select {
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 7px 10px;
        font-size: 13px;
        background: #f8fafc;
        cursor: pointer;
        color: #475569;
    }

    .tree-filter-group select:focus {
        border-color: #4f6df5;
        outline: none;
    }

    .tree-action-btns {
        display: flex;
        gap: 6px;
        margin-left: auto;
    }

    .tree-action-btns button {
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 500;
        background: #fff;
        color: #475569;
        cursor: pointer;
        transition: all .2s;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .tree-action-btns button:hover {
        background: #4f6df5;
        color: #fff;
        border-color: #4f6df5;
    }

    /* ── Search Results Dropdown (server-side) ── */
    .tree-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        max-height: 320px;
        overflow-y: auto;
        z-index: 100;
        margin-top: 4px;
        display: none;
    }
    .tree-search-results.open { display: block; }

    .tsr-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        cursor: pointer;
        transition: background .15s;
        border-bottom: 1px solid #f1f5f9;
    }
    .tsr-item:last-child { border-bottom: none; }
    .tsr-item:hover { background: #f0f4ff; }
    .tsr-avatar {
        width: 32px; height: 32px; border-radius: 50%; object-fit: cover;
        border: 1.5px solid #e8ecf1; flex-shrink: 0;
    }
    .tsr-name { font-size: 13px; font-weight: 600; color: #1e293b; }
    .tsr-meta { font-size: 11px; color: #64748b; }
    .tsr-empty {
        padding: 16px; text-align: center; font-size: 12px; color: #94a3b8;
    }
    .tsr-searching {
        padding: 16px; text-align: center; font-size: 12px; color: #94a3b8;
    }

    /* ── Stats Row ── */
    .hierarchy-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: #fff;
        border-radius: 10px;
        padding: 14px 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #fff;
        flex-shrink: 0;
    }

    .stat-icon.orange { background: linear-gradient(135deg, #ff6b35, #ff9a5c); }
    .stat-icon.blue   { background: linear-gradient(135deg, #4f6df5, #7c93f7); }
    .stat-icon.green  { background: linear-gradient(135deg, #2ecc71, #55e89a); }
    .stat-icon.purple { background: linear-gradient(135deg, #9b59b6, #c39bd3); }

    .stat-number { font-size: 22px; font-weight: 700; color: #1e293b; line-height: 1; }
    .stat-label  { font-size: 11px; color: #94a3b8; margin-top: 2px; }

    /* ── Tree Wrapper ── */
    .org-tree-wrapper {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        overflow-x: auto;
        min-height: 200px;
    }

    .org-tree {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* ══════════════════════════════════════════════════════════════
       COMPACT NODE CARDS — Horizontal Layout (avatar LEFT, info RIGHT)
       6-7 cards per line (~155px each + 14px gap)
       ══════════════════════════════════════════════════════════════ */
    .tree-node {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        margin-bottom: 6px;
    }

    .node-card {
        background: #fff;
        border: 1.5px solid #e8ecf1;
        border-radius: 10px;
        padding: 8px 10px;
        min-width: 140px;
        max-width: 175px;
        cursor: pointer;
        transition: all .2s ease;
        text-align: left;
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .node-card:hover {
        border-color: #4f6df5;
        box-shadow: 0 3px 14px rgba(79,109,245,0.13);
        transform: translateY(-1px);
    }

    .node-card.root-node {
        border-color: #4f6df5;
        border-width: 2px;
        background: linear-gradient(135deg, #f0f4ff, #fff);
        box-shadow: 0 2px 10px rgba(79,109,245,0.08);
    }

    .node-card.admin-node {
        border-color: #9b59b6;
        border-width: 2px;
        background: linear-gradient(135deg, #faf5ff, #fff);
        box-shadow: 0 2px 10px rgba(155,89,182,0.08);
    }

    .node-card.sub-report-card {
        border-style: dashed;
        border-color: #2ecc71;
        background: linear-gradient(135deg, #f0fdf4, #fff);
    }

    .node-card.sub-report-card:hover {
        border-color: #27ae60;
        box-shadow: 0 3px 14px rgba(46,204,113,0.13);
    }

    .node-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e8ecf1;
        display: block;
        flex-shrink: 0;
        margin: 0;
    }

    .root-node .node-avatar { border-color: #4f6df5; }
    .admin-node .node-avatar { border-color: #9b59b6; }
    .sub-report-card .node-avatar { border-color: #2ecc71; }

    .node-card-body {
        flex: 1;
        min-width: 0;
    }

    .node-name {
        font-size: 11.5px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
        line-height: 1.3;
    }

    .node-designation {
        font-size: 10px;
        color: #64748b;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
        line-height: 1.3;
    }

    .node-dept { display: none; }
    .node-roles { display: none; }

    .node-emp-count {
        font-size: 9px;
        font-weight: 600;
        padding: 1px 6px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 2px;
        margin-top: 1px;
    }

    .node-emp-count.has-reports   { background: #fff3e0; color: #e65100; }
    .node-emp-count.has-subs      { background: #eafaf1; color: #27ae60; }
    .node-emp-count.has-both      { background: #f0f4ff; color: #4f6df5; }
    .node-emp-count.no-reports    { background: #f1f5f9; color: #94a3b8; }

    .node-sub-rm-info { display: none; }

    /* ══════════════════════════════════════════════════════════════
       CONNECTOR LINES
       Direct Reports (solid blue 2.5px) · Sub-Reports (dashed green 2px)
       ══════════════════════════════════════════════════════════════ */

    .tree-children {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 16px;
        position: relative;
    }

    .tree-children::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 2.5px;
        height: 16px;
        background: #4f6df5;
    }

    .children-section-label {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #4f6df5;
        padding: 3px 10px;
        background: #eef2ff;
        border-radius: 20px;
        margin: 4px 0 6px 0;
        user-select: none;
    }

    .children-section-label--sub {
        color: #27ae60;
        background: #f0fdf4;
        margin-top: 10px;
    }

    .tree-children-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 14px;
        position: relative;
        padding-top: 0;
    }

    .tree-children-row--solid { padding-top: 16px; }
    .tree-children-row--solid::before {
        content: '';
        position: absolute;
        top: 0;
        left: 8%;
        right: 8%;
        height: 2.5px;
        background: #4f6df5;
    }

    .tree-children-row--dotted { padding-top: 16px; }
    .tree-children-row--dotted::before {
        content: '';
        position: absolute;
        top: 0;
        left: 8%;
        right: 8%;
        height: 2px;
        background: none;
        border-top: 2px dashed #2ecc71;
    }

    .tree-child-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        padding-top: 16px;
    }

    .tree-children-row--solid > .tree-child-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 2.5px;
        height: 16px;
        background: #4f6df5;
    }

    .tree-children-row--dotted > .tree-child-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 2px;
        height: 16px;
        background: none;
        border-left: 2px dashed #2ecc71;
    }

    .tree-children-row--solid > .show-more-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 2.5px;
        height: 16px;
        background: #4f6df5;
    }

    .show-more-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding-top: 16px;
        position: relative;
    }

    .show-more-btn button {
        background: #f8fafc;
        border: 1.5px dashed #cbd5e1;
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 11px;
        font-weight: 600;
        color: #4f6df5;
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
    }

    .show-more-btn button:hover {
        background: #eef2ff;
        border-color: #4f6df5;
    }

    .node-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        color: #94a3b8;
        font-size: 12px;
        gap: 8px;
    }

    .node-loading i { animation: spin 1s linear infinite; }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }

    .node-toggle {
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 8px;
        color: #64748b;
        z-index: 2;
        transition: all .15s;
    }

    .node-toggle:hover {
        background: #4f6df5;
        border-color: #4f6df5;
        color: #fff;
    }

    .node-toggle.collapsed i { transform: rotate(-90deg); }

    .tree-children.collapsed { display: none; }

    .node-card.search-match {
        border-color: #fbbf24 !important;
        box-shadow: 0 0 0 3px rgba(251,191,36,0.3) !important;
    }
    .tree-node.search-dim { opacity: 0.25; }

    .node-detail-panel {
        position: fixed;
        right: -400px;
        top: 0;
        width: 380px;
        height: 100vh;
        background: #fff;
        box-shadow: -4px 0 24px rgba(0,0,0,0.08);
        z-index: 1050;
        transition: right .25s ease;
        overflow-y: auto;
        padding: 20px;
    }

    .node-detail-panel.open { right: 0; }

    .panel-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.25);
        z-index: 1049;
        display: none;
    }

    .panel-overlay.open { display: block; }

    .panel-close {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: none;
        background: #f1f5f9;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 13px;
    }

    .panel-close:hover { background: #e2e8f0; }

    .panel-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e8ecf1;
        margin: 0 auto 10px;
        display: block;
    }

    .panel-name { text-align: center; font-size: 18px; font-weight: 700; color: #1e293b; }
    .panel-role { text-align: center; font-size: 12px; color: #94a3b8; margin-bottom: 16px; }

    .panel-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .panel-info-item { background: #f8fafc; border-radius: 8px; padding: 10px 12px; }
    .panel-info-label { font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: .3px; }
    .panel-info-value { font-size: 13px; font-weight: 600; color: #1e293b; margin-top: 2px; }

    .panel-chain {
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid #e8ecf1;
    }

    .panel-chain-title { font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 10px; }

    .chain-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 0;
    }

    .chain-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #e8ecf1;
    }

    .chain-name { font-size: 12px; font-weight: 500; color: #334155; }
    .chain-rel  { font-size: 10px; color: #94a3b8; }

    .tree-skeleton {
        display: flex;
        gap: 14px;
        justify-content: center;
        padding: 40px 0;
    }

    .skeleton-card {
        width: 155px;
        height: 56px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 10px;
    }

    @keyframes shimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-state i { font-size: 42px; display: block; margin-bottom: 10px; }
    .empty-state p { font-size: 14px; }

    .tree-legend {
        display: flex;
        gap: 20px;
        justify-content: center;
        padding: 12px 0 0 0;
        margin-top: 16px;
        border-top: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        color: #64748b;
    }

    .legend-line {
        display: inline-block;
        width: 30px;
        height: 3px;
        border-radius: 2px;
    }

    .legend-line--solid { background: #4f6df5; }

    .legend-line--dashed {
        background: none;
        border-top: 2px dashed #2ecc71;
        height: 0;
    }

    .legend-card {
        display: inline-block;
        width: 20px;
        height: 14px;
        border-radius: 3px;
        border: 2px solid #e8ecf1;
    }

    .legend-card--sub {
        border-style: dashed;
        border-color: #2ecc71;
    }

    @media (max-width: 768px) {
        .hierarchy-stats { grid-template-columns: repeat(2, 1fr); }
        .tree-controls { flex-direction: column; align-items: stretch; }
        .tree-search-box { max-width: 100%; }
        .tree-action-btns { margin-left: 0; justify-content: center; }
        .node-detail-panel { width: 100%; right: -100%; }
        .tree-children-row { flex-direction: column; align-items: center; }
        .tree-children-row::before { display: none; }
        .tree-child-wrapper::before { display: none; }
        .tree-legend { gap: 12px; }
        .node-card { min-width: 130px; max-width: 160px; }
    }
</style>
@endpush

@section('page-content')
<div class="content container-fluid hierarchy-container">

    <!-- Breadcrumb -->
    <x-breadcrumb class="col">
        <x-slot name="title">{{ __('Organization Hierarchy') }}</x-slot>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">{{ __('Employees') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Hierarchy') }}</li>
        </ul>
        <x-slot name="right">
            <div class="col-auto ms-auto">
                <div class="d-flex align-items-center gap-2">
                    @activeCan('view-own-hierarchy')
                    <a href="{{ route('hierarchy.my-hierarchy') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fa-solid fa-sitemap me-1"></i> My Hierarchy
                    </a>
                    @endactiveCan

                    @activeCan('edit-role-dept-permission')
                    <!-- <a href="{{ route('hierarchy.role-dept-permissions') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-shield-halved me-1"></i> Role Permissions
                    </a> -->
                    @endactiveCan
                </div>
            </div>
        </x-slot>
    </x-breadcrumb>

    <!-- Stats (server-computed) -->
    <div class="hierarchy-stats" id="hierarchyStats">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fa-solid fa-users"></i></div>
            <div>
                <div class="stat-number" id="totalEmployees">{{ $treeStats['total_employees'] ?? '--' }}</div>
                <div class="stat-label">Total Employees</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fa-solid fa-user-tie"></i></div>
            <div>
                <div class="stat-number" id="totalManagers">{{ $treeStats['total_managers'] ?? '--' }}</div>
                <div class="stat-label">With Reports</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fa-solid fa-building"></i></div>
            <div>
                <div class="stat-number" id="totalDepts">{{ $treeStats['total_departments'] ?? '--' }}</div>
                <div class="stat-label">Departments</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fa-solid fa-layer-group"></i></div>
            <div>
                <div class="stat-number" id="maxDepth">{{ $treeStats['max_depth'] ?? '--' }}</div>
                <div class="stat-label">Deepest Level</div>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="tree-controls">
        <div class="tree-search-box" style="position:relative">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="treeSearch" placeholder="Search employee..." autocomplete="off">
            <div class="tree-search-results" id="treeSearchResults"></div>
        </div>
        <div class="tree-filter-group">
            <select id="filterDepartment">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
            <select id="filterRole">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="tree-action-btns">
            <button onclick="expandAll()"><i class="fa-solid fa-expand"></i> Expand</button>
            <button onclick="collapseAll()"><i class="fa-solid fa-compress"></i> Collapse</button>
        </div>
    </div>

    <!-- Org Tree -->
    <div class="org-tree-wrapper" id="orgTreeWrapper">
        <div class="tree-skeleton" id="treeSkeleton">
            <div class="skeleton-card"></div>
            <div class="skeleton-card"></div>
            <div class="skeleton-card"></div>
            <div class="skeleton-card"></div>
            <div class="skeleton-card"></div>
            <div class="skeleton-card"></div>
            <div class="skeleton-card"></div>
        </div>
        <div class="org-tree" id="orgTree" style="display:none;"></div>

        <!-- Legend -->
        <div class="tree-legend" id="treeLegend" style="display:none;">
            <div class="legend-item">
                <span class="legend-line legend-line--solid"></span>
                <span>Direct Reports (solid)</span>
            </div>
            <div class="legend-item">
                <span class="legend-line legend-line--dashed"></span>
                <span>Sub-Reports (dashed)</span>
            </div>
            <div class="legend-item">
                <span class="legend-card"></span>
                <span>Direct Report Card</span>
            </div>
            <div class="legend-item">
                <span class="legend-card legend-card--sub"></span>
                <span>Sub-Report Card</span>
            </div>
        </div>
    </div>

</div>

<!-- Detail Panel -->
<div class="panel-overlay" id="panelOverlay" onclick="closeDetailPanel()"></div>
<div class="node-detail-panel" id="detailPanel">
    <button class="panel-close" onclick="closeDetailPanel()"><i class="fa-solid fa-xmark"></i></button>
    <div id="panelContent"></div>
</div>
@endsection

@push('page-scripts')
<script>
// ══════════════════════════════════════════════════════════════
// CONFIG (from controller)
// ══════════════════════════════════════════════════════════════
const MAX_RENDER_DEPTH = {{ $maxRenderDepth }};
const VISIBLE_CHILDREN_LIMIT = {{ $visibleChildrenLimit }};
const SUBORDINATES_API = '{{ route("hierarchy.subordinates-tree") }}';
const SEARCH_API = '{{ route("hierarchy.search") }}';
const CHAIN_API = '{{ route("hierarchy.reporting-chain") }}';
const DEFAULT_IMG = '{{ asset("images/user.jpg") }}';

const ROLE_COLORS = {
    'Admin': '#9b59b6', 'Manager': '#4f6df5', 'Tl': '#2ecc71',
    'Hr': '#e74c3c', 'Employee': '#64748b'
};

// ══════════════════════════════════════════════════════════════
// DATA
// ══════════════════════════════════════════════════════════════
const treeData = @js($treeData);
const nodeMap = new Map();
const loadedLazyNodes = new Set();

function flattenTree(nodes, result) {
    if (!result) result = [];
    for (const n of nodes) {
        result.push(n);
        nodeMap.set(n.id, n);
        if (n.children && n.children.length) flattenTree(n.children, result);
        if (n.sub_reports && n.sub_reports.length) {
            for (const sr of n.sub_reports) {
                if (!nodeMap.has(sr.id)) { result.push(sr); nodeMap.set(sr.id, sr); }
            }
        }
    }
    return result;
}

// ══════════════════════════════════════════════════════════════
// RENDER ENGINE
// ══════════════════════════════════════════════════════════════

function renderNodeCard(node, isSubReport = false) {
    const hasChildren = node.direct_reports > 0;
    const avatar = node.avatar || DEFAULT_IMG;

    let cardClass = 'node-card';
    if (node.type === 'Admin') cardClass += ' admin-node';
    if (node.depth === 0) cardClass += ' root-node';
    if (isSubReport) cardClass += ' sub-report-card';

    const dr = node.direct_reports || 0;
    const sr = node.sub_report_count || 0;
    const total = dr + sr;
    let countBadge;
    if (total === 0) {
        countBadge = `<span class="node-emp-count no-reports">0</span>`;
    } else if (dr > 0 && sr > 0) {
        countBadge = `<span class="node-emp-count has-both"><i class="fa-solid fa-users" style="font-size:8px"></i> ${dr} <span style="opacity:.6">|</span> ${sr} sub</span>`;
    } else if (dr > 0) {
        countBadge = `<span class="node-emp-count has-reports"><i class="fa-solid fa-users" style="font-size:8px"></i> ${dr}</span>`;
    } else {
        countBadge = `<span class="node-emp-count has-subs"><i class="fa-solid fa-user-group" style="font-size:8px"></i> ${sr} sub</span>`;
    }

    return `<div class="${cardClass}" onclick="showDetail(${node.id})">
        <img class="node-avatar" src="${avatar}" alt="" onerror="this.src='${DEFAULT_IMG}'">
        <div class="node-card-body">
            <div class="node-name" title="${node.name}">${node.name}</div>
            <div class="node-designation" title="${node.designation}">${node.designation || ''}</div>
            ${countBadge}
        </div>
    </div>`;
}

/**
 * Render a full tree node (card + optional children + optional sub-reports).
 *
 * FIX: data-role-ids stores ALL assigned role IDs as comma-separated list.
 *      This allows the role filter to match users by ANY of their roles,
 *      not just the active_role_id. Previously only active_role_id was
 *      stored, so filtering by a non-active role would miss the user.
 */
function renderNode(node, isSubReport = false) {
    const allRoleIds = (node.role_ids || []).join(',');

    if (isSubReport) {
        return `<div class="tree-node" data-node-id="${node.id}" data-dept-id="${node.department_id||''}" data-role-ids="${allRoleIds}" data-active-role-id="${node.active_role_id||''}" data-report-type="sub">
            ${renderNodeCard(node, true)}
        </div>`;
    }

    const hasChildren = node.direct_reports > 0;
    const hasSubReports = node.sub_reports && node.sub_reports.length > 0;
    const hasAnyReports = hasChildren || hasSubReports;
    const isLoaded = node.children_loaded && node.children && node.children.length > 0;
    const isLazy = !node.children_loaded && hasChildren;
    const needsSectionLabels = hasChildren && hasSubReports;

    const toggle = hasAnyReports
        ? `<div class="node-toggle ${!isLoaded ? 'collapsed' : ''}" onclick="event.stopPropagation();toggleNode(${node.id})"><i class="fa-solid fa-chevron-down"></i></div>`
        : '';

    let childrenHtml = '';

    if (isLoaded) {
        const visibleChildren = node.children.slice(0, VISIBLE_CHILDREN_LIMIT);
        const hiddenChildren = node.children.slice(VISIBLE_CHILDREN_LIMIT);

        childrenHtml = `<div class="tree-children${hasSubReports ? ' tree-children--has-sub' : ''}" id="children-${node.id}">`;

        if (needsSectionLabels) childrenHtml += `<div class="children-section-label">Direct Reports</div>`;

        childrenHtml += `<div class="tree-children-row tree-children-row--solid">`;
        visibleChildren.forEach(c => { childrenHtml += `<div class="tree-child-wrapper">${renderNode(c, false)}</div>`; });
        if (hiddenChildren && hiddenChildren.length > 0) {
            childrenHtml += `<div class="show-more-btn"><button onclick="event.stopPropagation();showMoreChildren(${node.id})"><i class="fa-solid fa-plus me-1"></i> ${hiddenChildren.length} more</button></div>`;
        }
        childrenHtml += `</div>`;

        if (hasSubReports) {
            if (needsSectionLabels) childrenHtml += `<div class="children-section-label children-section-label--sub">Sub-Reports</div>`;
            childrenHtml += `<div class="tree-children-row tree-children-row--dotted">`;
            node.sub_reports.forEach(sr => { childrenHtml += `<div class="tree-child-wrapper">${renderNode(sr, true)}</div>`; });
            childrenHtml += `</div>`;
        }
        childrenHtml += `</div>`;

        if (node.depth >= MAX_RENDER_DEPTH - 1) {
            childrenHtml = childrenHtml.replace(`class="tree-children${hasSubReports ? ' tree-children--has-sub' : ''}"`, `class="tree-children${hasSubReports ? ' tree-children--has-sub' : ''} collapsed"`);
        }
    } else if (isLazy) {
        childrenHtml = `<div class="tree-children collapsed" id="children-${node.id}"></div>`;
    } else if (hasSubReports && !hasChildren) {
        childrenHtml = `<div class="tree-children" id="children-${node.id}">`;
        childrenHtml += `<div class="children-section-label children-section-label--sub">Sub-Reports</div>`;
        childrenHtml += `<div class="tree-children-row tree-children-row--dotted">`;
        node.sub_reports.forEach(sr => { childrenHtml += `<div class="tree-child-wrapper">${renderNode(sr, true)}</div>`; });
        childrenHtml += `</div></div>`;
        if (node.depth >= MAX_RENDER_DEPTH - 1) {
            childrenHtml = childrenHtml.replace('class="tree-children"', 'class="tree-children collapsed"');
        }
    }

    const dr2 = node.direct_reports || 0;
    const sr2 = node.sub_report_count || 0;
    const total2 = dr2 + sr2;
    let directCount;
    if (total2 === 0) {
        directCount = `<span class="node-emp-count no-reports">0</span>`;
    } else if (dr2 > 0 && sr2 > 0) {
        directCount = `<span class="node-emp-count has-both"><i class="fa-solid fa-users" style="font-size:8px"></i> ${dr2} <span style="opacity:.6">|</span> ${sr2} sub</span>`;
    } else if (dr2 > 0) {
        directCount = `<span class="node-emp-count has-reports"><i class="fa-solid fa-users" style="font-size:8px"></i> ${dr2}</span>`;
    } else {
        directCount = `<span class="node-emp-count has-subs"><i class="fa-solid fa-user-group" style="font-size:8px"></i> ${sr2} sub</span>`;
    }

    return `<div class="tree-node" data-node-id="${node.id}" data-dept-id="${node.department_id||''}" data-role-ids="${allRoleIds}" data-active-role-id="${node.active_role_id||''}" data-loaded="${node.children_loaded ? 1 : 0}" data-report-type="direct">
        <div class="node-card ${node.type === 'Admin' ? 'admin-node' : ''} ${node.depth === 0 ? 'root-node' : ''}" onclick="showDetail(${node.id})">
            <img class="node-avatar" src="${node.avatar || DEFAULT_IMG}" alt="" onerror="this.src='${DEFAULT_IMG}'">
            <div class="node-card-body">
                <div class="node-name" title="${node.name}">${node.name}</div>
                <div class="node-designation" title="${node.designation}">${node.designation || ''}</div>
                ${directCount}
            </div>
            ${toggle}
        </div>
        ${childrenHtml}
    </div>`;
}

// ── Show More Children ──
function showMoreChildren(parentId) {
    const node = nodeMap.get(parentId);
    if (!node || !node.children) return;

    const parentEl = document.querySelector(`[data-node-id="${parentId}"]`);
    const childrenContainer = parentEl ? parentEl.querySelector(`#children-${parentId}`) : null;
    if (!childrenContainer) return;

    const solidRow = childrenContainer.querySelector('.tree-children-row--solid');
    if (!solidRow) return;

    const showMoreBtn = solidRow.querySelector('.show-more-btn');
    if (!showMoreBtn) return;

    const existingCount = solidRow.querySelectorAll('.tree-child-wrapper').length;
    const hiddenChildren = node.children.slice(existingCount);
    const batchSize = VISIBLE_CHILDREN_LIMIT;
    const nextBatch = hiddenChildren.slice(0, batchSize);
    const remaining = hiddenChildren.slice(batchSize);

    nextBatch.forEach(c => {
        const wrapper = document.createElement('div');
        wrapper.className = 'tree-child-wrapper';
        wrapper.innerHTML = renderNode(c, false);
        solidRow.insertBefore(wrapper, showMoreBtn);
    });

    if (remaining.length > 0) {
        showMoreBtn.querySelector('button').innerHTML = `<i class="fa-solid fa-plus me-1"></i> ${remaining.length} more`;
    } else {
        showMoreBtn.remove();
    }
}

// ══════════════════════════════════════════════════════════════
// TOGGLE EXPAND / COLLAPSE (with AJAX lazy-load)
// ══════════════════════════════════════════════════════════════
function toggleNode(id) {
    const el = document.getElementById('children-' + id);
    if (!el) return;

    const isCollapsed = el.classList.contains('collapsed');

    if (isCollapsed) {
        const treeNode = el.parentElement;
        const isLoaded = treeNode.dataset.loaded === '1';

        if (!isLoaded && !loadedLazyNodes.has(id)) {
            loadedLazyNodes.add(id);
            el.classList.remove('collapsed');
            el.innerHTML = '<div class="node-loading"><i class="fa-solid fa-spinner"></i> Loading...</div>';

            fetch(SUBORDINATES_API + '?user_id=' + id)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const node = nodeMap.get(id);
                        if (node) {
                            node.children = data.children || [];
                            node.sub_reports = data.sub_reports || [];
                            node.children_loaded = true;
                            node.children.forEach(c => nodeMap.set(c.id, c));
                            (node.sub_reports || []).forEach(sr => nodeMap.set(sr.id, sr));
                            treeNode.dataset.loaded = '1';

                            const hasSubReports = node.sub_reports && node.sub_reports.length > 0;
                            const needsSectionLabels = node.children.length > 0 && hasSubReports;
                            const visibleChildren = node.children.slice(0, VISIBLE_CHILDREN_LIMIT);
                            const hiddenChildren = node.children.slice(VISIBLE_CHILDREN_LIMIT);

                            let html = `<div class="tree-children${hasSubReports ? ' tree-children--has-sub' : ''}" id="children-${id}">`;

                            if (node.children.length > 0) {
                                if (needsSectionLabels) html += `<div class="children-section-label">Direct Reports</div>`;
                                html += `<div class="tree-children-row tree-children-row--solid">`;
                                visibleChildren.forEach(c => { html += `<div class="tree-child-wrapper">${renderNode(c, false)}</div>`; });
                                if (hiddenChildren.length > 0) {
                                    html += `<div class="show-more-btn"><button onclick="event.stopPropagation();showMoreChildren(${id})"><i class="fa-solid fa-plus me-1"></i> ${hiddenChildren.length} more</button></div>`;
                                }
                                html += `</div>`;
                            }

                            if (hasSubReports) {
                                if (needsSectionLabels) html += `<div class="children-section-label children-section-label--sub">Sub-Reports</div>`;
                                html += `<div class="tree-children-row tree-children-row--dotted">`;
                                node.sub_reports.forEach(sr => { html += `<div class="tree-child-wrapper">${renderNode(sr, true)}</div>`; });
                                html += `</div>`;
                            }
                            html += `</div>`;

                            const oldContainer = treeNode.querySelector(`#children-${id}`);
                            if (oldContainer) {
                                const temp = document.createElement('div');
                                temp.innerHTML = html;
                                treeNode.replaceChild(temp.firstElementChild, oldContainer);
                            }
                        }
                    } else {
                        el.innerHTML = '<div style="text-align:center;padding:12px;color:#94a3b8;font-size:12px">No subordinates found</div>';
                    }
                })
                .catch(() => {
                    el.innerHTML = '<div style="text-align:center;padding:12px;color:#e74c3c;font-size:12px">Failed to load. <a href="javascript:void(0)" onclick="retryLoad('+id+')">Retry</a></div>';
                    el.classList.add('collapsed');
                });

            const toggle = treeNode.querySelector(':scope > .node-card .node-toggle');
            if (toggle) toggle.classList.remove('collapsed');
            return;
        }
    }

    el.classList.toggle('collapsed');
    const treeNode = el.parentElement;
    const toggle = treeNode.querySelector(':scope > .node-card .node-toggle');
    if (toggle) toggle.classList.toggle('collapsed');
}

function retryLoad(id) {
    loadedLazyNodes.delete(id);
    const treeNode = document.querySelector(`[data-node-id="${id}"]`);
    if (treeNode) treeNode.dataset.loaded = '0';
    toggleNode(id);
}

// ══════════════════════════════════════════════════════════════
// EXPAND ALL / COLLAPSE ALL
// ══════════════════════════════════════════════════════════════
function expandAll() {
    document.querySelectorAll('.tree-children.collapsed').forEach(el => el.classList.remove('collapsed'));
    document.querySelectorAll('.node-toggle.collapsed').forEach(el => el.classList.remove('collapsed'));
}

function collapseAll() {
    document.querySelectorAll('.tree-children').forEach(el => el.classList.add('collapsed'));
    document.querySelectorAll('.node-toggle').forEach(el => el.classList.add('collapsed'));
}

// ══════════════════════════════════════════════════════════════
// SERVER-SIDE SEARCH (AJAX)
// ══════════════════════════════════════════════════════════════
let searchTimer;
let searchAbortController = null;

document.getElementById('treeSearch').addEventListener('input', function () {
    clearTimeout(searchTimer);
    const q = this.value.trim();
    const resultsEl = document.getElementById('treeSearchResults');

    if (!q) {
        resultsEl.classList.remove('open');
        resultsEl.innerHTML = '';
        document.querySelectorAll('.tree-node').forEach(node => {
            node.classList.remove('search-dim', 'search-match');
        });
        return;
    }

    searchTimer = setTimeout(() => {
        if (searchAbortController) searchAbortController.abort();
        searchAbortController = new AbortController();

        resultsEl.classList.add('open');
        resultsEl.innerHTML = '<div class="tsr-searching"><i class="fa-solid fa-spinner fa-spin"></i> Searching...</div>';

        fetch(SEARCH_API + '?q=' + encodeURIComponent(q), {
            signal: searchAbortController.signal,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(data => {
            const results = data.results || [];
            if (results.length === 0) {
                resultsEl.innerHTML = '<div class="tsr-empty"><i class="fa-solid fa-magnifying-glass" style="margin-right:4px"></i> No employees found</div>';
                return;
            }
            resultsEl.innerHTML = results.map(r => `
                <div class="tsr-item" onclick="navigateToNode(${r.id})">
                    <img class="tsr-avatar" src="${r.avatar || DEFAULT_IMG}" onerror="this.src='${DEFAULT_IMG}'" alt="">
                    <div>
                        <div class="tsr-name">${escHtml(r.name)}</div>
                        <div class="tsr-meta">${escHtml(r.designation || '')}${r.department ? ' · ' + escHtml(r.department) : ''}</div>
                    </div>
                </div>
            `).join('');
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                resultsEl.innerHTML = '<div class="tsr-empty">Search failed</div>';
            }
        });
    }, 300);
});

function navigateToNode(nodeId) {
    document.getElementById('treeSearchResults').classList.remove('open');

    const node = nodeMap.get(nodeId);
    if (!node) {
        showDetailForExternal(nodeId);
        return;
    }

    let current = node;
    while (current && current.reporting_manager_id) {
        const parentEl = document.querySelector(`[data-node-id="${current.reporting_manager_id}"]`);
        if (parentEl) {
            const childrenEl = parentEl.querySelector(`#children-${current.reporting_manager_id}`);
            if (childrenEl && childrenEl.classList.contains('collapsed')) {
                const toggle = parentEl.querySelector(':scope > .node-card .node-toggle');
                if (toggle) toggle.classList.remove('collapsed');
                childrenEl.classList.remove('collapsed');
            }
        }
        current = nodeMap.get(current.reporting_manager_id);
    }

    setTimeout(() => {
        const targetEl = document.querySelector(`[data-node-id="${nodeId}"]`);
        if (targetEl) {
            document.querySelectorAll('.node-card.search-match').forEach(el => el.classList.remove('search-match'));
            const card = targetEl.querySelector(':scope > .node-card');
            if (card) card.classList.add('search-match');
            targetEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            loadAndNavigate(nodeId);
        }
    }, 100);
}

function loadAndNavigate(nodeId) {
    const toLoad = [];
    let current = nodeMap.get(nodeId);
    while (current && current.reporting_manager_id) {
        const parent = nodeMap.get(current.reporting_manager_id);
        if (parent && !parent.children_loaded && parent.direct_reports > 0) {
            toLoad.push(parent.id);
        }
        current = parent;
    }

    if (toLoad.length === 0) return;

    let chain = Promise.resolve();
    toLoad.reverse().forEach(pid => {
        chain = chain.then(() => loadNodeSilent(pid));
    });
    chain.then(() => setTimeout(() => navigateToNode(nodeId), 200));
}

function loadNodeSilent(id) {
    const treeNode = document.querySelector(`[data-node-id="${id}"]`);
    if (!treeNode || treeNode.dataset.loaded === '1') return Promise.resolve();

    return fetch(SUBORDINATES_API + '?user_id=' + id)
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            const node = nodeMap.get(id);
            if (!node) return;
            node.children = data.children || [];
            node.sub_reports = data.sub_reports || [];
            node.children_loaded = true;
            node.children.forEach(c => nodeMap.set(c.id, c));
            (node.sub_reports || []).forEach(sr => nodeMap.set(sr.id, sr));
            treeNode.dataset.loaded = '1';

            const hasSubReports = node.sub_reports.length > 0;
            const needsSectionLabels = node.children.length > 0 && hasSubReports;
            const visibleChildren = node.children.slice(0, VISIBLE_CHILDREN_LIMIT);
            const hiddenChildren = node.children.slice(VISIBLE_CHILDREN_LIMIT);

            let html = `<div class="tree-children${hasSubReports ? ' tree-children--has-sub' : ''}" id="children-${id}">`;
            if (node.children.length > 0) {
                if (needsSectionLabels) html += `<div class="children-section-label">Direct Reports</div>`;
                html += `<div class="tree-children-row tree-children-row--solid">`;
                visibleChildren.forEach(c => { html += `<div class="tree-child-wrapper">${renderNode(c, false)}</div>`; });
                if (hiddenChildren.length > 0) {
                    html += `<div class="show-more-btn"><button onclick="event.stopPropagation();showMoreChildren(${id})"><i class="fa-solid fa-plus me-1"></i> ${hiddenChildren.length} more</button></div>`;
                }
                html += `</div>`;
            }
            if (hasSubReports) {
                if (needsSectionLabels) html += `<div class="children-section-label children-section-label--sub">Sub-Reports</div>`;
                html += `<div class="tree-children-row tree-children-row--dotted">`;
                node.sub_reports.forEach(sr => { html += `<div class="tree-child-wrapper">${renderNode(sr, true)}</div>`; });
                html += `</div>`;
            }
            html += `</div>`;

            const oldContainer = treeNode.querySelector(`#children-${id}`);
            if (oldContainer) {
                const temp = document.createElement('div');
                temp.innerHTML = html;
                treeNode.replaceChild(temp.firstElementChild, oldContainer);
            }
        });
}

function escHtml(str) {
    if (!str) return '';
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.tree-search-box')) {
        document.getElementById('treeSearchResults').classList.remove('open');
    }
});

// ══════════════════════════════════════════════════════════════
// DEPARTMENT & ROLE FILTERS (client-side on loaded nodes)
// ══════════════════════════════════════════════════════════════
//
// HELPER: Check if a node or ANY of its descendants matches a condition.
// Uses querySelectorAll('.tree-node') for reliable nested tree traversal,
// unlike the old node.querySelector() which only found first match and
// missed deeply nested descendants.
//
function nodeOrDescendantMatches(nodeEl, checkFn) {
    if (checkFn(nodeEl)) return true;
    const descendants = nodeEl.querySelectorAll('.tree-node');
    for (let i = 0; i < descendants.length; i++) {
        if (checkFn(descendants[i])) return true;
    }
    return false;
}

// Department filter — checks data-dept-id for exact match
document.getElementById('filterDepartment').addEventListener('change', function () {
    const val = this.value;
    document.querySelectorAll('.tree-node').forEach(node => {
        if (!val) { node.style.display = ''; return; }
        node.style.display = nodeOrDescendantMatches(node, function(el) {
            return el.dataset.deptId === val;
        }) ? '' : 'none';
    });
});

// Role filter — checks ALL role_ids stored in data-role-ids (comma-separated)
// FIX: Previously only checked data-role-id (single active_role_id).
// Users with multiple roles (e.g., Manager+Tl+Employee) were missed when
// filtering by a non-active role. Now checks ALL assigned role IDs.
document.getElementById('filterRole').addEventListener('change', function () {
    const val = this.value;
    document.querySelectorAll('.tree-node').forEach(node => {
        if (!val) { node.style.display = ''; return; }
        node.style.display = nodeOrDescendantMatches(node, function(el) {
            const ids = (el.dataset.roleIds || '').split(',').filter(Boolean);
            const activeId = el.dataset.activeRoleId;
            return ids.indexOf(val) !== -1 || activeId === val;
        }) ? '' : 'none';
    });
});

// ══════════════════════════════════════════════════════════════
// DETAIL SIDE PANEL
// ══════════════════════════════════════════════════════════════
function showDetail(id) {
    const node = nodeMap.get(id);
    if (!node) return;

    const avatar = node.avatar || DEFAULT_IMG;
    const roles = (node.role_names || []).join(', ') || node.active_role || node.type;

    const srmInfo = node.sub_reporting_manager_name
        ? `<div class="panel-info-item" style="grid-column: span 2"><div class="panel-info-label">Sub-Reporting Manager</div><div class="panel-info-value">${node.sub_reporting_manager_name}</div></div>`
        : '';

    document.getElementById('panelContent').innerHTML = `
        <img class="panel-avatar" src="${avatar}" onerror="this.src='${DEFAULT_IMG}'" alt="">
        <div class="panel-name">${escHtml(node.name)}</div>
        <div class="panel-role">${escHtml(roles)}</div>
        <div class="panel-info-grid">
            <div class="panel-info-item"><div class="panel-info-label">Designation</div><div class="panel-info-value">${escHtml(node.designation || 'N/A')}</div></div>
            <div class="panel-info-item"><div class="panel-info-label">Department</div><div class="panel-info-value">${escHtml(node.department || 'N/A')}</div></div>
            <div class="panel-info-item"><div class="panel-info-label">Email</div><div class="panel-info-value" style="font-size:11px">${escHtml(node.email||'N/A')}</div></div>
            <div class="panel-info-item"><div class="panel-info-label">Direct Reports</div><div class="panel-info-value">${node.direct_reports}</div></div>
            <div class="panel-info-item"><div class="panel-info-label">Sub-Reports</div><div class="panel-info-value">${node.sub_report_count || 0}</div></div>
            <div class="panel-info-item"><div class="panel-info-label">Total Descendants</div><div class="panel-info-value">${node.descendant_count}</div></div>
            <div class="panel-info-item"><div class="panel-info-label">Tree Depth</div><div class="panel-info-value">${node.depth}</div></div>
            <div class="panel-info-item"><div class="panel-info-label">Report Type</div><div class="panel-info-value">${node.report_type === 'sub' ? '<span style="color:#2ecc71">&#9679;</span> Sub-Report' : '<span style="color:#4f6df5">&#9679;</span> Direct'}</div></div>
            ${srmInfo}
        </div>
        <div class="panel-chain" id="panelChainSection"><div class="panel-chain-title">Reporting Chain</div><div class="tsr-searching"><i class="fa-solid fa-spinner fa-spin"></i> Loading chain...</div></div>`;

    document.getElementById('detailPanel').classList.add('open');
    document.getElementById('panelOverlay').classList.add('open');

    fetch(CHAIN_API + '?user_id=' + id, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const chain = data.chain || [];
        const truncated = data.truncated || false;
        const totalDepth = data.total_depth || chain.length;

        if (chain.length === 0) {
            const chainSection = document.getElementById('panelChainSection');
            if (chainSection) chainSection.innerHTML = '<div class="panel-chain-title">Reporting Chain</div><div style="color:#94a3b8;font-size:12px;padding:4px 0">Employee data not available</div>';
            return;
        }

        if (chain.length === 1) {
            const n = chain[0];
            const av = n.avatar || DEFAULT_IMG;
            const isTop = !n.reporting_manager_id;
            const chainSection = document.getElementById('panelChainSection');
            if (chainSection) {
                chainSection.innerHTML = `
                    <div class="panel-chain-title">Reporting Chain (1 level)</div>
                    <div class="chain-item" style="background:#f0f4ff;border-radius:8px;padding:8px 10px;margin-bottom:4px">
                        <img class="chain-avatar" src="${av}" onerror="this.src='${DEFAULT_IMG}'" alt="">
                        <div>
                            <div class="chain-name">${escHtml(n.name)}</div>
                            <div class="chain-rel" style="color:#4f6df5;font-weight:600">
                                ${isTop ? '<i class="fa-solid fa-crown" style="font-size:10px;margin-right:3px"></i> Top of Hierarchy' : 'Self'}
                                ${n.designation ? ' · ' + escHtml(n.designation) : ''}
                            </div>
                        </div>
                    </div>
                    ${isTop ? '<div style="color:#94a3b8;font-size:11px;padding:2px 0"><i class="fa-solid fa-circle-info" style="margin-right:3px"></i> This employee is at the top of the reporting hierarchy with no manager above.</div>' : ''}`;
            }
            return;
        }

        const chainHtml = chain.map((n, i) => {
            const av = n.avatar || DEFAULT_IMG;
            let rel = '';
            if (i === 0) rel = 'Self';
            else if (i === 1) rel = 'Reporting Manager';
            else if (truncated && i === chain.length - 1) rel = 'Level ' + i + ' · ...chain continues';
            else if (!truncated && i === chain.length - 1 && !n.reporting_manager_id) rel = 'Top of Hierarchy';
            else rel = 'Level ' + i + ' (Upward)';

            const isTop = (!truncated && i === chain.length - 1 && !n.reporting_manager_id);
            const isLast = (truncated && i === chain.length - 1);
            const highlightStyle = isTop ? 'background:#f0f4ff;border-radius:8px;padding:8px 10px;' : '';
            const dimStyle = isLast ? 'opacity:.6;border-left:2px dashed #e2e8f0;padding-left:8px;' : '';

            return `<div class="chain-item" style="${highlightStyle}${dimStyle}">
                <img class="chain-avatar" src="${av}" onerror="this.src='${DEFAULT_IMG}'" alt="">
                <div style="min-width:0;flex:1">
                    <div class="chain-name">${escHtml(n.name)}${isTop ? ' <i class="fa-solid fa-crown" style="font-size:10px;color:#f59e0b" title="Top of hierarchy"></i>' : ''}${isLast ? ' <i class="fa-solid fa-ellipsis" style="font-size:10px;color:#94a3b8"></i>' : ''}</div>
                    <div class="chain-rel">${rel}${n.designation ? ' · ' + escHtml(n.designation) : ''}</div>
                </div>
            </div>`;
        }).join('');

        const chainSection = document.getElementById('panelChainSection');
        if (chainSection) {
            const scrollStyle = chain.length > 8 ? 'max-height:340px;overflow-y:auto;' : '';

            let truncNotice = '';
            if (truncated && totalDepth > chain.length) {
                truncNotice = `<div style="background:#fffbeb;border:1px solid #fbbf24;border-radius:8px;padding:8px 10px;margin-top:8px;font-size:11px;color:#92400e">
                    <i class="fa-solid fa-triangle-exclamation" style="margin-right:4px"></i>
                    <strong>Showing ${chain.length} of ${totalDepth} levels.</strong> 
                    Chain is very deep (${totalDepth} levels). This may indicate incorrectly configured reporting managers in the database. 
                    Maximum display limit is ${chain.length} levels.
                </div>`;
            }

            chainSection.innerHTML = `
                <div class="panel-chain-title">
                    Reporting Chain (${truncated ? chain.length + ' of ' + totalDepth : chain.length} levels)
                    ${chain.length > 5 ? '<span style="font-size:10px;font-weight:400;color:#94a3b8;margin-left:6px">scroll to see all</span>' : ''}
                </div>
                <div style="${scrollStyle}">${chainHtml}</div>
                ${truncNotice}`;
        }
    })
    .catch(() => {
        const chainSection = document.getElementById('panelChainSection');
        if (chainSection) chainSection.innerHTML = '<div style="color:#e74c3c;font-size:12px">Failed to load chain</div>';
    });
}

function closeDetailPanel() {
    document.getElementById('detailPanel').classList.remove('open');
    document.getElementById('panelOverlay').classList.remove('open');
}

function showDetailForExternal(nodeId) {
    const panelContent = document.getElementById('panelContent');
    panelContent.innerHTML = `<div class="tsr-searching"><i class="fa-solid fa-spinner fa-spin"></i> Loading employee details...</div>`;
    document.getElementById('detailPanel').classList.add('open');
    document.getElementById('panelOverlay').classList.add('open');

    fetch(SEARCH_API + '?q=' + nodeId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const results = data.results || [];
        const emp = results.find(r => r.id === nodeId);
        if (!emp) {
            panelContent.innerHTML = '<div style="text-align:center;padding:24px;color:#94a3b8;font-size:12px">Employee not found</div>';
            return;
        }
        const avatar = emp.avatar || DEFAULT_IMG;
        const roles = emp.active_role || 'N/A';
        panelContent.innerHTML = `
            <img class="panel-avatar" src="${avatar}" onerror="this.src='${DEFAULT_IMG}'" alt="">
            <div class="panel-name">${escHtml(emp.name)}</div>
            <div class="panel-role">${escHtml(roles)}</div>
            <div class="panel-info-grid">
                <div class="panel-info-item"><div class="panel-info-label">Designation</div><div class="panel-info-value">${escHtml(emp.designation || 'N/A')}</div></div>
                <div class="panel-info-item"><div class="panel-info-label">Department</div><div class="panel-info-value">${escHtml(emp.department || 'N/A')}</div></div>
            </div>
            <div class="panel-chain" id="panelChainSection"><div class="panel-chain-title">Reporting Chain</div><div class="tsr-searching"><i class="fa-solid fa-spinner fa-spin"></i> Loading chain...</div></div>`;

        fetch(CHAIN_API + '?user_id=' + nodeId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(chainData => {
            const chain = chainData.chain || [];
            const truncated = chainData.truncated || false;
            const totalDepth = chainData.total_depth || chain.length;
            if (chain.length === 0) {
                const cs = document.getElementById('panelChainSection');
                if (cs) cs.innerHTML = '<div class="panel-chain-title">Reporting Chain</div><div style="color:#94a3b8;font-size:12px;padding:4px 0">Employee data not available</div>';
                return;
            }
            const chainHtml = chain.length === 1
                ? `<div class="chain-item" style="background:#f0f4ff;border-radius:8px;padding:8px 10px"><img class="chain-avatar" src="${chain[0].avatar || DEFAULT_IMG}" onerror="this.src='${DEFAULT_IMG}'" alt=""><div><div class="chain-name">${escHtml(chain[0].name)}</div><div class="chain-rel" style="color:#4f6df5;font-weight:600">${!chain[0].reporting_manager_id ? '<i class="fa-solid fa-crown" style="font-size:10px;margin-right:3px"></i> Top of Hierarchy' : 'Self'}${chain[0].designation ? ' · ' + escHtml(chain[0].designation) : ''}</div></div></div>`
                : chain.map((n, i) => `<div class="chain-item"><img class="chain-avatar" src="${n.avatar || DEFAULT_IMG}" onerror="this.src='${DEFAULT_IMG}'" alt=""><div style="min-width:0;flex:1"><div class="chain-name">${escHtml(n.name)}</div><div class="chain-rel">${i===0?'Self':i===1?'Reporting Manager':'Level '+i}</div></div></div>`).join('');
            const cs = document.getElementById('panelChainSection');
            if (cs) {
                const scrollStyle = chain.length > 8 ? 'max-height:340px;overflow-y:auto;' : '';
                cs.innerHTML = `<div class="panel-chain-title">Reporting Chain (${truncated ? chain.length+' of '+totalDepth : chain.length} levels)</div><div style="${scrollStyle}">${chainHtml}</div>`;
            }
        })
        .catch(() => {
            const cs = document.getElementById('panelChainSection');
            if (cs) cs.innerHTML = '<div style="color:#e74c3c;font-size:12px">Failed to load chain</div>';
        });
    })
    .catch(() => {
        panelContent.innerHTML = '<div style="text-align:center;padding:24px;color:#e74c3c;font-size:12px">Failed to load employee</div>';
    });
}

function buildChain(id) {
    const chain = [];
    let current = nodeMap.get(id);
    const visited = new Set();
    while (current && !visited.has(current.id)) {
        visited.add(current.id);
        chain.push(current);
        if (!current.reporting_manager_id) break;
        current = nodeMap.get(current.reporting_manager_id);
    }
    return chain;
}

// ══════════════════════════════════════════════════════════════
// INIT
// ══════════════════════════════════════════════════════════════
function initTree() {
    const tree = treeData.tree || [];

    flattenTree(tree);

    const treeEl = document.getElementById('orgTree');

    if (tree.length) {
        treeEl.innerHTML = `<div class="tree-children-row tree-children-row--solid">${tree.map(n => `<div class="tree-child-wrapper">${renderNode(n, false)}</div>`).join('')}</div>`;
    } else {
        treeEl.innerHTML = `
            <div class="empty-state">
                <i class="fa-solid fa-sitemap"></i>
                <p>No hierarchy data found.</p>
                <p style="font-size:12px;margin-top:4px">Assign reporting managers to employees to build the tree.</p>
            </div>`;
    }

    document.getElementById('treeSkeleton').style.display = 'none';
    treeEl.style.display = 'flex';
    document.getElementById('treeLegend').style.display = 'flex';
}

document.addEventListener('DOMContentLoaded', initTree);
</script>
@endpush
