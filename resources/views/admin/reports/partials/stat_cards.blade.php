@php
    $rptColorMap = [
        'blue'   => '3b7ddd',
        'green'  => '17b06c',
        'cyan'   => '1a9cb0',
        'orange' => 'f0762b',
        'purple' => '7148d6',
        'red'    => 'e6486a',
    ];
@endphp

<style type="text/css">
    .rpt-period { padding: 8px 16px 0; font-size: 13px; color: #666; }
    .rpt-period b { color: #222; }
    .rpt-stat-cards { padding: 12px 8px; margin: 0; }
    .rpt-stat-card {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #eceff5;
        border-radius: 4px;
        padding: 12px 14px;
        margin: 4px;
    }
    .rpt-stat-icon {
        flex: 0 0 auto;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 20px;
    }
    .rpt-stat-text { min-width: 0; }
    .rpt-stat-label {
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: #8a94a6;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .rpt-stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #2a2e34;
        line-height: 1.3;
    }
</style>

@if(!empty($period))
    <div class="rpt-period">Period: <b>{{ $period['from'] ?: 'All' }}</b> to <b>{{ $period['to'] ?: 'All' }}</b></div>
@endif

<div class="row rpt-stat-cards">
    @foreach($stats as $stat)
        @php $rptHex = $rptColorMap[$stat['color'] ?? 'blue'] ?? $rptColorMap['blue']; @endphp
        <div class="col-md-3 col-sm-6">
            <div class="rpt-stat-card">
                <div class="rpt-stat-icon" style="background:#{{ $rptHex }}1F; color:#{{ $rptHex }};">
                    <i class="mdi {{ $stat['icon'] ?? 'mdi-chart-box' }}"></i>
                </div>
                <div class="rpt-stat-text">
                    <div class="rpt-stat-label">{{ $stat['label'] }}</div>
                    <div class="rpt-stat-value">{{ $stat['value'] }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>
