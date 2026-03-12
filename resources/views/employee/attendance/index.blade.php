@extends('layouts.employee')
@section('title', __('emp.attendance'))

@push('styles')
<style>
.ph{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.ph h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}

/* Clock card */
.clock-card{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:28px;margin-bottom:20px;text-align:center}
.clock-time{font-family:'Syne',sans-serif;font-size:48px;font-weight:800;letter-spacing:-2px;margin-bottom:4px;color:var(--text)}
.clock-date{font-size:13px;color:var(--muted);margin-bottom:24px}

/* Status ring */
.status-ring{width:90px;height:90px;border-radius:50%;margin:0 auto 20px;display:flex;align-items:center;justify-content:center;font-size:28px;position:relative}
.ring-present{background:rgba(67,233,123,.1);border:3px solid #43e97b;color:#43e97b}
.ring-out{background:rgba(108,99,255,.1);border:3px solid var(--accent);color:var(--accent)}
.ring-none{background:var(--surface2);border:3px solid var(--border);color:var(--muted)}

/* Action buttons */
.action-btn{display:inline-flex;align-items:center;justify-content:center;gap:10px;width:100%;max-width:280px;padding:14px 28px;border-radius:14px;font-size:15px;font-weight:700;cursor:pointer;border:none;font-family:inherit;transition:all .2s}
.btn-checkin{background:linear-gradient(135deg,#43e97b,#38f9d7);color:#000;box-shadow:0 6px 20px rgba(67,233,123,.3)}
.btn-checkin:hover{opacity:.9;transform:translateY(-1px)}
.btn-checkout{background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff;box-shadow:0 6px 20px rgba(108,99,255,.3)}
.btn-checkout:hover{opacity:.9;transform:translateY(-1px)}
.btn-disabled{background:var(--surface2);color:var(--muted);border:1.5px solid var(--border);cursor:not-allowed}

/* Location status */
.loc-status{display:inline-flex;align-items:center;gap:7px;font-size:12px;font-weight:600;padding:6px 14px;border-radius:20px;margin-bottom:16px}
.loc-ok{background:rgba(67,233,123,.1);color:#43e97b;border:1px solid rgba(67,233,123,.2)}
.loc-far{background:rgba(255,101,132,.1);color:var(--accent2);border:1px solid rgba(255,101,132,.2)}
.loc-loading{background:var(--surface2);color:var(--muted);border:1px solid var(--border)}

/* Stats strip */
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px}
@media(max-width:600px){.stats{grid-template-columns:1fr 1fr}}
.stat{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:14px 16px}
.stat-v{font-family:'Syne',sans-serif;font-size:26px;font-weight:800}
.stat-l{font-size:11px;color:var(--muted);margin-top:2px}

/* Month nav */
.month-nav{display:flex;align-items:center;gap:10px;margin-bottom:18px}
.month-nav input{background:var(--surface2);border:1.5px solid var(--border);border-radius:10px;padding:8px 14px;font-size:13px;color:var(--text);font-family:inherit;outline:none}
.month-nav input:focus{border-color:var(--accent)}

/* Records table */
.tcard{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden}
.at{width:100%;border-collapse:collapse;font-size:13px}
.at th{text-align:start;font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);font-weight:700;padding:11px 16px;border-bottom:1px solid var(--border);background:var(--surface2);white-space:nowrap}
.at td{padding:11px 16px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
.at tr:last-child td{border-bottom:none}
.at tr:hover td{background:rgba(255,255,255,.02)}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700}
.badge-present{background:rgba(67,233,123,.12);color:#43e97b}
.badge-late{background:rgba(247,183,49,.12);color:#f7b731}
.badge-half_day{background:rgba(0,198,255,.12);color:#00c6ff}
.badge-absent{background:rgba(255,101,132,.12);color:var(--accent2)}
.dist-pill{font-size:10px;color:var(--muted);background:var(--surface2);border-radius:20px;padding:2px 7px;display:inline-block}
</style>
@endpush

@section('content')

<div class="ph">
    <div>
        <h1>{{ __('emp.attendance') }}</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:3px">{{ __('emp.attendance_subtitle') }}</p>
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

{{-- ── Clock + Check in/out Card ── --}}
<div class="clock-card">
    <div class="clock-time" id="live-clock">--:--:--</div>
    <div class="clock-date">{{ now()->translatedFormat('l، d F Y') }}</div>

    {{-- Status ring --}}
    @if($today && $today->check_out)
        <div class="status-ring ring-present"><i class="fas fa-home"></i></div>
        <div style="font-size:14px;font-weight:700;color:#43e97b;margin-bottom:6px">{{ __('emp.already_checked_out') }}</div>
        <div style="font-size:12px;color:var(--muted);margin-bottom:20px">
            {{ __('emp.checked_in_at') }}: {{ $today->check_in->format('H:i') }}
            &nbsp;|&nbsp;
            {{ __('emp.checked_out_at') }}: {{ $today->check_out->format('H:i') }}
            &nbsp;|&nbsp;
            {{ $today->worked_hours_formatted }}
        </div>
        <button class="action-btn btn-disabled" disabled>
            <i class="fas fa-check"></i> {{ __('emp.day_done') }}
        </button>
    @elseif($today && $today->check_in)
        <div class="status-ring ring-present"><i class="fas fa-briefcase"></i></div>
        <div style="font-size:14px;font-weight:700;color:#43e97b;margin-bottom:6px">{{ __('emp.currently_in') }}</div>
        <div style="font-size:12px;color:var(--muted);margin-bottom:20px">
            {{ __('emp.checked_in_at') }}: <strong>{{ $today->check_in->format('H:i') }}</strong>
        </div>
    @else
        <div class="status-ring ring-none"><i class="fas fa-clock"></i></div>
        <div style="font-size:14px;font-weight:700;color:var(--muted);margin-bottom:6px">{{ __('emp.not_checked_in') }}</div>
        <div style="font-size:12px;color:var(--muted);margin-bottom:20px">{{ __('emp.check_in_prompt') }}</div>
    @endif

    {{-- Location indicator --}}
    @if($hasLocation)
    <div class="loc-status loc-loading" id="loc-status">
        <i class="fas fa-circle-notch fa-spin"></i> {{ __('emp.getting_location') }}
    </div>
    <div id="loc-distance" style="font-size:11px;color:var(--muted);margin-bottom:14px;min-height:16px"></div>
    @else
    <div class="loc-status" style="background:rgba(247,183,49,.1);color:#f7b731;border-color:rgba(247,183,49,.2);margin-bottom:14px">
        <i class="fas fa-exclamation-triangle"></i> {{ __('emp.location_not_set') }}
    </div>
    @endif

    {{-- Action Button --}}
    @if(!$today || !$today->check_in)
        <form method="POST" action="{{ route('employee.attendance.checkin') }}" id="checkin-form">
            @csrf
            <input type="hidden" name="lat" id="ci-lat">
            <input type="hidden" name="lng" id="ci-lng">
            <button type="button" class="action-btn btn-checkin" id="checkin-btn"
                    onclick="submitWithLocation('checkin-form')" {{ !$hasLocation ? '' : '' }}>
                <i class="fas fa-sign-in-alt"></i> {{ __('emp.check_in') }}
            </button>
        </form>
    @elseif($today && !$today->check_out)
        <form method="POST" action="{{ route('employee.attendance.checkout') }}" id="checkout-form">
            @csrf
            <input type="hidden" name="lat" id="co-lat">
            <input type="hidden" name="lng" id="co-lng">
            <button type="button" class="action-btn btn-checkout" id="checkout-btn"
                    onclick="submitWithLocation('checkout-form')">
                <i class="fas fa-sign-out-alt"></i> {{ __('emp.check_out') }}
            </button>
        </form>
    @endif
</div>

{{-- ── Stats ── --}}
<div class="stats">
    <div class="stat">
        <div class="stat-v" style="color:#43e97b">{{ $stats['present'] }}</div>
        <div class="stat-l">{{ __('emp.present_days') }}</div>
    </div>
    <div class="stat">
        <div class="stat-v" style="color:#f7b731">{{ $stats['late'] }}</div>
        <div class="stat-l">{{ __('emp.late_days') }}</div>
    </div>
    <div class="stat">
        <div class="stat-v" style="color:var(--accent2)">{{ $stats['absent'] }}</div>
        <div class="stat-l">{{ __('emp.absent_days') }}</div>
    </div>
    <div class="stat">
        <div class="stat-v" style="color:var(--accent)">
            {{ $stats['total_hours'] ? floor($stats['total_hours']/60).'h' : '0h' }}
        </div>
        <div class="stat-l">{{ __('emp.total_hours') }}</div>
    </div>
</div>

{{-- ── Month filter ── --}}
<div class="month-nav">
    <i class="fas fa-calendar" style="color:var(--muted)"></i>
    <form method="GET" style="display:contents">
        <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()">
    </form>
</div>

{{-- ── Records ── --}}
<div class="tcard">
    @if($records->count())
    <div style="overflow-x:auto">
        <table class="at">
            <thead>
                <tr>
                    <th>{{ __('emp.date') }}</th>
                    <th>{{ __('emp.check_in') }}</th>
                    <th>{{ __('emp.check_out') }}</th>
                    <th>{{ __('emp.worked_hours') }}</th>
                    <th>{{ __('emp.status') }}</th>
                    <th>{{ __('emp.distance') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $r)
                <tr>
                    <td style="font-weight:600">{{ $r->date->translatedFormat('D، d M') }}</td>
                    <td>{{ $r->check_in?->format('H:i') ?? '—' }}</td>
                    <td>{{ $r->check_out?->format('H:i') ?? '—' }}</td>
                    <td style="font-weight:600;color:var(--accent)">{{ $r->worked_hours_formatted }}</td>
                    <td><span class="badge badge-{{ $r->status }}">{{ __('emp.'.$r->status) }}</span></td>
                    <td>
                        @if($r->check_in_distance)
                        <span class="dist-pill"><i class="fas fa-map-marker-alt" style="font-size:9px"></i> {{ round($r->check_in_distance) }}m</span>
                        @else
                        <span style="color:var(--muted)">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align:center;padding:48px 20px;color:var(--muted)">
        <i class="fas fa-calendar-times" style="font-size:36px;opacity:.2;display:block;margin-bottom:12px"></i>
        <p style="margin:0;font-size:13px">{{ __('emp.no_attendance_records') }}</p>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
// ── Live clock ─────────────────────────────────────────
function updateClock() {
    const now = new Date();
    document.getElementById('live-clock').textContent =
        now.toLocaleTimeString('ar', {hour:'2-digit', minute:'2-digit', second:'2-digit'});
}
setInterval(updateClock, 1000);
updateClock();

// ── Location & distance ────────────────────────────────
const COMPANY_LAT = {{ $companyLat ?? 'null' }};
const COMPANY_LNG = {{ $companyLng ?? 'null' }};
const MAX_RADIUS  = {{ $radius ?? 200 }};

let userLat = null, userLng = null, locationReady = false;

function haversine(lat1, lng1, lat2, lng2) {
    const R = 6371000;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat/2)**2 +
              Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) * Math.sin(dLng/2)**2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
}

function updateLocationUI(lat, lng) {
    userLat = lat; userLng = lng; locationReady = true;

    if (COMPANY_LAT === null) return; // لم يضبط الأدمن الموقع بعد

    const dist = Math.round(haversine(lat, lng, COMPANY_LAT, COMPANY_LNG));
    const el   = document.getElementById('loc-status');
    const dist_el = document.getElementById('loc-distance');

    if (dist <= MAX_RADIUS) {
        el.className = 'loc-status loc-ok';
        el.innerHTML = `<i class="fas fa-check-circle"></i> {{ __("emp.within_range") }}`;
        if (dist_el) dist_el.textContent = `{{ __("emp.you_are") }} ${dist}m {{ __("emp.from_office") }}`;
    } else {
        el.className = 'loc-status loc-far';
        el.innerHTML = `<i class="fas fa-times-circle"></i> {{ __("emp.out_of_range") }}`;
        if (dist_el) dist_el.textContent = `{{ __("emp.you_are") }} ${dist}m {{ __("emp.from_office") }} ({{ __("emp.max") }} ${MAX_RADIUS}m)`;
    }
}

if (navigator.geolocation && COMPANY_LAT !== null) {
    navigator.geolocation.watchPosition(
        pos => updateLocationUI(pos.coords.latitude, pos.coords.longitude),
        err => {
            const el = document.getElementById('loc-status');
            if (el) {
                el.className = 'loc-status loc-far';
                el.innerHTML = `<i class="fas fa-exclamation-triangle"></i> {{ __("emp.location_denied") }}`;
            }
        },
        { enableHighAccuracy: true, maximumAge: 10000 }
    );
}

// ── Submit with location ───────────────────────────────
function submitWithLocation(formId) {
    const form = document.getElementById(formId);
    const isCheckin = formId === 'checkin-form';

    if (!navigator.geolocation) {
        alert('{{ __("emp.geolocation_unsupported") }}');
        return;
    }

    if (COMPANY_LAT !== null) {
        if (!locationReady) {
            alert('{{ __("emp.wait_for_location") }}');
            return;
        }
        const dist = haversine(userLat, userLng, COMPANY_LAT, COMPANY_LNG);
        if (dist > MAX_RADIUS) {
            alert(`{{ __("emp.too_far_alert") }} (${Math.round(dist)}m / {{ __("emp.max") }} ${MAX_RADIUS}m)`);
            return;
        }
    }

    // حشو الـ lat/lng في الـ form
    const latField = form.querySelector(isCheckin ? '#ci-lat' : '#co-lat');
    const lngField = form.querySelector(isCheckin ? '#ci-lng' : '#co-lng');
    latField.value = userLat ?? 0;
    lngField.value = userLng ?? 0;

    form.submit();
}
</script>
@endpush