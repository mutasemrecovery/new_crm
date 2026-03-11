@extends('layouts.admin')
@section('title', __('admin.nav_services'))
@section('page-title', __('admin.nav_services'))

@push('styles')
    <style>
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap
        }

        .page-header h1 {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all .2s;
            font-family: inherit;
            white-space: nowrap;
            border: none
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #8b7eff);
            color: #fff;
            box-shadow: 0 4px 14px rgba(108, 99, 255, .3)
        }

        .btn-primary:hover {
            opacity: .9;
            color: #fff
        }

        .btn-sm {
            padding: 7px 12px;
            font-size: 12px;
            border-radius: 8px
        }

        .act-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 7px;
            color: var(--muted);
            text-decoration: none;
            transition: all .15s;
            font-size: 13px;
            background: none;
            border: none;
            cursor: pointer
        }

        .act-btn:hover {
            background: var(--surface2);
            color: var(--text)
        }

        .act-btn.del:hover {
            background: rgba(255, 101, 132, .1);
            color: var(--accent2)
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px
        }

        .svc-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: border-color .2s
        }

        .svc-card:hover {
            border-color: rgba(255, 255, 255, .15)
        }

        .svc-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .svc-card-left {
            display: flex;
            align-items: center;
            gap: 12px
        }

        .svc-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            flex-shrink: 0
        }

        .svc-name {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 14px
        }

        .svc-name-en {
            font-size: 12px;
            color: var(--muted)
        }

        .svc-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--muted);
            flex-wrap: wrap
        }

        /* type badge */
        .svc-type-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px
        }

        .svc-type-badge.recurring {
            background: rgba(0, 198, 255, .12);
            color: #00c6ff
        }

        .svc-type-badge.project {
            background: rgba(247, 183, 49, .12);
            color: #f7b731
        }

        /* time badge */
        .svc-time-badge {
            font-size: 11px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 4px
        }

        .svc-toggle {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--muted)
        }

        .toggle-switch {
            position: relative;
            width: 36px;
            height: 20px;
            display: inline-block
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0
        }

        .toggle-slider {
            position: absolute;
            inset: 0;
            background: var(--surface2);
            border-radius: 99px;
            transition: .3s;
            cursor: pointer;
            border: 1px solid var(--border)
        }

        .toggle-slider:before {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            left: 3px;
            bottom: 3px;
            background: #fff;
            border-radius: 50%;
            transition: .3s
        }

        input:checked+.toggle-slider {
            background: var(--accent);
            border-color: var(--accent)
        }

        input:checked+.toggle-slider:before {
            transform: translateX(16px)
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px
        }

        .modal-overlay.open {
            display: flex
        }

        .modal {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 28px;
            width: 100%;
            max-width: 560px;
            animation: modalIn .25s ease;
            max-height: 90vh;
            overflow-y: auto
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(-20px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .modal-title {
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 18px;
            padding: 0
        }

        .modal-close:hover {
            color: var(--text)
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 14px
        }

        .form-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .5px
        }

        .form-control {
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            color: var(--text);
            font-family: inherit;
            outline: none;
            transition: border-color .2s;
            width: 100%;
            box-sizing: border-box
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(108, 99, 255, .1)
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted)
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 14px;
            opacity: .3;
            display: block
        }

        /* type selector tabs */
        .type-tabs {
            display: flex;
            gap: 8px
        }

        .type-tab {
            flex: 1;
            padding: 9px 12px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: var(--surface2);
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            transition: all .2s;
            color: var(--muted)
        }

        .type-tab.active.recurring {
            border-color: #00c6ff;
            background: rgba(0, 198, 255, .08);
            color: #00c6ff
        }

        .type-tab.active.project {
            border-color: #f7b731;
            background: rgba(247, 183, 49, .08);
            color: #f7b731
        }

        /* minutes helper */
        .min-helper {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <h1>{{ __('admin.nav_services') }}</h1>
        <button class="btn btn-primary" onclick="openModal()">
            <i class="fas fa-plus"></i> {{ __('admin.add_service') }}
        </button>
    </div>

    @if ($services->count())
        <div class="services-grid">
            @foreach ($services as $svc)
                <div class="svc-card">
                    <div class="svc-card-top">
                        <div class="svc-card-left">
                            <div class="svc-icon-box" style="background:{{ $svc->color }}22;color:{{ $svc->color }}">
                                {{ $svc->icon ?: '' }}<i class="fas fa-layer-group"
                                    style="{{ $svc->icon ? 'display:none' : '' }}"></i>
                            </div>
                            <div>
                                <div class="svc-name">{{ $svc->name }}</div>
                                @if ($svc->name_en)
                                    <div class="svc-name-en">{{ $svc->name_en }}</div>
                                @endif
                            </div>
                        </div>
                        <div style="display:flex;gap:4px">
                            <button class="act-btn"
                                onclick="openEditModal(
                        {{ $svc->id }},
                        '{{ addslashes($svc->name) }}',
                        '{{ addslashes($svc->name_en ?? '') }}',
                        '{{ $svc->color }}',
                        '{{ addslashes($svc->icon ?? '') }}',
                        '{{ addslashes($svc->description ?? '') }}',
                        '{{ $svc->service_type ?? 'recurring' }}',
                        {{ $svc->estimated_minutes_per_unit ?? 60 }}
                    )"
                                title="{{ __('admin.edit') }}">
                                <i class="fas fa-pen"></i>
                            </button>
                            <form action="{{ route('admin.services.destroy', $svc) }}" method="POST"
                                id="del-s{{ $svc->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="act-btn del" title="{{ __('admin.delete') }}"
                                    onclick="confirmDelete('del-s{{ $svc->id }}','{{ __('admin.confirm_delete') }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="svc-meta">
                        {{-- clients count --}}
                        <span><i class="fas fa-users"></i> {{ $svc->clients_count }} {{ __('admin.clients') }}</span>

                        {{-- type badge --}}
                        <span class="svc-type-badge {{ $svc->service_type ?? 'recurring' }}">
                            {{ ($svc->service_type ?? 'recurring') === 'project' ? __('admin.project') : __('admin.recurring') }}
                        </span>

                        {{-- time badge --}}
                        @if ($svc->estimated_minutes_per_unit)
                            <span class="svc-time-badge">
                                ⏱ {{ $svc->estimated_minutes_per_unit }} {{ __('admin.min') }}
                                @if (($svc->service_type ?? 'recurring') === 'recurring')
                                    /{{ strtolower(__('admin.unit')) }}
                                @endif
                            </span>
                        @endif

                        <span
                            style="margin-inline-start:auto;width:10px;height:10px;border-radius:50%;background:{{ $svc->color }};display:inline-block"></span>
                    </div>

                    @if ($svc->description)
                        <p style="font-size:12px;color:var(--muted);line-height:1.5;margin:0">
                            {{ Str::limit($svc->description, 80) }}</p>
                    @endif

                    {{-- Toggle active --}}
                    <form action="{{ route('admin.services.update', $svc) }}" method="POST"
                        style="display:flex;align-items:center;gap:8px">
                        @csrf @method('PUT')
                        <input type="hidden" name="name" value="{{ $svc->name }}">
                        <input type="hidden" name="name_en" value="{{ $svc->name_en }}">
                        <input type="hidden" name="color" value="{{ $svc->color }}">
                        <input type="hidden" name="icon" value="{{ $svc->icon }}">
                        <input type="hidden" name="service_type" value="{{ $svc->service_type ?? 'recurring' }}">
                        <input type="hidden" name="estimated_minutes_per_unit"
                            value="{{ $svc->estimated_minutes_per_unit ?? 60 }}">
                        <label class="svc-toggle" style="cursor:pointer">
                            <label class="toggle-switch">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ $svc->is_active ? 'checked' : '' }} onchange="this.closest('form').submit()">
                                <span class="toggle-slider"></span>
                            </label>
                            <span>{{ $svc->is_active ? __('admin.active') : __('admin.inactive') }}</span>
                        </label>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-layer-group"></i>
            <p>{{ __('admin.no_services_yet') }}</p>
            <button class="btn btn-primary" onclick="openModal()" style="margin-top:16px">
                <i class="fas fa-plus"></i> {{ __('admin.add_service') }}
            </button>
        </div>
    @endif


    {{-- ═══════════════════════ CREATE MODAL ═══════════════════════ --}}
    <div class="modal-overlay" id="createModal">
        <div class="modal">
            <div class="modal-title">
                <span>{{ __('admin.add_service') }}</span>
                <button class="modal-close" onclick="closeModal()">×</button>
            </div>
            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf

                {{-- Names --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('admin.service_name_ar') }} *</label>
                        <input type="text" name="name" class="form-control" required placeholder="تصميم صور">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('admin.service_name_en') }}</label>
                        <input type="text" name="name_en" class="form-control" placeholder="Photo Design">
                    </div>
                </div>

                {{-- Color + Icon --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('admin.color') }}</label>
                        <input type="color" name="color" class="form-control" value="#6c63ff"
                            style="height:42px;padding:4px">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('admin.icon') }} (emoji)</label>
                        <input type="text" name="icon" class="form-control" placeholder="🎨">
                    </div>
                </div>

                {{-- Service Type --}}
                <div class="form-group">
                    <label class="form-label">{{ __('admin.service_type') }}</label>
                    <div class="type-tabs">
                        <div class="type-tab recurring active" id="c-tab-recurring" onclick="setType('c', 'recurring')">
                            🔄 {{ __('admin.recurring') }}
                            <div style="font-size:10px;font-weight:400;margin-top:2px">{{ __('admin.recurring_hint') }}
                            </div>
                        </div>
                        <div class="type-tab project" id="c-tab-project" onclick="setType('c', 'project')">
                            🏗️ {{ __('admin.project') }}
                            <div style="font-size:10px;font-weight:400;margin-top:2px">{{ __('admin.project_hint') }}
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="service_type" id="c-service-type" value="recurring">
                </div>

                {{-- Estimated minutes --}}
                <div class="form-group">
                    <label class="form-label" id="c-min-label">
                        {{ __('admin.estimated_minutes_per_unit') }}
                    </label>
                    <input type="number" name="estimated_minutes_per_unit" id="c-est-min" class="form-control"
                        min="1" value="60" placeholder="60">
                    <span class="min-helper" id="c-min-helper">
                        {{ __('admin.minutes_hint_recurring') }}
                    </span>
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label class="form-label">{{ __('admin.description') }}</label>
                    <textarea name="description" class="form-control" rows="2" style="resize:vertical"></textarea>
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:6px">
                    <button type="button" class="btn btn-secondary btn-sm"
                        onclick="closeModal()">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i>
                        {{ __('admin.create') }}</button>
                </div>
            </form>
        </div>
    </div>


    {{-- ═══════════════════════ EDIT MODAL ════════════════════════ --}}
    <div class="modal-overlay" id="editModal">
        <div class="modal">
            <div class="modal-title">
                <span>{{ __('admin.edit_service') }}</span>
                <button class="modal-close" onclick="closeEditModal()">×</button>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('admin.service_name_ar') }} *</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('admin.service_name_en') }}</label>
                        <input type="text" name="name_en" id="editNameEn" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('admin.color') }}</label>
                        <input type="color" name="color" id="editColor" class="form-control"
                            style="height:42px;padding:4px">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('admin.icon') }} (emoji)</label>
                        <input type="text" name="icon" id="editIcon" class="form-control" placeholder="🎨">
                    </div>
                </div>

                {{-- Service Type --}}
                <div class="form-group">
                    <label class="form-label">{{ __('admin.service_type') }}</label>
                    <div class="type-tabs">
                        <div class="type-tab recurring" id="e-tab-recurring" onclick="setType('e', 'recurring')">
                            🔄 {{ __('admin.recurring') }}
                            <div style="font-size:10px;font-weight:400;margin-top:2px">{{ __('admin.recurring_hint') }}
                            </div>
                        </div>
                        <div class="type-tab project" id="e-tab-project" onclick="setType('e', 'project')">
                            🏗️ {{ __('admin.project') }}
                            <div style="font-size:10px;font-weight:400;margin-top:2px">{{ __('admin.project_hint') }}
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="service_type" id="e-service-type" value="recurring">
                </div>

                {{-- Estimated minutes --}}
                <div class="form-group">
                    <label class="form-label" id="e-min-label">
                        {{ __('admin.estimated_minutes_per_unit') }}
                    </label>
                    <input type="number" name="estimated_minutes_per_unit" id="editEstMin" class="form-control"
                        min="1" placeholder="60">
                    <span class="min-helper" id="e-min-helper">
                        {{ __('admin.minutes_hint_recurring') }}
                    </span>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('admin.description') }}</label>
                    <textarea name="description" id="editDesc" class="form-control" rows="2" style="resize:vertical"></textarea>
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:6px">
                    <button type="button" class="btn btn-secondary btn-sm"
                        onclick="closeEditModal()">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i>
                        {{ __('admin.update') }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const routes = @json(['update_base' => url('admin/services')]);

        const minHints = {
            recurring: '{{ __('admin.minutes_hint_recurring') }}',
            project: '{{ __('admin.minutes_hint_project') }}',
        };
        const minLabels = {
            recurring: '{{ __('admin.estimated_minutes_per_unit') }}',
            project: '{{ __('admin.estimated_minutes_project') }}',
        };

        /* ── Type tabs ──────────────────────────────────────────── */
        function setType(prefix, type) {
            document.getElementById(prefix + '-service-type').value = type;

            ['recurring', 'project'].forEach(t => {
                const tab = document.getElementById(prefix + '-tab-' + t);
                tab.classList.toggle('active', t === type);
            });

            // update label + helper
            const helperEl = document.getElementById(prefix === 'c' ? 'c-min-helper' : 'e-min-helper');
            const labelEl = document.getElementById(prefix === 'c' ? 'c-min-label' : 'e-min-label');
            if (helperEl) helperEl.textContent = minHints[type];
            if (labelEl) labelEl.textContent = minLabels[type];
        }

        /* ── Create modal ───────────────────────────────────────── */
        function openModal() {
            document.getElementById('createModal').classList.add('open');
        }

        function closeModal() {
            document.getElementById('createModal').classList.remove('open');
        }

        /* ── Edit modal ─────────────────────────────────────────── */
        function closeEditModal() {
            document.getElementById('editModal').classList.remove('open');
        }

        function openEditModal(id, name, nameEn, color, icon, desc, serviceType, estMin) {
            document.getElementById('editForm').action = routes.update_base + '/' + id;
            document.getElementById('editName').value = name;
            document.getElementById('editNameEn').value = nameEn;
            document.getElementById('editColor').value = color;
            document.getElementById('editIcon').value = icon;
            document.getElementById('editDesc').value = desc;
            document.getElementById('editEstMin').value = estMin;

            // set type tabs
            setType('e', serviceType || 'recurring');

            document.getElementById('editModal').classList.add('open');
        }

        /* ── Close on backdrop click ────────────────────────────── */
        ['createModal', 'editModal'].forEach(id => {
            document.getElementById(id).addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('open');
            });
        });

        /* ── Re-open create modal on validation errors ──────────── */
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', () => openModal());
        @endif
    </script>
@endpush
