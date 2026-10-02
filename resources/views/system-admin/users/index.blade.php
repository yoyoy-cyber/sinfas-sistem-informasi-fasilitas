@extends('layouts.dashboard-admin')

@section('title', 'Kelola Akun Pengguna')

@section('styles')
<style>
    /* ===== FLOATING NAVBAR ===== */
    .navbar {
        background: rgba(255, 255, 255, 0.95);
        padding: 0.9rem 1.8rem;
        margin: 1.2rem 1.5rem 0;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        position: sticky;
        top: 1rem;
        z-index: 1000;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .navbar-left {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .logo-badge {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        font-weight: 900;
        letter-spacing: -0.5px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        flex-shrink: 0;
    }

    .logo-details {
        display: flex;
        flex-direction: column;
    }

    .logo-text {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: -0.3px;
        line-height: 1.1;
    }

    .logo-subtitle {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .btn-back {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        border: none;
        padding: 0.6rem 1.25rem;
        border-radius: 25px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .btn-back:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
    }

    /* ===== MAIN CONTENT ===== */
    .main-content {
        padding: 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ===== ALERT ===== */
    .alert-success {
        background: #ecfdf5;
        color: #065f46;
        padding: 1rem 1.4rem;
        border-radius: 14px;
        margin-bottom: 1.5rem;
        border: 1px solid #a7f3d0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
    }

    .alert-error {
        background: #fef2f2;
        color: #991b1b;
        padding: 1rem 1.4rem;
        border-radius: 14px;
        margin-bottom: 1.5rem;
        border: 1px solid #fecaca;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
    }

    /* ===== SEARCH & FILTER CARD ===== */
    .filter-card {
        background: white;
        border-radius: 20px;
        padding: 1.2rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.06);
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .search-row {
        display: flex;
        gap: 0.8rem;
        align-items: center;
    }

    .search-box {
        flex: 1;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.6rem;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        font-size: 0.9rem;
        font-weight: 500;
        outline: none;
        transition: all 0.2s;
        background: #f8fafc;
    }

    .search-box input:focus {
        border-color: #2563eb;
        background: white;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .search-icon {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        display: flex;
        align-items: center;
        pointer-events: none;
    }

    .search-icon svg {
        width: 18px;
        height: 18px;
        fill: currentColor;
    }

    .btn-search {
        background: #2563eb;
        color: white;
        border: none;
        padding: 0.75rem 1.4rem;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
    }

    .btn-search:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-reset {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
        padding: 0.75rem 1.2rem;
        border-radius: 14px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .btn-reset:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .role-pills {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 0.3rem;
        scrollbar-width: thin;
    }

    .role-pill {
        padding: 0.45rem 0.9rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        color: #64748b;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        white-space: nowrap;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .role-pill:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .role-pill.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
    }

    .role-pill .count-badge {
        background: rgba(0, 0, 0, 0.08);
        padding: 0.15rem 0.45rem;
        border-radius: 10px;
        font-size: 0.75rem;
    }

    .role-pill.active .count-badge {
        background: rgba(255, 255, 255, 0.25);
        color: white;
    }

    /* ===== TABLE SECTION ===== */
    .table-section {
        background: white;
        border-radius: 20px;
        padding: 1.8rem;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.06);
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1.2rem;
        border-bottom: 2px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .table-title-wrap {
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }

    .table-header h2 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
        letter-spacing: -0.3px;
    }

    .total-badge {
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.25rem 0.7rem;
        border-radius: 20px;
        border: 1px solid #bfdbfe;
    }

    .btn-add {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: white;
        border: none;
        padding: 0.7rem 1.4rem;
        border-radius: 25px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(2, 132, 199, 0.3);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(2, 132, 199, 0.4);
    }

    .btn-add svg {
        width: 18px;
        height: 18px;
        fill: currentColor;
    }

    /* ===== DESKTOP TABLE ===== */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    thead {
        background: #f8fafc;
    }

    th {
        padding: 0.9rem 1rem;
        font-weight: 700;
        color: #475569;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        vertical-align: middle;
        color: #334155;
    }

    tbody tr {
        transition: background 0.15s;
    }

    tbody tr:hover {
        background: #f8fafc;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: #4338ca;
        font-weight: 800;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .user-info-name {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.15rem;
    }

    .user-info-nis {
        font-size: 0.8rem;
        color: #64748b;
        font-family: monospace;
    }

    /* ===== ROLE BADGES ===== */
    .role-badge {
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.4px;
        color: white;
        display: inline-block;
        text-align: center;
    }

    .role-admin_sistem {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        box-shadow: 0 2px 8px rgba(124, 58, 237, 0.3);
    }

    .role-admin_sarana {
        background: linear-gradient(135deg, #ea580c, #c2410c);
        box-shadow: 0 2px 8px rgba(234, 88, 12, 0.3);
    }

    .role-guru {
        background: linear-gradient(135deg, #0d9488, #0f766e);
        box-shadow: 0 2px 8px rgba(13, 148, 136, 0.3);
    }

    .role-siswa {
        background: linear-gradient(135deg, #16a34a, #15803d);
        box-shadow: 0 2px 8px rgba(22, 163, 74, 0.3);
    }

    /* ===== ACTION BUTTONS ===== */
    .action-group {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-action {
        padding: 0.45rem 0.9rem;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        text-decoration: none;
    }

    .btn-action svg {
        width: 14px;
        height: 14px;
        fill: currentColor;
    }

    .btn-edit {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .btn-edit:hover {
        background: #f59e0b;
        color: white;
        border-color: #f59e0b;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
    }

    .btn-delete {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .btn-delete:hover {
        background: #dc2626;
        color: white;
        border-color: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(220, 38, 38, 0.3);
    }

    /* ===== MOBILE CARDS LIST (Hidden on Desktop) ===== */
    .mobile-cards-list {
        display: none;
        flex-direction: column;
        gap: 1rem;
    }

    .user-card {
        background: white;
        border-radius: 16px;
        padding: 1.2rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        transition: all 0.2s;
    }

    .user-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .user-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px dashed #e2e8f0;
        gap: 0.8rem;
    }

    .user-card-title {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        min-width: 0;
    }

    .user-card-name {
        font-weight: 800;
        font-size: 1rem;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-card-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.8rem;
        margin-bottom: 1rem;
        background: #f8fafc;
        padding: 0.9rem;
        border-radius: 12px;
    }

    .user-grid-item {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }

    .user-grid-item.full-width {
        grid-column: span 2;
    }

    .user-grid-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .user-grid-value {
        font-size: 0.88rem;
        font-weight: 600;
        color: #1e293b;
        word-break: break-all;
    }

    .user-card-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.6rem;
    }

    .user-card-actions .btn-action {
        width: 100%;
        justify-content: center;
        padding: 0.65rem 0.5rem;
        font-size: 0.85rem;
        border-radius: 10px;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #64748b;
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 0.8rem;
    }

    .empty-state h3 {
        font-size: 1.15rem;
        color: #1e293b;
        margin-bottom: 0.4rem;
    }

    /* ===== MODAL BASE STYLES ===== */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.65);
        z-index: 99999;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(5px);
        padding: 1rem;
        overflow-y: auto;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        padding: 2rem;
        border-radius: 24px;
        width: 480px;
        max-width: 100%;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25);
        animation: modalSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }

    @keyframes modalSlideUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .modal-header-section h3 {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-close-btn {
        background: #f1f5f9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #64748b;
        font-size: 1.1rem;
        transition: all 0.2s;
    }

    .modal-close-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .form-group {
        margin-bottom: 1.1rem;
    }

    .form-group label {
        display: block;
        font-size: 0.82rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 0.4rem;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.9rem;
        outline: none;
        transition: all 0.2s;
        box-sizing: border-box;
        background: #f8fafc;
        color: #1e293b;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #2563eb;
        background: white;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .form-hint {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.3rem;
    }

    .modal-actions {
        display: flex;
        gap: 0.8rem;
        margin-top: 1.8rem;
    }

    .btn-modal {
        flex: 1;
        padding: 0.85rem;
        border: none;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
    }

    .btn-save {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }

    .btn-update {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* ===== POPUP KONFIRMASI HAPUS (IDENTIK DENGAN ADMIN SARANA) ===== */
    .modal-box-delete {
        background: #e5e7eb;
        border-radius: 20px;
        width: 500px;
        max-width: 90%;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.45);
        animation: modalPop 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 3px solid #1f2937;
    }

    @keyframes modalPop {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .modal-header-danger {
        background: #dc2626;
        padding: 1.1rem;
        text-align: center;
    }

    .modal-header-danger h3 {
        color: white;
        font-size: 1.3rem;
        font-weight: 900;
        letter-spacing: 1px;
        margin: 0;
        text-transform: uppercase;
    }

    .modal-body-delete {
        padding: 2.2rem 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        background: #e5e7eb;
    }

    .warning-icon-wrap {
        flex-shrink: 0;
    }

    .warning-icon-wrap svg {
        width: 90px;
        height: 90px;
        display: block;
    }

    .modal-body-delete p {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.4;
        margin: 0;
    }

    .modal-footer-delete {
        padding: 1.2rem 2rem 2rem;
        display: flex;
        justify-content: center;
        gap: 1.2rem;
        background: #e5e7eb;
    }

    .btn-modal-delete-confirm {
        padding: 0.8rem 2.5rem;
        border-radius: 30px;
        font-size: 1rem;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 1px;
        border: none;
        background: #dc2626;
        color: white;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    }

    .btn-modal-delete-confirm:hover {
        transform: scale(1.05);
        background: #b91c1c;
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.45);
    }

    .btn-modal-batal-confirm {
        padding: 0.8rem 2.5rem;
        border-radius: 30px;
        font-size: 1rem;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 1px;
        border: none;
        background: #6b7280;
        color: white;
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
    }

    .btn-modal-batal-confirm:hover {
        transform: scale(1.05);
        background: #4b5563;
        box-shadow: 0 6px 20px rgba(75, 85, 99, 0.45);
    }

    /* ===== RESPONSIVE MEDIA QUERIES ===== */
    @media (max-width: 768px) {
        .navbar {
            margin: 0.8rem 0.8rem 0;
            padding: 0.8rem 1rem;
            border-radius: 16px;
        }

        .logo-badge {
            width: 36px;
            height: 36px;
            font-size: 1.1rem;
        }

        .logo-text {
            font-size: 1.05rem;
        }

        .logo-subtitle {
            display: none;
        }

        .btn-back {
            padding: 0.5rem 0.9rem;
            font-size: 0.78rem;
        }

        .btn-back span.btn-text {
            display: none;
        }

        .main-content {
            padding: 1rem 0.8rem;
        }

        .filter-card {
            padding: 1rem;
            border-radius: 16px;
        }

        .search-row {
            flex-direction: column;
            gap: 0.6rem;
        }

        .search-box {
            width: 100%;
        }

        .btn-search, .btn-reset {
            width: 100%;
            justify-content: center;
            padding: 0.7rem;
        }

        .table-section {
            padding: 1.2rem 1rem;
            border-radius: 16px;
        }

        .table-header {
            flex-direction: column;
            align-items: stretch;
            gap: 0.8rem;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
            padding: 0.75rem;
        }

        /* Hide desktop table and show mobile cards */
        .table-responsive {
            display: none;
        }

        .mobile-cards-list {
            display: flex;
        }

        .modal-content {
            padding: 1.4rem;
            border-radius: 20px;
        }

        .modal-body-delete {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem 1.2rem;
            gap: 1rem;
        }

        .warning-icon-wrap svg {
            width: 70px;
            height: 70px;
        }

        .modal-body-delete p {
            font-size: 1rem;
        }

        .modal-footer-delete {
            padding: 1rem 1.2rem 1.5rem;
            gap: 0.8rem;
        }

        .btn-modal-delete-confirm, .btn-modal-batal-confirm {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            flex: 1;
        }
    }
</style>
@endsection

@section('content')

<!-- FLOATING NAVBAR -->
<nav class="navbar">
    <div class="navbar-left">
        <div class="logo-badge">SF</div>
        <div class="logo-details">
            <span class="logo-text">Manajemen Akun</span>
            <span class="logo-subtitle">Kelola dan atur akun pengguna SINFAS</span>
        </div>
    </div>
    <div class="navbar-right">
        <a href="{{ route('system-admin.dashboard') }}" class="btn-back">
            <span>←</span> <span class="btn-text">Dashboard</span>
        </a>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="main-content">

    <!-- Flash Alert -->
    @if(session('success'))
        <div class="alert-success">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error">
            <span>⚠️</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- FILTER & SEARCH CARD -->
    <div class="filter-card">
        <form method="GET" action="{{ route('system-admin.users') }}">
            <div class="search-row">
                <div class="search-box">
                    <span class="search-icon">
                        <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIS/NIP, email, atau no HP...">
                </div>
                @if(request('role'))
                    <input type="hidden" name="role" value="{{ request('role') }}">
                @endif
                <button type="submit" class="btn-search">
                    Cari
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ route('system-admin.users') }}" class="btn-reset">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Role Filter Pills -->
        <div class="role-pills">
            <a href="{{ route('system-admin.users', array_merge(request()->except('role'), ['page' => 1])) }}" 
               class="role-pill {{ !request('role') ? 'active' : '' }}">
                <span>Semua</span>
                <span class="count-badge">{{ $roleCounts['all'] ?? 0 }}</span>
            </a>
            <a href="{{ route('system-admin.users', array_merge(request()->except('role'), ['role' => 'siswa', 'page' => 1])) }}" 
               class="role-pill {{ request('role') === 'siswa' ? 'active' : '' }}">
                <span>Siswa</span>
                <span class="count-badge">{{ $roleCounts['siswa'] ?? 0 }}</span>
            </a>
            <a href="{{ route('system-admin.users', array_merge(request()->except('role'), ['role' => 'guru', 'page' => 1])) }}" 
               class="role-pill {{ request('role') === 'guru' ? 'active' : '' }}">
                <span>Guru</span>
                <span class="count-badge">{{ $roleCounts['guru'] ?? 0 }}</span>
            </a>
            <a href="{{ route('system-admin.users', array_merge(request()->except('role'), ['role' => 'admin_sarana', 'page' => 1])) }}" 
               class="role-pill {{ request('role') === 'admin_sarana' ? 'active' : '' }}">
                <span>Admin Sarana</span>
                <span class="count-badge">{{ $roleCounts['admin_sarana'] ?? 0 }}</span>
            </a>
            <a href="{{ route('system-admin.users', array_merge(request()->except('role'), ['role' => 'admin_sistem', 'page' => 1])) }}" 
               class="role-pill {{ request('role') === 'admin_sistem' ? 'active' : '' }}">
                <span>Admin Sistem</span>
                <span class="count-badge">{{ $roleCounts['admin_sistem'] ?? 0 }}</span>
            </a>
        </div>
    </div>

    <!-- TABLE SECTION -->
    <div class="table-section">
        
        <!-- Header -->
        <div class="table-header">
            <div class="table-title-wrap">
                <h2>Daftar Pengguna</h2>
                <span class="total-badge">{{ $users->total() }} Akun</span>
            </div>
            <button type="button" onclick="openTambahModal()" class="btn-add">
                <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                <span>+ Tambah Akun</span>
            </button>
        </div>

        @if($users->count() > 0)
            <!-- DESKTOP TABLE -->
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>NIS / NIP</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>No. HP</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="user-info-name">{{ $user->nama_lengkap }}</div>
                                        <div class="user-info-nis">{{ $user->nis_nip }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="font-family: monospace; font-weight: 600; color: #475569;">
                                    {{ $user->nis_nip }}
                                </span>
                            </td>
                            <td>
                                <span style="color: #334155;">{{ $user->email }}</span>
                            </td>
                            <td>
                                <span class="role-badge role-{{ $user->role }}">
                                    {{ strtoupper(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td>
                                <span style="color: #475569; font-weight: 500;">{{ $user->no_hp ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="action-group" style="justify-content: flex-end;">
                                    <!-- Tombol Edit -->
                                    <button type="button" 
                                        class="btn-action btn-edit"
                                        data-id="{{ $user->id }}"
                                        data-nama="{{ $user->nama_lengkap }}"
                                        data-nis="{{ $user->nis_nip }}"
                                        data-email="{{ $user->email }}"
                                        data-hp="{{ $user->no_hp }}"
                                        data-role="{{ $user->role }}"
                                        onclick="openEditModal(this)">
                                        <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                        Edit
                                    </button>

                                    <!-- Tombol Hapus (Pop Up Custom) -->
                                    <button type="button" 
                                        class="btn-action btn-delete"
                                        onclick="openDeleteModal('{{ route('system-admin.delete-user', $user->id) }}', '{{ addslashes($user->nama_lengkap) }}')">
                                        <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- MOBILE CARDS LIST -->
            <div class="mobile-cards-list">
                @foreach($users as $user)
                <div class="user-card">
                    <div class="user-card-header">
                        <div class="user-card-title">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                            </div>
                            <div>
                                <div class="user-card-name">{{ $user->nama_lengkap }}</div>
                                <div style="font-size: 0.78rem; color: #64748b; font-family: monospace;">{{ $user->nis_nip }}</div>
                            </div>
                        </div>
                        <span class="role-badge role-{{ $user->role }}">
                            {{ strtoupper(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>

                    <div class="user-card-grid">
                        <div class="user-grid-item">
                            <span class="user-grid-label">🪪 NIS/NIP</span>
                            <span class="user-grid-value" style="font-family: monospace;">{{ $user->nis_nip }}</span>
                        </div>
                        <div class="user-grid-item">
                            <span class="user-grid-label">📞 No HP</span>
                            <span class="user-grid-value">{{ $user->no_hp ?? '-' }}</span>
                        </div>
                        <div class="user-grid-item full-width">
                            <span class="user-grid-label">✉️ Email</span>
                            <span class="user-grid-value">{{ $user->email }}</span>
                        </div>
                    </div>

                    <div class="user-card-actions">
                        <button type="button" 
                            class="btn-action btn-edit"
                            data-id="{{ $user->id }}"
                            data-nama="{{ $user->nama_lengkap }}"
                            data-nis="{{ $user->nis_nip }}"
                            data-email="{{ $user->email }}"
                            data-hp="{{ $user->no_hp }}"
                            data-role="{{ $user->role }}"
                            onclick="openEditModal(this)">
                            <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                            Edit
                        </button>

                        <button type="button" 
                            class="btn-action btn-delete"
                            onclick="openDeleteModal('{{ route('system-admin.delete-user', $user->id) }}', '{{ addslashes($user->nama_lengkap) }}')">
                            <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                            Hapus
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if(method_exists($users, 'links'))
                <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                    {{ $users->links() }}
                </div>
            @endif

        @else
            <div class="empty-state">
                <div class="empty-icon">👥</div>
                <h3>Tidak Ada Data Pengguna</h3>
                <p>Tidak ditemukan akun pengguna yang sesuai dengan pencarian atau filter yang dipilih.</p>
            </div>
        @endif

    </div>
</div>

<!-- MODAL TAMBAH USER -->
<div id="modalTambah" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header-section">
            <h3><span>➕</span> Tambah Akun Baru</h3>
            <button type="button" class="modal-close-btn" onclick="closeTambahModal()">&times;</button>
        </div>
        <form action="{{ route('system-admin.create-user') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>
            </div>
            
            <div class="form-group">
                <label>NIS / NIP</label>
                <input type="text" name="nis_nip" placeholder="Contoh: 12345678" required>
            </div>
            
            <div class="form-group">
                <label>Alamat Email</label>
                <input type="email" name="email" placeholder="contoh@sinfas.sch.id" required>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" minlength="6" required>
            </div>
            
            <div class="form-group">
                <label>Nomor HP / WhatsApp</label>
                <input type="text" name="no_hp" placeholder="08xxxxxxxxxx" required>
            </div>
            
            <div class="form-group">
                <label>Role / Peran Pengguna</label>
                <select name="role" required>
                    <option value="">-- Pilih Role Pengguna --</option>
                    <option value="siswa">Siswa</option>
                    <option value="guru">Guru</option>
                    <option value="admin_sarana">Admin Sarana</option>
                    <option value="admin_sistem">Admin Sistem</option>
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeTambahModal()" class="btn-modal btn-cancel">Batal</button>
                <button type="submit" class="btn-modal btn-save">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT USER -->
<div id="modalEdit" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header-section">
            <h3><span>✏️</span> Edit Akun Pengguna</h3>
            <button type="button" class="modal-close-btn" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editForm" action="" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="edit_nama" placeholder="Nama Lengkap" required>
            </div>
            
            <div class="form-group">
                <label>NIS / NIP</label>
                <input type="text" name="nis_nip" id="edit_nis" placeholder="NIS / NIP" required>
            </div>
            
            <div class="form-group">
                <label>Alamat Email</label>
                <input type="email" name="email" id="edit_email" placeholder="Email" required>
            </div>
            
            <div class="form-group">
                <label>Nomor HP / WhatsApp</label>
                <input type="text" name="no_hp" id="edit_hp" placeholder="No HP" required>
            </div>
            
            <div class="form-group">
                <label>Role / Peran Pengguna</label>
                <select name="role" id="edit_role" required>
                    <option value="siswa">Siswa</option>
                    <option value="guru">Guru</option>
                    <option value="admin_sarana">Admin Sarana</option>
                    <option value="admin_sistem">Admin Sistem</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                <div class="form-hint">Biarkan kosong jika password pengguna tidak diubah.</div>
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeEditModal()" class="btn-modal btn-cancel">Batal</button>
                <button type="submit" class="btn-modal btn-update">Perbarui Akun</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL KONFIRMASI HAPUS USER ===== -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box-delete">
        <div class="modal-header-danger">
            <h3>KONFIRMASI HAPUS</h3>
        </div>
        <div class="modal-body-delete">
            <div class="warning-icon-wrap">
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="50,8 95,88 5,88" fill="#facc15" stroke="#dc2626" stroke-width="5" stroke-linejoin="round"/>
                    <text x="50" y="72" font-size="50" font-weight="900" text-anchor="middle" fill="#1f2937">!</text>
                </svg>
            </div>
            <p id="deleteText">Yakin ingin menghapus akun ini? Data tidak dapat dikembalikan!</p>
        </div>
        <div class="modal-footer-delete">
            <button type="button" class="btn-modal-delete-confirm" onclick="confirmDelete()">YA, HAPUS</button>
            <button type="button" class="btn-modal-batal-confirm" onclick="closeDeleteModal()">BATAL</button>
        </div>
    </div>
</div>

<!-- FORM HAPUS (Hidden) -->
<form id="deleteUserForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
    // ===== MODAL TAMBAH =====
    function openTambahModal() {
        document.getElementById('modalTambah').classList.add('active');
    }

    function closeTambahModal() {
        document.getElementById('modalTambah').classList.remove('active');
    }

    // ===== MODAL EDIT =====
    function openEditModal(button) {
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        const nis = button.getAttribute('data-nis');
        const email = button.getAttribute('data-email');
        const hp = button.getAttribute('data-hp');
        const role = button.getAttribute('data-role');

        document.getElementById('editForm').action = '/super-admin/users/' + id;
        document.getElementById('edit_nama').value = nama || '';
        document.getElementById('edit_nis').value = nis || '';
        document.getElementById('edit_email').value = email || '';
        document.getElementById('edit_hp').value = hp || '';
        document.getElementById('edit_role').value = role || 'siswa';
        
        document.getElementById('modalEdit').classList.add('active');
    }

    function closeEditModal() {
        document.getElementById('modalEdit').classList.remove('active');
    }

    // ===== MODAL KONFIRMASI HAPUS =====
    function openDeleteModal(url, userName) {
        document.getElementById('deleteUserForm').action = url;
        document.getElementById('deleteText').innerHTML = 'Yakin ingin menghapus akun <strong>"' + userName + '"</strong>? Data tidak dapat dikembalikan!';
        document.getElementById('deleteModal').classList.add('active');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
    }

    function confirmDelete() {
        document.getElementById('deleteUserForm').submit();
    }

    // Tutup modal saat klik di luar area konten
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay').forEach(modal => {
                modal.classList.remove('active');
            });
        }
    });
</script>
@endsection