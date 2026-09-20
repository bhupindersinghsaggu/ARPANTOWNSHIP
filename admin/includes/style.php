<style>
    * { box-sizing: border-box; }
    body {
        margin: 0;
        font-family: 'Segoe UI', Arial, sans-serif;
        background: #f2f4f1;
        color: #1c2a29;
        -webkit-font-smoothing: antialiased;
    }

    #topProgressBar {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        width: 0%;
        background: #cda45e;
        z-index: 99999;
        transition: width 1.2s ease-out, opacity .3s ease .2s;
        opacity: 0;
    }
    #topProgressBar.progress-active {
        width: 90%;
        opacity: 1;
        transition: width 1.2s ease-out, opacity .2s ease;
    }
    .admin-topbar {
        background: linear-gradient(135deg, #103c3b 0%, #0c2e2d 100%);
        color: #fff;
        padding: 14px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        border-bottom: 2px solid #cda45e;
        box-shadow: 0 2px 12px rgba(0,0,0,.15);
    }
    .admin-topbar a { color: #f5d9a0; text-decoration: none; font-weight: 600; transition: opacity .2s ease; }
    .admin-topbar a:hover { opacity: .8; }
    .admin-topbar .brand { display: flex; align-items: center; gap: 10px; font-weight: 700; letter-spacing: .4px; }
    .admin-topbar .brand img { height: 26px; width: auto; display: block; }
    .admin-topbar-right { display: flex; align-items: center; gap: 16px; }
    .admin-topbar-user { font-size: 13px; }
    .admin-hamburger {
        display: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 6px;
        width: 34px;
        height: 34px;
        flex-direction: column;
        justify-content: center;
        gap: 5px;
    }
    .admin-hamburger span {
        display: block;
        width: 100%;
        height: 2px;
        background: #f5d9a0;
        border-radius: 2px;
    }
    .admin-topbar-menu {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .admin-wrap { max-width: 1400px; margin: 30px auto; padding: 0 20px; }
    .admin-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 1px 2px rgba(16,60,59,.04), 0 12px 32px rgba(16,60,59,.08);
        padding: 26px 28px;
        margin-bottom: 24px;
        border: 1px solid rgba(16,60,59,.06);
    }
    .admin-auth-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #103c3b;
        padding: 20px;
    }
    .admin-auth-box {
        background: #fff;
        border-radius: 12px;
        padding: 36px 32px;
        width: 100%;
        max-width: 380px;
        box-shadow: 0 20px 60px rgba(0,0,0,.3);
    }
    .admin-auth-logo {
        display: block;
        height: 56px;
        width: auto;
        margin: 0 auto 18px;
    }
    .admin-auth-box h1 {
        font-size: 20px;
        margin: 0 0 4px;
        text-align: center;
        color: #103c3b;
    }
    .admin-auth-box p.sub { color: #7a7a7a; font-size: 13px; margin: 0 0 22px; text-align: center; }
    .admin-field { margin-bottom: 16px; }
    .admin-field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #333; }
    .admin-field input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d8d8d8;
        border-radius: 6px;
        font-size: 14px;
    }
    .admin-field input:focus { outline: none; border-color: #cda45e; }
    .admin-btn {
        display: inline-block;
        background: #103c3b;
        color: #fff;
        border: none;
        border-radius: 30px;
        padding: 11px 26px;
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
    }
    .admin-btn:hover { background: #0b2b2a; }
    .admin-btn.danger { background: #b3261e; }
    .admin-btn.ghost { background: transparent; color: #103c3b; border: 1px solid #103c3b; }
    .admin-alert { padding: 10px 14px; border-radius: 6px; font-size: 13px; margin-bottom: 18px; }
    .admin-alert.error { background: #fdecea; color: #b3261e; border: 1px solid #f5c2be; }
    .admin-alert.success { background: #e8f4ee; color: #103c3b; border: 1px solid #b9ddc8; }

    .admin-table-wrap {
        border: 1px solid #edf0ee;
        border-radius: 12px;
        overflow: hidden;
    }
    table.admin-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
    table.admin-table th, table.admin-table td {
        text-align: left;
        padding: 14px 14px;
        border-bottom: 1px solid #f0f2f0;
        vertical-align: middle;
    }
    table.admin-table thead th {
        color: #103c3b;
        text-transform: uppercase;
        font-size: 10.5px;
        letter-spacing: .6px;
        font-weight: 700;
        background: #f7f8f6;
        border-bottom: 1px solid #e7eae7;
    }
    table.admin-table tbody tr { transition: background .15s ease; }
    table.admin-table tbody tr:last-child td { border-bottom: none; }
    table.admin-table tbody tr:hover { background: #f9faf8; }
    table.admin-table tr.unread { background: #fdfaf2; box-shadow: inset 3px 0 0 #cda45e; }
    table.admin-table tr.unread:hover { background: #fbf5e6; }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: .3px;
        text-transform: uppercase;
        white-space: nowrap;
        border: 1px solid transparent;
    }
    .badge.unread { background: #cda45e; color: #fff; }
    .badge.read { background: #f1f2f0; color: #8a938f; border-color: #e5e7e4; }
    .badge.source-whatsapp { background: #e6f9ee; color: #0f9d58; border-color: #cdefdc; }
    .badge.source-contact_form { background: #eaf1fb; color: #2c5c8a; border-color: #d6e5f5; }

    .admin-actions { display: flex; flex-wrap: wrap; gap: 8px; }
    .admin-actions form { display: inline-flex; }
    .admin-actions button {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #eef2f1;
        border: 1px solid #dbe2e0;
        color: #103c3b;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        padding: 7px 14px;
        border-radius: 20px;
        white-space: nowrap;
        transition: background .15s ease, box-shadow .15s ease, transform .1s ease;
    }
    .admin-actions button svg { flex-shrink: 0; }
    .admin-actions button:hover { background: #e2e9e7; box-shadow: 0 2px 6px rgba(16,60,59,.1); }
    .admin-actions button:active { transform: scale(.97); }
    .admin-actions button.danger { color: #b3261e; border-color: #f3c9c5; background: #fdecea; }
    .admin-actions button.danger:hover { background: #fbdedb; box-shadow: 0 2px 6px rgba(179,38,30,.15); }

    .admin-search { display: flex; gap: 10px; flex-wrap: wrap; }
    .admin-search input {
        padding: 10px 14px;
        border: 1px solid #e0e3e0;
        border-radius: 8px;
        font-size: 13.5px;
        width: 260px;
        max-width: 100%;
        background: #fafbfa;
        transition: border-color .2s ease, background .2s ease;
    }
    .admin-search input:focus { outline: none; border-color: #cda45e; background: #fff; }
    .admin-status-filter {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        padding: 10px 32px 10px 14px;
        border: 1px solid #e0e3e0;
        border-radius: 8px;
        font-size: 13.5px;
        color: #333;
        background: #fafbfa url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23103c3b' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 14px center;
    }
    .admin-status-filter:focus { outline: none; border-color: #cda45e; }

    .phone-link {
        color: #103c3b;
        text-decoration: none;
        font-weight: 600;
    }
    .phone-link:hover { text-decoration: underline; }
    .wa-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        margin-left: 6px;
        border-radius: 50%;
        background: #25D366;
        color: #fff;
        vertical-align: middle;
    }
    .wa-link:hover { opacity: .85; }

    .status-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        padding: 7px 30px 7px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .2px;
        cursor: pointer;
        border: 1px solid transparent;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23103c3b' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        transition: box-shadow .2s ease, transform .1s ease;
    }
    .status-select:hover { box-shadow: 0 2px 8px rgba(16,60,59,.12); }
    .status-select:focus { outline: none; box-shadow: 0 0 0 3px rgba(205,164,94,.35); }
    .status-select.status-new { background-color: #f1f2f0; color: #666; }
    .status-select.status-in_progress { background-color: #eaf1fb; color: #2c5c8a; }
    .status-select.status-follow_up { background-color: #fdf1de; color: #9a6b1a; }
    .status-select.status-deal_close { background-color: #e6f9ee; color: #0f9d58; }
    .status-select.status-dead_query { background-color: #fdecea; color: #b3261e; }

    .admin-pagination { margin-top: 18px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
    .admin-pagination a, .admin-pagination span {
        display: inline-block;
        padding: 7px 13px;
        border-radius: 8px;
        border: 1px solid #e5e7e4;
        color: #103c3b;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: background .15s ease, box-shadow .15s ease;
    }
    .admin-pagination a:hover { background: #f2f4f1; box-shadow: 0 2px 6px rgba(16,60,59,.08); }
    .admin-pagination a.disabled { opacity: .35; pointer-events: none; }
    .admin-pagination .current { background: #103c3b; color: #fff; border-color: #103c3b; box-shadow: 0 2px 8px rgba(16,60,59,.25); }
    .msg-cell { max-width: 320px; white-space: pre-wrap; word-break: break-word; }

    /* -------------------------------------------------- */
    /* Mobile responsive                                   */
    /* -------------------------------------------------- */
    @media (max-width: 767px) {
        .admin-hamburger { display: flex; }
        .admin-topbar-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            left: 0;
            background: #0b2b2a;
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 24px;
            box-shadow: 0 10px 20px rgba(0,0,0,.2);
            z-index: 50;
        }
        .admin-topbar-menu.admin-menu-open { display: flex; }

        .admin-wrap { margin: 16px auto; padding: 0 12px; }
        .admin-card { padding: 14px; border-radius: 16px; }

        .admin-search { width: 100%; }
        .admin-search input { width: 100%; }
        .admin-status-filter { flex: 1; }

        .admin-table-wrap { border: none; border-radius: 0; overflow: visible; }
        table.admin-table thead { display: none; }
        table.admin-table, table.admin-table tbody { display: block; width: 100%; }
        table.admin-table tr {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            column-gap: 14px;
            row-gap: 10px;
            width: 100%;
            background: #fff;
            border: 1px solid #edf0ee;
            border-radius: 14px;
            margin-bottom: 14px;
            padding: 16px 16px 16px 18px;
            box-shadow: 0 1px 2px rgba(16,60,59,.03), 0 8px 20px rgba(16,60,59,.05);
        }
        table.admin-table tr.unread {
            box-shadow: inset 4px 0 0 #cda45e, 0 8px 20px rgba(16,60,59,.06);
        }
        table.admin-table td {
            display: block;
            border-bottom: none;
            padding: 0;
            flex: 1 1 100%;
        }
        table.admin-table td[data-label]:before {
            content: attr(data-label);
            display: block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #a9b0ab;
            margin-bottom: 3px;
        }

        /* Name becomes the card title, Date a muted overline above it */
        table.admin-table td:nth-child(1) { order: 1; flex-basis: 100%; }
        table.admin-table td:nth-child(1):before { display: none; }
        table.admin-table td:nth-child(1) { font-size: 11.5px; font-weight: 600; color: #9aa39e; letter-spacing: .2px; }

        table.admin-table td:nth-child(2) { order: 2; flex-basis: 100%; margin-bottom: 2px; }
        table.admin-table td:nth-child(2):before { display: none; }
        table.admin-table td:nth-child(2) { font-size: 17px; font-weight: 700; color: #103c3b; }

        /* Source + Read badges sit side by side */
        table.admin-table td:nth-child(5) { order: 3; flex: 0 1 auto; }
        table.admin-table td:nth-child(7) { order: 4; flex: 0 1 auto; }
        table.admin-table td:nth-child(5):before,
        table.admin-table td:nth-child(7):before { display: none; }

        table.admin-table td:nth-child(3) { order: 5; flex-basis: 100%; }
        table.admin-table td:nth-child(4) { order: 6; flex-basis: 100%; }
        table.admin-table td:nth-child(6) { order: 7; flex-basis: 100%; }
        table.admin-table td:nth-child(8) { order: 8; flex-basis: 100%; padding-top: 8px; border-top: 1px dashed #eef0ee; }
        table.admin-table td:nth-child(9) { order: 9; flex-basis: 100%; padding-top: 4px; }

        .msg-cell { max-width: 100%; }
    }
</style>
