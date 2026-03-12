@extends('layouts.employee')
@section('title', __('emp.nav_payroll'))
@push('styles')
<style>
.ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.ph h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.latest-card{background:linear-gradient(135deg,var(--accent) 0%,#8b7eff 100%);border-radius:20px;padding:26px 28px;margin-bottom:20px;color:#fff;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px}
.latest-month{font-size:13px;opacity:.8;margin-bottom:4px}
.latest-net{font-family:'Syne',sans-serif;font-size:40px;font-weight:800}
.latest-currency{font-size:18px;opacity:.8;margin-inline-start:6px}
.latest-meta{font-size:12px;opacity:.7;margin-top:6px}
.btn-light{display:inline-flex;align-items:center;gap:7px;background:rgba(255,255,255,.15);color:#fff;border:1.5px solid rgba(255,255,255,.25);border-radius:10px;padding:10px 18px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit}
.btn-light:hover{background:rgba(255,255,255,.25);color:#fff}
.tcard{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden}
.at{width:100%;border-collapse:collapse;font-size:13px}
.at th{text-align:start;font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);font-weight:700;padding:11px 16px;border-bottom:1px solid var(--border);background:var(--surface2)}
.at td{padding:11px 16px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
.at tr:last-child td{border-bottom:none}
.at tr:hover td{background:rgba(255,255,255,.02)}
</style>
@endpush
@section('content')

<div class="ph">
    <div>
        <h1>{{ __('emp.nav_payroll') }}</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:3px">{{ __('emp.payroll_subtitle') }}</p>
    </div>
</div>

@if($latest)
<div class="latest-card">
    <div>
        <div class="latest-month">{{ $latest->month_name }}</div>
        <div>
            <span class="latest-net">{{ number_format($latest->net_salary,2) }}</span>
            <span class="latest-currency">JD</span>
        </div>
        <div class="latest-meta">
            {{ __('emp.paid_on') }}: {{ $latest->paid_at?->translatedFormat('d F Y') ?? '—' }}
            &nbsp;|&nbsp; {{ __('emp.working_days') }}: {{ $latest->working_days }}
        </div>
    </div>
    <a href="{{ route('employee.payroll.show', $latest) }}" class="btn-light">
        <i class="fas fa-eye"></i> {{ __('emp.view_slip') }}
    </a>
</div>
@endif

<div class="tcard">
    @if($payrolls->count())
    <div style="overflow-x:auto">
        <table class="at">
            <thead>
                <tr>
                    <th>{{ __('emp.month') }}</th>
                    <th>{{ __('emp.basic_salary') }}</th>
                    <th>{{ __('emp.bonuses') }}</th>
                    <th>{{ __('emp.deductions') }}</th>
                    <th>{{ __('emp.net_salary') }}</th>
                    <th>{{ __('emp.paid_date') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($payrolls as $p)
                <tr>
                    <td style="font-weight:700">{{ $p->month_name }}</td>
                    <td>{{ number_format($p->basic_salary,2) }}</td>
                    <td style="color:#43e97b">
                        {{ ($p->commissions_amount+$p->bonuses) > 0 ? '+'.number_format($p->commissions_amount+$p->bonuses,2) : '—' }}
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
                    <td style="font-size:12px;color:var(--muted)">{{ $p->paid_at?->format('d M Y') ?? '—' }}</td>
                    <td>
                        <a href="{{ route('employee.payroll.show', $p) }}"
                           style="color:var(--accent);font-size:12px;text-decoration:none">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:14px 16px">{{ $payrolls->links() }}</div>
    @else
    <div style="text-align:center;padding:52px 20px;color:var(--muted)">
        <i class="fas fa-file-invoice-dollar" style="font-size:38px;opacity:.2;display:block;margin-bottom:12px"></i>
        <p style="margin:0;font-size:13px">{{ __('emp.no_payrolls_yet') }}</p>
    </div>
    @endif
</div>
@endsection