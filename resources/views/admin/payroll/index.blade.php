
@extends('layouts.admin')
@section('title', __('admin.nav_payroll'))
@push('styles')
<style>
.ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.ph h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.btn{display:inline-flex;align-items:center;gap:7px;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;border:none;white-space:nowrap}
.btn-primary{background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff;box-shadow:0 4px 14px rgba(108,99,255,.3)}.btn-primary:hover{opacity:.9;color:#fff}
.btn-ghost{background:var(--surface2);color:var(--text);border:1.5px solid var(--border)}.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.btn-sm{padding:6px 12px;font-size:12px;border-radius:8px}
.btn-success{background:rgba(67,233,123,.1);color:#43e97b;border:1.5px solid rgba(67,233,123,.2)}.btn-success:hover{background:rgba(67,233,123,.2)}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px}
@media(max-width:700px){.stats{grid-template-columns:1fr 1fr}}
.stat{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px 18px}
.stat-v{font-family:'Syne',sans-serif;font-size:26px;font-weight:800}
.stat-l{font-size:11px;color:var(--muted);margin-top:2px}
.tcard{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-bottom:16px}
.tcard-head{padding:14px 18px;border-bottom:1px solid var(--border);font-size:13px;font-weight:700;display:flex;align-items:center;gap:8px}
.at{width:100%;border-collapse:collapse;font-size:13px}
.at th{text-align:start;font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);font-weight:700;padding:10px 16px;border-bottom:1px solid var(--border);background:var(--surface2)}
.at td{padding:10px 16px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
.at tr:last-child td{border-bottom:none}
.at tr:hover td{background:rgba(255,255,255,.02)}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700}
.badge-draft{background:rgba(247,183,49,.12);color:#f7b731}
.badge-paid{background:rgba(67,233,123,.12);color:#43e97b}
.av{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,var(--accent),#8b7eff);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0}
.emp-cell{display:flex;align-items:center;gap:9px}
.fs{background:var(--surface2);border:1.5px solid var(--border);border-radius:9px;padding:8px 13px;color:var(--text);font-size:13px;font-family:inherit;outline:none}
.fs:focus{border-color:var(--accent)}
.missing-chip{display:flex;align-items:center;gap:7px;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:7px 12px;font-size:12px}
/* generate panel */
.gen-card{background:var(--surface);border:1px solid rgba(108,99,255,.25);border-radius:16px;padding:20px;margin-bottom:16px}
.gen-title{font-size:13px;font-weight:700;color:var(--accent);margin-bottom:14px;display:flex;align-items:center;gap:8px}
</style>
@endpush
@section('content')

<div class="ph">
    <div>
        <h1>{{ __('admin.nav_payroll') }}</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:3px">{{ __('admin.payroll_subtitle') }}</p>
    </div>
    <form method="GET" style="display:flex;gap:10px;align-items:center">
        <input type="month" name="month" class="fs" value="{{ $month }}" onchange="this.form.submit()">
    </form>
</div>

{{-- Stats --}}
<div class="stats">
    <div class="stat">
        <div class="stat-v" style="color:var(--accent)">{{ number_format($stats['total_net'],2) }}</div>
        <div class="stat-l">{{ __('admin.total_net_salary') }} (JD)</div>
    </div>
    <div class="stat">
        <div class="stat-v" style="color:#43e97b">{{ $stats['paid_count'] }}</div>
        <div class="stat-l">{{ __('admin.paid_payrolls') }}</div>
    </div>
    <div class="stat">
        <div class="stat-v" style="color:#f7b731">{{ $stats['draft_count'] }}</div>
        <div class="stat-l">{{ __('admin.draft_payrolls') }}</div>
    </div>
    <div class="stat">
        <div class="stat-v" style="color:var(--muted)">{{ $stats['total_count'] }}</div>
        <div class="stat-l">{{ __('admin.total_generated') }}</div>
    </div>
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

{{-- Generate panel --}}
@if($missingEmployees->count())
<div class="gen-card">
    <div class="gen-title"><i class="fas fa-magic"></i> {{ __('admin.generate_payrolls') }}</div>
    <p style="font-size:12px;color:var(--muted);margin-bottom:14px">
        {{ $missingEmployees->count() }} {{ __('admin.employees_without_payroll') }}
    </p>
    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px">
        @foreach($missingEmployees as $emp)
        <div class="missing-chip">
            <div class="av" style="width:22px;height:22px;font-size:9px">{{ strtoupper(mb_substr($emp->name,0,1)) }}</div>
            {{ $emp->name }}
        </div>
        @endforeach
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
        {{-- Generate all --}}
        <form method="POST" action="{{ route('admin.payroll.generateAll') }}">
            @csrf
            <input type="hidden" name="month" value="{{ $month }}">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-bolt"></i> {{ __('admin.generate_all') }}
            </button>
        </form>
        {{-- Generate one --}}
        <form method="POST" action="{{ route('admin.payroll.generate') }}" style="display:flex;gap:8px">
            @csrf
            <input type="hidden" name="month" value="{{ $month }}">
            <select name="employee_id" class="fs">
                <option value="">{{ __('admin.select_employee') }}</option>
                @foreach($missingEmployees as $emp)
                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-ghost">{{ __('admin.generate_one') }}</button>
        </form>
    </div>
</div>
@endif

{{-- Payrolls table --}}
<div class="tcard">
    @if($payrolls->count())
    <div style="overflow-x:auto">
        <table class="at">
            <thead>
                <tr>
                    <th>{{ __('admin.employee') }}</th>
                    <th>{{ __('admin.basic_salary') }}</th>
                    <th>{{ __('admin.commissions') }}</th>
                    <th>{{ __('admin.bonuses') }}</th>
                    <th>{{ __('admin.total_deductions') }}</th>
                    <th>{{ __('admin.net_salary') }}</th>
                    <th>{{ __('admin.status') }}</th>
                    <th>{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payrolls as $p)
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="av">{{ strtoupper(mb_substr($p->employee->name,0,1)) }}</div>
                            <div>
                                <div style="font-weight:600">{{ $p->employee->name }}</div>
                                <div style="font-size:10px;color:var(--muted)">
                                    {{ __('admin.absent') }}: {{ $p->absent_days }}d &nbsp;|&nbsp; {{ __('admin.late') }}: {{ $p->late_count }}x
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight:600">{{ number_format($p->basic_salary,2) }}</td>
                    <td style="color:#43e97b">
                        {{ $p->commissions_amount > 0 ? '+'.number_format($p->commissions_amount,2) : '—' }}
                    </td>
                    <td style="color:#00c6ff">
                        {{ $p->bonuses > 0 ? '+'.number_format($p->bonuses,2) : '—' }}
                    </td>
                    <td style="color:var(--accent2)">
                        {{ $p->total_deductions > 0 ? '-'.number_format($p->total_deductions,2) : '—' }}
                    </td>
                    <td>
                        <span style="font-family:'Syne',sans-serif;font-size:15px;font-weight:800;color:var(--accent)">
                            {{ number_format($p->net_salary,2) }}
                        </span>
                        <span style="font-size:11px;color:var(--muted)"> JD</span>
                    </td>
                    <td><span class="badge badge-{{ $p->status }}">{{ __('admin.'.$p->status) }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.payroll.show', $p) }}" class="btn btn-ghost btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($p->isDraft())
                            <a href="{{ route('admin.payroll.edit', $p) }}" class="btn btn-ghost btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.payroll.markPaid', $p) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> {{ __('admin.mark_paid') }}
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align:center;padding:52px 20px;color:var(--muted)">
        <i class="fas fa-file-invoice-dollar" style="font-size:38px;opacity:.2;display:block;margin-bottom:12px"></i>
        <p style="margin:0;font-size:13px">{{ __('admin.no_payrolls_generated') }}</p>
    </div>
    @endif
</div>
@endsection