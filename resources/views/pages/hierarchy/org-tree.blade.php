@extends('layouts.app')

@push('page-styles')
<style>
    /* ========== ORG TREE — CLEAN & MINIMAL ========== */
    .hierarchy-container { font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; }

    /* Controls Bar */
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
        border-color: var(--primary, #ff6b35);
        outline: none;
        box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
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
        border-color: var(--primary, #ff6b35);
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
        background: var(--primary, #ff6b35);
        color: #fff;
        border-color: var(--primary, #ff6b35);
    }

    /* Stats Row */
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

    /* Tree Wrapper */
    .org-tree-wrapper {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        overflow-x: auto;
        min-height: 200px;
    }

    .org-tree {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Node Cards */
    .tree-node {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }

    .node-card {
        background: #fff;
        border: 1.5px solid #e8ecf1;
        border-radius: 10px;
        padding: 12px 16px;
        min-width: 180px;
        max-width: 240px;
        cursor: pointer;
        transition: all .2s ease;
        text-align: center;
        position: relative;
    }

    .node-card:hover {
        border-color: var(--primary, #ff6b35);
        box-shadow: 0 4px 16px rgba(255,107,53,0.12);
        transform: translateY(-1px);
    }

    .node-card.root-node {
        border-color: var(--primary, #ff6b35);
        background: linear-gradient(135deg, #fff8f4, #fff);
    }

    .node-card.admin-node {
        border-color: #9b59b6;
        background: linear-gradient(135deg, #faf5ff, #fff);
    }

    .node-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 6px;
        border: 2px solid #e8ecf1;
        display: block;
    }

    .node-name {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    .node-designation {
        font-size: 11px;
        color: #64748b;
        margin-bottom: 4px;
    }

    .node-dept {
        display: inline-block;
        font-size: 10px;
        padding: 2px 7px;
        background: #f0f4ff;
        color: #4f6df5;
        border-radius: 20px;
        margin-bottom: 4px;
    }

    .node-roles {
        display: flex;
        flex-wrap: wrap;
        gap: 3px;
        justify-content: center;
        margin-bottom: 6px;
    }

    .node-role-badge {
        font-size: 9px;
        padding: 1px 5px;
        border-radius: 10px;
        color: #fff;
    }

    .node-emp-count {
        font-size: 10px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }

    .node-emp-count.has-reports { background: #fff3e0; color: #e65100; }
    .node-emp-count.no-reports  { background: #f1f5f9; color: #94a3b8; }

    /* Tree Connector Lines */
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
        width: 2px;
        height: 16px;
        background: #cbd5e1;
    }

    .tree-children-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        position: relative;
    }

    .tree-children-row::before {
        content: '';
        position: absolute;
        top: 0;
        left: calc(25%);
        right: calc(25%);
        height: 2px;
        background: #cbd5e1;
    }

    .tree-child-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        padding-top: 16px;
    }

    .tree-child-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        width: 2px;
        height: 16px;
        background: #cbd5e1;
    }

    .tree-child-wrapper.single-child::before { display: none; }

    /* Expand/Collapse Toggle */
    .node-toggle {
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #fff;
        border: 1.5px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 9px;
        color: #64748b;
        z-index: 2;
        transition: all .15s;
    }

    .node-toggle:hover {
        background: var(--primary, #ff6b35);
        border-color: var(--primary, #ff6b35);
        color: #fff;
    }

    .node-toggle.collapsed i { transform: rotate(-90deg); }

    .tree-children.collapsed { display: none; }

    /* Search Highlight */
    .node-card.search-match { border-color: #fbbf24; box-shadow: 0 0 0 2px rgba(251,191,36,0.3); }
    .tree-node.search-dim   { opacity: 0.25; }

    /* Detail Side Panel */
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

    /* Skeleton Loader */
    .tree-skeleton {
        display: flex;
        gap: 20px;
        justify-content: center;
        padding: 40px 0;
    }

    .skeleton-card {
        width: 180px;
        height: 160px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 10px;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-state i { font-size: 42px; display: block; margin-bottom: 10px; }
    .empty-state p { font-size: 14px; }

    /* Responsive */
    @media (max-width: 768px) {
        .hierarchy-stats { grid-template-columns: repeat(2, 1fr); }
        .tree-controls { flex-direction: column; align-items: stretch; }
        .tree-search-box { max-width: 100%; }
        .tree-action-btns { margin-left: 0; justify-content: center; }
        .node-detail-panel { width: 100%; right: -100%; }
        .tree-children-row { flex-direction: column; align-items: center; }
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
                    <a href="{{ route('hierarchy.role-dept-permissions') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-shield-halved me-1"></i> Role Permissions
                    </a>
                    @endactiveCan
                </div>
            </div>
        </x-slot>
    </x-breadcrumb>

    <!-- Stats -->
    <div class="hierarchy-stats" id="hierarchyStats">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fa-solid fa-users"></i></div>
            <div>
                <div class="stat-number" id="totalEmployees">--</div>
                <div class="stat-label">Total Employees</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fa-solid fa-user-tie"></i></div>
            <div>
                <div class="stat-number" id="totalManagers">--</div>
                <div class="stat-label">With Reports</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fa-solid fa-building"></i></div>
            <div>
                <div class="stat-number" id="totalDepts">--</div>
                <div class="stat-label">Departments</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fa-solid fa-layer-group"></i></div>
            <div>
                <div class="stat-number" id="maxDepth">--</div>
                <div class="stat-label">Deepest Level</div>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="tree-controls">
        <div class="tree-search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="treeSearch" placeholder="Search employee..." autocomplete="off">
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
        </div>
        <div class="org-tree" id="orgTree" style="display:none;"></div>
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
// ── Role color map ──
const ROLE_COLORS = {
    'Admin': '#9b59b6', 'Manager': '#4f6df5', 'Tl': '#2ecc71',
    'Hr': '#e74c3c', 'Employee': '#64748b'
};
const DEFAULT_IMG = '{{ asset("images/user.jpg") }}';

// ── Data ──
const treeData = @js($treeData);
let allNodes = [];
const nodeMap = new Map();

// ── Utility: Flatten tree into array + build lookup map ──
function flattenTree(nodes, result) {
    if (!result) result = [];
    for (const n of nodes) {
        result.push(n);
        nodeMap.set(n.id, n);
        if (n.children && n.children.length) flattenTree(n.children, result);
    }
    return result;
}

// ── Stats ──
function computeStats(nodes) {
    const flat = flattenTree(nodes);
    allNodes = flat;
    return {
        totalEmp: flat.length,
        managers: flat.filter(n => n.direct_reports > 0).length,
        maxDepth: flat.reduce((m, n) => Math.max(m, n.depth), 0)
    };
}

// ── Render single node ──
function renderNode(node) {
    const hasChildren = node.children && node.children.length > 0;
    const avatar = node.avatar || DEFAULT_IMG;
    const color = ROLE_COLORS[node.active_role] || '#64748b';

    let cardClass = 'node-card';
    if (node.type === 'Admin') cardClass += ' admin-node';
    if (node.depth === 0) cardClass += ' root-node';

    const roleBadges = (node.role_names || []).map(r =>
        `<span class="node-role-badge" style="background:${ROLE_COLORS[r] || '#64748b'}">${r}</span>`
    ).join('');

    const countBadge = node.direct_reports > 0
        ? `<span class="node-emp-count has-reports"><i class="fa-solid fa-users"></i> ${node.direct_reports} direct${node.descendant_count > node.direct_reports ? ' &middot; ' + node.descendant_count + ' total' : ''}</span>`
        : `<span class="node-emp-count no-reports"><i class="fa-regular fa-user"></i> Individual</span>`;

    const srmLine = node.sub_reporting_manager_name
        ? `<div style="font-size:10px;color:#94a3b8;margin-top:2px"><i class="fa-solid fa-user-group" style="font-size:9px"></i> Sub-RM: ${node.sub_reporting_manager_name}</div>`
        : '';

    const toggle = hasChildren
        ? `<div class="node-toggle" onclick="event.stopPropagation();toggleNode(${node.id})"><i class="fa-solid fa-chevron-down"></i></div>`
        : '';

    const childrenHtml = hasChildren
        ? `<div class="tree-children" id="children-${node.id}">${renderChildrenRow(node.children)}</div>`
        : '';

    return `<div class="tree-node" data-node-id="${node.id}" data-dept-id="${node.department_id||''}" data-role-id="${node.active_role_id||''}">
        <div class="${cardClass}" onclick="showDetail(${node.id})">
            <img class="node-avatar" src="${avatar}" alt="" onerror="this.src='${DEFAULT_IMG}'">
            <div class="node-name">${node.name}</div>
            <div class="node-designation">${node.designation}</div>
            ${node.department !== 'N/A' ? `<div class="node-dept">${node.department}</div>` : ''}
            <div class="node-roles">${roleBadges}</div>
            ${countBadge}
            ${srmLine}
            ${toggle}
        </div>
        ${childrenHtml}
    </div>`;
}

function renderChildrenRow(children) {
    if (!children || !children.length) return '';
    const single = children.length === 1 ? ' single-child' : '';
    return `<div class="tree-children-row">${children.map(c => `<div class="tree-child-wrapper${single}">${renderNode(c)}</div>`).join('')}</div>`;
}

// ── Toggle ──
function toggleNode(id) {
    const el = document.getElementById('children-' + id);
    if (!el) return;
    el.classList.toggle('collapsed');
    const toggle = el.previousElementSibling?.querySelector('.node-toggle');
    if (toggle) toggle.classList.toggle('collapsed');
}

function expandAll() {
    document.querySelectorAll('.tree-children').forEach(el => el.classList.remove('collapsed'));
    document.querySelectorAll('.node-toggle').forEach(el => el.classList.remove('collapsed'));
}

function collapseAll() {
    document.querySelectorAll('.tree-children').forEach(el => el.classList.add('collapsed'));
    document.querySelectorAll('.node-toggle').forEach(el => el.classList.add('collapsed'));
}

// ── Search (debounced) ──
let searchTimer;
document.getElementById('treeSearch').addEventListener('input', function () {
    clearTimeout(searchTimer);
    const q = this.value.toLowerCase().trim();
    searchTimer = setTimeout(() => {
        document.querySelectorAll('.tree-node').forEach(node => {
            const name = node.querySelector('.node-name')?.textContent.toLowerCase() || '';
            const desg = node.querySelector('.node-designation')?.textContent.toLowerCase() || '';
            if (!q) {
                node.classList.remove('search-dim', 'search-match');
                return;
            }
            const match = name.includes(q) || desg.includes(q);
            node.classList.toggle('search-dim', !match);
            node.classList.toggle('search-match', match);
        });
        if (q) expandAll();
    }, 250);
});

// ── Filters ──
document.getElementById('filterDepartment').addEventListener('change', function () {
    const val = this.value;
    document.querySelectorAll('.tree-node').forEach(node => {
        if (!val) { node.style.display = ''; return; }
        const has = node.dataset.deptId === val || node.querySelector(`[data-dept-id="${val}"]`);
        node.style.display = has ? '' : 'none';
    });
});

document.getElementById('filterRole').addEventListener('change', function () {
    const val = this.value;
    document.querySelectorAll('.tree-node').forEach(node => {
        if (!val) { node.style.display = ''; return; }
        const has = node.dataset.roleId === val || node.querySelector(`[data-role-id="${val}"]`);
        node.style.display = has ? '' : 'none';
    });
});

// ── Detail Panel ──
function showDetail(id) {
    const node = nodeMap.get(id);
    if (!node) return;
    const avatar = node.avatar || DEFAULT_IMG;
    const roles = (node.role_names || []).join(', ') || node.active_role || node.type;

    // Build reporting chain upward
    const chain = buildChain(id);
    const chainHtml = chain.length > 1 ? chain.map((n, i) => {
        const av = n.avatar || DEFAULT_IMG;
        const rel = i === 0 ? 'Self' : (i === 1 ? 'Reporting Manager' : 'Upward Chain');
        return `<div class="chain-item">
            <img class="chain-avatar" src="${av}" onerror="this.src='${DEFAULT_IMG}'" alt="">
            <div><div class="chain-name">${n.name}</div><div class="chain-rel">${rel} &middot; ${n.designation}</div></div>
        </div>`;
    }).join('') : '<div style="color:#94a3b8;font-size:12px">No reporting manager assigned</div>';

    document.getElementById('panelContent').innerHTML = `
        <img class="panel-avatar" src="${avatar}" onerror="this.src='${DEFAULT_IMG}'" alt="">
        <div class="panel-name">${node.name}</div>
        <div class="panel-role">${roles}</div>
        <div class="panel-info-grid">
            <div class="panel-info-item"><div class="panel-info-label">Designation</div><div class="panel-info-value">${node.designation}</div></div>
            <div class="panel-info-item"><div class="panel-info-label">Department</div><div class="panel-info-value">${node.department}</div></div>
            <div class="panel-info-item"><div class="panel-info-label">Email</div><div class="panel-info-value" style="font-size:11px">${node.email||'N/A'}</div></div>
            <div class="panel-info-item"><div class="panel-info-label">Direct Reports</div><div class="panel-info-value">${node.direct_reports}</div></div>
            <div class="panel-info-item"><div class="panel-info-label">Total Descendants</div><div class="panel-info-value">${node.descendant_count}</div></div>
            <div class="panel-info-item"><div class="panel-info-label">Tree Depth</div><div class="panel-info-value">${node.depth}</div></div>
        </div>
        <div class="panel-chain"><div class="panel-chain-title">Reporting Chain</div>${chainHtml}</div>`;

    document.getElementById('detailPanel').classList.add('open');
    document.getElementById('panelOverlay').classList.add('open');
}

function closeDetailPanel() {
    document.getElementById('detailPanel').classList.remove('open');
    document.getElementById('panelOverlay').classList.remove('open');
}

// Build upward chain from nodeMap (no API call needed)
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

// ── Init ──
function initTree() {
    const stats = computeStats(treeData.tree || []);

    document.getElementById('totalEmployees').textContent = stats.totalEmp;
    document.getElementById('totalManagers').textContent = stats.managers;
    document.getElementById('totalDepts').textContent = {{ $departments->count() }};
    document.getElementById('maxDepth').textContent = stats.maxDepth;

    const treeEl = document.getElementById('orgTree');
    treeEl.innerHTML = (treeData.tree || []).length
        ? treeData.tree.map(n => renderNode(n)).join('')
        : '<div class="empty-state"><i class="fa-solid fa-sitemap"></i><p>No hierarchy data found.</p><p style="font-size:12px;margin-top:4px">Assign reporting managers to employees to build the tree.</p></div>';

    document.getElementById('treeSkeleton').style.display = 'none';
    treeEl.style.display = 'flex';
}

document.addEventListener('DOMContentLoaded', initTree);
</script>
@endpush
