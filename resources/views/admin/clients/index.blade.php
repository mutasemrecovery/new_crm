@extends('layouts.admin')
@section('title', __('admin.nav_clients'))
@section('page-title', __('admin.nav_clients'))
@push('styles')
    <style>
        .page-header {
            display: flex;
            align-items: flex-start;
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

        .page-header p {
            font-size: 13px;
            color: var(--muted);
            margin-top: 4px
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--accent), #8b7eff);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            font-family: inherit;
            transition: opacity .2s;
            box-shadow: 0 4px 14px rgba(108, 99, 255, .3);
            white-space: nowrap
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
            padding: 9px 16px;
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

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 14px;
            margin-bottom: 24px
        }

        .stat-mini {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 18px 20px
        }

        .stat-mini-val {
            font-family: 'Syne', sans-serif;
            font-size: 26px;
            font-weight: 800
        }

        .stat-mini-label {
            font-size: 12px;
            color: var(--muted);
            margin-top: 3px
        }

        .filters-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            align-items: center
        }

        .filter-select {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 9px 14px;
            color: var(--text);
            font-size: 13px;
            font-family: inherit;
            outline: none;
            cursor: pointer
        }

        .filter-select:focus {
            border-color: var(--accent)
        }

        .search-wrap {
            flex: 1;
            min-width: 200px;
            display: flex;
            align-items: center;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 0 14px;
            gap: 8px
        }

        .search-wrap input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            color: var(--text);
            font-size: 13px;
            padding: 9px 0;
            font-family: inherit
        }

        .search-wrap i {
            color: var(--muted);
            font-size: 13px
        }

        .table-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px
        }

        .admin-table th {
            text-align: start;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            font-weight: 600;
            padding: 13px 16px;
            border-bottom: 1px solid var(--border);
            background: var(--surface2);
            white-space: nowrap
        }

        .admin-table td {
            padding: 13px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, .04);
            vertical-align: middle
        }

        .admin-table tr:last-child td {
            border-bottom: none
        }

        .admin-table tr:hover td {
            background: rgba(255, 255, 255, .02)
        }

        .badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            white-space: nowrap
        }

        .badge-active {
            background: rgba(67, 233, 123, .12);
            color: #43e97b
        }

        .badge-pending {
            background: rgba(108, 99, 255, .12);
            color: var(--accent)
        }

        .badge-paused {
            background: rgba(247, 183, 49, .12);
            color: #f7b731
        }

        .badge-closed {
            background: rgba(255, 101, 132, .12);
            color: var(--accent2)
        }

        .badge-high {
            background: rgba(255, 101, 132, .12);
            color: var(--accent2)
        }

        .badge-medium {
            background: rgba(247, 183, 49, .12);
            color: #f7b731
        }

        .badge-low {
            background: rgba(67, 233, 123, .12);
            color: #43e97b
        }

        .client-avatar {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0
        }

        .client-info {
            display: flex;
            align-items: center;
            gap: 10px
        }

        .client-name {
            font-weight: 600
        }

        .client-sub {
            font-size: 11px;
            color: var(--muted);
            margin-top: 1px
        }

        .service-tag {
            display: inline-block;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 5px;
            padding: 1px 7px;
            font-size: 11px;
            color: var(--muted);
            margin: 1px
        }

        .action-btn {
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

        .action-btn:hover {
            background: var(--surface2);
            color: var(--text)
        }

        .action-btn.del:hover {
            background: rgba(255, 101, 132, .1);
            color: var(--accent2)
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted)
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 14px;
            opacity: .25;
            display: block
        }
    </style>
@endpush
@section('content')
    <div class="page-header">
        <div>
            <h1>{{ __('admin.nav_clients') }}</h1>
            <p>{{ __('admin.clients_subtitle') }}</p>
        </div>
        <a href="{{ route('admin.clients.create') }}" class="btn-primary"><i class="fas fa-plus"></i>
            {{ __('admin.add_client') }}</a>
    </div>
    <div class="stats-row">
        <div class="stat-mini">
            <div class="stat-mini-val">{{ $stats['total'] }}</div>
            <div class="stat-mini-label">{{ __('admin.total_clients') }}</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-val" style="color:#43e97b">{{ $stats['active'] }}</div>
            <div class="stat-mini-label">{{ __('admin.active') }}</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-val" style="color:var(--accent)">{{ $stats['pending'] }}</div>
            <div class="stat-mini-label">{{ __('admin.pending') }}</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-val" style="color:var(--accent2)">{{ $stats['closed'] }}</div>
            <div class="stat-mini-label">{{ __('admin.closed') }}</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-val" style="color:#f7b731">${{ number_format($stats['revenue'], 0) }}</div>
            <div class="stat-mini-label">{{ __('admin.monthly_revenue') }}</div>
        </div>
    </div>
    <form method="GET">
        <div class="filters-bar">
            <div class="search-wrap"><i class="fas fa-search"></i><input type="text" name="search"
                    value="{{ request('search') }}" placeholder="{{ __('admin.search_clients') }}"></div>
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">{{ __('admin.all_statuses') }}</option>
                @foreach (['active', 'pending', 'paused', 'closed'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ __('admin.' . $s) }}
                    </option>
                @endforeach
            </select>
            <select name="priority" class="filter-select" onchange="this.form.submit()">
                <option value="">{{ __('admin.all_priorities') }}</option>
                @foreach (['high', 'medium', 'low'] as $p)
                    <option value="{{ $p }}" {{ request('priority') == $p ? 'selected' : '' }}>{{ __('admin.' . $p) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary" style="padding:9px 16px"><i class="fas fa-search"></i></button>
            @if (request()->hasAny(['search', 'status', 'priority']))
                <a href="{{ route('admin.clients.index') }}" class="btn-secondary" style="padding:9px 14px"><i
                        class="fas fa-times"></i></a>
            @endif
        </div>
    </form>
    <div class="table-card">
        @if ($clients->count())
            <div style="overflow-x:auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>{{ __('admin.client') }}</th>
                            <th>{{ __('admin.contact') }}</th>
                            <th>{{ __('admin.monthly_value') }}</th>
                            <th>{{ __('admin.status') }}</th>
                            <th>{{ __('admin.priority') }}</th>
                            <th>{{ __('admin.contract_end') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            <tr>
                                <td>
                                    <div class="client-info">
                                        <div class="client-avatar">{{ strtoupper(mb_substr($client->name, 0, 1)) }}</div>
                                        <div>
                                            <div class="client-name">{{ $client->name }}</div>
                                            @if ($client->industry)
                                                <div class="client-sub">{{ $client->industry }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $client->contact_person ?? '—' }}</div>
                                    @if ($client->phone)
                                        <div style="font-size:11px;color:var(--muted)">{{ $client->phone }}</div>
                                    @endif
                                </td>
                                
                                <td><span
                                        style="font-family:'Syne',sans-serif;font-weight:700">${{ number_format($client->monthly_value, 0) }}</span>
                                </td>
                                <td><span
                                        class="badge badge-{{ $client->status }}">{{ __('admin.' . $client->status) }}</span>
                                </td>
                                <td><span
                                        class="badge badge-{{ $client->priority }}">{{ __('admin.' . $client->priority) }}</span>
                                </td>
                                <td style="font-size:12px">
                                    @if ($client->contract_end)
                                        @php $diff = now()->diffInDays($client->contract_end, false) @endphp
                                        <span
                                            style="color:{{ $diff < 30 && $diff >= 0 ? '#f7b731' : ($diff < 0 ? 'var(--accent2)' : 'var(--muted)') }}">
                                            {{ $client->contract_end->format('Y-m-d') }}{{ $diff < 0 ? ' ⚠' : '' }}
                                        </span>
                                    @else
                                        <span style="color:var(--muted)">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex;gap:4px">
                                        <a href="{{ route('admin.clients.show', $client) }}" class="action-btn"
                                            title="{{ __('admin.view') }}"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.clients.edit', $client) }}" class="action-btn"
                                            title="{{ __('admin.edit') }}"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('admin.clients.destroy', $client) }}" method="POST"
                                            id="del-c-{{ $client->id }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="action-btn del"
                                                onclick="confirmDelete('del-c-{{ $client->id }}','{{ addslashes(__('admin.confirm_delete')) }}')"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($clients->hasPages())
                <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;justify-content:flex-end">
                    {{ $clients->links() }}</div>
            @endif
        @else
            <div class="empty-state"><i class="fas fa-handshake"></i>
                <p>{{ __('admin.no_clients_yet') }}</p>
                <a href="{{ route('admin.clients.create') }}" class="btn-primary"
                    style="margin-top:16px;display:inline-flex"><i class="fas fa-plus"></i>
                    {{ __('admin.add_client') }}</a>
            </div>
        @endif
    </div>
@endsection
