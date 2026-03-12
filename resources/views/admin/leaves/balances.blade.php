@extends('layouts.admin')
@section('title', __('admin.leave_balances'))
@push('styles')
<style>
.ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.ph h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.btn{display:inline-flex;align-items:center;gap:7px;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;border:none}
.btn-ghost{background:var(--surface2);color:var(--text);border:1.5px solid var(--border)}.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.tcard{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden}
.at{width:100%;border-collapse:collapse;font-size:13px}
.at th{text-align:start;font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);font-weight:700;padding:11px 16px;border-bottom:1px solid var(--border);background:var(--surface2);white-space:nowrap}
.at td{padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
.at tr:last-child td{border-bottom:none}
.at tr:hover td{background:rgba(255,255,255,.02)}
.av{width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,var(--accent),#8b7eff);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#fff;flex-shrink:0}
.emp-cell{display:flex;align-items:center;gap:9px}
.balance-bar{height:6px;border-radius:3px;background:var(--surface2);overflow:hidden;width:120px}
.balance-fill{height:100%;border-radius:3px;transition:width .6s}
.fin-sm{background:var(--surface2);border:1.5px solid var(--border);border-radius:8px;padding:6px 10px;font-size:13px;color:var(--text);font-family:inherit;outline:none;width:70px;text-align:center}
.fin-sm:focus{border-color:var(--accent)}
.fs{background:var(--surface2);border:1.5px solid var(--border);border-radius:9px;padding:8px 13px;color:var(--text);font-size:13px;font-family:inherit;outline:none}
.fs:focus{border-color:var(--accent)}
.save-btn{background:var(--accent);color:#fff;border:none;border-radius:7px;padding:6px 12px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit}
.save-btn:hover{opacity:.85}
</style>
@endpush
@section('content')

<div class="ph">
    <div>
        <h1>{{ __('admin.leave_balances') }}</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:3px">{{ __('admin.leave_balances_subtitle') }}</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center">
        <form method="GET">
            <select name="year" class="fs" onchange="this.form.submit()">
                @foreach([now()->year+1, now()->year, now()->year-1] as $y)
                <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.leaves.index') }}" class="btn btn-ghost">
            <i class="fas fa-arrow-right"></i> {{ __('admin.back') }}
        </a>
    </div>
</div>

@if(session('success'))
<div style="background:rgba(67,233,123,.08);border:1px solid rgba(67,233,123,.2);border-radius:12px;padding:12px 16px;margin-bottom:18px;color:#43e97b;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="tcard">
    <div style="overflow-x:auto">
        <table class="at">
            <thead>
                <tr>
                    <th>{{ __('admin.employee') }}</th>
                    <th>{{ __('admin.annual_balance') }}</th>
                    <th>{{ __('admin.used_days') }}</th>
                    <th>{{ __('admin.remaining_days') }}</th>
                    <th>{{ __('admin.progress') }}</th>
                    <th>{{ __('admin.update_balance') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $emp)
                @php $bal = $emp->balance; $pct = $bal->annual_balance > 0 ? ($bal->used_days/$bal->annual_balance)*100 : 0; @endphp
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="av">{{ strtoupper(mb_substr($emp->name,0,1)) }}</div>
                            <div>
                                <div style="font-weight:600">{{ $emp->name }}</div>
                                <div style="font-size:11px;color:var(--muted)">{{ $emp->job_title }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight:700;font-size:15px;color:var(--accent)">{{ $bal->annual_balance }} {{ __('admin.days') }}</td>
                    <td style="font-weight:600;color:#f7b731">{{ $bal->used_days }}</td>
                    <td>
                        <span style="font-weight:700;color:{{ $bal->remaining > 5 ? '#43e97b' : 'var(--accent2)' }}">
                            {{ $bal->remaining }}
                        </span>
                    </td>
                    <td>
                        <div class="balance-bar">
                            <div class="balance-fill" style="width:{{ min(100,$pct) }}%;background:{{ $pct >= 90 ? 'var(--accent2)' : ($pct >= 60 ? '#f7b731' : 'var(--accent)') }}"></div>
                        </div>
                        <div style="font-size:10px;color:var(--muted);margin-top:3px">{{ round($pct) }}%</div>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.leaves.balance.update', $emp) }}" style="display:flex;align-items:center;gap:8px">
                            @csrf @method('PATCH')
                            <input type="hidden" name="year" value="{{ $year }}">
                            <input type="number" name="annual_balance" class="fin-sm"
                                   value="{{ $bal->annual_balance }}" min="0" max="365">
                            <button type="submit" class="save-btn">{{ __('admin.save') }}</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection