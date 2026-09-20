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
    }
    .admin-topbar a { color: #f5d9a0; text-decoration: none; font-weight: 600; }
    .admin-topbar .brand { font-weight: 700; letter-spacing: .5px; }
    .admin-wrap { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
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
    }
    .admin-auth-box {
        background: #fff;
        border-radius: 12px;
        padding: 36px 32px;
        width: 100%;
        max-width: 380px;
        box-shadow: 0 20px 60px rgba(0,0,0,.3);
    }
    .admin-auth-box h1 {
        font-size: 20px;
        margin: 0 0 4px;
        color: #103c3b;
    }
    .admin-auth-box p.sub { color: #7a7a7a; font-size: 13px; margin: 0 0 22px; }
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
    .badge { display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .badge.unread { background: #cda45e; color: #103c3b; }
    .badge.read { background: #eee; color: #777; }
    .admin-actions form { display: inline; }
    .admin-actions button {
        background: none;
        border: none;
        color: #103c3b;
        cursor: pointer;
        font-size: 12.5px;
        text-decoration: underline;
        padding: 0;
        margin-right: 10px;
    }
    .admin-actions button.danger { color: #b3261e; }
    .admin-search input {
        padding: 9px 12px;
        border: 1px solid #d8d8d8;
        border-radius: 6px;
        font-size: 13.5px;
        width: 260px;
    }
    .admin-pagination { margin-top: 16px; }
    .admin-pagination a, .admin-pagination span {
        display: inline-block;
        padding: 6px 12px;
        margin-right: 6px;
        border-radius: 6px;
        border: 1px solid #ddd;
        color: #103c3b;
        text-decoration: none;
        font-size: 13px;
    }
    .admin-pagination .current { background: #103c3b; color: #fff; border-color: #103c3b; }
    .msg-cell { max-width: 320px; white-space: pre-wrap; word-break: break-word; }
</style>
