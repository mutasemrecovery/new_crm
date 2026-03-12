{{-- ══════════════════════════════════════════════════
     employee/leaves/index.blade.php
══════════════════════════════════════════════════ --}}
@extends('layouts.employee')
@section('title', __('emp.nav_leaves'))
@push('styles')
<style>
.ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.ph h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.btn{display:inline-flex;align-items:center;gap:7px;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;border:none}
.btn-primary{background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff;box-shadow:0 4px 14px rgba(108,99,255,.3)}.btn-primary:hover{opacity:.9;color:#fff}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px}
@media(max-width:600px){.stats{grid-template-columns:1fr 1fr}}
.stat{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:14px 16px}
.stat-v{font-family:'Syne',sans-serif;font-size:26px;font-weight:800}
.stat-l{font-size:11px;color:var(--muted);margin-top:2px}
.balance-card{background:var(--surface);border:1px solid rgba(108,99,255,.2);border-radius:16px;padding:18px 20px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}
.balance-bar{height:8px;border-radius:4px;background:var(--surface2);flex:1;min-width:120px;overflow:hidden}
.balance-fill{height:100%;border-radius:4px;background:linear-gradient(90deg,var(--accent),#8b7eff)}
.tcard{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden}
.at{width:100%;border-collapse:collapse;font-size:13px}
.at th{text-align:start;font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);font-weight:700;padding:11px 16px;border-bottom:1px solid var(--border);background:var(--surface2)}
.at td{padding:11px 16px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
.at tr:last-child td{border-bottom:none}
.at tr:hover td{background:rgba(255,255,255,.02)}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700}
.badge-pending{background:rgba(247,183,49,.12);color:#f7b731}
.badge-approved{background:rgba(67,233,123,.12);color:#43e97b}
.badge-rejected{background:rgba(255,101,132,.12);color:var(--accent2)}
.badge-annual{background:rgba(108,99,255,.12);color:var(--accent)}
.badge-sick{background:rgba(0,198,255,.12);color:#00c6ff}
.badge-emergency{background:rgba(255,101,132,.12);color:var(--accent2)}
.badge-unpaid{background:rgba(107,114,128,.12);color:var(--muted)}
.badge-other{background:rgba(247,183,49,.12);color:#f7b731}
.del-btn{background:none;border:none;color:var(--muted);cursor:pointer;padding:5px 8px;border-radius:6px;font-size:12px;transition:all .15s}
.del-btn:hover{background:rgba(255,101,132,.1);color:var(--accent2)}
</style>
@endpush
@section('content')

<div class="ph">
    <div>
        <h1>{{ __('emp.nav_leaves') }}</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:3px">{{ __('emp.leaves_subtitle') }}</p>
    </div>
    <a href="{{ route('employee.leaves.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> {{ __('emp.request_leave') }}
    </a>
</div>

@if(session('success'))
<div style="background:rgba(67,233,123,.08);border:1px solid rgba(67,233,123,.2);border-radius:12px;padding:12px 16px;margin-bottom:18px;color:#43e97b;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:rgba(255,101,132,.08);border:1px solid rgba(255,101,132,.2);border-radius:12px;padding:12px 16px;margin-bottom:18px;color:var(--accent2);font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

{{-- Annual balance bar --}}
<div class="balance-card">
    <div>
        <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px">
            {{ __('emp.annual_balance') }} {{ now()->year }}
        </div>
        <div style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:var(--accent)">
            {{ $balance->remaining }} <span style="font-size:13px;color:var(--muted);font-weight:400">/ {{ $balance->annual_balance }} {{ __('emp.days') }}</span>
        </div>
    </div>
    <div style="flex:1;min-width:140px">
        <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--muted);margin-bottom:5px">
            <span>{{ __('emp.used') }}: {{ $balance->used_days }}</span>
            <span>{{ __('emp.remaining') }}: {{ $balance->remaining }}</span>
        </div>
        <div class="balance-bar">
            <div class="balance-fill" style="width:{{ $balance->annual_balance > 0 ? min(100,($balance->used_days/$balance->annual_balance)*100) : 0 }}%"></div>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="stats">
    <div class="stat">
        <div class="stat-v" style="color:#f7b731">{{ $stats['pending'] }}</div>
        <div class="stat-l">{{ __('emp.pending') }}</div>
    </div>
    <div class="stat">
        <div class="stat-v" style="color:#43e97b">{{ $stats['approved'] }}</div>
        <div class="stat-l">{{ __('emp.approved') }}</div>
    </div>
    <div class="stat">
        <div class="stat-v" style="color:var(--accent)">{{ $stats['remaining'] }}</div>
        <div class="stat-l">{{ __('emp.remaining_days') }}</div>
    </div>
    <div class="stat">
        <div class="stat-v" style="color:var(--muted)">{{ $stats['used'] }}</div>
        <div class="stat-l">{{ __('emp.used_days') }}</div>
    </div>
</div>

{{-- Leaves table --}}
<div class="tcard">
    @if($leaves->count())
    <div style="overflow-x:auto">
        <table class="at">
            <thead>
                <tr>
                    <th>{{ __('emp.leave_type') }}</th>
                    <th>{{ __('emp.start_date') }}</th>
                    <th>{{ __('emp.end_date') }}</th>
                    <th>{{ __('emp.days_count') }}</th>
                    <th>{{ __('emp.status') }}</th>
                    <th>{{ __('emp.admin_note') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaves as $leave)
                <tr>
                    <td><span class="badge badge-{{ $leave->type }}">{{ __('emp.leave_'.$leave->type) }}</span></td>
                    <td style="font-size:12px">{{ $leave->start_date->translatedFormat('d M Y') }}</td>
                    <td style="font-size:12px">{{ $leave->end_date->translatedFormat('d M Y') }}</td>
                    <td>
                        <span style="font-family:'Syne',sans-serif;font-weight:800;color:var(--accent)">{{ $leave->days_count }}</span>
                        <span style="font-size:11px;color:var(--muted)"> {{ __('emp.days') }}</span>
                    </td>
                    <td><span class="badge badge-{{ $leave->status }}">{{ __('emp.'.$leave->status) }}</span></td>
                    <td style="font-size:12px;color:var(--muted);max-width:180px">
                        {{ $leave->admin_note ? Str::limit($leave->admin_note, 60) : '—' }}
                    </td>
                    <td>
                        @if($leave->isPending())
                        <form method="POST" action="{{ route('employee.leaves.destroy', $leave) }}"
                              onsubmit="return confirm('{{ __('emp.confirm_cancel_leave') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="del-btn" title="{{ __('emp.cancel_request') }}">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:14px 16px">{{ $leaves->links() }}</div>
    @else
    <div style="text-align:center;padding:52px 20px;color:var(--muted)">
        <i class="fas fa-calendar-times" style="font-size:38px;opacity:.2;display:block;margin-bottom:12px"></i>
        <p style="margin:0;font-size:13px">{{ __('emp.no_leaves_yet') }}</p>
    </div>
    @endif
</div>
@endsection