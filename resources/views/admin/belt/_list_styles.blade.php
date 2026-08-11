{{--
    Shared chrome for the belt master lists (Niwar Code, Bukkal Code, Belt
    Costing) so the three read as one module.

    Actions sit inline rather than behind an "Action" dropdown: there are only
    ever two or three of them, the dropdown hid them behind an extra click, and
    inside a table-responsive wrapper its menu was clipped by the scroll
    container - the last item was simply unreachable.
--}}
<style>
    .belt-table { border: 1px solid #e3e8ef; }
    .belt-table > thead > tr > th {
        background: #f6f9fc;
        border-bottom: 2px solid #e3e8ef;
        font-size: 11px;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: #5b6b80;
        font-weight: 600;
        vertical-align: middle;
        padding: 10px 12px;
        white-space: nowrap;
    }
    .belt-table > tbody > tr > td {
        vertical-align: middle;
        padding: 9px 12px;
        border-color: #eceff5;
    }
    .belt-table > tbody > tr:hover { background-color: #f5f8fc; }
    .belt-table .num { text-align: right; }
    .belt-table .sr { width: 56px; text-align: center; color: #8a97a8; }

    /* Actions column: quiet until hovered, so the data leads. */
    .belt-actions-cell { white-space: nowrap; width: 1%; text-align: right; }
    .belt-act {
        display: inline-block;
        padding: 4px 10px;
        margin-left: 4px;
        border: 1px solid #d5dde7;
        border-radius: 3px;
        background: #fff;
        color: #41546b;
        font-size: 12.5px;
        line-height: 1.5;
        text-decoration: none;
    }
    .belt-act:hover, .belt-act:focus {
        background: #eef4fb;
        border-color: #9fc0e0;
        color: #16548f;
        text-decoration: none;
    }
    .belt-act i { margin-right: 4px; }
    .belt-act-danger { color: #a5303a; border-color: #e6c3c6; }
    .belt-act-danger:hover, .belt-act-danger:focus {
        background: #fdeced;
        border-color: #d99aa1;
        color: #8c2029;
    }

    .belt-empty { text-align: center; padding: 34px 12px; color: #9aa5b4; }
</style>
