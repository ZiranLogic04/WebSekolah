<style scoped>
/* Table Modern Premium */
.table-modern thead th {
    background-color: #f8fafc;
    color: #64748b;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1rem 1.5rem;
    border-bottom: 2px solid #e2e8f0;
}

.table-modern tbody td {
    padding: 1rem 1.5rem;
    vertical-align: middle;
    color: #334155;
    font-size: 0.95rem;
    border-bottom: 1px solid #f1f5f9;
}

.table-modern tbody tr:last-child td {
    border-bottom: none;
}

.table-modern tbody tr:hover {
    background-color: #f8fafc;
}

/* Row Selected */
.row-selected td {
    background-color: #eff6ff !important;
}

/* Badge Tweaks */
.status-badge {
    padding: 0.35em 0.8em;
    font-size: 0.85em;
    font-weight: 600;
    border-radius: 50px;
    letter-spacing: 0.3px;
    display: inline-flex;
    align-items: center;
}

.bidang-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 0.35rem 0.75rem;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.8rem;
}

.gender-badge {
    padding: 0.25rem 0.6rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.gender-badge.male {
    background: #eff6ff;
    color: #2563eb;
}
.gender-badge.female {
    background: #fdf2f8;
    color: #db2777;
}

/* Modern Icon Actions - Premium Style */
.btn-icon-modern {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background-color: #f3f4f6;
    color: #6b7280;
    font-size: 1rem;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

.btn-icon-modern:hover {
    transform: translateY(-2px);
    color: white;
}

/* View: Info Blue */
.btn-icon-view:hover {
    background-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

/* Edit: Amber */
.btn-icon-edit:hover {
    background-color: #f59e0b; 
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

/* Delete: Red */
.btn-icon-delete:hover {
    background-color: #ef4444; 
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Detail Modal Styles */
.detail-header {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    padding: 2rem;
    border-radius: 12px 12px 0 0;
    color: white;
    position: relative;
}

.detail-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 4px solid rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    color: white;
    overflow: hidden;
}

.detail-avatar-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.info-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.03);
    height: 100%;
    border: 1px solid #f1f5f9;
}

.info-item {
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    font-weight: 600;
    display: block;
    margin-bottom: 2px;
}

.info-value {
    color: #1e293b;
    font-weight: 500;
    font-size: 0.95rem;
}

.modal-content.modern-modal {
    border: none;
    border-radius: 16px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
