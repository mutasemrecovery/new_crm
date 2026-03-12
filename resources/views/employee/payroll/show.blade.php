@extends('layouts.employee')
@section('title', __('emp.salary_slip'))
@push('styles')
<style>
.ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.btn{display:inline-flex;align-items:center;gap:7px;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;border:none}
.btn-ghost{background:var(--surface2);color:var(--text);border:1.5px solid var(--border)}.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.slip{background:var(--surface);border:1px solid var(--border);border-radius:20px;overflow:hidden;max-width:680px;margin:0 auto}
.slip-header{background:linear-gradient(135deg,var(--accent),#8b7eff);padding:24px 28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}
.slip-company{font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:#fff}
.slip-sub{font-size:11px;color:rgba(255,255,255,.7);margin-top:2px}
.slip-month{font-family:'Syne',sans-serif;font-size:26px;font-weight:800;color:#fff;text-align:end}
.slip-emp{display:flex;align-items:center;gap:14px;padding:18px 24px;background:var(--surface2);border-bottom:1px solid var(--border)}
.slip-av{width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,var(--accent),#8b7eff);display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;color:#fff}
.slip-name{font-family:'Syne',sans-serif;font-size:15px;font-weight:800}
.slip-role{font-size:11px;color:var(--muted);margin-top:2px}
.slip-body{padding:22px 24px}
.section-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:10px;padding-bottom:5px;border-bottom:1px solid var(--border)}
.row{display:flex;align-items:center;justify-content:space-between;padding:7px 0;border-bottom:1px solid rgba(255,255,255,.04);font-size:13px}
.row:last-child{border-bottom:none}
.row-lbl{color:var(--muted)}
.row-val{font-weight:600}
.add{color:#43e97b}.ded{color:var(--accent2)}
.att-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:20px}
.att-cell{background:var(--surface2);border-radius:10px;padding:10px;text-align:center}
.att-val{font-family:'Syne',sans-serif;font-size:20px;font-weight:800}
.att-lbl{font-size:10px;color:var(--muted);margin-top:2px}
.totals{display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--border);border-top:1px solid var(--border)}
.total-cell{background:var(--surface2);padding:14px 18px;text-align:center}
.total-val{font-family:'Syne',sans-serif;font-size:18px;font-weight:800}
.total-lbl{font-size:10px;color:var(--muted);margin-top:2px}
.net-block{background:linear-gradient(135deg,var(--accent),#8b7eff);padding:18px 24px;display:flex;align-items:center;justify-content:space-between}
.net-label{color:rgba(255,255,255,.8);font-size:13px;font-weight:600}
.net-val{font-family:'Syne',sans-serif;font-size:28px;font-weight:800;color:#fff}
</style>
@endpush
@section('content')
 
<div class="ph">
    <div style="display:flex;align-items:center;gap:12px">
        <a href="{{ route('employee.payroll.index') }}" class="btn btn-ghost" style="padding:8px 12px">
            <i class="fas fa-arrow-right"></i>
        </a>
        <div>
            <h1 style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;margin:0">{{ __('emp.salary_slip') }}</h1>
            <p style="font-size:12px;color:var(--muted);margin:2px 0 0">{{ $payroll->month_name }}</p>
        </div>
    </div>
</div>
 
<div class="slip">
    <div class="slip-header">
        <div>
            <div class="slip-company">{{ \App\Models\Setting::get('company_name','RecoveryCRM') }}</div>
            <div class="slip-sub">{{ __('emp.salary_slip') }}</div>
        </div>
        <div>
            <div class="slip-month">{{ \Carbon\Carbon::create($payroll->year,$payroll->month)->translatedFormat('F') }}</div>
            <div style="font-size:11px;color:rgba(255,255,255,.7);text-align:end">{{ $payroll->year }}</div>
        </div>
    </div>
 
    <div class="slip-emp">
        <div class="slip-av">{{ strtoupper(mb_substr($payroll->employee->name,0,1)) }}</div>
        <div>
            <div class="slip-name">{{ $payroll->employee->name }}</div>
            <div class="slip-role">{{ $payroll->employee->job_title }}</div>
        </div>
    </div>
 
    <div class="slip-body">
        <div class="section-title">{{ __('emp.attendance') }}</div>
        <div class="att-grid" style="margin-bottom:20px">
            <div class="att-cell"><div class="att-val" style="color:#43e97b">{{ $payroll->working_days }}</div><div class="att-lbl">{{ __('emp.present') }}</div></div>
            <div class="att-cell"><div class="att-val" style="color:var(--accent2)">{{ $payroll->absent_days }}</div><div class="att-lbl">{{ __('emp.absent') }}</div></div>
            <div class="att-cell"><div class="att-val" style="color:#f7b731">{{ $payroll->late_count }}</div><div class="att-lbl">{{ __('emp.late') }}</div></div>
        </div>
 
        <div class="section-title">{{ __('emp.earnings') }}</div>
        <div style="margin-bottom:18px">
            <div class="row"><span class="row-lbl">{{ __('emp.basic_salary') }}</span><span class="row-val">{{ number_format($payroll->basic_salary,2) }} JD</span></div>
            @if($payroll->commissions_amount>0)<div class="row"><span class="row-lbl">{{ __('emp.commissions') }}</span><span class="row-val add">+{{ number_format($payroll->commissions_amount,2) }} JD</span></div>@endif
            @if($payroll->bonuses>0)<div class="row"><span class="row-lbl">{{ __('emp.bonuses') }}</span><span class="row-val add">+{{ number_format($payroll->bonuses,2) }} JD</span></div>@endif
        </div>
 
        <div class="section-title">{{ __('emp.deductions') }}</div>
        <div style="margin-bottom:18px">
            @if($payroll->deduction_absence>0)<div class="row"><span class="row-lbl">{{ __('emp.absence_deduction') }} ({{ $payroll->absent_days }}d)</span><span class="row-val ded">-{{ number_format($payroll->deduction_absence,2) }} JD</span></div>@endif
            @if($payroll->deduction_late>0)<div class="row"><span class="row-lbl">{{ __('emp.late_deduction') }} ({{ $payroll->late_count }}x)</span><span class="row-val ded">-{{ number_format($payroll->deduction_late,2) }} JD</span></div>@endif
            @if($payroll->deduction_manual>0)<div class="row"><span class="row-lbl">{{ __('emp.other_deductions') }}@if($payroll->deduction_manual_note) ({{ $payroll->deduction_manual_note }})@endif</span><span class="row-val ded">-{{ number_format($payroll->deduction_manual,2) }} JD</span></div>@endif
            @if($payroll->total_deductions==0)<div class="row"><span class="row-lbl" style="color:var(--muted)">{{ __('emp.no_deductions') }}</span><span>—</span></div>@endif
        </div>
 
        @if($payroll->notes)
        <div style="background:var(--surface2);border-radius:10px;padding:10px 14px;font-size:12px;color:var(--muted);border-inline-start:3px solid var(--accent);margin-bottom:16px">{{ $payroll->notes }}</div>
        @endif
    </div>
 
    <div class="totals">
        <div class="total-cell"><div class="total-val" style="color:#43e97b">{{ number_format($payroll->total_additions,2) }}</div><div class="total-lbl">{{ __('emp.total_earnings') }} JD</div></div>
        <div class="total-cell"><div class="total-val" style="color:var(--accent2)">{{ number_format($payroll->total_deductions,2) }}</div><div class="total-lbl">{{ __('emp.total_deductions') }} JD</div></div>
    </div>
 
    <div class="net-block">
        <span class="net-label">{{ __('emp.net_salary') }}</span>
        <span class="net-val">{{ number_format($payroll->net_salary,2) }} <span style="font-size:16px;opacity:.8">JD</span></span>
    </div>
</div>
 
<div style="text-align:center;margin-top:12px;font-size:12px;color:var(--muted)">
    <i class="fas fa-check-circle" style="color:#43e97b"></i>
    {{ __('emp.paid_on') }}: {{ $payroll->paid_at?->translatedFormat('d F Y') ?? '—' }}
</div>
 
@endsection