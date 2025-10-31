<style>
    /* --- MODERN PROGRESS BAR --- */
    .progress {
        margin-bottom: 20px;
        height: 25px;
        border-radius: 15px;
        background-color: #e9ecef;
        box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
    }
    .progress-bar {
        font-size: 14px;
        line-height: 25px;
        color: #333; /* Cor do texto escura para melhor legibilidade */
        font-weight: bold;
        text-align: center;
        transition: width .6s ease;
    }
    .progress-bar-success { background-color: #28a745; color: #fff; }
    .progress-bar-warning { background-color: #ffc107; }
    .progress-bar-danger { background-color: #dc3545; color: #fff; }

    /* --- MODERN TABS --- */
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 0;
    }
    .nav-tabs > li > a {
        font-size: 1.1em;
        font-weight: 500;
        border: none;
        border-radius: 8px 8px 0 0;
        color: #495057;
        margin-right: 5px;
        background-color: #f8f9fa;
        border-bottom: 2px solid transparent;
    }
    .nav-tabs > li > a:hover {
        border-color: transparent;
        background-color: #e9ecef;
    }
    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus {
        color: #007bff;
        background-color: #fff;
        border: 2px solid #dee2e6;
        border-bottom-color: transparent;
    }
    .tab-content {
        border: 1px solid #ddd;
        border-top: 0;
        padding: 20px;
        border-radius: 0 0 5px 5px;
    }
    .form-actions {
        border-top: 0;
        background-color: transparent;
        padding: 20px 0 0 0;
    }
    /* Estilos para o Toggle Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #28a745;
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }
</style>
<style>
    /* Toggles menores para uma UI mais limpa */
    .small-toggle.switch {
        width: 40px;
        height: 20px;
    }
    .small-toggle .slider:before {
        height: 12px;
        width: 12px;
        left: 4px;
        bottom: 4px;
    }
    .small-toggle input:checked + .slider:before {
        transform: translateX(20px);
    }
    .equip-row {
        display: flex; align-items: center; gap: 15px;
    }
</style>