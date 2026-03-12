@extends('layouts.admin')
@section('title', __('admin.leave_request'))
@push('styles')
<style>
.ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.ph h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.btn{display:inline-flex;align-items:center;gap:7px;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;border:none}
.btn-approve{background:linear-gradient(135deg,#43e97b,#38f9d7);color:#000;box-shadow:0 4px 14px rgba(67,233,123,.3)}.btn-approve:hover{opacity:.9}
.btn-reject{background:rgba(255,101,132,.1);color:var(--accent2);border:1.5px solid rgba(255,101,132,.2)}.btn-reject:hover{background:rgba(255,101,132,.15)}
.btn-ghost{background:var(--surface2);color:var(--text);border:1.5px solid var(--border)}.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
@media(max-width:700px){.grid2{grid-template-columns:1fr}}
.card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:16px}
.card-title{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;margin-bottom:18px;display:flex;align-items:center;gap:9px;color:var(--text)}
.card-title i{color:var(--accent)}
.dl{display:grid;grid-template-columns:1fr 1fr;gap:12px}
@media(max-width:500px){.dl{grid-template-columns:1fr}}
.dt{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.6px;margin-bottom:3px}
.dd{font-size:14px;font-weight:600;color:var(--text)}
.badge{display:inline-block;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700}
.badge-pending{background:rgba(247,183,49,.12);color:#f7b731}
.badge-approved{background:rgba(67,233,123,.12);color:#43e97b}
.badge-rejected{background:rgba(255,101,132,.12);color:var(--accent2)}
.badge-annual{background:rgba(108,99,255,.12);color:var(--accent)}
.badge-sick{background:rgba(0,198,255,.12);color:#00c6ff}
.badge-emergency{background:rgba(255,101,132,.12);color:var(--accent2)}
.badge-unpaid{background:rgba(107,114,128,.12);color:var(--muted)}
.badge-other{background:rgba(247,183,49,.12);color:#f7b731}
.days-big{font-family:'Syne',sans-serif;font-size:52px;font-weight:800;color:var(--accent);line-height:1}
.balance-bar{height:8px;border-radius:4px;background:var(--surface2);margin-top:8px;overflow:hidden}
.balance-fill{height:100%;border-radius:4px;background:linear-gradient(90deg,var(--accent),#8b7eff);transition:width .6s}
.fin{background:var(--surface2);border:1.5px solid var(--border);border-radius:10px;padding:10px 14px;font-size:13px;color:var(--text);font-family:inherit;outline:none;width:100%;box-sizing:border-box}
.fin:focus{border-color:var(--accent)}
textarea.fin{min-height:90px;resize:vertical}
.av{width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,var(--accent),#8b7eff);display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:800;color:#fff;flex-shrink:0}
/* Modal */
.modal-backdrop{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:1000;align-items:center;justify-content:center}
.modal-backdrop.open{display:flex}
.modal{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:28px;width:100%;max-width:420px;margin:16px}
.modal-title{font-family:'Syne',sans-serif;font-size:16px;font-weight:700;margin-bottom:16px;display:flex;align-items:center;gap:8px}
</style>
@endpush
@section('content')

<div class="ph">
    <div>
        <h1>{{ __('admin.leave_request') }} #{{ $leave->id }}</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:3px">
            {{ __('admin.submitted') }}: {{ $leave->created_at->translatedFormat('d F Y — H:i') }}
        </p>
    </div>
    <div style="display:flex;gap:10px;align-items:center">
        <span class="badge badge-{{ $leave->status }}" style="font-size:13px;padding:6px 16px">
            {{ __('admin.'.$leave->status) }}
        </span>
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
@if(session('error'))
<div style="background:rgba(255,101,132,.08);border:1px solid rgba(255,101,132,.2);border-radius:12px;padding:12px 16px;margin-bottom:18px;color:var(--accent2);font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<div class="grid2">
    {{-- ── Left: Request details ── --}}
    <div>
        {{-- Employee card --}}
        <div class="card" style="display:flex;align-items:center;gap:14px;margin-bottom:16px">
            <div class="av">{{ strtoupper(mb_substr($leave->employee->name,0,1)) }}</div>
            <div>
                <div style="font-weight:700;font-size:15px">{{ $leave->employee->name }}</div>
                <div style="font-size:12px;color:var(--muted)">{{ $leave->employee->job_title }}</div>
                <div style="font-size:11px;color:var(--muted);margin-top:2px">{{ $leave->employee->department ?? '' }}</div>
            </div>
        </div>

        {{-- Request info --}}
        <div class="card">
            <div class="card-title"><i class="fas fa-calendar-alt"></i> {{ __('admin.leave_details') }}</div>
            <div style="text-align:center;margin-bottom:22px">
                <div class="days-big">{{ $leave->days_count }}</div>
                <div style="font-size:13px;color:var(--muted);margin-top:4px">{{ __('admin.days') }}</div>
                <span class="badge badge-{{ $leave->type }}" style="margin-top:8px">
                    {{ __('admin.leave_'.$leave->type) }}
                </span>
            </div>
            <div class="dl">
                <div>
                    <div class="dt">{{ __('admin.start_date') }}</div>
                    <div class="dd">{{ $leave->start_date->translatedFormat('d F Y') }}</div>
                </div>
                <div>
                    <div class="dt">{{ __('admin.end_date') }}</div>
                    <div class="dd">{{ $leave->end_date->translatedFormat('d F Y') }}</div>
                </div>
            </div>
            @if($leave->reason)
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <div class="dt" style="margin-bottom:6px">{{ __('admin.reason') }}</div>
                <p style="font-size:13px;color:var(--text);margin:0;line-height:1.6">{{ $leave->reason }}</p>
            </div>
            @endif
        </div>

        {{-- Balance card --}}
        @if($leave->type === 'annual')
        <div class="card">
            <div class="card-title"><i class="fas fa-piggy-bank"></i> {{ __('admin.annual_balance') }}</div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                <span style="font-size:13px;color:var(--muted)">{{ __('admin.used') }} {{ $balance->used_days }} / {{ $balance->annual_balance }} {{ __('admin.days') }}</span>
                <span style="font-weight:700;color:{{ $balance->remaining >= $leave->days_count ? '#43e97b' : 'var(--accent2)' }}">
                    {{ $balance->remaining }} {{ __('admin.remaining') }}
                </span>
            </div>
            <div class="balance-bar">
                <div class="balance-fill" style="width:{{ $balance->annual_balance > 0 ? min(100, ($balance->used_days/$balance->annual_balance)*100) : 0 }}%"></div>
            </div>
            @if($balance->remaining < $leave->days_count && $leave->isPending())
            <div style="margin-top:10px;font-size:12px;color:var(--accent2);display:flex;align-items:center;gap:6px">
                <i class="fas fa-exclamation-triangle"></i>
                {{ __('admin.balance_insufficient_warning', ['days' => $leave->days_count, 'remaining' => $balance->remaining]) }}
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- ── Right: Actions ── --}}
    <div>
        {{-- Pending — show approve/reject --}}
        @if($leave->isPending())
        <div class="card">
            <div class="card-title"><i class="fas fa-gavel"></i> {{ __('admin.review_request') }}</div>

            {{-- Approve --}}
            <form method="POST" action="{{ route('admin.leaves.approve', $leave) }}" style="margin-bottom:12px">
                @csrf @method('PATCH')
                <div style="margin-bottom:12px">
                    <label style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.6px;display:block;margin-bottom:6px">
                        {{ __('admin.admin_note_optional') }}
                    </label>
                    <textarea name="admin_note" class="fin" placeholder="{{ __('admin.approval_note_hint') }}"></textarea>
                </div>
                <button type="submit" class="btn btn-approve" style="width:100%;justify-content:center">
                    <i class="fas fa-check-circle"></i> {{ __('admin.approve_leave') }}
                </button>
            </form>

            <div style="position:relative;text-align:center;margin:12px 0">
                <div style="height:1px;background:var(--border)"></div>
                <span style="position:absolute;top:-9px;left:50%;transform:translateX(-50%);background:var(--surface);padding:0 10px;font-size:11px;color:var(--muted)">{{ __('admin.or') }}</span>
            </div>

            {{-- Reject --}}
            <button type="button" class="btn btn-reject" style="width:100%;justify-content:center" onclick="document.getElementById('reject-modal').classList.add('open')">
                <i class="fas fa-times-circle"></i> {{ __('admin.reject_leave') }}
            </button>
        </div>

        @else
        {{-- Already reviewed --}}
        <div class="card">
            <div class="card-title">
                <i class="fas fa-{{ $leave->isApproved() ? 'check-circle' : 'times-circle' }}"
                   style="color:{{ $leave->isApproved() ? '#43e97b' : 'var(--accent2)' }}"></i>
                {{ __('admin.'.($leave->isApproved() ? 'leave_was_approved' : 'leave_was_rejected')) }}
            </div>
            @if($leave->reviewer)
            <div style="font-size:13px;color:var(--muted);margin-bottom:10px">
                {{ __('admin.reviewed_by') }}: <strong style="color:var(--text)">{{ $leave->reviewer->username }}</strong>
                <br>{{ $leave->reviewed_at->translatedFormat('d F Y — H:i') }}
            </div>
            @endif
            @if($leave->admin_note)
            <div style="background:var(--surface2);border-radius:10px;padding:12px 14px;font-size:13px;line-height:1.6;border-inline-start:3px solid {{ $leave->isApproved() ? '#43e97b' : 'var(--accent2)' }}">
                {{ $leave->admin_note }}
            </div>
            @endif
        </div>
        @endif

        {{-- Other leaves by this employee --}}
        <div class="card">
            <div class="card-title"><i class="fas fa-history"></i> {{ __('admin.employee_leaves_history') }}</div>
            @php
                $history = \App\Models\Leave::where('employee_id', $leave->employee_id)
                    ->where('id', '!=', $leave->id)
                    ->latest()->limit(5)->get();
            @endphp
            @if($history->count())
                @foreach($history as $h)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.04)">
                    <div>
                        <span class="badge badge-{{ $h->type }}" style="font-size:9px">{{ __('admin.leave_'.$h->type) }}</span>
                        <span style="font-size:12px;color:var(--muted);margin-inline-start:6px">
                            {{ $h->start_date->format('d M') }} → {{ $h->end_date->format('d M Y') }}
                        </span>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px">
                        <span style="font-size:12px;font-weight:700;color:var(--accent)">{{ $h->days_count }}d</span>
                        <span class="badge badge-{{ $h->status }}" style="font-size:9px">{{ __('admin.'.$h->status) }}</span>
                    </div>
                </div>
                @endforeach
            @else
                <p style="font-size:13px;color:var(--muted);margin:0">{{ __('admin.no_previous_leaves') }}</p>
            @endif
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal-backdrop" id="reject-modal">
    <div class="modal">
        <div class="modal-title" style="color:var(--accent2)">
            <i class="fas fa-times-circle"></i> {{ __('admin.reject_leave') }}
        </div>
        <form method="POST" action="{{ route('admin.leaves.reject', $leave) }}">
            @csrf @method('PATCH')
            <div style="margin-bottom:16px">
                <label style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.6px;display:block;margin-bottom:6px">
                    {{ __('admin.rejection_reason') }} *
                </label>
                <textarea name="admin_note" class="fin" required placeholder="{{ __('admin.rejection_reason_hint') }}"></textarea>
            </div>
            <div style="display:flex;gap:10px">
                <button type="button" class="btn btn-ghost" style="flex:1;justify-content:center"
                        onclick="document.getElementById('reject-modal').classList.remove('open')">
                    {{ __('admin.cancel') }}
                </button>
                <button type="submit" class="btn btn-reject" style="flex:1;justify-content:center">
                    <i class="fas fa-times"></i> {{ __('admin.confirm_reject') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection