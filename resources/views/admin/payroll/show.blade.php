{{-- ══════════════════════════════════════════════════
     admin/payroll/show.blade.php  — سليب الراتب
══════════════════════════════════════════════════ --}}
@extends('layouts.admin')
@section('title', __('admin.salary_slip'))
@push('styles')
<style>
.ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.btn{display:inline-flex;align-items:center;gap:7px;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;border:none}
.btn-ghost{background:var(--surface2);color:var(--text);border:1.5px solid var(--border)}.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.btn-success{background:rgba(67,233,123,.1);color:#43e97b;border:1.5px solid rgba(67,233,123,.2)}.btn-success:hover{background:rgba(67,233,123,.2)}
.btn-primary{background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff}

/* Slip card */
.slip{background:var(--surface);border:1px solid var(--border);border-radius:20px;overflow:hidden;max-width:760px;margin:0 auto}
.slip-header{background:linear-gradient(135deg,var(--accent) 0%,#8b7eff 100%);padding:28px 32px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px}
.slip-company{font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:#fff}
.slip-subtitle{font-size:12px;color:rgba(255,255,255,.7);margin-top:2px}
.slip-month{font-family:'Syne',sans-serif;font-size:28px;font-weight:800;color:#fff;text-align:end}
.slip-month-sub{font-size:11px;color:rgba(255,255,255,.7);text-align:end;margin-top:2px}

.slip-emp{display:flex;align-items:center;gap:16px;padding:20px 28px;border-bottom:1px solid var(--border);background:var(--surface2)}
.slip-av{width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,var(--accent),#8b7eff);display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:800;color:#fff}
.slip-name{font-family:'Syne',sans-serif;font-size:16px;font-weight:800}
.slip-role{font-size:12px;color:var(--muted);margin-top:2px}

.slip-body{padding:24px 28px}
.section-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:12px;padding-bottom:6px;border-bottom:1px solid var(--border)}

/* Row item */
.row{display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.04)}
.row:last-child{border-bottom:none}
.row-label{font-size:13px;color:var(--muted);display:flex;align-items:center;gap:7px}
.row-val{font-size:13px;font-weight:600}
.row-val.add{color:#43e97b}
.row-val.ded{color:var(--accent2)}
.row-val.neutral{color:var(--text)}

/* Totals */
.totals{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1px;background:var(--border);border-top:1px solid var(--border)}
.total-cell{background:var(--surface2);padding:16px 20px;text-align:center}
.total-val{font-family:'Syne',sans-serif;font-size:20px;font-weight:800}
.total-lbl{font-size:11px;color:var(--muted);margin-top:3px}

/* Net block */
.net-block{background:linear-gradient(135deg,var(--accent),#8b7eff);padding:20px 28px;display:flex;align-items:center;justify-content:space-between}
.net-label{color:rgba(255,255,255,.8);font-size:13px;font-weight:600}
.net-val{font-family:'Syne',sans-serif;font-size:32px;font-weight:800;color:#fff}
.net-currency{font-size:16px;font-weight:600;color:rgba(255,255,255,.8);margin-inline-start:6px}

/* Attendance summary */
.att-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px}
.att-cell{background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px;text-align:center}
.att-val{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.att-lbl{font-size:10px;color:var(--muted);margin-top:2px}

.badge-paid{display:inline-flex;align-items:center;gap:5px;background:rgba(67,233,123,.12);color:#43e97b;border:1px solid rgba(67,233,123,.2);border-radius:20px;padding:4px 12px;font-size:11px;font-weight:700}
.badge-draft{display:inline-flex;align-items:center;gap:5px;background:rgba(247,183,49,.12);color:#f7b731;border:1px solid rgba(247,183,49,.2);border-radius:20px;padding:4px 12px;font-size:11px;font-weight:700}
</style>
@endpush
@section('content')

<div class="ph">
    <div style="display:flex;align-items:center;gap:12px">
        <a href="{{ route('admin.payroll.index') }}" class="btn btn-ghost" style="padding:8px 12px">
            <i class="fas fa-arrow-right"></i>
        </a>
        <div>
            <h1 style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;margin:0">
                {{ __('admin.salary_slip') }}
            </h1>
            <p style="font-size:12px;color:var(--muted);margin:2px 0 0">{{ $payroll->employee->name }} — {{ $payroll->month_name }}</p>
        </div>
    </div>
    <div style="display:flex;gap:10px;align-items:center">
        <span class="badge-{{ $payroll->status }}">
            <i class="fas fa-{{ $payroll->isPaid() ? 'check-circle' : 'clock' }}"></i>
            {{ __('admin.'.$payroll->status) }}
        </span>
        @if($payroll->isDraft())
        <a href="{{ route('admin.payroll.edit', $payroll) }}" class="btn btn-ghost">
            <i class="fas fa-edit"></i> {{ __('admin.edit') }}
        </a>
        <form method="POST" action="{{ route('admin.payroll.markPaid', $payroll) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check"></i> {{ __('admin.mark_paid') }}
            </button>
        </form>
        @endif
    </div>
</div>

@if(session('success'))
<div style="background:rgba(67,233,123,.08);border:1px solid rgba(67,233,123,.2);border-radius:12px;padding:12px 16px;margin-bottom:18px;color:#43e97b;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- ══ Salary Slip ══ --}}
<div class="slip">
    {{-- Header --}}
    <div class="slip-header">
        <div>
            <div class="slip-company">{{ \App\Models\Setting::get('company_name', 'RecoveryCRM') }}</div>
            <div class="slip-subtitle">{{ __('admin.salary_slip') }}</div>
        </div>
        <div>
            <div class="slip-month">{{ \Carbon\Carbon::create($payroll->year, $payroll->month)->translatedFormat('F') }}</div>
            <div class="slip-month-sub">{{ $payroll->year }}</div>
        </div>
    </div>

    {{-- Employee --}}
    <div class="slip-emp">
        <div class="slip-av">{{ strtoupper(mb_substr($payroll->employee->name,0,1)) }}</div>
        <div>
            <div class="slip-name">{{ $payroll->employee->name }}</div>
            <div class="slip-role">{{ $payroll->employee->job_title }} &nbsp;|&nbsp; {{ $payroll->employee->department ?? __('admin.no_department') }}</div>
        </div>
    </div>

    <div class="slip-body">

        {{-- Attendance summary --}}
        <div class="section-title">{{ __('admin.attendance_summary') }}</div>
        <div class="att-grid" style="margin-bottom:22px">
            <div class="att-cell">
                <div class="att-val" style="color:#43e97b">{{ $payroll->working_days }}</div>
                <div class="att-lbl">{{ __('admin.present_days') }}</div>
            </div>
            <div class="att-cell">
                <div class="att-val" style="color:var(--accent2)">{{ $payroll->absent_days }}</div>
                <div class="att-lbl">{{ __('admin.absent_days') }}</div>
            </div>
            <div class="att-cell">
                <div class="att-val" style="color:#f7b731">{{ $payroll->late_count }}</div>
                <div class="att-lbl">{{ __('admin.late_count') }}</div>
            </div>
        </div>

        {{-- Earnings --}}
        <div class="section-title">{{ __('admin.earnings') }}</div>
        <div style="margin-bottom:20px">
            <div class="row">
                <span class="row-label"><i class="fas fa-coins" style="color:var(--accent)"></i> {{ __('admin.basic_salary') }}</span>
                <span class="row-val neutral">{{ number_format($payroll->basic_salary,2) }} JD</span>
            </div>
            @if($payroll->commissions_amount > 0)
            <div class="row">
                <span class="row-label"><i class="fas fa-percentage" style="color:#43e97b"></i> {{ __('admin.commissions') }}</span>
                <span class="row-val add">+{{ number_format($payroll->commissions_amount,2) }} JD</span>
            </div>
            @endif
            @if($payroll->bonuses > 0)
            <div class="row">
                <span class="row-label"><i class="fas fa-gift" style="color:#00c6ff"></i> {{ __('admin.bonuses') }}</span>
                <span class="row-val add">+{{ number_format($payroll->bonuses,2) }} JD</span>
            </div>
            @endif
                
            @if($payroll->overtime_amount > 0)
            <div class="row">
                <span class="row-label">
                    <i class="fas fa-bolt" style="color:#f7b731"></i>
                    {{ __('admin.overtime') }} ({{ $payroll->overtime_hours }}h)
                </span>
                <span class="row-val add">+{{ number_format($payroll->overtime_amount,2) }} JD</span>
            </div>
            @endif

        </div>

        {{-- Deductions --}}
        <div class="section-title">{{ __('admin.deductions') }}</div>
        <div style="margin-bottom:20px">
            @if($payroll->deduction_absence > 0)
            <div class="row">
                <span class="row-label"><i class="fas fa-user-times" style="color:var(--accent2)"></i> {{ __('admin.absence_deduction') }} ({{ $payroll->absent_days }} {{ __('admin.days') }})</span>
                <span class="row-val ded">-{{ number_format($payroll->deduction_absence,2) }} JD</span>
            </div>
            @endif
            @if($payroll->deduction_late > 0)
            <div class="row">
                <span class="row-label"><i class="fas fa-clock" style="color:#f7b731"></i> {{ __('admin.late_deduction') }} ({{ $payroll->late_count }}x)</span>
                <span class="row-val ded">-{{ number_format($payroll->deduction_late,2) }} JD</span>
            </div>
            @endif
            @if($payroll->deduction_manual > 0)
            <div class="row">
                <span class="row-label">
                    <i class="fas fa-minus-circle" style="color:var(--accent2)"></i>
                    {{ __('admin.manual_deduction') }}
                    @if($payroll->deduction_manual_note)
                    <span style="font-size:11px;color:var(--muted)">({{ $payroll->deduction_manual_note }})</span>
                    @endif
                </span>
                <span class="row-val ded">-{{ number_format($payroll->deduction_manual,2) }} JD</span>
            </div>
            @endif
            @if($payroll->total_deductions == 0)
            <div class="row">
                <span class="row-label" style="color:var(--muted)">{{ __('admin.no_deductions') }}</span>
                <span class="row-val neutral">—</span>
            </div>
            @endif
        </div>

        {{-- Notes --}}
        @if($payroll->notes)
        <div style="background:var(--surface2);border-radius:10px;padding:12px 14px;font-size:13px;color:var(--muted);border-inline-start:3px solid var(--accent);margin-bottom:20px">
            <i class="fas fa-sticky-note" style="margin-inline-end:6px"></i> {{ $payroll->notes }}
        </div>
        @endif
    </div>

    {{-- Totals bar --}}
    <div class="totals">
        <div class="total-cell">
            <div class="total-val" style="color:#43e97b">{{ number_format($payroll->total_additions,2) }}</div>
            <div class="total-lbl">{{ __('admin.total_earnings') }} (JD)</div>
        </div>
        <div class="total-cell">
            <div class="total-val" style="color:var(--accent2)">{{ number_format($payroll->total_deductions,2) }}</div>
            <div class="total-lbl">{{ __('admin.total_deductions') }} (JD)</div>
        </div>
        <div class="total-cell">
            <div class="total-val" style="color:var(--accent)">{{ $payroll->working_days }}</div>
            <div class="total-lbl">{{ __('admin.working_days') }}</div>
        </div>
    </div>

    {{-- Net salary --}}
    <div class="net-block">
        <div class="net-label">{{ __('admin.net_salary') }}</div>
        <div>
            <span class="net-val">{{ number_format($payroll->net_salary,2) }}</span>
            <span class="net-currency">JD</span>
        </div>
    </div>
</div>

@if($payroll->isPaid())
<div style="text-align:center;margin-top:12px;font-size:12px;color:var(--muted)">
    <i class="fas fa-check-circle" style="color:#43e97b"></i>
    {{ __('admin.paid_on') }}: {{ $payroll->paid_at->translatedFormat('d F Y') }}
</div>
@endif

@endsection