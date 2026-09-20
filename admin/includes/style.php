<style>
    * { box-sizing: border-box; }
    body {
        margin: 0;
        font-family: 'Segoe UI', Arial, sans-serif;
        background: #f4f6f5;
        color: #1c2a29;
    }
    .admin-topbar {
        background: #103c3b;
        color: #fff;
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
    }
    .admin-topbar a { color: #f5d9a0; text-decoration: none; font-weight: 600; }
    .admin-topbar .brand { font-weight: 700; letter-spacing: .5px; }
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
        border-radius: 10px;
        box-shadow: 0 2px 14px rgba(0,0,0,.06);
        padding: 24px;
        margin-bottom: 24px;
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

    table.admin-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
    table.admin-table th, table.admin-table td {
        text-align: left;
        padding: 10px 12px;
        border-bottom: 1px solid #eee;
        vertical-align: top;
    }
    table.admin-table th { color: #7a7a7a; text-transform: uppercase; font-size: 11px; letter-spacing: .4px; }
    table.admin-table tr.unread { background: #fbf6ec; font-weight: 600; }

    .badge { display: inline-block; padding: 3px 11px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap; }
    .badge.unread { background: #cda45e; color: #103c3b; }
    .badge.read { background: #eee; color: #777; }
    .badge.source-whatsapp { background: #dcf5e6; color: #128c4a; }
    .badge.source-contact_form { background: #e6edf4; color: #2c5c8a; }

    .admin-actions { display: flex; flex-wrap: wrap; gap: 8px; }
    .admin-actions form { display: inline-flex; }
    .admin-actions button {
        background: #eef2f1;
        border: 1px solid #dbe2e0;
        color: #103c3b;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        padding: 7px 14px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .admin-actions button:hover { background: #e2e9e7; }
    .admin-actions button.danger { color: #b3261e; border-color: #f3c9c5; background: #fdecea; }
    .admin-actions button.danger:hover { background: #fbdedb; }

    .admin-search { display: flex; gap: 8px; flex-wrap: wrap; }
    .admin-search input {
        padding: 9px 12px;
        border: 1px solid #d8d8d8;
        border-radius: 6px;
        font-size: 13.5px;
        width: 260px;
        max-width: 100%;
    }
    .admin-status-filter {
        padding: 9px 12px;
        border: 1px solid #d8d8d8;
        border-radius: 6px;
        font-size: 13.5px;
        color: #333;
        background: #fff;
    }

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
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid transparent;
    }
    .status-select.status-new { background: #eee; color: #666; }
    .status-select.status-in_progress { background: #e6edf4; color: #2c5c8a; }
    .status-select.status-follow_up { background: #fdf1de; color: #9a6b1a; }
    .status-select.status-deal_close { background: #e0f5e8; color: #148a4b; }
    .status-select.status-dead_query { background: #fdecea; color: #b3261e; }

    .admin-pagination { margin-top: 16px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
    .admin-pagination a, .admin-pagination span {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid #ddd;
        color: #103c3b;
        text-decoration: none;
        font-size: 13px;
    }
    .admin-pagination a.disabled { opacity: .4; pointer-events: none; }
    .admin-pagination .current { background: #103c3b; color: #fff; border-color: #103c3b; }
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
        .admin-card { padding: 16px; }

        .admin-search { width: 100%; }
        .admin-search input { width: 100%; }

        table.admin-table thead { display: none; }
        table.admin-table, table.admin-table tbody, table.admin-table tr, table.admin-table td {
            display: block;
            width: 100%;
        }
        table.admin-table tr {
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 12px;
            padding: 10px 12px;
        }
        table.admin-table td {
            border-bottom: none;
            padding: 6px 0;
        }
        table.admin-table td[data-label]:before {
            content: attr(data-label);
            display: block;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #999;
            margin-bottom: 2px;
        }
        .msg-cell { max-width: 100%; }
    }
</style>
