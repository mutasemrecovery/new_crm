@extends('layouts.admin')
@section('title', isset($client) ? __('admin.edit_client') : __('admin.add_client'))
@section('page-title', isset($client) ? __('admin.edit_client') : __('admin.add_client'))

@push('styles')
    <style>
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 26px;
            gap: 16px;
            flex-wrap: wrap
        }

        .page-header h1 {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--accent), #8b7eff);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 11px 24px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            font-family: inherit;
            transition: opacity .2s;
            box-shadow: 0 4px 14px rgba(108, 99, 255, .3)
        }

        .btn-primary:hover {
            opacity: .9;
            color: #fff
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--surface2);
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
            font-family: inherit;
            cursor: pointer
        }

        .btn-secondary:hover {
            border-color: var(--accent);
            color: var(--accent)
        }

        .form-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 26px;
            margin-bottom: 20px
        }

        .form-card-title {
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 9px
        }

        .form-card-title i {
            color: var(--accent)
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px
        }

        @media(max-width:700px) {
            .form-grid {
                grid-template-columns: 1fr
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px
        }

        .form-group.full {
            grid-column: 1/-1
        }

        .form-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .6px
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
            transition: border-color .2s, box-shadow .2s;
            width: 100%
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(108, 99, 255, .12)
        }

        .form-control::placeholder {
            color: var(--muted)
        }

        .form-control.is-invalid {
            border-color: var(--accent2)
        }

        .field-error {
            font-size: 11px;
            color: var(--accent2)
        }

        /* ── Service Picker ─────────────────────────────────────── */
        .service-picker-row {
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 10px;
            transition: border-color .2s, background .2s
        }

        .service-picker-row.checked {
            border-color: var(--accent);
            background: rgba(108, 99, 255, .06)
        }

        .service-header {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none
        }

        .service-header input[type=checkbox] {
            width: 16px;
            height: 16px;
            accent-color: var(--accent);
            cursor: pointer;
            flex-shrink: 0
        }

        .service-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0
        }

        .service-label-name {
            font-size: 13px;
            font-weight: 600;
            flex: 1
        }

        /* type badge */
        .svc-type-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            flex-shrink: 0
        }

        .svc-type-badge.recurring {
            background: rgba(0, 198, 255, .12);
            color: #00c6ff
        }

        .svc-type-badge.project {
            background: rgba(247, 183, 49, .12);
            color: #f7b731
        }

        /* time hint next to badge */
        .svc-time-hint {
            font-size: 11px;
            color: var(--muted);
            flex-shrink: 0
        }

        /* expanded fields */
        .service-extra {
            display: none;
            margin-top: 14px
        }

        .service-extra.open {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px
        }

        @media(max-width:600px) {
            .service-extra.open {
                grid-template-columns: 1fr
            }
        }

        /* workload summary pill */
        .workload-pill {
            margin-top: 10px;
            padding: 8px 12px;
            background: rgba(108, 99, 255, .08);
            border: 1px solid rgba(108, 99, 255, .2);
            border-radius: 8px;
            font-size: 12px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap
        }

        .workload-pill strong {
            color: var(--text)
        }

        /* weekly distribute toggle */
        .toggle-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-top: 6px
        }

        .mini-toggle {
            position: relative;
            width: 32px;
            height: 18px;
            display: inline-block;
            flex-shrink: 0
        }

        .mini-toggle input {
            opacity: 0;
            width: 0;
            height: 0
        }

        .mini-slider {
            position: absolute;
            inset: 0;
            background: var(--surface2);
            border-radius: 99px;
            transition: .25s;
            cursor: pointer;
            border: 1px solid var(--border)
        }

        .mini-slider:before {
            content: '';
            position: absolute;
            width: 11px;
            height: 11px;
            left: 2px;
            bottom: 2px;
            background: #fff;
            border-radius: 50%;
            transition: .25s
        }

        .mini-toggle input:checked+.mini-slider {
            background: var(--accent);
            border-color: var(--accent)
        }

        .mini-toggle input:checked+.mini-slider:before {
            transform: translateX(14px)
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <h1>{{ isset($client) ? __('admin.edit_client') : __('admin.add_client') }}</h1>
        <a href="{{ route('admin.clients.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i> {{ __('admin.back') }}
        </a>
    </div>

    <form method="POST" action="{{ isset($client) ? route('admin.clients.update', $client) : route('admin.clients.store') }}">
        @csrf
        @if (isset($client))
            @method('PUT')
        @endif

        {{-- Basic Info --}}
        <div class="form-card">
            <div class="form-card-title"><i class="fas fa-building"></i> {{ __('admin.basic_info') }}</div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">{{ __('admin.client_name') }} *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $client->name ?? '') }}" required>
                    @error('name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('admin.contact_person') }}</label>
                    <input type="text" name="contact_person" class="form-control"
                        value="{{ old('contact_person', $client->contact_person ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('admin.phone') }}</label>
                    <input type="text" name="phone" class="form-control" dir="ltr"
                        value="{{ old('phone', $client->phone ?? '') }}" placeholder="+9665xxxxxxxx">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('admin.whatsapp') }}</label>
                    <input type="text" name="whatsapp" class="form-control" dir="ltr"
                        value="{{ old('whatsapp', $client->whatsapp ?? '') }}" placeholder="+9665xxxxxxxx">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('admin.email') }}</label>
                    <input type="email" name="email" class="form-control" dir="ltr"
                        value="{{ old('email', $client->email ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('admin.industry') }}</label>
                    <input type="text" name="industry" class="form-control"
                        value="{{ old('industry', $client->industry ?? '') }}">
                </div>
                <div class="form-group full">
                    <label class="form-label">{{ __('admin.address') }}</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $client->address ?? '') }}</textarea>
                </div>
                <div class="form-group full">
                    <label class="form-label">{{ __('admin.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $client->notes ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Contract & Status --}}
        <div class="form-card">
            <div class="form-card-title"><i class="fas fa-file-contract"></i> {{ __('admin.contract_details') }}</div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">{{ __('admin.status') }} *</label>
                    <select name="status" class="form-control" required>
                        @foreach (['active', 'pending', 'paused', 'closed'] as $s)
                            <option value="{{ $s }}"
                                {{ old('status', $client->status ?? 'active') === $s ? 'selected' : '' }}>
                                {{ __('admin.' . $s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('admin.priority') }} *</label>
                    <select name="priority" class="form-control" required>
                        @foreach (['high', 'medium', 'low'] as $p)
                            <option value="{{ $p }}"
                                {{ old('priority', $client->priority ?? 'medium') === $p ? 'selected' : '' }}>
                                {{ __('admin.' . $p) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('admin.contract_start') }}</label>
                    <input type="date" name="contract_start" class="form-control"
                        value="{{ old('contract_start', isset($client->contract_start) ? $client->contract_start->format('Y-m-d') : '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('admin.contract_end') }}</label>
                    <input type="date" name="contract_end" class="form-control"
                        value="{{ old('contract_end', isset($client->contract_end) ? $client->contract_end->format('Y-m-d') : '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('admin.monthly_value') }} ($)</label>
                    <input type="number" name="monthly_value" class="form-control" step="0.01" min="0"
                        value="{{ old('monthly_value', $client->monthly_value ?? 0) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('admin.assigned_sales') }}</label>
                    <select name="assigned_sales_id" class="form-control">
                        <option value="">— {{ __('admin.none') }} —</option>
                        @foreach ($salesEmployees as $emp)
                            <option value="{{ $emp->id }}"
                                {{ old('assigned_sales_id', $client->assigned_sales_id ?? '') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Services --}}
        <div class="form-card">
            <div class="form-card-title">
                <i class="fas fa-layer-group"></i> {{ __('admin.services') }}
                <span style="font-size:12px;color:var(--muted);font-weight:400;margin-inline-start:4px">
                    ({{ __('admin.select_services_hint') }})
                </span>
            </div>

            @forelse($services as $service)
                @php
                    $isProject = $service->service_type === 'project';
                    $checked = in_array($service->id, old('services', $clientServiceIds ?? []));
                    $pivot = isset($client) ? $client->clientServices->firstWhere('service_id', $service->id) : null;

                    // values — old() first, then pivot, then defaults
                    $price = old("service_prices.{$service->id}", $pivot->price ?? '');
                    $status = old("service_statuses.{$service->id}", $pivot->status ?? 'active');
                    $start = old(
                        "service_starts.{$service->id}",
                        isset($pivot->start_date) ? $pivot->start_date->format('Y-m-d') : '',
                    );
                    $details = old("service_details.{$service->id}", $pivot->details ?? '');
                    $qty = (int) old("service_quantities.{$service->id}", $pivot->monthly_quantity ?? 1);
                    $distrib = old("service_distribute.{$service->id}", $pivot->distribute_weekly ?? 1);

                    // computed hints
                    $estMin = $service->estimated_minutes_per_unit ?? 0;
                    $totalMin = $qty * $estMin;
                    $weeklyQty = $isProject ? 0 : round($qty / 4, 1);
                    $weeklyMin = $isProject ? $estMin : round($totalMin / 4);
                    $weeklyHrs = round($weeklyMin / 60, 1);
                @endphp

                <div class="service-picker-row {{ $checked ? 'checked' : '' }}" id="srow-{{ $service->id }}">

                    {{-- Header row --}}
                    <label class="service-header" onclick="toggleService({{ $service->id }})">
                        <input type="checkbox" name="services[]" value="{{ $service->id }}"
                            id="svc-{{ $service->id }}" {{ $checked ? 'checked' : '' }}
                            onclick="event.stopPropagation();toggleService({{ $service->id }})">
                        <div class="service-dot" style="background:{{ $service->color }}"></div>
                        <span class="service-label-name">
                            {{ app()->getLocale() === 'ar' ? $service->name : $service->name_en ?? $service->name }}
                        </span>
                        <span style="font-size:15px;line-height:1">{{ $service->icon }}</span>
                        <span class="svc-type-badge {{ $isProject ? 'project' : 'recurring' }}">
                            {{ $isProject ? __('admin.project') : __('admin.recurring') }}
                        </span>
                        @if ($estMin)
                            <span class="svc-time-hint">
                                ⏱ {{ $estMin }}
                                {{ __('admin.min') }}{{ $isProject ? '' : '/' . strtolower(__('admin.unit')) }}
                            </span>
                        @endif
                    </label>

                    {{-- Expanded fields --}}
                    <div class="service-extra {{ $checked ? 'open' : '' }}" id="sextra-{{ $service->id }}">

                        {{-- Price --}}
                        <div class="form-group">
                            <label class="form-label">{{ __('admin.monthly_price') }} ($)</label>
                            <input type="number" name="service_prices[{{ $service->id }}]" class="form-control"
                                step="0.01" min="0" value="{{ $price }}" placeholder="0.00">
                        </div>

                        {{-- Quantity — hidden & locked to 1 for projects --}}
                        <div class="form-group" id="qty-group-{{ $service->id }}"
                            style="{{ $isProject ? 'display:none' : '' }}">
                            <label class="form-label">
                                {{ __('admin.monthly_quantity') }}
                                <span id="weekly-hint-{{ $service->id }}"
                                    style="font-size:10px;color:var(--accent);font-weight:600;margin-inline-start:4px">
                                    {{ $checked && !$isProject && $qty > 0 ? '(' . $weeklyQty . ' / ' . __('admin.week') . ')' : '' }}
                                </span>
                            </label>
                            <input type="number" name="service_quantities[{{ $service->id }}]"
                                id="qty-{{ $service->id }}" class="form-control" value="{{ $isProject ? 1 : $qty }}"
                                min="1" {{ $isProject ? 'readonly' : '' }}
                                oninput="recalc({{ $service->id }}, {{ $estMin }}, {{ $isProject ? 'true' : 'false' }})">
                        </div>

                        {{-- Hidden qty=1 for projects --}}
                        @if ($isProject)
                            <input type="hidden" name="service_quantities[{{ $service->id }}]" value="1">
                        @endif

                        {{-- Status --}}
                        <div class="form-group">
                            <label class="form-label">{{ __('admin.service_status') }}</label>
                            <select name="service_statuses[{{ $service->id }}]" class="form-control">
                                @foreach (['active', 'paused', 'completed'] as $st)
                                    <option value="{{ $st }}" {{ $status === $st ? 'selected' : '' }}>
                                        {{ __('admin.' . $st) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Start date --}}
                        <div class="form-group">
                            <label class="form-label">{{ __('admin.start_date') }}</label>
                            <input type="date" name="service_starts[{{ $service->id }}]" class="form-control"
                                value="{{ $start }}">
                        </div>

                        {{-- Details --}}
                        <div class="form-group" style="grid-column:1/-1">
                            <label class="form-label">{{ __('admin.details') }}</label>
                            <input type="text" name="service_details[{{ $service->id }}]" class="form-control"
                                value="{{ $details }}" placeholder="{{ __('admin.service_details_hint') }}">
                        </div>

                        {{-- Weekly distribute toggle — recurring only --}}
                        @if (!$isProject)
                            <div style="grid-column:1/-1">
                                <div class="toggle-row">
                                    <label class="mini-toggle">
                                        <input type="checkbox" name="service_distribute[{{ $service->id }}]"
                                            id="dist-{{ $service->id }}" value="1" {{ $distrib ? 'checked' : '' }}
                                            onchange="recalc({{ $service->id }}, {{ $estMin }}, false)">
                                        <span class="mini-slider"></span>
                                    </label>
                                    <span
                                        style="font-size:12px;color:var(--muted)">{{ __('admin.distribute_weekly') }}</span>
                                </div>
                            </div>
                        @else
                            {{-- project: hidden distribute = 0 --}}
                            <input type="hidden" name="service_distribute[{{ $service->id }}]" value="0">
                        @endif

                        {{-- Workload summary pill --}}
                        <div class="workload-pill" id="pill-{{ $service->id }}"
                            style="grid-column:1/-1;{{ !$checked || !$estMin ? 'display:none' : '' }}">
                            @if ($isProject)
                                <i class="fas fa-clock" style="color:var(--accent)"></i>
                                <span>{{ __('admin.total_project_time') }}:
                                    <strong>{{ $estMin }} {{ __('admin.min') }}</strong>
                                    ({{ round($estMin / 60, 1) }} {{ __('admin.hrs') }})
                                </span>
                            @else
                                <i class="fas fa-calendar-week" style="color:var(--accent)"></i>
                                <span id="pill-text-{{ $service->id }}">
                                    <strong>{{ $qty }}×</strong> × {{ $estMin }}{{ __('admin.min') }}
                                    = <strong>{{ $totalMin }}
                                        {{ __('admin.min') }}/{{ __('admin.month') }}</strong>
                                    &nbsp;|&nbsp; {{ __('admin.weekly') }}: <strong>{{ $weeklyQty }}
                                        {{ __('admin.units') }}</strong>
                                    ({{ $weeklyHrs }} {{ __('admin.hrs') }})
                                </span>
                            @endif
                        </div>

                    </div>{{-- /.service-extra --}}
                </div>{{-- /.service-picker-row --}}
            @empty
                <p style="color:var(--muted);font-size:13px">
                    {{ __('admin.no_services') }}
                    <a href="{{ route('admin.services.index') }}"
                        style="color:var(--accent)">{{ __('admin.add_services') }}</a>
                </p>
            @endforelse
        </div>

        <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:4px">
            <a href="{{ route('admin.clients.index') }}" class="btn-secondary">{{ __('admin.cancel') }}</a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                {{ isset($client) ? __('admin.update') : __('admin.create') }}
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        /* ── Toggle service row open/close ──────────────────────── */
        function toggleService(id) {
            const cb = document.getElementById('svc-' + id);
            const extra = document.getElementById('sextra-' + id);
            const row = document.getElementById('srow-' + id);
            extra.classList.toggle('open', cb.checked);
            row.classList.toggle('checked', cb.checked);
        }

        /* ── Recalculate workload pill ──────────────────────────── */
        function recalc(id, estMin, isProject) {
            if (isProject || !estMin) return;

            const qtyInput = document.getElementById('qty-' + id);
            const distToggle = document.getElementById('dist-' + id);
            const hintEl = document.getElementById('weekly-hint-' + id);
            const pillEl = document.getElementById('pill-' + id);
            const pillText = document.getElementById('pill-text-' + id);

            const qty = parseInt(qtyInput?.value) || 0;
            const distrib = distToggle?.checked ?? true;
            const totalMin = qty * estMin;
            const weeklyQty = distrib ? (qty / 4).toFixed(1) : '—';
            const weeklyMin = distrib ? Math.round(totalMin / 4) : 0;
            const weeklyHrs = distrib ? (weeklyMin / 60).toFixed(1) : '—';

            // update inline weekly hint
            if (hintEl) {
                hintEl.textContent = distrib && qty > 0 ?
                    `(${weeklyQty} / {{ __('admin.week') }})` :
                    '';
            }

            // update pill
            if (pillEl && pillText) {
                pillEl.style.display = qty > 0 ? '' : 'none';
                pillText.innerHTML =
                    `<strong>${qty}×</strong> × ${estMin}{{ __('admin.min') }} = ` +
                    `<strong>${totalMin} {{ __('admin.min') }}/{{ __('admin.month') }}</strong>` +
                    ` &nbsp;|&nbsp; {{ __('admin.weekly') }}: <strong>${weeklyQty} {{ __('admin.units') }}</strong>` +
                    ` (${weeklyHrs} {{ __('admin.hrs') }})`;
            }
        }

        /* ── Init on page load ──────────────────────────────────── */
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('input[name="services[]"]').forEach(cb => {
                const id = cb.value;
                document.getElementById('sextra-' + id)?.classList.toggle('open', cb.checked);
                document.getElementById('srow-' + id)?.classList.toggle('checked', cb.checked);
            });
        });
    </script>
@endpush
